<?php
require_once __DIR__ . '/../../includes/init.php';

$pageTitle = 'Pesanan Masuk';
$role = 'seller';
$active = 'orders';
$idTenant = (int) (tenant_aktif()['id_tenant'] ?? 0);

if ($idTenant <= 0 && !empty($_SESSION['user_id'])) {
    $stmtTenant = $pdo->prepare('SELECT id_tenant FROM tenant WHERE id_pengguna = ?');
    $stmtTenant->execute([(int) $_SESSION['user_id']]);
    $idTenant = (int) $stmtTenant->fetchColumn();
}

if ($idTenant <= 0) {
    die('Session tenant tidak ditemukan. Silakan login kembali.');
}

if (empty($_SESSION['csrf_pesanan'])) {
    $_SESSION['csrf_pesanan'] = bin2hex(random_bytes(32));
}
$tokenCsrf = $_SESSION['csrf_pesanan'];
$idPesanan = (int) (filter_var(
    $_GET['id'] ?? $_GET['id_pesanan'] ?? $_POST['id_pesanan'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
) ?: 0);
$statusBerikutnya = [
    'menunggu_pembayaran' => 'diterima',
    'diterima' => 'diproses',
    'diproses' => 'siap_diambil',
    'siap_diambil' => 'selesai',
];
$statusLabel = [
    'menunggu_pembayaran' => 'Menunggu Pembayaran',
    'diterima' => 'Diterima',
    'diproses' => 'Diproses',
    'siap_diambil' => 'Siap Diambil',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Ditolak',
];
$tahapan = ['diterima', 'diproses', 'siap_diambil', 'selesai'];
$filterStatus = (string) ($_GET['status'] ?? 'aktif');
$cari = trim((string) ($_GET['cari'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesanAksi = '';
    if (!hash_equals($tokenCsrf, (string) ($_POST['csrf_token'] ?? ''))) {
        $pesanAksi = 'Sesi formulir tidak valid. Muat ulang halaman lalu coba lagi.';
    } elseif ($idPesanan <= 0) {
        $pesanAksi = 'Pesanan tidak valid.';
    } else {
        $stmtStatus = $pdo->prepare('SELECT status_pesanan FROM pesanan WHERE id_pesanan = ? AND id_tenant = ?');
        $stmtStatus->execute([$idPesanan, $idTenant]);
        $statusSaatIni = $stmtStatus->fetchColumn();

        if ($statusSaatIni === false) {
            $pesanAksi = 'Pesanan tidak ditemukan atau bukan milik tenant ini.';
        } elseif (($_POST['aksi'] ?? '') === 'pembayaran') {
            $update = $pdo->prepare(
                "UPDATE pembayaran b
                SET status_pembayaran = 'lunas', waktu_konfirmasi = now()
                FROM pesanan p
                WHERE b.id_pesanan = p.id_pesanan
                    AND b.id_pesanan = ? AND p.id_tenant = ?
                    AND b.status_pembayaran = 'belum_lunas'
                    AND (b.metode_bayar = 'tunai' OR b.bukti_bayar IS NOT NULL)"
            );
            $update->execute([$idPesanan, $idTenant]);
            $pesanAksi = $update->rowCount() > 0
                ? 'Pembayaran berhasil dikonfirmasi.'
                : 'Pembayaran belum dapat dikonfirmasi. Pastikan bukti QRIS tersedia atau pembayaran tunai sudah diterima.';
        } elseif (($_POST['aksi'] ?? '') === 'status') {
            $statusBaru = (string) ($_POST['status'] ?? '');
            $bolehUbah = ($statusBerikutnya[$statusSaatIni] ?? null) === $statusBaru
                || ($statusBaru === 'dibatalkan' && !in_array($statusSaatIni, ['selesai', 'dibatalkan'], true));

            if ($bolehUbah) {
                $update = $pdo->prepare(
                    'UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ? AND id_tenant = ? AND status_pesanan = ?'
                );
                $update->execute([$statusBaru, $idPesanan, $idTenant, $statusSaatIni]);
                $pesanAksi = $update->rowCount() > 0
                    ? 'Status pesanan diperbarui menjadi ' . ($statusLabel[$statusBaru] ?? $statusBaru) . '.'
                    : 'Status pesanan berubah di perangkat lain. Muat ulang halaman.';
            } else {
                $pesanAksi = 'Perubahan status tidak sesuai urutan proses pesanan.';
            }
        } else {
            $pesanAksi = 'Aksi tidak dikenali.';
        }
    }

    $_SESSION['pesanan_flash'] = $pesanAksi;
    $kembaliKe = $idPesanan > 0 ? '?id=' . $idPesanan : '';
    header('Location: ' . url('pages/seller/pesanan_masuk.php' . $kembaliKe));
    exit;
}

$pesanFlash = (string) ($_SESSION['pesanan_flash'] ?? '');
unset($_SESSION['pesanan_flash']);

$stmtStatistik = $pdo->prepare(
    "SELECT
        COALESCE(SUM(CASE WHEN b.status_pembayaran = 'lunas' AND p.status_pesanan <> 'dibatalkan' THEN d.subtotal ELSE 0 END), 0) AS pendapatan,
        COUNT(DISTINCT CASE WHEN p.status_pesanan NOT IN ('selesai', 'dibatalkan') THEN p.id_pesanan END) AS pesanan_aktif,
        COALESCE(SUM(CASE WHEN p.status_pesanan <> 'dibatalkan' THEN d.jumlah ELSE 0 END), 0) AS porsi
    FROM pesanan p
    LEFT JOIN pembayaran b ON b.id_pesanan = p.id_pesanan
    LEFT JOIN detail_pesanan d ON d.id_pesanan = p.id_pesanan
    WHERE p.id_tenant = ? AND p.tanggal_pesanan::date = CURRENT_DATE"
);
$stmtStatistik->execute([$idTenant]);
$statistik = $stmtStatistik->fetch(PDO::FETCH_ASSOC) ?: ['pendapatan' => 0, 'pesanan_aktif' => 0, 'porsi' => 0];

$where = ['p.id_tenant = ?'];
$params = [$idTenant];
if ($filterStatus === 'aktif') {
    $where[] = "p.status_pesanan NOT IN ('selesai', 'dibatalkan')";
} elseif (in_array($filterStatus, array_keys($statusLabel), true)) {
    $where[] = 'p.status_pesanan = ?';
    $params[] = $filterStatus;
}
if ($cari !== '') {
    $where[] = '(s.nama ILIKE ? OR s.no_meja ILIKE ? OR CAST(p.no_antrean AS TEXT) ILIKE ? OR CAST(p.id_pesanan AS TEXT) ILIKE ?)';
    array_push($params, '%' . $cari . '%', '%' . $cari . '%', '%' . $cari . '%', '%' . $cari . '%');
}
$stmtPesanan = $pdo->prepare(
    "SELECT p.id_pesanan, p.no_antrean, p.status_pesanan, p.tanggal_pesanan,
        s.nama AS nama_pembeli, s.no_meja,
        b.metode_bayar, b.status_pembayaran,
        COALESCE(SUM(d.subtotal), 0) AS total,
        COALESCE(SUM(d.jumlah), 0) AS porsi,
        string_agg(d.nama_menu || ' × ' || d.jumlah, ', ' ORDER BY d.id_detail) AS ringkasan_menu
    FROM pesanan p
    JOIN sesi_pembeli s ON s.id_sesi = p.id_sesi
    LEFT JOIN pembayaran b ON b.id_pesanan = p.id_pesanan
    LEFT JOIN detail_pesanan d ON d.id_pesanan = p.id_pesanan
    WHERE " . implode(' AND ', $where) . "
    GROUP BY p.id_pesanan, p.no_antrean, p.status_pesanan, p.tanggal_pesanan,
        s.nama, s.no_meja, b.metode_bayar, b.status_pembayaran
    ORDER BY CASE p.status_pesanan
        WHEN 'menunggu_pembayaran' THEN 1 WHEN 'diterima' THEN 2
        WHEN 'diproses' THEN 3 WHEN 'siap_diambil' THEN 4 ELSE 5 END,
        p.tanggal_pesanan DESC"
);
$stmtPesanan->execute($params);
$pesanan = $stmtPesanan->fetchAll(PDO::FETCH_ASSOC);

$pesananDetail = null;
$detail = [];
$totalDetail = 0;
if ($idPesanan > 0) {
    $stmtDetailPesanan = $pdo->prepare(
        "SELECT p.*, s.nama AS nama_pembeli, s.no_meja,
            b.metode_bayar, b.jumlah_bayar, b.status_pembayaran, b.bukti_bayar
        FROM pesanan p
        JOIN sesi_pembeli s ON s.id_sesi = p.id_sesi
        LEFT JOIN pembayaran b ON b.id_pesanan = p.id_pesanan
        WHERE p.id_pesanan = ? AND p.id_tenant = ?"
    );
    $stmtDetailPesanan->execute([$idPesanan, $idTenant]);
    $pesananDetail = $stmtDetailPesanan->fetch(PDO::FETCH_ASSOC) ?: null;
    if ($pesananDetail) {
        $stmtItem = $pdo->prepare('SELECT nama_menu, harga_satuan, jumlah, subtotal FROM detail_pesanan WHERE id_pesanan = ? ORDER BY id_detail ASC');
        $stmtItem->execute([$idPesanan]);
        $detail = $stmtItem->fetchAll(PDO::FETCH_ASSOC);
        $totalDetail = array_sum(array_map(static fn(array $item): float => (float) $item['subtotal'], $detail));
    }
}

require __DIR__ . '/../../includes/header.php';
?>

<main class="mx-auto max-w-6xl px-4 py-5 pb-10 sm:px-6">
    <?php if ($pesanFlash !== ''): ?>
        <p role="status" class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-900"><?= e($pesanFlash) ?></p>
    <?php endif; ?>

    <?php if ($idPesanan > 0): ?>
        <?php if (!$pesananDetail): ?>
            <section role="alert" class="rounded-xl border border-rose-200 bg-white p-6 text-sm font-semibold text-rose-800">Pesanan tidak ditemukan atau bukan milik tenant ini. <a class="ml-2 text-primary underline" href="<?= e(url('pages/seller/pesanan_masuk.php')) ?>">Kembali ke pesanan masuk</a></section>
        <?php else: ?>
            <?php
            $statusDetail = $pesananDetail['status_pesanan'];
            $statusBayar = $pesananDetail['status_pembayaran'] ?? 'belum_lunas';
            $posisiStatus = array_search($statusDetail, $tahapan, true);
            $targetStatus = $statusBerikutnya[$statusDetail] ?? null;
            ?>
            <a href="<?= e(url('pages/seller/pesanan_masuk.php')) ?>" class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-muted hover:text-primary"><span aria-hidden="true">←</span> Kembali ke pesanan masuk</a>
            <div class="grid items-start gap-4 lg:grid-cols-[minmax(0,1.8fr)_minmax(280px,0.9fr)]">
                <section class="rounded-xl bg-white p-5 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-line/70 pb-4">
                        <div>
                            <h1 class="text-2xl font-extrabold tracking-tight"><?= e($pesananDetail['nama_pembeli'] ?: 'Pembeli') ?></h1>
                            <p class="mt-1 text-sm text-muted">Antrean <?= (int) $pesananDetail['no_antrean'] ?> · <?= e(date('H:i', strtotime($pesananDetail['tanggal_pesanan']))) ?> WIB</p>
                        </div>
                            <div class="flex flex-wrap items-center gap-2"><span class="rounded-md px-2.5 py-1 text-[10px] font-extrabold uppercase <?= $statusDetail === 'dibatalkan' ? 'bg-danger-soft text-danger' : ($statusDetail === 'selesai' ? 'bg-ok-soft text-ok' : 'bg-soft text-primary') ?>"><?= e($statusLabel[$statusDetail] ?? $statusDetail) ?></span><span class="rounded-lg bg-soft px-3 py-2 text-sm font-extrabold text-primary">MEJA <?= e($pesananDetail['no_meja']) ?></span></div>
                    </div>

                    <div class="py-4">
                        <h2 class="mb-3 text-xs font-extrabold uppercase tracking-wide text-muted">Daftar pesanan</h2>
                        <ul class="divide-y divide-line/70">
                            <?php foreach ($detail as $item): ?>
                                <li class="flex items-center justify-between gap-3 py-3 text-sm">
                                    <span><b><?= (int) $item['jumlah'] ?>×</b> <?= e($item['nama_menu']) ?><small class="mt-1 block text-muted"><?= rupiah($item['harga_satuan']) ?> / porsi</small></span>
                                    <b class="shrink-0"><?= rupiah($item['subtotal']) ?></b>
                                </li>
                            <?php endforeach; ?>
                            <?php if (!$detail): ?><li class="py-3 text-sm text-muted">Belum ada rincian menu.</li><?php endif; ?>
                        </ul>
                        <div class="mt-3 flex items-center justify-between border-t border-line/70 pt-4 text-sm text-muted"><span>Total pembayaran</span><strong class="text-lg text-ink"><?= rupiah($totalDetail) ?></strong></div>
                    </div>

                    <div class="rounded-xl bg-surface p-4 sm:p-5">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <h2 class="text-xs font-extrabold uppercase tracking-wide text-muted">Pembayaran <?= e(strtoupper($pesananDetail['metode_bayar'] ?? '')) ?></h2>
                            <span class="rounded-md px-2.5 py-1 text-[11px] font-extrabold uppercase <?= $statusBayar === 'lunas' ? 'bg-ok-soft text-ok' : 'bg-warn-soft text-warn' ?>"><?= $statusBayar === 'lunas' ? 'Lunas' : 'Belum lunas' ?></span>
                        </div>
                        <?php if (!empty($pesananDetail['bukti_bayar'])): ?>
                            <a href="<?= e(url($pesananDetail['bukti_bayar'])) ?>" target="_blank" rel="noopener" class="mb-3 block overflow-hidden rounded-lg border border-line bg-white">
                                <img src="<?= e(url($pesananDetail['bukti_bayar'])) ?>" alt="Bukti pembayaran pesanan" class="max-h-64 w-full object-contain" loading="lazy">
                            </a>
                        <?php elseif (($pesananDetail['metode_bayar'] ?? '') === 'qris'): ?>
                            <p class="mb-3 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Bukti transfer belum diunggah pembeli.</p>
                        <?php else: ?>
                            <p class="mb-3 rounded-lg bg-blue-50 p-3 text-sm text-blue-900">Pembayaran tunai dilakukan langsung di tenant.</p>
                        <?php endif; ?>
                        <?php if ($statusBayar !== 'lunas' && $pesananDetail['metode_bayar'] !== null): ?>
                            <form method="post">
                                <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>">
                                <input type="hidden" name="id_pesanan" value="<?= (int) $idPesanan ?>">
                                <input type="hidden" name="aksi" value="pembayaran">
                                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40"><span aria-hidden="true">✓</span> Konfirmasi pembayaran</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if ($targetStatus !== null || !in_array($statusDetail, ['selesai', 'dibatalkan'], true)): ?>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <?php if ($targetStatus !== null): ?>
                                <form method="post" class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden" name="id_pesanan" value="<?= (int) $idPesanan ?>"><input type="hidden" name="aksi" value="status"><input type="hidden" name="status" value="<?= e($targetStatus) ?>">
                                    <button type="submit" class="h-11 w-full rounded-lg bg-primary px-5 text-sm font-bold text-white hover:bg-primary-dark"><?= $statusDetail === 'menunggu_pembayaran' ? 'Terima Pesanan' : e($statusLabel[$targetStatus]) ?></button>
                                </form>
                            <?php endif; ?>
                            <?php if (!in_array($statusDetail, ['selesai', 'dibatalkan'], true)): ?>
                                <form method="post" data-confirm="Tolak dan batalkan pesanan ini?">
                                    <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden" name="id_pesanan" value="<?= (int) $idPesanan ?>"><input type="hidden" name="aksi" value="status"><input type="hidden" name="status" value="dibatalkan">
                                    <button type="submit" class="h-11 rounded-lg px-4 text-sm font-bold text-danger hover:bg-danger-soft">Tolak pesanan</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <aside class="rounded-xl bg-white p-5 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
                    <h2 class="text-xs font-extrabold uppercase tracking-wide text-muted">Alur proses pesanan</h2>
                    <ol class="mt-5 space-y-0">
                        <?php foreach ($tahapan as $index => $tahap): ?>
                            <?php $sudahLewat = $posisiStatus !== false && $posisiStatus >= $index; $sedang = $statusDetail === $tahap; ?>
                            <li class="relative flex gap-3 pb-5 last:pb-0">
                                <?php if ($index < count($tahapan) - 1): ?><span class="absolute left-[10px] top-5 h-full w-px <?= $sudahLewat ? 'bg-primary' : 'bg-line' ?>"></span><?php endif; ?>
                                <span class="relative z-10 mt-0.5 grid size-[21px] shrink-0 place-items-center rounded-full border-2 <?= $sudahLewat ? 'border-primary bg-primary text-white' : 'border-outline bg-white text-transparent' ?>"><?= $sudahLewat ? '✓' : '' ?></span>
                                <div><p class="text-sm font-bold <?= $sedang ? 'text-primary' : '' ?>"><?= e($statusLabel[$tahap]) ?></p><p class="text-xs text-muted"><?= ['Pesanan masuk ke sistem', 'Sedang disiapkan dapur', 'Makanan di loket penyerahan', 'Telah diterima pembeli'][$index] ?></p></div>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                    <?php if (in_array($statusDetail, ['dibatalkan', 'selesai'], true)): ?>
                        <p class="mt-4 border-t border-line/70 pt-4 text-sm font-semibold <?= $statusDetail === 'dibatalkan' ? 'text-danger' : 'text-ok' ?>">Pesanan <?= e(strtolower($statusLabel[$statusDetail])) ?>.</p>
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
            <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5"><p class="text-[11px] font-extrabold uppercase tracking-wide text-muted">Pendapatan hari ini</p><p class="mt-2 text-2xl font-extrabold"><?= rupiah($statistik['pendapatan']) ?></p></article>
            <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5"><p class="text-[11px] font-extrabold uppercase tracking-wide text-muted">Pesanan aktif</p><p class="mt-2 text-2xl font-extrabold"><?= (int) $statistik['pesanan_aktif'] ?><span class="ml-1 text-sm font-semibold text-muted">Pesanan</span></p></article>
            <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5"><p class="text-[11px] font-extrabold uppercase tracking-wide text-muted">Porsi terjual hari ini</p><p class="mt-2 text-2xl font-extrabold"><?= (int) $statistik['porsi'] ?><span class="ml-1 text-sm font-semibold text-muted">Porsi</span></p></article>
        </section>

        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-wide">Antrean pesanan <span class="grid min-w-6 place-items-center rounded-full bg-primary px-1.5 py-1 text-[11px] text-white"><?= count($pesanan) ?></span></h2>
            <form method="get" class="flex flex-col gap-2 sm:flex-row">
                <label class="sr-only" for="filter-status">Filter status pesanan</label>
                <select id="filter-status" name="status" class="h-10 rounded-lg border border-outline bg-white px-3 text-sm" onchange="this.form.submit()">
                    <option value="aktif" <?= $filterStatus === 'aktif' ? 'selected' : '' ?>>Pesanan aktif</option>
                    <?php foreach ($statusLabel as $nilai => $label): ?><option value="<?= e($nilai) ?>" <?= $filterStatus === $nilai ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
                    <option value="semua" <?= $filterStatus === 'semua' ? 'selected' : '' ?>>Semua pesanan</option>
                </select>
                <label class="sr-only" for="cari-pesanan">Cari pesanan, pembeli, atau meja</label>
                <input id="cari-pesanan" type="search" name="cari" value="<?= e($cari) ?>" placeholder="Cari pembeli, meja, antrean..." class="h-10 w-full rounded-lg border border-outline bg-white px-3 text-sm sm:w-64">
                <button type="submit" class="h-10 rounded-lg bg-primary px-4 text-sm font-bold text-white hover:bg-primary-dark">Cari</button>
            </form>
        </div>

        <?php if (!$pesanan): ?>
            <section class="rounded-xl bg-white px-5 py-12 text-center shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60"><p class="font-bold">Belum ada pesanan yang cocok.</p><p class="mt-1 text-sm text-muted">Coba ubah filter atau kata pencarian.</p></section>
        <?php else: ?>
            <section class="grid items-start gap-4 md:grid-cols-2">
                <?php foreach ($pesanan as $p): ?>
                    <?php $next = $statusBerikutnya[$p['status_pesanan']] ?? null; ?>
                    <article class="rounded-xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3"><span class="shrink-0 rounded-md bg-primary px-3 py-2 text-xs font-extrabold text-white">MEJA <?= e($p['no_meja']) ?></span><div class="min-w-0"><p class="truncate text-sm font-bold"><?= e($p['nama_pembeli'] ?: 'Pembeli') ?></p><p class="text-xs text-muted">Antrean <?= (int) $p['no_antrean'] ?> · #<?= (int) $p['id_pesanan'] ?></p></div></div>
                            <div class="ml-auto text-right"><span class="inline-block rounded-md px-2 py-1 text-[10px] font-extrabold uppercase <?= in_array($p['status_pesanan'], ['selesai', 'dibatalkan'], true) ? ($p['status_pesanan'] === 'dibatalkan' ? 'bg-danger-soft text-danger' : 'bg-ok-soft text-ok') : 'bg-soft text-primary' ?>"><?= e($statusLabel[$p['status_pesanan']] ?? $p['status_pesanan']) ?></span><p class="mt-1 text-[11px] text-muted"><?= e(date('H:i', strtotime($p['tanggal_pesanan']))) ?> WIB</p></div>
                        </div>
                        <div class="mt-4 border-t border-line/70 pt-3">
                            <p class="text-[10px] font-extrabold uppercase tracking-wide text-muted">Rincian menu pesanan</p>
                            <p class="mt-1 line-clamp-2 min-h-5 text-sm text-muted"><?= e($p['ringkasan_menu'] ?: 'Belum ada rincian menu') ?></p>
                            <div class="mt-3 flex items-center justify-between border-t border-line/50 pt-3"><span class="text-sm text-muted">Total pembayaran</span><strong class="text-base"><?= rupiah($p['total']) ?></strong></div>
                        </div>
                        <div class="mt-4">
                            <p class="mb-2 text-[10px] font-extrabold uppercase tracking-wide text-muted">Progres alur dapur tenant</p>
                            <div class="grid grid-cols-4 gap-1.5">
                                <?php foreach ($tahapan as $index => $tahap): ?>
                                    <?php $selesaiTahap = array_search($p['status_pesanan'], $tahapan, true) !== false && array_search($p['status_pesanan'], $tahapan, true) >= $index; ?>
                                    <span class="rounded-md border px-1 py-2 text-center <?= $selesaiTahap ? 'border-primary bg-primary text-white' : 'border-line bg-surface text-muted' ?>"><b class="block text-[10px]"><?= $index + 1 ?>. <?= e($statusLabel[$tahap]) ?></b></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <a href="<?= e(url('pages/seller/pesanan_masuk.php?id=' . (int) $p['id_pesanan'])) ?>" class="text-xs font-bold text-primary hover:underline">Lihat detail →</a>
                            <?php if ($next !== null): ?>
                                <form method="post">
                                    <input type="hidden" name="csrf_token" value="<?= e($tokenCsrf) ?>"><input type="hidden" name="id_pesanan" value="<?= (int) $p['id_pesanan'] ?>"><input type="hidden" name="aksi" value="status"><input type="hidden" name="status" value="<?= e($next) ?>">
                                    <button type="submit" class="rounded-lg bg-soft px-3 py-2 text-xs font-extrabold text-primary hover:bg-line"><?= $p['status_pesanan'] === 'menunggu_pembayaran' ? 'Terima pesanan' : 'Tandai ' . e(strtolower($statusLabel[$next])) ?></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <aside class="mt-5 flex gap-3 rounded-xl bg-soft p-4 text-sm text-primary"><span class="grid size-5 shrink-0 place-items-center rounded-full border border-primary font-bold" aria-hidden="true">i</span><div><p class="font-extrabold">Petunjuk antrean tenant</p><p class="mt-0.5 text-xs leading-relaxed">Perbarui status sesuai alur dapur. Setelah pesanan ditandai siap diambil, pembeli dapat melihat status antreannya.</p></div></aside>
    <?php endif; ?>
</main>

<script>
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});
</script>