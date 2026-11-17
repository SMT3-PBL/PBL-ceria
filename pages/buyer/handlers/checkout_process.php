<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../models/Menu.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  die('DEBUG: request bukan POST.');
}

$sesi      = sesi_pembeli();
$idTenant  = (int)($_POST['id_tenant'] ?? 0);
$metode    = $_POST['metode_bayar'] ?? '';
$menuModel = new Menu($pdo);

if (!in_array($metode, ['qris', 'tunai'], true)) {
  die('DEBUG: metode pembayaran tidak valid: ' . htmlspecialchars($metode));
}

// pesanan.id_sesi wajib diisi
if (empty($sesi['id_sesi'])) {
  echo '<pre>DEBUG: sesi pembeli tidak punya id_sesi. Isi $sesi:' . "\n";
  var_dump($sesi);
  echo '</pre>';
  exit;
}

// 1. Item keranjang milik tenant ini
$itemCheckout = array_filter(
  $_SESSION['cart'] ?? [],
  fn($i) => (int)($i['id_tenant'] ?? 0) === $idTenant
);
if (empty($itemCheckout)) {
  echo '<pre>DEBUG: keranjang kosong untuk tenant ' . $idTenant . "\n";
  var_dump($_SESSION['cart'] ?? null);
  echo '</pre>';
  exit;
}

// 2. Hitung ulang dari database
$baris = [];
$total = 0;
foreach ($itemCheckout as $idMenu => $item) {
  $menu = $menuModel->getMenuById((string)$idMenu);
  if (!$menu) {
    die("DEBUG: menu $idMenu tidak ada di database.");
  }
  if ((int)$menu['id_tenant'] !== $idTenant) {
    die("DEBUG: menu $idMenu bukan milik tenant $idTenant.");
  }
  if ($menu['status_menu'] !== 'tersedia') {
    die("DEBUG: status_menu menu $idMenu adalah '" . htmlspecialchars($menu['status_menu']) . "', bukan 'tersedia'.");
  }

  $jumlah   = max(1, (int)$item['jumlah']);
  $subtotal = $menu['harga'] * $jumlah;
  $total   += $subtotal;
  $baris[]  = [
    'id_menu'   => (int)$idMenu,
    'nama_menu' => $menu['nama_menu'],
    'harga'     => $menu['harga'],
    'jumlah'    => $jumlah,
    'subtotal'  => $subtotal,
  ];
}

// 3. Validasi & simpan bukti bayar (hanya QRIS)
$namaFileBukti = null;
$folder = __DIR__ . '/../../../uploads/bukti/';
if ($metode === 'qris') {
  $f = $_FILES['bukti_bayar'] ?? null;
  if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
    die('Bukti bayar wajib diunggah.');
  }
  if ($f['size'] > 2 * 1024 * 1024) {
    die('Ukuran bukti bayar maksimal 2MB.');
  }
  $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  $ekstensi = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
  if (!$ekstensi) {
    die('Format harus JPG, PNG, atau WEBP.');
  }
  if (!is_dir($folder)) mkdir($folder, 0775, true);

  $namaFileBukti = 'bukti_' . bin2hex(random_bytes(8)) . '.' . $ekstensi;
  if (!move_uploaded_file($f['tmp_name'], $folder . $namaFileBukti)) {
    die('Gagal menyimpan bukti bayar.');
  }
}

// 4. Simpan dalam satu transaksi
function cek_db(PDO $pdo, string $langkah): void {
  $e = $pdo->errorInfo();
  if (!empty($e[0]) && $e[0] !== '00000') {
    throw new Exception("[$langkah] " . $e[0] . ' - ' . ($e[2] ?? ''));
  }
}

$langkah = 'mulai transaksi';
try {
  $pdo->beginTransaction();
  cek_db($pdo, $langkah);

  $langkah = 'kunci antrean';
  $pdo->exec('SELECT pg_advisory_xact_lock(' . (int)$idTenant . ')');
  cek_db($pdo, $langkah);

  $langkah = 'hitung no_antrean';
  $st = $pdo->prepare(
    "SELECT COALESCE(MAX(no_antrean), 0) + 1
       FROM pesanan
      WHERE id_tenant = ? AND tanggal_antrean = CURRENT_DATE"
  );
  $st->execute([$idTenant]);
  cek_db($pdo, $langkah);
  $noAntrean = (int)$st->fetchColumn();

  $langkah = 'insert pesanan';
  $st = $pdo->prepare(
    "INSERT INTO pesanan (no_antrean, id_tenant, id_sesi)
     VALUES (?, ?, ?) RETURNING id_pesanan"
  );
  $st->execute([$noAntrean, $idTenant, $sesi['id_sesi']]);
  cek_db($pdo, $langkah);
  $idPesanan = (int)$st->fetchColumn();

  $langkah = 'insert detail_pesanan';
  $stDetail = $pdo->prepare(
    "INSERT INTO detail_pesanan (id_pesanan, id_menu, nama_menu, harga_satuan, jumlah, subtotal)
     VALUES (?, ?, ?, ?, ?, ?)"
  );
  foreach ($baris as $b) {
    $stDetail->execute([
      $idPesanan,
      $b['id_menu'],
      $b['nama_menu'],
      $b['harga'],
      $b['jumlah'],
      $b['subtotal'],
    ]);
    cek_db($pdo, $langkah);
  }

  $langkah = 'insert pembayaran';
  $pdo->prepare(
    "INSERT INTO pembayaran (id_pesanan, metode_bayar, jumlah_bayar, bukti_bayar)
     VALUES (?, ?, ?, ?)"
  )->execute([$idPesanan, $metode, $total, $namaFileBukti]);
  cek_db($pdo, $langkah);

  $pdo->commit();
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  if ($namaFileBukti) @unlink($folder . $namaFileBukti);
  die('DEBUG [' . $langkah . ']: ' . htmlspecialchars($e->getMessage()));
}

// 5. Kosongkan item tenant ini dari keranjang
foreach (array_keys($itemCheckout) as $idMenu) {
  unset($_SESSION['cart'][$idMenu]);
}

header('Location: ' . url('pages/buyer/status.php?id_pesanan=' . $idPesanan));
exit;