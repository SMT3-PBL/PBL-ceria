<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Laporan.php';

require_role('seller');

$tenant   = tenant_aktif();
$idTenant = (string) $tenant['id_tenant'];

// Validasi tanggal dari query string, default: awal bulan ini sampai hari ini
function tanggal_valid(?string $v, string $default): string {
  $d = DateTime::createFromFormat('Y-m-d', (string) $v);
  return ($d && $d->format('Y-m-d') === $v) ? $v : $default;
}
$dari   = tanggal_valid($_GET['dari']   ?? null, date('Y-m-01'));
$sampai = tanggal_valid($_GET['sampai'] ?? null, date('Y-m-d'));
if ($dari > $sampai) {
  [$dari, $sampai] = [$sampai, $dari];
}

$laporan   = new Laporan($pdo);
$ringkasan = $laporan->ringkasan($idTenant, $dari, $sampai);
$terlaris  = $laporan->menuTerlaris($idTenant, $dari, $sampai);
$riwayat   = $laporan->riwayat($idTenant, $dari, $sampai);

$jumlahPesanan = (int) $ringkasan['jumlah_pesanan'];
$pendapatan    = (int) $ringkasan['total_pendapatan'];
$rataRata      = $jumlahPesanan > 0 ? (int) round($pendapatan / $jumlahPesanan) : 0;

function rp(int|float|string $n): string {
  return 'Rp' . number_format((float) $n, 0, ',', '.');
}

$pageTitle = 'Laporan Penjualan';
$role      = 'seller';
$active    = 'reports';
require __DIR__ . '/../../../includes/header.php';

// URL: ?dari=2026-11-01&sampai=2026-11-05

foreach ($_GET as $key => $value) {
  echo "$key = $value<br>";
}
?>

<main class="mx-auto max-w-6xl space-y-4 p-4 pb-10 sm:p-6 lg:space-y-6">

  <!-- Judul + filter tanggal -->
  <section class="flex flex-wrap items-end justify-between gap-4 print:hidden">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight">Laporan Penjualan</h1>
      <p class="text-sm text-muted">Hanya pesanan berstatus selesai yang dihitung.</p>
    </div>
    <form method="get" action="" class="flex flex-wrap items-end gap-3">
      <label class="text-xs font-bold uppercase tracking-wider text-muted">Dari
        <input type="date" name="dari" value="<?= e($dari) ?>" max="<?= e(date('Y-m-d')) ?>"
          class="mt-1 block h-11 rounded-lg border border-outline bg-white px-3 text-base font-normal normal-case tracking-normal text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
      </label>
      <label class="text-xs font-bold uppercase tracking-wider text-muted">Sampai
        <input type="date" name="sampai" value="<?= e($sampai) ?>" max="<?= e(date('Y-m-d')) ?>"
          class="mt-1 block h-11 rounded-lg border border-outline bg-white px-3 text-base font-normal normal-case tracking-normal text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
      </label>
      <button type="submit"
        class="h-11 rounded-lg bg-primary px-5 text-sm font-semibold text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">Tampilkan</button>
      <button type="button" onclick="window.print()"
        class="h-11 rounded-lg bg-soft px-5 text-sm font-bold text-primary hover:bg-line">Cetak</button>
    </form>
  </section>

  <!-- Judul khusus cetak -->
  <div class="hidden print:block">
    <h1 class="text-xl font-extrabold"><?= e($tenant['nama_tenant'] ?? '-') ?> - Laporan Penjualan</h1>
    <p class="text-sm">Periode <?= e(date('d/m/Y', strtotime($dari))) ?> s/d <?= e(date('d/m/Y', strtotime($sampai))) ?>
    </p>
  </div>

  <!-- Kartu ringkasan -->
  <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <?php
    $kartu = [
      ['Total Pendapatan', rp($pendapatan), 'text-primary'],
      ['Jumlah Pesanan', (string) $jumlahPesanan, 'text-ink'],
      ['Menu Terjual', (string) (int) $ringkasan['total_item'], 'text-ink'],
      ['Rata-rata / Pesanan', rp($rataRata), 'text-ink'],
    ];
    foreach ($kartu as [$label, $nilai, $warna]): ?>
    <div class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <p class="text-[11px] font-extrabold uppercase tracking-wider text-muted"><?= e($label) ?></p>
      <p class="mt-2 text-2xl font-extrabold <?= $warna ?>"><?= e($nilai) ?></p>
    </div>
    <?php endforeach; ?>
  </section>

  <div class="grid gap-4 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)] lg:gap-6">

    <!-- Menu terlaris -->
    <section class="h-fit rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <h2 class="mb-3 text-[11px] font-extrabold uppercase tracking-wider text-muted">Menu Terlaris</h2>
      <?php if (!$terlaris): ?>
      <p class="py-4 text-center text-sm text-muted">Belum ada penjualan pada periode ini.</p>
      <?php else: ?>
      <ol class="space-y-2">
        <?php foreach ($terlaris as $i => $m): ?>
        <li class="flex items-center gap-3 rounded-lg bg-surface px-3 py-2.5">
          <span
            class="grid size-7 shrink-0 place-items-center rounded-full bg-soft text-xs font-extrabold text-primary"><?= $i + 1 ?></span>
          <span class="min-w-0 flex-1">
            <span class="block truncate text-sm font-bold"><?= e($m['nama_menu']) ?></span>
            <span class="block text-xs text-muted"><?= rp($m['pendapatan']) ?></span>
          </span>
          <span class="text-sm font-extrabold"><?= (int) $m['terjual'] ?>x</span>
        </li>
        <?php endforeach; ?>
      </ol>
      <?php endif; ?>
    </section>

    <!-- Riwayat pesanan -->
    <section
      class="min-w-0 rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <div class="mb-4 flex items-center gap-2">
        <h2 class="text-lg font-extrabold uppercase tracking-tight">Riwayat Penjualan</h2>
        <span class="rounded-full bg-soft px-2.5 py-0.5 text-xs font-bold text-primary"><?= count($riwayat) ?>
          Pesanan</span>
      </div>

      <?php if (!$riwayat): ?>
      <p class="py-6 text-center text-sm text-muted">Tidak ada pesanan selesai pada periode ini.</p>
      <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[600px] text-left text-sm">
          <thead>
            <tr class="border-b border-line text-[11px] font-extrabold uppercase tracking-wider text-muted">
              <th class="py-2 pr-3">Antrean</th>
              <th class="py-2 pr-3">Waktu</th>
              <th class="py-2 pr-3">Pembeli</th>
              <th class="py-2 pr-3 text-center">Meja</th>
              <th class="py-2 pr-3 text-center">Item</th>
              <th class="py-2 pr-3 text-center">Bayar</th>
              <th class="py-2 text-right">Total</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-line/60">
            <?php foreach ($riwayat as $r): ?>
            <tr>
              <td class="py-3 pr-3 font-bold">#<?= (int) $r['no_antrean'] ?></td>
              <td class="py-3 pr-3 text-muted"><?= e(date('d/m/Y H:i', strtotime($r['waktu_pesan']))) ?></td>
              <td class="py-3 pr-3"><?= e($r['nama_pembeli'] ?? '-') ?></td>
              <td class="py-3 pr-3 text-center"><?= e((string) ($r['no_meja'] ?? '-')) ?></td>
              <td class="py-3 pr-3 text-center"><?= (int) $r['total_item'] ?></td>
              <td class="py-3 pr-3 text-center text-xs font-bold uppercase"><?= e($r['metode_bayar'] ?? '-') ?></td>
              <td class="py-3 text-right font-extrabold"><?= rp($r['total']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="border-t-2 border-line">
              <td colspan="6" class="py-3 pr-3 text-right text-xs font-extrabold uppercase tracking-wider text-muted">
                Total</td>
              <td class="py-3 text-right text-base font-extrabold text-primary"><?= rp($pendapatan) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>