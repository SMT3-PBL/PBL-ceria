<?php
// pages/seller/dashboard/pesanan_masuk.php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../models/Pesanan.php';

$pageTitle = 'Pesanan Masuk';
$role = 'seller';
$active = 'orders';

$idTenant = (int) (tenant_aktif()['id_tenant'] ?? 0);
if ($idTenant <= 0 && !empty($_SESSION['user_id'])) {
  $idTenant = Pesanan::idTenantByPengguna($pdo, (int) $_SESSION['user_id']);
}
if ($idTenant <= 0) {
  die('Session tenant tidak ditemukan. Silakan login kembali.');
}

if (empty($_SESSION['csrf_pesanan'])) {
  $_SESSION['csrf_pesanan'] = bin2hex(random_bytes(32));
}
$tokenCsrf = $_SESSION['csrf_pesanan'];
$urlHandler = url('pages/seller/handlers/update_pesanan_process.php');

$idPesanan = (int) (filter_var(
  $_GET['id'] ?? $_GET['id_pesanan'] ?? null,
  FILTER_VALIDATE_INT,
  ['options' => ['min_range' => 1]]
) ?: 0);

$statusBerikutnya = Pesanan::STATUS_BERIKUTNYA;
$statusLabel = Pesanan::STATUS_LABEL;
$tahapan = Pesanan::TAHAPAN;
$filterStatus = (string) ($_GET['status'] ?? 'aktif');
$cari = trim((string) ($_GET['cari'] ?? ''));

$pesanFlash = (string) ($_SESSION['pesanan_flash'] ?? '');
unset($_SESSION['pesanan_flash']);

$statistik = Pesanan::statistikHariIni($pdo, $idTenant);
$pesanan = Pesanan::daftar($pdo, $idTenant, $filterStatus, $cari);

$pesananDetail = null;
$detail = [];
$totalDetail = 0;
if ($idPesanan > 0) {
  $pesananDetail = Pesanan::detail($pdo, $idPesanan, $idTenant);
  if ($pesananDetail) {
    $detail = Pesanan::item($pdo, $idPesanan);
    $totalDetail = array_sum(array_map(static fn(array $item): float => (float) $item['subtotal'], $detail));
  }
}

require __DIR__ . '/../../../includes/header.php';
?>

<main class="mx-auto max-w-6xl px-4 py-5 pb-10 sm:px-6">
  <?php if ($pesanFlash !== ''): ?>
  <p role="status"
    class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-900">
    <?= e($pesanFlash) ?></p>
  <?php endif; ?>

  <?php if ($idPesanan > 0): ?>
  <?php if (!$pesananDetail): ?>
  <section role="alert" class="rounded-xl border border-rose-200 bg-white p-6 text-sm font-semibold text-rose-800">
    Pesanan tidak ditemukan atau bukan milik tenant ini. <a class="ml-2 text-primary underline"
      href="<?= e(url('pages/seller/dashboard/pesanan_masuk.php')) ?>">Kembali ke pesanan masuk</a></section>
  <?php else: ?>
  <?php
      $statusDetail = $pesananDetail['status_pesanan'];
      $statusBayar = $pesananDetail['status_pembayaran'] ?? 'belum_lunas';
      $posisiStatus = array_search($statusDetail, $tahapan, true);
      $sudahLunas = $statusBayar === 'lunas';
      // 'diterima' terjadi otomatis saat pembayaran dikonfirmasi; tahap lanjutan wajib sudah lunas
      $targetStatus = ($statusDetail !== 'menunggu_pembayaran' && $sudahLunas)
        ? ($statusBerikutnya[$statusDetail] ?? null)
        : null;
      $perluBayar = !$sudahLunas && !in_array($statusDetail, ['selesai', 'dibatalkan'], true);
      // Tolak hanya boleh sebelum pembayaran dikonfirmasi
      $bisaTolak = $statusDetail === 'menunggu_pembayaran';
      ?>
  <a href="<?= e(url('pages/seller/dashboard/pesanan_masuk.php')) ?>"
    class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-muted hover:text-primary"><span
      aria-hidden="true">←</span> Kembali ke pesanan masuk</a>
  <div class="grid items-start gap-4 lg:grid-cols-[minmax(0,1.8fr)_minmax(280px,0.9fr)]">
    <section class="rounded-xl bg-white p-5 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <div class="flex flex-wrap items-start justify-between gap-3 border-b border-line/70 pb-4">
        <div>
          <h1 class="text-2xl font-extrabold tracking-tight"><?= e($pesananDetail['nama_pembeli'] ?: 'Pembeli') ?></h1>
          <p class="mt-1 text-sm text-muted">Antrean <?= (int) $pesananDetail['no_antrean'] ?> ·
            <?= e(date('H:i', strtotime($pesananDetail['tanggal_pesanan']))) ?> WIB</p>
        </div>
        <div class="flex flex-wrap items-center gap-2"><span
            class="rounded-md px-2.5 py-1 text-[10px] font-extrabold uppercase <?= $statusDetail === 'dibatalkan' ? 'bg-danger-soft text-danger' : ($statusDetail === 'selesai' ? 'bg-ok-soft text-ok' : 'bg-soft text-primary') ?>"><?= e($statusLabel[$statusDetail] ?? $statusDetail) ?></span><span
            class="rounded-lg bg-soft px-3 py-2 text-sm font-extrabold text-primary">MEJA
            <?= e($pesananDetail['no_meja']) ?></span></div>
      </div>

      <div class="py-4">
        <h2 class="mb-3 text-xs font-extrabold uppercase tracking-wide text-muted">Daftar pesanan</h2>
        <ul class="divide-y divide-line/70">
          <?php foreach ($detail as $item): ?>
          <li class="flex items-center justify-between gap-3 py-3 text-sm">
            <span><b><?= (int) $item['jumlah'] ?>×</b> <?= e($item['nama_menu']) ?><small
                class="mt-1 block text-muted"><?= rupiah($item['harga_satuan']) ?> / porsi</small></span>
            <b class="shrink-0"><?= rupiah($item['subtotal']) ?></b>
          </li>
          <?php endforeach; ?>
          <?php if (!$detail): ?><li class="py-3 text-sm text-muted">Belum ada rincian menu.</li><?php endif; ?>
        </ul>
        <div class="mt-3 flex items-center justify-between border-t border-line/70 pt-4 text-sm text-muted"><span>Total
            pembayaran</span><strong class="text-lg text-ink"><?= rupiah($totalDetail) ?></strong></div>
      </div>

      <div class="rounded-xl bg-surface p-4 sm:p-5">
        <div class="mb-3 flex items-center justify-between gap-2">
          <h2 class="text-xs font-extrabold uppercase tracking-wide text-muted">Pembayaran
            <?= e(strtoupper($pesananDetail['metode_bayar'] ?? '')) ?></h2>
          <span
            class="rounded-md px-2.5 py-1 text-[11px] font-extrabold uppercase <?= $statusBayar === 'lunas' ? 'bg-ok-soft text-ok' : ($statusDetail === 'dibatalkan' ? 'bg-danger-soft text-danger' : 'bg-warn-soft text-warn') ?>"><?= $statusBayar === 'lunas' ? 'Lunas' : ($statusDetail === 'dibatalkan' ? 'Dibatalkan' : 'Belum lunas') ?></span>
        </div>
        <?php if (!empty($pesananDetail['bukti_bayar'])): ?>
        <?php $urlBukti = url('uploads/bukti/' . $pesananDetail['bukti_bayar']); ?>
        <a href="<?= e($urlBukti) ?>" target="_blank" rel="noopener"
          class="mb-3 block overflow-hidden rounded-lg border border-line bg-white">
          <img src="<?= e($urlBukti) ?>" alt="Bukti pembayaran pesanan" class="max-h-64 w-full object-contain"
            loading="lazy">
        </a>
        <?php elseif (($pesananDetail['metode_bayar'] ?? '') === 'qris'): ?>
        <p class="mb-3 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Bukti transfer belum diunggah pembeli.</p>
        <?php else: ?>
        <p class="mb-3 rounded-lg bg-blue-50 p-3 text-sm text-blue-900">Pembayaran tunai dilakukan langsung di tenant.
        </p>
        <?php endif; ?>
        <?php if ($statusBayar !== 'lunas' && $pesananDetail['metode_bayar'] !== null && $statusDetail !== 'dibatalkan'): ?>
        <form method="post" action="<?= e($urlHandler) ?>">
          <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>">
          <input type="hidden" name="id_pesanan" value="<?= (int) $idPesanan ?>">
          <input type="hidden" name="aksi" value="pembayaran">
          <button type="submit"
            class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40"><span
              aria-hidden="true">✓</span> Konfirmasi pembayaran</button>
        </form>
        <?php endif; ?>
      </div>

      <?php if ($targetStatus !== null || $bisaTolak): ?>
      <div class="mt-4 flex flex-wrap items-center gap-3">
        <?php if ($perluBayar): ?>
        <p class="flex-1 text-sm font-semibold text-muted">Konfirmasi pembayaran dulu untuk melanjutkan proses pesanan.
        </p>
        <?php endif; ?>
        <?php if ($targetStatus !== null): ?>
        <form method="post" action="<?= e($urlHandler) ?>" class="flex-1">
          <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden" name="id_pesanan"
            value="<?= (int) $idPesanan ?>"><input type="hidden" name="aksi" value="status"><input type="hidden"
            name="status" value="<?= e($targetStatus) ?>">
          <button type="submit"
            class="h-11 w-full rounded-lg bg-primary px-5 text-sm font-bold text-white hover:bg-primary-dark">Tandai
            <?= e($statusLabel[$targetStatus]) ?></button>
        </form>
        <?php endif; ?>
        <?php if ($bisaTolak): ?>
        <form method="post" action="<?= e($urlHandler) ?>" data-confirm="Tolak dan batalkan pesanan ini?">
          <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden" name="id_pesanan"
            value="<?= (int) $idPesanan ?>"><input type="hidden" name="aksi" value="status"><input type="hidden"
            name="status" value="dibatalkan">
          <button type="submit" class="h-11 rounded-lg px-4 text-sm font-bold text-danger hover:bg-danger-soft">Tolak
            pesanan</button>
        </form>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </section>

    <aside class="rounded-xl bg-white p-5 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <h2 class="text-xs font-extrabold uppercase tracking-wide text-muted">Alur proses pesanan</h2>
      <ol class="mt-5 space-y-0">
        <?php foreach ($tahapan as $index => $tahap): ?>
        <?php $sudahLewat = $posisiStatus !== false && $posisiStatus >= $index;
              $sedang = $statusDetail === $tahap; ?>
        <li class="relative flex gap-3 pb-5 last:pb-0">
          <?php if ($index < count($tahapan) - 1): ?><span
            class="absolute left-[10px] top-5 h-full w-px <?= $sudahLewat ? 'bg-primary' : 'bg-line' ?>"></span><?php endif; ?>
          <span
            class="relative z-10 mt-0.5 grid size-[21px] shrink-0 place-items-center rounded-full border-2 <?= $sudahLewat ? 'border-primary bg-primary text-white' : 'border-outline bg-white text-transparent' ?>"><?= $sudahLewat ? '✓' : '' ?></span>
          <div>
            <p class="text-sm font-bold <?= $sedang ? 'text-primary' : '' ?>"><?= e($statusLabel[$tahap]) ?></p>
            <p class="text-xs text-muted">
              <?= ['Pesanan masuk ke sistem', 'Sedang disiapkan dapur', 'Makanan di loket penyerahan', 'Telah diterima pembeli'][$index] ?>
            </p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
      <?php if (in_array($statusDetail, ['dibatalkan', 'selesai'], true)): ?>
      <p
        class="mt-4 border-t border-line/70 pt-4 text-sm font-semibold <?= $statusDetail === 'dibatalkan' ? 'text-danger' : 'text-ok' ?>">
        Pesanan <?= e(strtolower($statusLabel[$statusDetail])) ?>.</p>
      <?php endif; ?>
    </aside>
  </div>
  <?php endif; ?>
  <?php else: ?>
  <section class="mb-5">
    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Pesanan masuk</h1>
    <p class="mt-1 text-sm text-muted">Pantau antrean dan kelola pesanan tenant hari ini.</p>
  </section>

  <section aria-label="Ringkasan hari ini" class="mb-6 grid gap-3 sm:grid-cols-3">
    <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5">
      <p class="text-[11px] font-extrabold uppercase tracking-wide text-muted">Pendapatan hari ini</p>
      <p class="mt-2 text-2xl font-extrabold"><?= rupiah($statistik['pendapatan']) ?></p>
    </article>
    <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5">
      <p class="text-[11px] font-extrabold uppercase tracking-wide text-muted">Pesanan aktif</p>
      <p class="mt-2 text-2xl font-extrabold"><?= (int) $statistik['pesanan_aktif'] ?><span
          class="ml-1 text-sm font-semibold text-muted">Pesanan</span></p>
    </article>
    <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5">
      <p class="text-[11px] font-extrabold uppercase tracking-wide text-muted">Porsi terjual hari ini</p>
      <p class="mt-2 text-2xl font-extrabold"><?= (int) $statistik['porsi'] ?><span
          class="ml-1 text-sm font-semibold text-muted">Porsi</span></p>
    </article>
  </section>

  <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-wide">Antrean pesanan <span
        class="grid min-w-6 place-items-center rounded-full bg-primary px-1.5 py-1 text-[11px] text-white"><?= count($pesanan) ?></span>
    </h2>
    <form method="get" class="flex flex-col gap-2 sm:flex-row">
      <label class="sr-only" for="filter-status">Filter status pesanan</label>
      <select id="filter-status" name="status" class="h-10 rounded-lg border border-outline bg-white px-3 text-sm"
        onchange="this.form.submit()">
        <option value="aktif" <?= $filterStatus === 'aktif' ? 'selected' : '' ?>>Pesanan aktif</option>
        <?php foreach ($statusLabel as $nilai => $label): ?><option value="<?= e($nilai) ?>"
          <?= $filterStatus === $nilai ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
        <option value="semua" <?= $filterStatus === 'semua' ? 'selected' : '' ?>>Semua pesanan</option>
      </select>
      <label class="sr-only" for="cari-pesanan">Cari pesanan, pembeli, atau meja</label>
      <input id="cari-pesanan" type="search" name="cari" value="<?= e($cari) ?>"
        placeholder="Cari pembeli, meja, antrean..."
        class="h-10 w-full rounded-lg border border-outline bg-white px-3 text-sm sm:w-64">
      <button type="submit"
        class="h-10 rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary-dark">Cari</button>
    </form>
  </div>

  <?php if (!$pesanan): ?>
  <section
    class="rounded-xl bg-white px-5 py-12 text-center shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60">
    <p class="font-bold">Belum ada pesanan yang cocok.</p>
    <p class="mt-1 text-sm text-muted">Coba ubah filter atau kata pencarian.</p>
  </section>
  <?php else: ?>
  <section class="grid items-start gap-4 md:grid-cols-2">
    <?php foreach ($pesanan as $p): ?>
    <?php
          $lunasKartu = ($p['status_pembayaran'] ?? '') === 'lunas';
          $menunggu = $p['status_pesanan'] === 'menunggu_pembayaran';
          $metodeKartu = (string) ($p['metode_bayar'] ?? '');
          $adaBukti = !empty($p['bukti_bayar']);
          // Bisa dikonfirmasi langsung dari daftar: tunai, atau QRIS yang sudah ada bukti
          $bisaKonfirmasi = $menunggu && !$lunasKartu && ($metodeKartu === 'tunai' || ($metodeKartu === 'qris' && $adaBukti));
          $next = ($p['status_pesanan'] !== 'menunggu_pembayaran' && $lunasKartu)
            ? ($statusBerikutnya[$p['status_pesanan']] ?? null)
            : null;
          ?>
    <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="flex min-w-0 items-center gap-3"><span
            class="shrink-0 rounded-md bg-primary px-3 py-2 text-xs font-extrabold text-white">MEJA
            <?= e($p['no_meja']) ?></span>
          <div class="min-w-0">
            <p class="truncate text-sm font-bold"><?= e($p['nama_pembeli'] ?: 'Pembeli') ?></p>
            <p class="text-xs text-muted">Antrean <?= (int) $p['no_antrean'] ?> · #<?= (int) $p['id_pesanan'] ?></p>
          </div>
        </div>
        <div class="ml-auto text-right"><span
            class="inline-block rounded-md px-2 py-1 text-[10px] font-extrabold uppercase <?= in_array($p['status_pesanan'], ['selesai', 'dibatalkan'], true) ? ($p['status_pesanan'] === 'dibatalkan' ? 'bg-danger-soft text-danger' : 'bg-ok-soft text-ok') : 'bg-soft text-primary' ?>"><?= e($statusLabel[$p['status_pesanan']] ?? $p['status_pesanan']) ?></span>
          <p class="mt-1 text-[11px] text-muted"><?= e(date('H:i', strtotime($p['tanggal_pesanan']))) ?> WIB</p>
        </div>
      </div>
      <div class="mt-4 border-t border-line/70 pt-3">
        <p class="text-[10px] font-extrabold uppercase tracking-wide text-muted">Rincian menu pesanan</p>
        <p class="mt-1 line-clamp-2 min-h-5 text-sm text-muted">
          <?= e($p['ringkasan_menu'] ?: 'Belum ada rincian menu') ?></p>
        <div class="mt-3 flex items-center justify-between border-t border-line/50 pt-3"><span
            class="text-sm text-muted">Total pembayaran</span><strong
            class="text-base"><?= rupiah($p['total']) ?></strong></div>
        <?php if ($metodeKartu !== ''): ?>
        <div class="mt-2 flex items-center justify-between gap-2 text-xs text-muted">
          <span>Metode: <b class="uppercase text-ink"><?= e($metodeKartu) ?></b></span>
          <?php if ($adaBukti): ?>
          <a href="<?= e(url('uploads/bukti/' . $p['bukti_bayar'])) ?>" target="_blank" rel="noopener"
            class="font-bold text-primary hover:underline">Lihat bukti ↗</a>
          <?php elseif ($metodeKartu === 'qris'): ?>
          <span class="font-semibold text-warn">Bukti belum diunggah</span>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="mt-4">
        <p class="mb-2 text-[10px] font-extrabold uppercase tracking-wide text-muted">Progres alur dapur tenant</p>
        <div class="grid grid-cols-4 gap-1.5">
          <?php foreach ($tahapan as $index => $tahap): ?>
          <?php $selesaiTahap = array_search($p['status_pesanan'], $tahapan, true) !== false && array_search($p['status_pesanan'], $tahapan, true) >= $index; ?>
          <span
            class="rounded-md border px-1 py-2 text-center <?= $selesaiTahap ? 'border-primary bg-primary text-white' : 'border-line bg-surface text-muted' ?>"><b
              class="block text-[10px]"><?= $index + 1 ?>. <?= e($statusLabel[$tahap]) ?></b></span>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
        <a href="<?= e(url('pages/seller/dashboard/pesanan_masuk.php?id=' . (int) $p['id_pesanan'])) ?>"
          class="text-xs font-bold text-primary hover:underline">Lihat detail →</a>
        <div class="flex items-center gap-2">
          <?php if ($menunggu): ?>
          <form method="post" action="<?= e($urlHandler) ?>" data-confirm="Tolak dan batalkan pesanan ini?">
            <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden"
              name="id_pesanan" value="<?= (int) $p['id_pesanan'] ?>"><input type="hidden" name="aksi"
              value="status"><input type="hidden" name="status" value="dibatalkan">
            <button type="submit"
              class="rounded-lg px-3 py-2 text-xs font-extrabold text-danger hover:bg-danger-soft">Tolak</button>
          </form>
          <?php if ($bisaKonfirmasi): ?>
          <form method="post" action="<?= e($urlHandler) ?>">
            <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden"
              name="id_pesanan" value="<?= (int) $p['id_pesanan'] ?>"><input type="hidden" name="aksi"
              value="pembayaran">
            <button type="submit"
              class="rounded-lg bg-primary px-3 py-2 text-xs font-extrabold text-white hover:bg-primary-dark">✓
              Konfirmasi pembayaran</button>
          </form>
          <?php else: ?>
          <span class="rounded-md bg-warn-soft px-2.5 py-1.5 text-[11px] font-extrabold uppercase text-warn">Belum
            lunas</span>
          <?php endif; ?>
          <?php elseif ($next !== null): ?>
          <form method="post" action="<?= e($urlHandler) ?>">
            <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden"
              name="id_pesanan" value="<?= (int) $p['id_pesanan'] ?>"><input type="hidden" name="aksi"
              value="status"><input type="hidden" name="status" value="<?= e($next) ?>">
            <button type="submit"
              class="rounded-lg bg-soft px-3 py-2 text-xs font-extrabold text-primary hover:bg-line">Tandai
              <?= e(strtolower($statusLabel[$next])) ?></button>
          </form>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </section>
  <?php endif; ?>

  <aside class="mt-5 flex gap-3 rounded-xl bg-soft p-4 text-sm text-primary"><span
      class="grid size-5 shrink-0 place-items-center rounded-full border border-primary font-bold"
      aria-hidden="true">i</span>
    <div>
      <p class="font-extrabold">Petunjuk antrean tenant</p>
      <p class="mt-0.5 text-xs leading-relaxed">Perbarui status sesuai alur dapur. Setelah pesanan ditandai siap
        diambil, pembeli dapat melihat status antreannya.</p>
    </div>
  </aside>
  <?php endif; ?>

</main>

<script>
document.querySelectorAll('form[data-confirm]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    if (!window.confirm(form.dataset.confirm)) event.preventDefault();
  });
});
</script>