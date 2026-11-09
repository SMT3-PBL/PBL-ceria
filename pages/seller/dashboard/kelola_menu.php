<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Tenant.php';
require_once BASE_PATH . '/models/Menu.php'; // 1. Panggil file model Menu

require_role('seller');

$tenant = tenant_aktif();
$id_tenant = $tenant['id_tenant'] ?? '';

// 2. Inisialisasi object Menu
$menuModel = new Menu($pdo);

// Ambil parameter filter kategori & keyword pencarian dari URL (jika ada)
$kategoriFilter = $_GET['kategori'] ?? '';
$keywordCari    = $_GET['cari'] ?? '';

// 3. Ambil data menu secara dinamis menggunakan fungsi model
$daftarMenu = $menuModel->getMenusByTenant($id_tenant, $kategoriFilter, $keywordCari);

// Hitung total keseluruhan menu & per kategori untuk bagian sidebar
$totalSemuaMenu = $menuModel->countMenuByTenant($id_tenant);
$totalMakanan   = $menuModel->countMenuByTenantAndCategory($id_tenant, 'Makanan');
$totalMinuman   = $menuModel->countMenuByTenantAndCategory($id_tenant, 'Minuman');
$totalSnack     = $menuModel->countMenuByTenantAndCategory($id_tenant, 'Snack');

// Proses simpan status buka/tutup (kode lama kamu)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'simpan_status') {
  $statusBaru = isset($_POST['status_buka']) ? 'buka' : 'tutup';
  $ok = (new Tenant($pdo))->updateOperationalStatus((string) $id_tenant, $statusBaru);

  $_SESSION['flash'] = $ok
    ? ['tipe' => 'ok', 'pesan' => 'Status tenant diperbarui: ' . ucfirst($statusBaru) . '.']
    : ['tipe' => 'danger', 'pesan' => 'Gagal memperbarui status tenant.'];

  redirect('pages/seller/dashboard/kelola_menu.php');
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$tenantBuka = strtolower((string) ($tenant['status_tenant'] ?? '')) === 'buka';

$pageTitle = 'Kelola Menu';
$role      = 'seller';
$active    = 'menus';
require __DIR__ . '/../../../includes/header.php';
?>

<?php if ($flash): ?>
<div id="flash-message" class="mx-auto max-w-6xl px-4 pt-4 sm:px-6">
  <p role="status"
    class="rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipe'] === 'ok' ? 'bg-ok-soft text-ok' : 'bg-danger-soft text-danger' ?>">
    <?= e($flash['pesan']) ?>
  </p>
</div>
<?php endif; ?>

<main class="mx-auto grid max-w-6xl gap-4 p-4 pb-10 sm:p-6 lg:grid-cols-[290px_minmax(0,1fr)] lg:gap-6">
  <!-- AREA KIRI (SIDEBAR): Berisi panel status operasional toko dan filter kategori menu -->
  <aside class="grid h-fit min-w-0 gap-4 sm:grid-cols-2 lg:sticky lg:top-24 lg:grid-cols-1">
    <!-- Buka/Tutup Tenant -->
    <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <form method="post" action="<?= url('pages/seller/handlers/update_tenant_status.php') ?>" class="mt-3">
        <input type="hidden" name="aksi" value="simpan_status">
        <div class="flex items-center justify-between gap-3">
          <span class="text-lg font-extrabold text-primary"><?= $tenantBuka ? 'Tenant Buka' : 'Tenant Tutup' ?></span>
          <label class="relative inline-flex cursor-pointer items-center">
            <input type="checkbox" name="status_buka" value="1" <?= $tenantBuka ? 'checked' : '' ?> class="peer sr-only"
              aria-label="Tenant buka">
            <span
              class="h-7 w-12 rounded-full bg-outline transition peer-checked:bg-emerald-800 peer-focus-visible:ring-2 peer-focus-visible:ring-primary/40 after:absolute after:left-1 after:top-1 after:size-5 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
          </label>
        </div>
        <p class="mt-2 text-xs text-muted">Menutup tenant akan menyembunyikan semua menu dari katalog pembeli secara
          instan.</p>
        <button type="submit"
          class="mt-3 h-10 w-full rounded-lg bg-soft text-sm font-bold text-primary hover:bg-line">Simpan
          Status</button>
      </form>
    </section>

    <!-- Kategori Menu -->
    <!-- Kategori Menu -->
    <section
      class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 min-w-0">
      <div class="mb-2 flex items-center justify-between">
        <h2 class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Kategori Menu</h2>
        <span class="rounded-full bg-soft px-2 py-0.5 text-xs font-bold text-primary"><?= $totalSemuaMenu ?></span>
      </div>

      <ul
        class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1 lg:mx-0 lg:block lg:space-y-0.5 lg:overflow-visible lg:px-0 lg:pb-0">
        <li class="shrink-0">
          <a href="<?= url('pages/seller/dashboard/kelola_menu.php') ?>"
            class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line <?= empty($kategoriFilter) ? 'bg-soft text-primary' : '' ?>">
            <span>Semua Menu</span><span class="text-xs font-bold opacity-70"><?= $totalSemuaMenu ?></span>
          </a>
        </li>
        <li class="shrink-0">
          <a href="?kategori=Makanan"
            class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line <?= $kategoriFilter === 'Makanan' ? 'bg-soft text-primary' : '' ?>">
            <span>Makanan</span><span class="text-xs font-bold opacity-70"><?= $totalMakanan ?></span>
          </a>
        </li>
        <li class="shrink-0">
          <a href="?kategori=Minuman"
            class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line <?= $kategoriFilter === 'Minuman' ? 'bg-soft text-primary' : '' ?>">
            <span>Minuman</span><span class="text-xs font-bold opacity-70"><?= $totalMinuman ?></span>
          </a>
        </li>
        <li class="shrink-0">
          <a href="?kategori=Snack"
            class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line <?= $kategoriFilter === 'Snack' ? 'bg-soft text-primary' : '' ?>">
            <span>Snack</span><span class="text-xs font-bold opacity-70"><?= $totalSnack ?></span>
          </a>
        </li>
      </ul>

      <p class="mt-3 flex items-center justify-between rounded-lg bg-surface px-3 py-2.5 text-sm">
        <span class="font-semibold text-muted">Menu tersedia</span>
        <span class="font-extrabold"><?= $totalSemuaMenu ?> / <?= $totalSemuaMenu ?></span>
      </p>
    </section>
  </aside>

  <!-- AREA KANAN: Berisi kolom pencarian, tombol tambah menu, dan list daftar produk -->
  <div class="min-w-0 space-y-4">
    <div class="flex gap-3">
      <form method="get" action="" class="relative flex-1">
        <label for="cari" class="sr-only">Cari menu</label>
        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="size-4"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
          </svg></span>
        <input type="search" id="cari" name="cari" data-filter-input="menu" placeholder="Cari menu..."
          class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 pl-10">
      </form>
      <button type="button" data-menu-form
        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 shrink-0 px-4 sm:px-5"><svg
          class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" aria-hidden="true">
          <path d="M5 12h14" />
          <path d="M12 5v14" />
        </svg><span><span class="hidden sm:inline">Tambah </span>Menu<span class="hidden sm:inline">
            Baru</span></span></button>
    </div>

    <!-- Daftar Menu -->
    <!-- Daftar Menu -->
    <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
      <div class="mb-4 flex items-center gap-2">
        <h2 class="text-lg font-extrabold uppercase tracking-tight">Daftar Menu</h2>
        <span class="rounded-full bg-soft px-2.5 py-0.5 text-xs font-bold text-primary"><?= count($daftarMenu) ?>
          Menu</span>
      </div>

      <?php if (empty($daftarMenu)): ?>
      <!-- Pesan jika menu kosong -->
      <p class="py-6 text-center text-sm text-muted">Menu tidak ditemukan.</p>
      <?php else: ?>
      <ul class="space-y-3">
        <?php foreach ($daftarMenu as $row): ?>
        <li data-filter-item="menu" data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
          class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4">

          <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20">
            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
              <circle cx="9" cy="9" r="2" />
              <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
            </svg>
          </span>

          <!-- Informasi Detail Produk dari Database -->
          <div class="min-w-0 flex-1 basis-40">
            <p class="font-bold leading-snug"><?= htmlspecialchars($row['nama_menu']) ?></p>
            <p class="text-xs text-muted"><?= htmlspecialchars($row['kategori']) ?></p>
            <p class="text-xs text-muted mt-0.5"><?= htmlspecialchars($row['deskripsi'] ?? '') ?></p>
            <p class="mt-1 font-extrabold">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
          </div>

          <!-- Tombol Aksi (Status, Edit, dan Hapus) -->
          <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <!-- Badge Status Menu -->
            <span
              class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg 
              <?= (strtolower($row['status_menu']) === 'tersedia') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
              <?= htmlspecialchars(strtoupper($row['status_menu'])) ?>
            </span>

            <!-- Tombol Edit (Membawa data asli baris ini ke Modal) -->
            <button type="button" data-menu-form data-id="<?= $row['id_menu'] ?>"
              data-nama="<?= htmlspecialchars($row['nama_menu']) ?>"
              data-deskripsi="<?= htmlspecialchars($row['deskripsi'] ?? '') ?>" data-harga="<?= $row['harga'] ?>"
              data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
              data-status="<?= htmlspecialchars($row['status_menu'] ?? 'Tersedia') ?>"
              aria-label="Edit <?= htmlspecialchars($row['nama_menu']) ?>"
              class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft focus:outline-none focus:ring-2 focus:ring-primary/40">
              <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
              </svg>
            </button>

            <button type="button" onclick="bukaModalHapus('<?= $row['id_menu'] ?>')" aria-label="Hapus Menu"
              class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-danger/40">
              <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 6h18" />
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
              </svg>
            </button>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </section>
  </div>
</main>

<!-- Tambah/Edit Menu -->

<?php
require_once __DIR__ . '/../../../includes/components/modalTambahMenu.php';
require_once __DIR__ . '/../../../includes/components/modalHapusMenu.php';

require_once __DIR__ . '/../../../includes/footer.php';
?>