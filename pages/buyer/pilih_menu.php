<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Pilih Menu & Tenant';
$role      = 'buyer';
$active    = 'menu';

// Inisialisasi keranjang di session jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$tenant_id = $_GET['tenant'] ?? 1;
$kategori_filter = $_GET['kategori'] ?? 'Semua Menu';

require __DIR__ . '/../../includes/header.php';
?>
  <main class="mx-auto grid max-w-7xl grid-cols-1 gap-4 p-4 pb-28 md:grid-cols-[minmax(0,1fr)_320px] md:pb-6 lg:grid-cols-[300px_minmax(0,1fr)_330px] lg:gap-5">

  <!-- Daftar Tenant --> 
    <aside class="h-fit min-w-0 rounded-xl border border-line bg-white p-4 md:col-span-2 lg:sticky lg:top-24 lg:col-span-1">
      <div class="mb-3 flex items-center justify-between gap-2">
        <h2 class="text-xs font-bold uppercase tracking-wider text-muted">Daftar Tenant</h2>
        <span class="rounded-full bg-soft px-2.5 py-0.5 text-xs font-semibold text-primary">3 Buka</span>
      </div>
      <ul class="space-y-2">
        <li>
          <a href="?tenant=1&kategori=<?= urlencode($kategori_filter) ?>" class="flex items-center gap-3 rounded-xl border <?= $tenant_id == 1 ? 'border-primary bg-soft' : 'border-line bg-white' ?> p-3 text-sm">
            <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-surface font-bold text-muted">AG</span>
            <span class="font-semibold text-ink">Ayam Geprek Mas Joko</span>
          </a>
        </li>
        <li>
          <a href="?tenant=2&kategori=<?= urlencode($kategori_filter) ?>" class="flex items-center gap-3 rounded-xl border <?= $tenant_id == 2 ? 'border-primary bg-soft' : 'border-line bg-white' ?> p-3 text-sm">
            <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-surface font-bold text-muted">BS</span>
            <span class="font-semibold text-ink">Bakso Solo Pak Kunto</span>
          </a>
        </li>
      </ul>
    </aside>

  <!-- Daftar Menu & Kategori --> 
    <section class="min-w-0 rounded-xl border border-line bg-white p-4 sm:p-5">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
          <p class="text-xs font-bold uppercase tracking-wider text-primary">Tenant Terpilih</p>
          <h1 class="text-xl font-bold tracking-tight text-ink sm:text-2xl">
            <?= $tenant_id == 2 ? 'Bakso Solo Pak Kunto' : 'Ayam Geprek Mas Joko' ?>
          </h1>
        </div>
      </div>

      <!-- Kategori Menu -->
      <nav aria-label="Kategori menu" class="-mx-4 mt-4 flex gap-2 overflow-x-auto px-4 pb-1 text-sm sm:mx-0 sm:flex-wrap sm:px-0">
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
        <?php if ($tenant_id == 1): ?>
          <!-- Menu Tenant 1: Ayam Geprek Mas Joko -->
          <?php if ($kategori_filter === 'Semua Menu' || $kategori_filter === 'Makanan'): ?>
          <li class="flex items-center justify-between gap-3 rounded-xl border border-line bg-white p-4">
            <div class="min-w-0 pr-2">
              <p class="font-semibold text-ink">Ayam Geprek Level 3 <span class="ml-1 rounded-md bg-danger-soft px-2 py-0.5 align-middle text-[11px] font-semibold text-danger">Pedas</span></p>
              <p class="mt-0.5 text-sm text-muted">Ayam crispy paha dan dada geprek sambal bawang level sedang.</p>
              <p class="mt-1.5 font-bold text-ink">Rp15.000</p>
            </div>
            <div class="shrink-0">
              <form action="handlers/keranjang.php" method="POST">
                <input type="hidden" name="id_menu" value="1">
                <input type="hidden" name="nama_menu" value="Ayam Geprek Level 3">
                <input type="hidden" name="harga" value="15000">
                <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
                <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
                <button type="submit" name="action" value="add" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white whitespace-nowrap hover:bg-primary-dark transition shadow-sm">
                  <span>+</span> Tambah
                </button>
              </form>
            </div>
          </li>
          <?php endif; ?>

          <?php if ($kategori_filter === 'Semua Menu' || $kategori_filter === 'Minuman'): ?>
          <li class="flex items-center justify-between gap-3 rounded-xl border border-line bg-white p-4">
            <div class="min-w-0 pr-2">
              <p class="font-semibold text-ink">Es Teh Manis Jumbo</p>
              <p class="mt-0.5 text-sm text-muted">Es teh manis ukuran jumbo yang segar.</p>
              <p class="mt-1.5 font-bold text-ink">Rp4.000</p>
            </div>
            <div class="shrink-0">
              <form action="handlers/keranjang.php" method="POST">
                <input type="hidden" name="id_menu" value="2">
                <input type="hidden" name="nama_menu" value="Es Teh Manis Jumbo">
                <input type="hidden" name="harga" value="4000">
                <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
                <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
                <button type="submit" name="action" value="add" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white whitespace-nowrap hover:bg-primary-dark transition shadow-sm">
                  <span>+</span> Tambah
                </button>
              </form>
            </div>
          </li>
          <?php endif; ?>

          <?php if ($kategori_filter === 'Semua Menu' || $kategori_filter === 'Snack'): ?>
          <li class="flex items-center justify-between gap-3 rounded-xl border border-line bg-white p-4">
            <div class="min-w-0 pr-2">
              <p class="font-semibold text-ink">Kulit Ayam Crispy Pedas</p>
              <p class="mt-0.5 text-sm text-muted">Kulit ayam goreng super renyah dengan bumbu cabai.</p>
              <p class="mt-1.5 font-bold text-ink">Rp10.000</p>
            </div>
            <div class="shrink-0">
              <form action="handlers/keranjang.php" method="POST">
                <input type="hidden" name="id_menu" value="3">
                <input type="hidden" name="nama_menu" value="Kulit Ayam Crispy Pedas">
                <input type="hidden" name="harga" value="10000">
                <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
                <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
                <button type="submit" name="action" value="add" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white whitespace-nowrap hover:bg-primary-dark transition shadow-sm">
                  <span>+</span> Tambah
                </button>
              </form>
            </div>
          </li>
          <?php endif; ?>

        <?php else: ?>
          <!-- Menu Tenant 2: Bakso Solo Pak Kunto -->
          <?php if ($kategori_filter === 'Semua Menu' || $kategori_filter === 'Makanan'): ?>
          <li class="flex items-center justify-between gap-3 rounded-xl border border-line bg-white p-4">
            <div class="min-w-0 pr-2">
              <p class="font-semibold text-ink">Bakso Urat</p>
              <p class="mt-0.5 text-sm text-muted">Bakso urat besar dengan kuah kaldu sapi.</p>
              <p class="mt-1.5 font-bold text-ink">Rp15.000</p>
            </div>
            <div class="shrink-0">
              <form action="handlers/keranjang.php" method="POST">
                <input type="hidden" name="id_menu" value="4">
                <input type="hidden" name="nama_menu" value="Bakso Urat">
                <input type="hidden" name="harga" value="15000">
                <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
                <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
                <button type="submit" name="action" value="add" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white whitespace-nowrap hover:bg-primary-dark transition shadow-sm">
                  <span>+</span> Tambah
                </button>
              </form>
            </div>
          </li>
          <?php endif; ?>

          <?php if ($kategori_filter === 'Semua Menu' || $kategori_filter === 'Snack'): ?>
          <li class="flex items-center justify-between gap-3 rounded-xl border border-line bg-white p-4">
            <div class="min-w-0 pr-2">
              <p class="font-semibold text-ink">Pangsit Goreng</p>
              <p class="mt-0.5 text-sm text-muted">Pangsit goreng isi ayam gurih.</p>
              <p class="mt-1.5 font-bold text-ink">Rp8.000</p>
            </div>
            <div class="shrink-0">
              <form action="handlers/keranjang.php" method="POST">
                <input type="hidden" name="id_menu" value="5">
                <input type="hidden" name="nama_menu" value="Pangsit Goreng">
                <input type="hidden" name="harga" value="8000">
                <input type="hidden" name="id_tenant" value="<?= $tenant_id ?>">
                <input type="hidden" name="kategori" value="<?= e($kategori_filter) ?>">
                <button type="submit" name="action" value="add" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white whitespace-nowrap hover:bg-primary-dark transition shadow-sm">
                  <span>+</span> Tambah
                </button>
              </form>
            </div>
          </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </section>

  <!-- Keranjang Belanja PHP Murni --> 
    <aside class="h-fit rounded-xl border-2 border-primary/70 bg-white p-4 shadow-sm md:sticky md:top-24">
      <div class="flex items-start justify-between gap-2">
        <span class="rounded-md bg-soft px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-primary">Keranjang</span>
        <span class="text-xs font-semibold text-muted"><?= count($_SESSION['cart']) ?> Jenis Menu</span>
      </div>

      <ul class="mt-4 divide-y divide-dashed divide-line">
        <?php 
        $total = 0;
        if (empty($_SESSION['cart'])): 
        ?>
          <p class="py-4 text-center text-sm text-muted">Belum ada menu yang dipilih.</p>
        <?php else: 
          foreach ($_SESSION['cart'] as $id => $item):
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
                <button type="submit" class="grid size-8 place-items-center rounded bg-surface text-base font-bold text-ink hover:bg-line transition">&minus;</button>
              </form>
            </div>
          </li>
        <?php endforeach; endif; ?>
      </ul>

      <div class="mt-4 rounded-lg bg-surface p-3 text-sm">
        <p class="flex justify-between font-bold text-ink">
          <span>Total Tagihan</span>
          <span class="text-primary">Rp<?= number_format($total, 0, ',', '.') ?></span>
        </p>
      </div>

      <form action="<?= url('pages/buyer/checkout.php') ?>" method="get" class="mt-4">
        <button type="submit" class="flex h-12 w-full items-center justify-center rounded-lg bg-primary font-semibold text-white hover:bg-primary-dark transition shadow-sm">
          Lanjut Pembayaran
        </button>
      </form>
    </aside>
  </main>
</body>
</html>