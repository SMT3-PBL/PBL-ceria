<?php
// pages/seller/handlers/update_pesanan_process.php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../models/Pesanan.php';

$halamanPesanan = 'pages/seller/dashboard/pesanan_masuk.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . url($halamanPesanan));
  exit;
}

$idTenant = (int) (tenant_aktif()['id_tenant'] ?? 0);
if ($idTenant <= 0 && !empty($_SESSION['user_id'])) {
  $idTenant = Pesanan::idTenantByPengguna($pdo, (int) $_SESSION['user_id']);
}
if ($idTenant <= 0) {
  die('Session tenant tidak ditemukan. Silakan login kembali.');
}

$tokenCsrf = (string) ($_SESSION['csrf_pesanan'] ?? '');
$idPesanan = (int) (filter_var(
  $_POST['id_pesanan'] ?? null,
  FILTER_VALIDATE_INT,
  ['options' => ['min_range' => 1]]
) ?: 0);

$pesanAksi = '';

if ($tokenCsrf === '' || !hash_equals($tokenCsrf, (string) ($_POST['csrf_token'] ?? ''))) {
  $pesanAksi = 'Sesi formulir tidak valid. Muat ulang halaman lalu coba lagi.';
} elseif ($idPesanan <= 0) {
  $pesanAksi = 'Pesanan tidak valid.';
} else {
  $statusSaatIni = Pesanan::statusSaatIni($pdo, $idPesanan, $idTenant);
  $aksi = (string) ($_POST['aksi'] ?? '');

  if ($statusSaatIni === null) {
    $pesanAksi = 'Pesanan tidak ditemukan atau bukan milik tenant ini.';
  } elseif ($aksi === 'pembayaran') {
    $pesanAksi = Pesanan::konfirmasiPembayaran($pdo, $idPesanan, $idTenant)
      ? 'Pembayaran berhasil dikonfirmasi. Pesanan siap diproses.'
      : 'Pembayaran belum dapat dikonfirmasi. Pastikan bukti QRIS tersedia atau pembayaran tunai sudah diterima.';
  } elseif ($aksi === 'status') {
    $statusBaru = (string) ($_POST['status'] ?? '');

    $lunas = Pesanan::statusPembayaran($pdo, $idPesanan, $idTenant) === 'lunas';

    if (Pesanan::bolehUbahStatus($statusSaatIni, $statusBaru, $lunas)) {
      $pesanAksi = Pesanan::ubahStatus($pdo, $idPesanan, $idTenant, $statusSaatIni, $statusBaru)
        ? 'Status pesanan diperbarui menjadi ' . (Pesanan::STATUS_LABEL[$statusBaru] ?? $statusBaru) . '.'
        : 'Status pesanan berubah di perangkat lain. Muat ulang halaman.';
    } else {
      $pesanAksi = (!$lunas && $statusBaru !== 'dibatalkan')
        ? 'Konfirmasi pembayaran terlebih dahulu sebelum memproses pesanan.'
        : 'Perubahan status tidak sesuai urutan proses pesanan.';
    }
  } else {
    $pesanAksi = 'Aksi tidak dikenali.';
  }
}

$_SESSION['pesanan_flash'] = $pesanAksi;
$kembaliKe = $idPesanan > 0 ? '?id=' . $idPesanan : '';
header('Location: ' . url($halamanPesanan . $kembaliKe));
exit;