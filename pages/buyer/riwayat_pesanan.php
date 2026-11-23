<?php
require_once __DIR__ . '/../../includes/init.php';

$pageTitle = 'Riwayat & Detail Pesanan';
$role      = 'buyer';
$active    = 'history';

$sesi        = sesi_pembeli();
$noMeja      = (string) ($sesi['no_meja'] ?? '-');
$namaPembeli = (string) ($sesi['nama_pembeli'] ?? 'Pengunjung');
$sesiKey     = (string) ($sesi['id_sesi'] ?? session_id());

if (!isset($_SESSION['riwayat_pesanan_sesi']) || ($_SESSION['riwayat_pesanan_sesi_id'] ?? '') !== $sesiKey) {
  $_SESSION['riwayat_pesanan_sesi_id'] = $sesiKey;
  $_SESSION['riwayat_pesanan_sesi']    = [
    [
      'waktu'             => '23 Nov 2026, 07:15 WIB',
      'nama_tenant'       => 'Ayam Geprek Mas Joko',
      'metode_pembayaran' => 'QRIS',
      'catatan'           => 'Sambal dipisah, tidak terlalu asin.',
      'items'             => [
        ['nama_menu' => 'Ayam Geprek Level 3', 'jumlah' => 2, 'harga' => 15000],
        ['nama_menu' => 'Es Teh Manis Jumbo',   'jumlah' => 2, 'harga' => 4000],
      ],
    ],
    [
      'waktu'             => '23 Nov 2026, 07:28 WIB',
      'nama_tenant'       => 'Bakso Solo Pak Kunto',
      'metode_pembayaran' => 'Tunai',
      'catatan'           => 'Pakai bawang goreng banyak.',
      'items'             => [
        ['nama_menu' => 'Bakso Urat',     'jumlah' => 1, 'harga' => 15000],
        ['nama_menu' => 'Pangsit Goreng', 'jumlah' => 1, 'harga' => 8000],
      ],
    ],
  ];
}

$riwayatPesanan = array_values($_SESSION['riwayat_pesanan_sesi']);
$selectedIndex  = isset($_GET['pilih']) ? (int) $_GET['pilih'] : 0;
$pesananAktif   = $riwayatPesanan[$selectedIndex] ?? ($riwayatPesanan[0] ?? null);

require __DIR__ . '/../../includes/header.php';
?>

<main class="mx-auto grid max-w-7xl grid-cols-1 gap-4 p-4 pb-24 lg:grid-cols-[minmax(0,1fr)_380px] lg:gap-6 lg:pb-8">

  <!-- Daftar Riwayat Pesanan -->
  <section class="min-w-0 rounded-2xl border border-line bg-white p-4 shadow-sm sm:p-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-primary">Aktivitas Pemesanan</p>
        <h1 class="mt-0.5 text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Riwayat Pesanan</h1>
        <p class="mt-1 text-sm text-muted">
          Meja No. <span class="font-semibold text-ink"><?= e($noMeja) ?></span> &bull; <?= e($namaPembeli) ?>
        </p>
      </div>
      <a href="<?= url('pages/buyer/pilih_menu.php') ?>"
        class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-dark">
        + Pesan Lagi
      </a>
    </div>

    <hr class="my-5 border-line/70">

    <?php if (empty($riwayatPesanan)): ?>
      <div class="rounded-xl border border-dashed border-line bg-surface p-8 text-center">
        <p class="font-semibold text-ink">Belum ada riwayat pesanan</p>
        <p class="mt-1 text-sm text-muted">Pesanan yang telah Anda buat pada sesi ini akan muncul di halaman ini.</p>
      </div>
    <?php else: ?>
      <ul class="space-y-3">
        <?php foreach ($riwayatPesanan as $idx => $pesanan):
          $isSelected      = ($pesananAktif !== null && $selectedIndex === $idx);
          $subtotalPesanan = 0;
          $totalItem       = 0;
          foreach (($pesanan['items'] ?? []) as $it) {
            $qty = (int) ($it['jumlah'] ?? 0);
            $hrg = (int) ($it['harga'] ?? 0);
            $subtotalPesanan += $qty * $hrg;
            $totalItem += $qty;
          }
        ?>
          <li>
            <a href="?pilih=<?= $idx ?>"
              class="block rounded-xl border p-4 transition <?= $isSelected ? 'border-primary bg-soft/70 shadow-sm' : 'border-line bg-white hover:border-primary/50 hover:bg-surface/60' ?>">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                  <p class="font-bold text-ink"><?= e($pesanan['nama_tenant'] ?? 'Tenant') ?></p>
                  <p class="mt-0.5 text-xs text-muted"><?= e($pesanan['waktu'] ?? '-') ?> &bull; <?= $totalItem ?> Item</p>
                </div>

                <div class="text-right">
                  <p class="text-xs text-muted">Total</p>
                  <p class="text-base font-extrabold text-ink">Rp<?= number_format($subtotalPesanan, 0, ',', '.') ?></p>
                </div>
              </div>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>

  <!-- Panel Detail Pesanan -->
  <aside class="h-fit rounded-2xl border-2 border-primary/70 bg-white p-4 shadow-sm sm:p-5 lg:sticky lg:top-24">
    <div class="flex items-center justify-between gap-2">
      <span class="rounded-md bg-soft px-2.5 py-1 text-[11px] font-extrabold uppercase tracking-wider text-primary">
        Detail Pesanan
      </span>
    </div>

    <?php if ($pesananAktif === null): ?>
      <p class="py-8 text-center text-sm text-muted">Pilih salah satu riwayat pesanan untuk melihat rinciannya.</p>
    <?php else:
      $totalTagihan = 0;
    ?>
      <div class="mt-4 rounded-xl border border-line bg-surface p-3.5 text-sm">
        <div>
          <p class="text-xs text-muted">Tenant</p>
          <p class="font-bold text-ink"><?= e($pesananAktif['nama_tenant'] ?? '-') ?></p>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-2 border-t border-line/70 pt-3 text-xs">
          <div>
            <span class="block text-muted">Waktu Pesan</span>
            <span class="font-semibold text-ink"><?= e($pesananAktif['waktu'] ?? '-') ?></span>
          </div>
          <div>
            <span class="block text-muted">Pembayaran</span>
            <span class="font-semibold text-ink"><?= e($pesananAktif['metode_pembayaran'] ?? '-') ?></span>
          </div>
          <div class="col-span-2">
            <span class="block text-muted">Pemesan</span>
            <span class="font-semibold text-ink"><?= e($namaPembeli) ?> &bull; Meja <?= e($noMeja) ?></span>
          </div>
        </div>
      </div>

      <!-- Rincian Menu -->
      <div class="mt-4">
        <h2 class="text-xs font-bold uppercase tracking-wider text-muted">Item Pesanan</h2>
        <ul class="mt-2 divide-y divide-dashed divide-line">
          <?php foreach (($pesananAktif['items'] ?? []) as $item):
            $qty          = (int) ($item['jumlah'] ?? 0);
            $hargaSatuan  = (int) ($item['harga'] ?? 0);
            $lineTotal    = $qty * $hargaSatuan;
            $totalTagihan += $lineTotal;
          ?>
            <li class="flex items-center justify-between gap-3 py-3 text-sm">
              <div class="min-w-0">
                <p class="font-semibold text-ink"><?= $qty ?>&times; <?= e($item['nama_menu'] ?? 'Menu') ?></p>
                <p class="text-xs text-muted">@ Rp<?= number_format($hargaSatuan, 0, ',', '.') ?></p>
              </div>
              <span class="shrink-0 font-bold text-ink">Rp<?= number_format($lineTotal, 0, ',', '.') ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php if (!empty($pesananAktif['catatan'])): ?>
        <div class="mt-3 rounded-lg border border-line/70 bg-soft/60 p-3 text-xs">
          <span class="block font-bold text-ink">Catatan Pesanan:</span>
          <p class="mt-0.5 text-muted"><?= e($pesananAktif['catatan']) ?></p>
        </div>
      <?php endif; ?>

      <!-- Ringkasan Total -->
      <div class="mt-4 rounded-xl bg-surface p-3.5 text-sm">
        <div class="flex items-center justify-between font-extrabold text-ink">
          <span>Total Pembayaran</span>
          <span class="text-base text-primary">Rp<?= number_format($totalTagihan, 0, ',', '.') ?></span>
        </div>
      </div>
    <?php endif; ?>
  </aside>
</main>

<?php require __DIR__ . '/../../includes/footer.php'; ?>