<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Kelola Menu';
$role      = 'seller';
$active    = 'menus';
require __DIR__ . '/../../includes/header.php';
?>
  <main class="mx-auto grid max-w-6xl gap-4 p-4 pb-10 sm:p-6 lg:grid-cols-[290px_minmax(0,1fr)] lg:gap-6">

    <aside class="grid h-fit min-w-0 gap-4 sm:grid-cols-2 lg:sticky lg:top-24 lg:grid-cols-1">
      <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
        <form method="post" action="" class="mt-3">
          <div class="flex items-center justify-between gap-3">
            <span class="text-lg font-extrabold text-primary">Tenant Buka</span>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="status_buka" value="1" checked class="peer sr-only" aria-label="Tenant buka">
              <span class="h-7 w-12 rounded-full bg-outline transition peer-checked:bg-emerald-800 peer-focus-visible:ring-2 peer-focus-visible:ring-primary/40 after:absolute after:left-1 after:top-1 after:size-5 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
            </label>
          </div>
          <p class="mt-2 text-xs text-muted">Menutup tenant akan menyembunyikan semua menu dari katalog pembeli secara instan.</p>
          <button type="submit" class="mt-3 h-10 w-full rounded-lg bg-soft text-sm font-bold text-primary hover:bg-line">Simpan Status</button>
        </form>
      </section>

      <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 min-w-0">
        <div class="mb-2 flex items-center justify-between">
          <h2 class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Kategori Menu</h2>
          <span class="rounded-full bg-soft px-2 py-0.5 text-xs font-bold text-primary">3</span>
        </div>
        <ul class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1 lg:mx-0 lg:block lg:space-y-0.5 lg:overflow-visible lg:px-0 lg:pb-0"><li class="shrink-0"><a href="<?= url('pages/seller/menus.php') ?>" data-filter-kategori="menu" data-kategori="" aria-current="page" class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line aria-[current=page]:bg-soft aria-[current=page]:text-primary"><span>Semua Menu</span><span class="text-xs font-bold opacity-70">3</span></a></li><li class="shrink-0"><a href="?kategori=Makanan" data-filter-kategori="menu" data-kategori="Makanan" class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line aria-[current=page]:bg-soft aria-[current=page]:text-primary"><span>Makanan</span><span class="text-xs font-bold opacity-70">1</span></a></li><li class="shrink-0"><a href="?kategori=Minuman" data-filter-kategori="menu" data-kategori="Minuman" class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line aria-[current=page]:bg-soft aria-[current=page]:text-primary"><span>Minuman</span><span class="text-xs font-bold opacity-70">1</span></a></li><li class="shrink-0"><a href="?kategori=Snack" data-filter-kategori="menu" data-kategori="Snack" class="flex items-center justify-between gap-3 whitespace-nowrap rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft max-lg:border max-lg:border-line aria-[current=page]:bg-soft aria-[current=page]:text-primary"><span>Snack</span><span class="text-xs font-bold opacity-70">1</span></a></li></ul>
        <p class="mt-3 flex items-center justify-between rounded-lg bg-surface px-3 py-2.5 text-sm"><span class="font-semibold text-muted">Menu tersedia</span><span class="font-extrabold">3 / 3</span></p>
      </section>
    </aside>

    <div class="min-w-0 space-y-4">
      <div class="flex gap-3">
        <form method="get" action="" class="relative flex-1">
          <label for="cari" class="sr-only">Cari menu</label>
          <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></span>
          <input type="search" id="cari" name="cari" data-filter-input="menu" placeholder="Cari menu..." class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 pl-10">
        </form>
        <button type="button" data-menu-form class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 shrink-0 px-4 sm:px-5"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg><span><span class="hidden sm:inline">Tambah </span>Menu<span class="hidden sm:inline"> Baru</span></span></button>
      </div>

      <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
        <div class="mb-4 flex items-center gap-2">
          <h2 class="text-lg font-extrabold uppercase tracking-tight">Daftar Menu</h2>
          <span class="rounded-full bg-soft px-2.5 py-0.5 text-xs font-bold text-primary">3 Menu</span>
        </div>
        <ul class="space-y-3">
        <li data-filter-item="menu" data-kategori="Makanan" class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4">
          <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20"><svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg></span>
          <div class="min-w-0 flex-1 basis-40">
            <p class="font-bold leading-snug">Ayam Geprek Level 3</p>
            <p class="text-xs text-muted">Makanan</p>
            <p class="mt-1 font-extrabold">Rp15.000</p>
          </div>
          <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <span class="rounded-md bg-ok-soft px-2.5 py-1 text-[11px] font-extrabold uppercase text-ok">Stok 30</span>
            <button type="button" data-menu-form data-id="1" data-nama="Ayam Geprek Level 3" data-harga="15000" data-stok="30" data-kategori="Makanan" aria-label="Edit Ayam Geprek Level 3"
              class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft focus:outline-none focus:ring-2 focus:ring-primary/40"><svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></button>
            <form method="post" action="" data-confirm="Hapus menu ini?">
              <input type="hidden" name="id_menu" value="1">
              <button type="submit" name="hapus" value="1" aria-label="Hapus Ayam Geprek Level 3" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-danger/40"><svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </form>
          </div>
        </li>
        <li data-filter-item="menu" data-kategori="Minuman" class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4">
          <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20"><svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg></span>
          <div class="min-w-0 flex-1 basis-40">
            <p class="font-bold leading-snug">Es Teh Manis</p>
            <p class="text-xs text-muted">Minuman</p>
            <p class="mt-1 font-extrabold">Rp4.000</p>
          </div>
          <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <span class="rounded-md bg-ok-soft px-2.5 py-1 text-[11px] font-extrabold uppercase text-ok">Stok 40</span>
            <button type="button" data-menu-form data-id="2" data-nama="Es Teh Manis" data-harga="4000" data-stok="40" data-kategori="Minuman" aria-label="Edit Es Teh Manis"
              class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft focus:outline-none focus:ring-2 focus:ring-primary/40"><svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></button>
            <form method="post" action="" data-confirm="Hapus menu ini?">
              <input type="hidden" name="id_menu" value="2">
              <button type="submit" name="hapus" value="1" aria-label="Hapus Es Teh Manis" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-danger/40"><svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </form>
          </div>
        </li>
        <li data-filter-item="menu" data-kategori="Snack" class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4">
          <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20"><svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg></span>
          <div class="min-w-0 flex-1 basis-40">
            <p class="font-bold leading-snug">Kulit Ayam Crispy</p>
            <p class="text-xs text-muted">Snack</p>
            <p class="mt-1 font-extrabold">Rp10.000</p>
          </div>
          <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <span class="rounded-md bg-ok-soft px-2.5 py-1 text-[11px] font-extrabold uppercase text-ok">Stok 20</span>
            <button type="button" data-menu-form data-id="3" data-nama="Kulit Ayam Crispy" data-harga="10000" data-stok="20" data-kategori="Snack" aria-label="Edit Kulit Ayam Crispy"
              class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft focus:outline-none focus:ring-2 focus:ring-primary/40"><svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></button>
            <form method="post" action="" data-confirm="Hapus menu ini?">
              <input type="hidden" name="id_menu" value="3">
              <button type="submit" name="hapus" value="1" aria-label="Hapus Kulit Ayam Crispy" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-danger/40"><svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
            </form>
          </div>
        </li>
        </ul>
        <p data-filter-empty="menu" hidden class="py-6 text-center text-sm text-muted">Menu tidak ditemukan.</p>
      </section>
    </div>
  </main>

  <dialog id="dlgMenu" class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl bg-white p-0 text-ink shadow-2xl backdrop:bg-ink/40">
    <form method="post" action="" class="space-y-4 p-5 sm:p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-extrabold" data-title>Tambah Menu Baru</h2>
        <button type="button" data-close aria-label="Tutup" class="grid size-9 place-items-center rounded-lg text-muted hover:bg-soft"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
      </div>
      <input type="hidden" name="id_menu" value="">
      <input type="hidden" name="id_tenant" value="1">
      <p><label for="nama_menu" class="mb-1.5 block text-sm font-semibold text-ink">Nama Menu</label><input type="text" id="nama_menu" name="nama_menu" required class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>
      <div class="grid gap-4 sm:grid-cols-2">
        <p><label for="harga" class="mb-1.5 block text-sm font-semibold text-ink">Harga Jual (Rp)</label><input type="number" id="harga" name="harga" min="0" required inputmode="numeric" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>
        <p><label for="stok" class="mb-1.5 block text-sm font-semibold text-ink">Stok</label><input type="number" id="stok" name="stok" min="0" required inputmode="numeric" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>
      </div>
      <p><label for="kategori" class="mb-1.5 block text-sm font-semibold text-ink">Kategori</label>
        <select id="kategori" name="kategori" required class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"><option value="">Pilih kategori</option><option value="Makanan">Makanan</option><option value="Minuman">Minuman</option><option value="Snack">Snack</option></select></p>
      <div class="flex justify-end gap-2 pt-1">
        <button type="button" data-close class="h-11 rounded-lg px-5 text-sm font-bold text-muted hover:bg-soft">Batal</button>
        <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">Simpan</button>
      </div>
    </form>
  </dialog>
  

