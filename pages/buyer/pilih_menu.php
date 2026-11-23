<?php
require_once __DIR__ . '/../../includes/init.php';
if (empty($_SESSION['sesi']['id_sesi'])) {
  redirect('pages/buyer/dashboard.php');
}
require_once __DIR__ . '/../../models/Tenant.php';
require_once __DIR__ . '/../../models/Menu.php';

$pageTitle = 'Pilih Menu & Tenant';
$role      = 'buyer';
$active    = 'menu';

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Buang item lama yang tidak punya id_tenant
$_SESSION['cart'] = array_filter(
  $_SESSION['cart'],
  fn($i) => !empty($i['id_tenant'])
);

$tenantModel = new Tenant($pdo);
$menuModel   = new Menu($pdo);

$kategori_filter = $_GET['kategori'] ?? 'Semua Menu';

// Hanya tenant yang statusnya buka
$daftarTenant = array_filter(
  $tenantModel->semua(),
  fn($t) => $t['status_tenant'] === 'buka'
);

$daftarTenant = array_reverse($daftarTenant);

// Tenant yang sedang dibuka (kalau ?tenant kosong, pakai tenant pertama yang buka)
$tenant_id = (int)($_GET['tenant'] ?? ($daftarTenant[0]['id_tenant'] ?? 0));

// Tolak tenant yang tutup walaupun id-nya diketik manual di URL
$idBuka         = array_map('intval', array_column($daftarTenant, 'id_tenant'));
$tenantTerpilih = in_array($tenant_id, $idBuka, true) ? $tenantModel->cari($tenant_id) : null;

// 'Semua Menu' = tanpa filter kategori
$kategoriQuery = ($kategori_filter === 'Semua Menu') ? '' : $kategori_filter;
$menuList = $tenantTerpilih ? $menuModel->getMenusByTenant((string)$tenant_id, $kategoriQuery) : [];

$jumlahBuka = count(array_filter($daftarTenant, fn($t) => $t['status_tenant'] === 'buka'));

// Jumlah item per tenant (untuk badge di daftar tenant)
$jumlahPerTenant = [];
foreach ($_SESSION['cart'] as $item) {
  $tid = (int)$item['id_tenant'];
  $jumlahPerTenant[$tid] = ($jumlahPerTenant[$tid] ?? 0) + (int)$item['jumlah'];
}

// Keranjang milik tenant yang sedang dibuka
$itemKeranjang = $tenantTerpilih
  ? array_filter($_SESSION['cart'], fn($i) => (int)$i['id_tenant'] === $tenant_id)
  : [];

require __DIR__ . '/../../includes/header.php';
?>
<main
  class="mx-auto grid max-w-7xl grid-cols-1 gap-4 p-4 pb-28 md:grid-cols-[minmax(0,1fr)_320px] md:pb-6 lg:grid-cols-[300px_minmax(0,1fr)_330px] lg:gap-5">

  <!-- Daftar Tenant -->
  <aside
    class="h-fit min-w-0 rounded-xl border border-line bg-white p-4 md:col-span-2 lg:sticky lg:top-24 lg:col-span-1">
    <div class="mb-3 flex items-center justify-between gap-2">
      <h2 class="text-xs font-bold uppercase tracking-wider text-muted">Daftar Tenant</h2>
      <span class="rounded-full bg-soft px-2.5 py-0.5 text-xs font-semibold text-primary">
        <?= $jumlahBuka ?> Buka
      </span>
    </div>
    <ul class="space-y-2">
      <?php foreach ($daftarTenant as $t): ?>
      <li>
        <a href="?tenant=<?= $t['id_tenant'] ?>&kategori=<?= urlencode($kategori_filter) ?>"
          class="flex items-center gap-3 rounded-xl border <?= $tenant_id == $t['id_tenant'] ? 'border-primary bg-soft' : 'border-line bg-white' ?> p-3 text-sm">
          <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-surface font-bold text-muted">
            <?= e(strtoupper(substr($t['nama_tenant'], 0, 2))) ?>
          </span>
          <span class="font-semibold text-ink"><?= e($t['nama_tenant']) ?></span>
          <?php if (!empty($jumlahPerTenant[(int)$t['id_tenant']])): ?>
          <span class="ml-auto rounded-full bg-primary px-2 py-0.5 text-xs font-semibold text-white">
            <?= $jumlahPerTenant[(int)$t['id_tenant']] ?>
          </span>
          <?php endif; ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </aside>

  <!-- Daftar Menu & Kategori -->
  <section class="min-w-0 rounded-xl border border-line bg-white p-4 sm:p-5">
    <div class="flex flex-wrap items-start justify-between gap-3">
      <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-wider text-primary">Tenant Terpilih</p>
        <h1 class="text-xl font-bold tracking-tight text-ink sm:text-2xl">
          <?= e($tenantTerpilih['nama_tenant'] ?? 'Tenant tidak ditemukan') ?>
        </h1>
      </div>
    </div>

    <!-- Kategori Menu -->
    <nav aria-label="Kategori menu"
      class="-mx-4 mt-4 flex gap-2 overflow-x-auto px-4 pb-1 text-sm sm:mx-0 sm:flex-wrap sm:px-0">
      <?php
      $kategoriList = ['Semua Menu', 'Makanan', 'Minuman', 'Snack'];
      foreach ($kategoriList as $kat):
        $isActive = ($kategori_filter === $kat);
      ?>
      <a href="?tenant=<?= $tenant_id ?>&kategori=<?= urlencode($kat) ?>"
        class="shrink-0 rounded-full px-4 py-1.5 font-medium transition <?= $isActive ? 'bg-primary text-white' : 'bg-surface text-muted hover:bg-line' ?>">
        <?= $kat ?>
      </a>
      <?php endforeach; ?>
    </nav>

    <hr class="my-4 border-line/60">
    <h2 class="sr-only">Daftar Menu</h2>
    <ul class="space-y-3">
      <?php if (empty($menuList)): ?>
      <p class="py-6 text-center text-sm text-muted">Belum ada menu di kategori ini.</p>
      <?php endif; ?>

      <?php foreach ($menuList as $m): ?>
      <li class="flex items-center justify-between gap-3 rounded-xl border border-line bg-white p-4">
        <div class="min-w-0 pr-2">
          <p class="font-semibold text-ink"><?= e($m['nama_menu']) ?></p>
          <p class="mt-0.5 text-sm text-muted"><?= e($m['deskripsi']) ?></p>
          <p class="mt-1.5 font-bold text-ink">Rp<?= number_format($m['harga'], 0, ',', '.') ?></p>
        </div>
        <div class="shrink-0">
          <form action="handlers/keranjang.php" method="POST">
            <input type="hidden" name="id_menu" value="<?= $m['id_menu'] ?>">
            <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
            <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
            <button type="submit" name="action" value="add"
              class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white whitespace-nowrap hover:bg-primary-dark transition shadow-sm">
              <span>+</span> Tambah
            </button>
          </form>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <!-- Keranjang Belanja (per tenant) -->
  <aside class="h-fit rounded-xl border-2 border-primary/70 bg-white p-4 shadow-sm md:sticky md:top-24">
    <div class="flex items-start justify-between gap-2">
      <span
        class="rounded-md bg-soft px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-primary">Keranjang</span>
      <span class="text-xs font-semibold text-muted"><?= count($itemKeranjang) ?> Jenis Menu</span>
    </div>

    <ul class="mt-4 divide-y divide-dashed divide-line">
      <?php
      $total = 0;
      if (empty($itemKeranjang)):
      ?>
      <p class="py-4 text-center text-sm text-muted">Belum ada menu yang dipilih.</p>
      <?php else:
        foreach ($itemKeranjang as $id => $item):
          $subtotal = $item['harga'] * $item['jumlah'];
          $total += $subtotal;
        ?>
      <li class="flex items-center justify-between py-3 text-sm">
        <div class="min-w-0 pr-2">
          <p class="font-semibold text-ink"><?= $item['jumlah'] ?>&times; <?= e($item['nama_menu']) ?></p>
          <p class="text-xs text-muted">Rp<?= number_format($subtotal, 0, ',', '.') ?></p>
        </div>
        <div class="shrink-0">
          <form action="handlers/keranjang.php" method="POST">
            <input type="hidden" name="id_menu" value="<?= $id ?>">
            <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
            <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
            <input type="hidden" name="action" value="decrease">
            <button type="submit"
              class="grid size-8 place-items-center rounded bg-surface text-base font-bold text-ink hover:bg-line transition">&minus;</button>
          </form>
        </div>
      </li>
      <?php endforeach;
      endif; ?>
    </ul>

    <div class="mt-4 rounded-lg bg-surface p-3 text-sm">
      <p class="flex justify-between font-bold text-ink">
        <span>Total Tagihan</span>
        <span class="text-primary">Rp<?= number_format($total, 0, ',', '.') ?></span>
      </p>
    </div>

    <form action="<?= url('pages/buyer/checkout.php') ?>" method="get" class="mt-4">
      <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
      <button type="submit" <?= empty($itemKeranjang) ? 'disabled' : '' ?>
        class="flex h-12 w-full items-center justify-center rounded-lg bg-primary font-semibold text-white hover:bg-primary-dark transition shadow-sm disabled:cursor-not-allowed disabled:opacity-50">
        Lanjut Pembayaran
      </button>
    </form>
  </aside>
</main>
</body>

</html>