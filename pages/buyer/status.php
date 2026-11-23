<?php
// pages/buyer/status.php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Pesanan.php';

$pageTitle = 'Status Pesanan';
$role      = 'buyer';
$active    = 'status';

require __DIR__ . '/../../includes/header.php';

$sesi   = sesi_pembeli();
$idSesi = (int) ($sesi['id_sesi'] ?? 0);

// Pesanan yang baru dibuat (opsional) -> disorot
$fokusId = (int) (filter_var(
  $_GET['id_pesanan'] ?? null,
  FILTER_VALIDATE_INT,
  ['options' => ['min_range' => 1]]
) ?: 0);

$semua = $idSesi > 0 ? Pesanan::daftarBySesi($pdo, $idSesi) : [];

if (!$semua) {
  echo '<main class="max-w-4xl mx-auto p-6 text-center"><div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200"><h1 class="text-lg font-bold text-slate-800">Belum ada pesanan</h1><p class="text-sm text-slate-500 mt-1">Silakan lakukan pemesanan terlebih dahulu.</p><a href="' . e(url('pages/buyer/pilih_menu.php')) . '" class="mt-4 inline-flex h-10 items-center rounded-lg bg-primary px-4 text-sm font-semibold text-white hover:bg-primary-dark transition">Pilih Menu</a></div></main>';
  require __DIR__ . '/../../includes/footer.php';
  exit;
}

// Pisahkan pesanan berjalan dan riwayat (selesai / ditolak)
$aktif = [];
$riwayat = [];
foreach ($semua as $p) {
  if (in_array($p['status_pesanan'], ['selesai', 'dibatalkan'], true)) {
    $riwayat[] = $p;
  } else {
    $aktif[] = $p;
  }
}

$itemPerPesanan = Pesanan::itemBanyak($pdo, array_column($semua, 'id_pesanan'));
$tandaAwal      = Pesanan::tandaDari($semua);
$urlCek         = url('pages/buyer/handlers/cek_status_pesanan.php');

// Judul, pesan, dan warna badge sesuai status
$infoStatus = static function (string $status, array $sesi): array {
  switch ($status) {
    case 'menunggu_pembayaran':
      return ['Menunggu Pembayaran', 'Pesanan akan diproses setelah tenant mengonfirmasi pembayaran.', 'bg-amber-100 text-amber-800'];
    case 'diterima':
      return ['Pembayaran Diterima', 'Pesanan Anda sudah diterima tenant dan segera diproses.', 'bg-blue-100 text-blue-800'];
    case 'diproses':
      return ['Sedang Diproses', 'Silakan duduk santai di Meja No. ' . ($sesi['no_meja'] ?? '-') . '.', 'bg-blue-100 text-blue-800'];
    case 'siap_diambil':
      return ['Siap Diambil!', 'Silakan ambil pesanan Anda di loket tenant.', 'bg-emerald-100 text-emerald-800'];
    case 'selesai':
      return ['Selesai', 'Terima kasih sudah memesan. Selamat menikmati!', 'bg-emerald-100 text-emerald-800'];
    case 'dibatalkan':
      return ['Ditolak', 'Tenant tidak dapat memproses pesanan ini. Jika sudah membayar, hubungi tenant untuk pengembalian dana.', 'bg-rose-100 text-rose-800'];
  }
  return [Pesanan::STATUS_LABEL[$status] ?? $status, '', 'bg-slate-100 text-slate-700'];
};

$grup = [
  ['Pesanan Berjalan', $aktif, false],
  ['Riwayat Pesanan', $riwayat, true],
];
?>

<main class="mx-auto max-w-4xl space-y-8 p-4 pb-20 sm:p-6" id="status-container" data-tanda="<?= e($tandaAwal) ?>"
  data-url-cek="<?= e($urlCek) ?>" data-ada-aktif="<?= $aktif ? '1' : '0' ?>">

  <header class="flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-xl font-bold tracking-tight text-slate-900">Status Pesanan</h1>
      <p class="mt-0.5 text-sm text-slate-500">
        <?= e($sesi['nama_pembeli'] ?? 'Tamu') ?> · Meja No. <?= e($sesi['no_meja'] ?? '-') ?>
        · <?= count($semua) ?> pesanan
      </p>
    </div>
    <a href="<?= e(url('pages/buyer/pilih_menu.php')) ?>"
      class="inline-flex h-10 items-center rounded-lg bg-primary px-4 text-sm font-semibold text-white hover:bg-primary-dark transition">
      + Pesan Lagi
    </a>
  </header>

  <?php foreach ($grup as [$judulGrup, $daftar, $isRiwayat]): ?>
  <section class="space-y-4">
    <h2 class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400">
      <?= e($judulGrup) ?>
      <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-600"><?= count($daftar) ?></span>
    </h2>

    <?php if (!$daftar): ?>
    <p class="rounded-2xl border border-dashed border-slate-200 bg-white p-5 text-center text-sm text-slate-400">
      <?= $isRiwayat ? 'Belum ada pesanan yang selesai atau ditolak.' : 'Tidak ada pesanan yang sedang berjalan.' ?>
    </p>
    <?php endif; ?>

    <?php foreach ($daftar as $p): ?>
    <?php
        $idP         = (int) $p['id_pesanan'];
        $statusKey   = (string) $p['status_pesanan'];
        $dibatalkan  = $statusKey === 'dibatalkan';
        [$judulStatus, $pesanStatus, $badgeKelas] = $infoStatus($statusKey, $sesi);
        $posisi      = array_search($statusKey, Pesanan::TAHAPAN, true);
        $step        = $posisi === false ? 0 : $posisi + 1;
        $items       = $itemPerPesanan[$idP] ?? [];
        $total       = array_sum(array_map(static fn(array $i): float => (float) $i['subtotal'], $items));
        $metode      = strtoupper((string) ($p['metode_bayar'] ?? ''));
        $lunas       = ($p['status_pembayaran'] ?? '') === 'lunas';
        $sorot       = $idP === $fokusId;
        ?>
    <article id="pesanan-<?= $idP ?>"
      class="rounded-2xl border bg-white p-5 shadow-sm <?= $sorot ? 'border-blue-400 ring-2 ring-blue-200' : 'border-slate-200' ?> <?= $isRiwayat ? 'opacity-90' : '' ?>">

      <!-- Kepala kartu: tenant, antrean, status -->
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
          <p class="truncate text-base font-bold text-slate-900"><?= e($p['nama_tenant']) ?></p>
          <p class="mt-0.5 text-xs text-slate-500">
            Antrean <b class="text-blue-600">#<?= e($p['no_antrean']) ?></b>
            · <span class="font-mono">#PSN-<?= $idP ?></span>
            · <?= e(date('d M, H:i', strtotime($p['tanggal_pesanan']))) ?> WIB
          </p>
        </div>
        <span class="rounded-full px-3 py-0.5 text-xs font-bold <?= $badgeKelas ?>"><?= e($judulStatus) ?></span>
      </div>

      <?php if ($pesanStatus !== ''): ?>
      <p class="mt-3 text-sm <?= $dibatalkan ? 'text-rose-700' : 'text-slate-600' ?>"><?= e($pesanStatus) ?></p>
      <?php endif; ?>

      <!-- Progres -->
      <?php if (!$dibatalkan): ?>
      <div class="mt-4 space-y-2">
        <div class="grid grid-cols-4 gap-2">
          <?php for ($i = 1; $i <= 4; $i++): ?>
          <div
            class="h-2 rounded-full <?= $step >= $i ? 'bg-blue-600' : 'bg-slate-200' ?> <?= (!$isRiwayat && $step === $i && $i < 4 && $statusKey === 'diproses') ? 'animate-pulse' : '' ?>">
          </div>
          <?php endfor; ?>
        </div>
        <div class="grid grid-cols-4 text-center text-[11px] font-semibold text-slate-400">
          <?php foreach (Pesanan::TAHAPAN as $idx => $tahap): ?>
          <span
            class="<?= $step >= $idx + 1 ? 'font-bold text-blue-600' : '' ?>"><?= e(Pesanan::STATUS_LABEL[$tahap]) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Rincian menu -->
      <details class="mt-4 rounded-xl bg-slate-50 p-3" <?= $isRiwayat ? '' : 'open' ?>>
        <summary class="cursor-pointer text-xs font-bold uppercase tracking-wider text-slate-500">
          Rincian Menu (<?= count($items) ?>)
        </summary>
        <ul class="mt-3 divide-y divide-slate-200 text-sm">
          <?php foreach ($items as $it): ?>
          <li class="flex justify-between gap-3 py-2">
            <div class="min-w-0">
              <p class="font-semibold text-slate-900"><?= e($it['nama_menu']) ?></p>
              <p class="text-xs text-slate-400"><?= (int) $it['jumlah'] ?>x @
                Rp<?= number_format($it['harga_satuan'], 0, ',', '.') ?></p>
            </div>
            <span
              class="shrink-0 font-semibold text-slate-900">Rp<?= number_format($it['subtotal'], 0, ',', '.') ?></span>
          </li>
          <?php endforeach; ?>
          <?php if (!$items): ?>
          <li class="py-2 text-xs text-slate-400">Belum ada rincian menu.</li>
          <?php endif; ?>
        </ul>
      </details>

      <!-- Total & pembayaran -->
      <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3 text-sm">
        <span class="text-xs text-slate-500">
          <?php if ($metode !== ''): ?>
          Bayar: <b class="text-slate-800"><?= e($metode) ?></b> ·
          <?= $dibatalkan ? 'Dibatalkan' : ($lunas ? 'Lunas' : 'Belum lunas') ?>
          <?php endif; ?>
        </span>
        <span class="text-base font-bold <?= $dibatalkan ? 'text-slate-400 line-through' : 'text-blue-600' ?>">
          Rp<?= number_format($total, 0, ',', '.') ?>
        </span>
      </div>

      <?php if ($dibatalkan): ?>
      <a href="<?= e(url('pages/buyer/pilih_menu.php?tenant=' . (int) $p['id_tenant'])) ?>"
        class="mt-3 inline-flex h-10 items-center rounded-lg border border-slate-200 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
        Pesan lagi di <?= e($p['nama_tenant']) ?>
      </a>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </section>
  <?php endforeach; ?>
</main>

<!-- Auto-refresh: muat ulang kalau status pesanan mana pun berubah -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('status-container');
  if (!container) return;

  // Rapikan posisi ke pesanan yang baru dibuat
  const fokus = <?= (int) $fokusId ?>;
  if (fokus > 0) {
    const el = document.getElementById('pesanan-' + fokus);
    if (el) el.scrollIntoView({
      block: 'center'
    });
  }

  // Semua pesanan sudah final: tidak perlu cek lagi
  if (container.dataset.adaAktif !== '1') return;

  const tandaAwal = container.dataset.tanda;
  const urlCek = container.dataset.urlCek;

  const timer = setInterval(async () => {
    try {
      const response = await fetch(urlCek, {
        cache: 'no-store'
      });
      const result = await response.json();

      if (result.success && result.tanda !== tandaAwal) {
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