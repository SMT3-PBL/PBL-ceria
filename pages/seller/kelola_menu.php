<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Kelola Menu';
$role      = 'seller';
$active    = 'menus';
require __DIR__ . '/../../includes/header.php';
?>
  <main class="mx-auto grid max-w-6xl gap-4 p-4 pb-10 sm:p-6 lg:grid-cols-[290px_minmax(0,1fr)] lg:gap-6">
    
    <!-- AREA KIRI (SIDEBAR) -->
    <aside class="grid h-fit min-w-0 gap-4 sm:grid-cols-2 lg:sticky lg:top-24 lg:grid-cols-1">
      <!-- Buka/Tutup Tenant -->
      <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
        <div class="mt-3">
          <div class="flex items-center justify-between gap-3">
            <span class="text-lg font-extrabold text-primary">Tenant Buka</span>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" id="toggleTenantBuka" checked class="peer sr-only" aria-label="Tenant buka">
              <span class="h-7 w-12 rounded-full bg-outline transition peer-checked:bg-emerald-600 after:absolute after:left-1 after:top-1 after:size-5 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
            </label>
          </div>
          <p class="mt-2 text-xs text-muted">Menutup tenant akan menyembunyikan semua menu dari katalog pembeli.</p>
        </div>
      </section>

      <!-- Kategori Menu --> 
      <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 min-w-0">
        <div class="mb-2 flex items-center justify-between">
          <h2 class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Kategori Menu</h2>
          <span class="rounded-full bg-soft px-2 py-0.5 text-xs font-bold text-primary" id="totalMenuCount">3</span>
        </div>
        <ul class="space-y-0.5">
          <li><button onclick="filterKategori('')" class="w-full text-left flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-primary bg-soft"><span>Semua Menu</span></button></li>
          <li><button onclick="filterKategori('Makanan')" class="w-full text-left flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft"><span>Makanan</span></button></li>
          <li><button onclick="filterKategori('Minuman')" class="w-full text-left flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft"><span>Minuman</span></button></li>
          <li><button onclick="filterKategori('Snack')" class="w-full text-left flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-muted hover:bg-soft"><span>Snack</span></button></li>
        </ul>
      </section>
    </aside>

    <!-- AREA KANAN -->
    <div class="min-w-0 space-y-4">
      <div class="flex gap-3">
        <div class="relative flex-1">
          <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          </span>
          <input type="search" id="inputPencarian" oninput="cariMenu()" placeholder="Cari menu..." class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 pl-10">
        </div>
        <!-- Tombol Tambah Menu Baru -->
        <button type="button" onclick="bukaModalTambah()" class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark shrink-0">
          <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          <span>Tambah Menu Baru</span>
        </button>
      </div>

      <!-- Daftar Menu --> 
      <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
        <div class="mb-4 flex items-center gap-2">
          <h2 class="text-lg font-extrabold uppercase tracking-tight">Daftar Menu</h2>
          <span class="rounded-full bg-soft px-2.5 py-0.5 text-xs font-bold text-primary" id="badgeTotalMenu">3 Menu</span>
        </div>
        
        <ul class="space-y-3" id="listDaftarMenu">
          <!-- Item Menu 1 -->
          <li class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4 menu-item" data-kategori="Makanan" data-nama="Ayam Geprek Level 3">
            <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20">
              <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </span>
            <div class="min-w-0 flex-1 basis-40">
              <p class="font-bold leading-snug menu-nama">Ayam Geprek Level 3</p>
              <p class="text-xs text-muted menu-kategori">Makanan</p>
              <p class="mt-1 font-extrabold menu-harga">Rp15.000</p>
            </div>
            <div class="ml-auto flex items-center gap-2 sm:gap-3">
              <button type="button" onclick="toggleStok(this)" class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-extrabold uppercase transition border bg-ok-soft text-ok border-emerald-300 hover:bg-emerald-100 stok-btn">
                <span class="size-2 rounded-full bg-ok"></span>
                <span class="stok-text">Tersedia</span>
              </button>
              <button type="button" onclick="bukaModalEdit(this)" class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
              </button>
              <button type="button" onclick="hapusMenu(this)" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </li>

          <!-- Item Menu 2 -->
          <li class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4 menu-item" data-kategori="Minuman" data-nama="Es Teh Manis">
            <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20">
              <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </span>
            <div class="min-w-0 flex-1 basis-40">
              <p class="font-bold leading-snug menu-nama">Es Teh Manis</p>
              <p class="text-xs text-muted menu-kategori">Minuman</p>
              <p class="mt-1 font-extrabold menu-harga">Rp4.000</p>
            </div>
            <div class="ml-auto flex items-center gap-2 sm:gap-3">
              <button type="button" onclick="toggleStok(this)" class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-extrabold uppercase transition border bg-ok-soft text-ok border-emerald-300 hover:bg-emerald-100 stok-btn">
                <span class="size-2 rounded-full bg-ok"></span>
                <span class="stok-text">Tersedia</span>
              </button>
              <button type="button" onclick="bukaModalEdit(this)" class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
              </button>
              <button type="button" onclick="hapusMenu(this)" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </li>

          <!-- Item Menu 3 -->
          <li class="flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4 menu-item" data-kategori="Snack" data-nama="Kulit Ayam Crispy">
            <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20">
              <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </span>
            <div class="min-w-0 flex-1 basis-40">
              <p class="font-bold leading-snug menu-nama">Kulit Ayam Crispy</p>
              <p class="text-xs text-muted menu-kategori">Snack</p>
              <p class="mt-1 font-extrabold menu-harga">Rp10.000</p>
            </div>
            <div class="ml-auto flex items-center gap-2 sm:gap-3">
              <button type="button" onclick="toggleStok(this)" class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-extrabold uppercase transition border bg-ok-soft text-ok border-emerald-300 hover:bg-emerald-100 stok-btn">
                <span class="size-2 rounded-full bg-ok"></span>
                <span class="stok-text">Tersedia</span>
              </button>
              <button type="button" onclick="bukaModalEdit(this)" class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
              </button>
              <button type="button" onclick="hapusMenu(this)" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </li>
        </ul>
      </section>
    </div>
  </main>

  <!-- Modal Dialog Tambah / Edit Menu --> 
  <dialog id="dlgMenu" class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl bg-white p-0 text-ink shadow-2xl backdrop:bg-ink/40">
    <form id="formMenu" onsubmit="simpanMenu(event)" class="space-y-4 p-5 sm:p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-extrabold" id="modalTitle">Tambah Menu Baru</h2>
        <button type="button" onclick="tutupModal()" class="grid size-9 place-items-center rounded-lg text-muted hover:bg-soft">
          <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <input type="hidden" id="editIndex" value="">
      
      <div>
        <label for="nama_menu" class="mb-1.5 block text-sm font-semibold text-ink">Nama Menu</label>
        <input type="text" id="nama_menu" required class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label for="harga" class="mb-1.5 block text-sm font-semibold text-ink">Harga Jual (Rp)</label>
          <input type="number" id="harga" min="0" required class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div>
          <label for="stok_awal" class="mb-1.5 block text-sm font-semibold text-ink">Stok</label>
          <select id="stok_awal" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            <option value="tersedia">Tersedia</option>
            <option value="habis">Habis</option>
          </select>
        </div>
      </div>

      <div>
        <label for="kategori" class="mb-1.5 block text-sm font-semibold text-ink">Kategori</label>
        <select id="kategori" required class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
          <option value="">Pilih kategori</option>
          <option value="Makanan">Makanan</option>
          <option value="Minuman">Minuman</option>
          <option value="Snack">Snack</option>
        </select>
      </div>

      <div class="flex justify-end gap-2 pt-1">
        <button type="button" onclick="tutupModal()" class="h-11 rounded-lg px-5 text-sm font-bold text-muted hover:bg-soft">Batal</button>
        <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark">Simpan</button>
      </div>
    </form>
  </dialog>

  <script src="../../assets/kelola_menu.js"></script>
</body>
</html>