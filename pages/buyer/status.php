<?php
// pages/buyer/status.php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Pesanan.php';

$pageTitle = 'Status Pesanan';
$role      = 'buyer';
$active    = 'status';

require __DIR__ . '/../../includes/header.php';

$sesi = sesi_pembeli();
$idSesi = (int) ($sesi['id_sesi'] ?? 0);

// Parameter 'id_pesanan' dari URL (opsional)
$id_pesanan = (int) (filter_var(
  $_GET['id_pesanan'] ?? null,
  FILTER_VALIDATE_INT,
  ['options' => ['min_range' => 1]]
) ?: 0);

$pesanan = $id_pesanan > 0
  ? Pesanan::byIdUntukSesi($pdo, $id_pesanan, $idSesi)
  : Pesanan::terakhirBySesi($pdo, $idSesi);

if (!$pesanan) {
  echo '<main class="max-w-4xl mx-auto p-6 text-center"><div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200"><h1 class="text-lg font-bold text-slate-800">Belum ada pesanan aktif</h1><p class="text-sm text-slate-500 mt-1">Silakan lakukan pemesanan terlebih dahulu.</p></div></main>';
  require __DIR__ . '/../../includes/footer.php';
  exit;
}

// Item pesanan
$items = Pesanan::item($pdo, (int) $pesanan['id_pesanan']);

// Hitung total harga dari item
$total_harga = 0;
foreach ($items as $it) {
  $total_harga += ($it['subtotal'] ?? ($it['jumlah'] * $it['harga_satuan']));
}

// ===== Status pesanan =====
$statusKey   = (string) $pesanan['status_pesanan'];
$statusLabel = Pesanan::STATUS_LABEL[$statusKey] ?? $statusKey;
$dibatalkan  = $statusKey === 'dibatalkan';
$selesai     = $statusKey === 'selesai';

// step 0 = menunggu pembayaran, 1..4 = Diterima..Selesai
$posisi = array_search($statusKey, Pesanan::TAHAPAN, true);
$step   = $posisi === false ? 0 : $posisi + 1;

// Teks & warna banner sesuai status
if ($dibatalkan) {
  $judul = 'Pesanan Anda Ditolak';
  $pesan = 'Maaf, tenant tidak dapat memproses pesanan ini. Jika Anda sudah membayar, silakan hubungi tenant untuk pengembalian dana.';
  $bannerKelas = 'border-rose-200 bg-rose-50';
  $judulKelas  = 'text-rose-950';
  $teksKelas   = 'text-rose-800';
  $badgeKelas  = 'bg-rose-100 text-rose-800';
} elseif ($selesai) {
  $judul = 'Pesanan Selesai';
  $pesan = 'Terima kasih sudah memesan. Selamat menikmati!';
  $bannerKelas = 'border-emerald-200 bg-emerald-50';
  $judulKelas  = 'text-emerald-950';
  $teksKelas   = 'text-emerald-800';
  $badgeKelas  = 'bg-emerald-100 text-emerald-800';
} else {
  if ($statusKey === 'menunggu_pembayaran') {
    $judul = 'Menunggu Pembayaran';
    $pesan = 'Pesanan Anda akan diproses setelah tenant mengonfirmasi pembayaran.';
  } elseif ($statusKey === 'siap_diambil') {
    $judul = 'Pesanan Anda Siap Diambil!';
    $pesan = 'Silakan ambil pesanan Anda di loket tenant.';
  } else {
    $judul = 'Pesanan Anda Sedang Diproses!';
    $pesan = 'Silakan duduk santai di Meja No. ' . ($sesi['no_meja'] ?? '-') . '.';
  }
  $bannerKelas = 'border-blue-100 bg-blue-50/70';
  $judulKelas  = 'text-blue-950';
  $teksKelas   = 'text-blue-800';
  $badgeKelas  = 'bg-amber-100 text-amber-800';
}
?>

<main class="mx-auto max-w-6xl p-4 pb-20 sm:p-6 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_340px]" id="status-container"
  data-id-pesanan="<?= (int) $pesanan['id_pesanan'] ?>">

  <!-- Section Status Real-Time -->
  <div class="space-y-6">
    <!-- Banner Status -->
    <section
      class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border <?= $bannerKelas ?> p-5 sm:p-6">
      <div>
        <h1 class="text-xl font-bold tracking-tight <?= $judulKelas ?>"><?= e($judul) ?></h1>
        <p class="mt-1 text-sm <?= $teksKelas ?>"><?= e($pesan) ?>
          No. Antrean Anda: <b>#<?= e($pesanan['no_antrean']) ?></b>.</p>
      </div>
      <?php if (!$dibatalkan && !$selesai): ?>
      <div class="rounded-xl bg-white px-4 py-2.5 text-right shadow-sm border border-blue-100 shrink-0">
        <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Estimasi Penyajian</span>
        <span class="text-lg font-extrabold text-blue-600">10-15 Menit</span>
      </div>
      <?php endif; ?>
    </section>

    <!-- Progress Tracking Per Tenant -->
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-6">
      <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Proses Stand Makanan & Minuman</h2>

      <div class="space-y-3">
        <div class="flex items-center justify-between text-sm">
          <span class="font-bold text-slate-900">Tenant ID: <?= e($pesanan['id_tenant']) ?></span>
          <span id="status-badge" data-status="<?= e($statusKey) ?>"
            class="rounded-full px-3 py-0.5 text-xs font-bold <?= $badgeKelas ?>">
            <?= e($statusLabel) ?>
          </span>
        </div>

        <?php if ($dibatalkan): ?>
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
          <p class="font-bold">Pesanan ini tidak diproses.</p>
          <p class="mt-1">Anda bisa memesan kembali dari tenant yang sama atau tenant lain.</p>
          <a href="<?= e(url('pages/buyer/pilih_menu.php')) ?>"
            class="mt-3 inline-flex h-10 items-center rounded-lg bg-primary px-4 text-sm font-semibold text-white hover:bg-primary-dark transition">
            Pesan Lagi
          </a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-4 gap-2">
          <div class="h-2 rounded-full <?= $step >= 1 ? 'bg-blue-600' : 'bg-slate-200' ?>"></div>
          <div
            class="h-2 rounded-full <?= $step >= 2 ? 'bg-blue-600' : 'bg-slate-200' ?> <?= $step === 2 ? 'animate-pulse' : '' ?>">
          </div>
          <div class="h-2 rounded-full <?= $step >= 3 ? 'bg-blue-600' : 'bg-slate-200' ?>"></div>
          <div class="h-2 rounded-full <?= $step >= 4 ? 'bg-blue-600' : 'bg-slate-200' ?>"></div>
        </div>
        <div class="grid grid-cols-4 text-center text-[11px] font-semibold text-slate-400">
          <span class="<?= $step >= 1 ? 'text-blue-600 font-bold' : '' ?>">Diterima</span>
          <span class="<?= $step >= 2 ? 'text-blue-600 font-bold' : '' ?>">Diproses</span>
          <span class="<?= $step >= 3 ? 'text-blue-600 font-bold' : '' ?>">Siap Diambil</span>
          <span class="<?= $step >= 4 ? 'text-blue-600 font-bold' : '' ?>">Selesai</span>
        </div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <!-- Rincian Ringkasan Pesanan (Nota Digital) -->
  <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Detail Pesanan</h2>
      <span class="text-xs font-mono font-semibold text-slate-500">#PSN-<?= e($pesanan['id_pesanan']) ?></span>
    </div>

    <div class="text-xs space-y-1.5 text-slate-600">
      <p class="flex justify-between"><span>Pemesan:</span> <b
          class="text-slate-900"><?= e($sesi['nama_pembeli'] ?? 'Tamu') ?></b></p>
      <p class="flex justify-between"><span>Nomor Meja:</span> <b class="text-slate-900">Meja No.
          <?= e($sesi['no_meja'] ?? '-') ?></b></p>
      <p class="flex justify-between"><span>No. Antrean:</span> <b
          class="text-blue-600">#<?= e($pesanan['no_antrean']) ?></b></p>
    </div>

    <hr class="border-slate-100">

    <ul class="space-y-3 text-xs">
      <?php foreach ($items as $item): ?>
      <li class="flex justify-between">
        <div>
          <p class="font-bold text-slate-900"><?= e($item['nama_menu'] ?? 'Menu Item') ?></p>
          <p class="text-slate-400"><?= e($item['jumlah']) ?>x @
            Rp<?= number_format($item['harga_satuan'] ?? 0, 0, ',', '.') ?></p>
        </div>
        <span
          class="font-semibold text-slate-900">Rp<?= number_format($item['subtotal'] ?? ($item['jumlah'] * $item['harga_satuan']), 0, ',', '.') ?></span>
      </li>
      <?php endforeach; ?>
    </ul>

    <hr class="border-slate-100">

    <div class="space-y-1 text-xs">
      <div class="flex justify-between text-base font-bold text-slate-900 pt-1">
        <span>Total Akhir</span>
        <span
          class="<?= $dibatalkan ? 'text-slate-400 line-through' : 'text-blue-600' ?>">Rp<?= number_format($total_harga, 0, ',', '.') ?></span>
      </div>
    </div>
  </aside>
</main>

<!-- Skrip JavaScript untuk Auto-Refresh Real-time -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('status-container');
  const badge = document.getElementById('status-badge');
  if (!container || !badge) return;

  // Status akhir: tidak perlu dicek lagi
  if (['selesai', 'dibatalkan'].includes(badge.dataset.status)) return;

  const idPesanan = container.dataset.idPesanan;

  const timer = setInterval(async () => {
    try {
      const response = await fetch(`handlers/cek_status_pesanan.php?id_pesanan=${idPesanan}`);
      const result = await response.json();

      if (result.success && result.status_pesanan !== badge.dataset.status) {
        clearInterval(timer);
        location.reload();
      }
    } catch (error) {
      console.error('Gagal memperbarui status:', error);
    }
  }, 5000);
});
</script>

<?php require __DIR__ . '/../../includes/footer.php'; ?>