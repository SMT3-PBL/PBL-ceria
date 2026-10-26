<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Pilih Menu & Tenant';
$role      = 'buyer';
$active    = 'menu';
require __DIR__ . '/../../includes/header.php';
?>
  <main class="mx-auto grid max-w-7xl grid-cols-1 gap-4 p-4 pb-28 md:grid-cols-[minmax(0,1fr)_320px] md:pb-6 lg:grid-cols-[300px_minmax(0,1fr)_330px] lg:gap-5">

    <aside class="h-fit min-w-0 rounded-xl border border-slate-200 bg-white p-4 md:col-span-2 lg:sticky lg:top-24 lg:col-span-1">
      <div class="mb-3 flex items-center justify-between gap-2">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Tenant</h2>
        <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">2 Buka</span>
      </div>
      <p class="mb-3 hidden text-sm text-slate-500 md:block">Pilih tenant untuk memesan. Setiap tenant memiliki antrean terpisah.</p>
      <ul class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1 lg:mx-0 lg:block lg:space-y-2 lg:overflow-visible lg:px-0 lg:pb-0">
          <li class="shrink-0 lg:shrink">
            <a href="<?= url('pages/buyer/menu.php') ?>?tenant=1" data-tenant-link="1" aria-current="page" class="group flex w-60 items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 text-sm hover:bg-slate-50 aria-[current=page]:border-blue-600 aria-[current=page]:bg-blue-50/60 aria-[current=page]:ring-1 aria-[current=page]:ring-blue-600 lg:w-auto">
              <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500 group-aria-[current=page]:bg-blue-600 group-aria-[current=page]:text-white">AG</span>
              <span class="min-w-0 flex-1">
                <span class="block truncate font-semibold text-slate-900">Ayam Geprek Mas Joko</span>
                <span class="block truncate text-xs text-slate-500">Spesialis Ayam Sambal Pedas</span>
              </span>
            </a>
          </li>
          <li class="shrink-0 lg:shrink">
            <a href="<?= url('pages/buyer/menu.php') ?>?tenant=2" data-tenant-link="2" class="group flex w-60 items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 text-sm hover:bg-slate-50 aria-[current=page]:border-blue-600 aria-[current=page]:bg-blue-50/60 aria-[current=page]:ring-1 aria-[current=page]:ring-blue-600 lg:w-auto">
              <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500 group-aria-[current=page]:bg-blue-600 group-aria-[current=page]:text-white">BS</span>
              <span class="min-w-0 flex-1">
                <span class="block truncate font-semibold text-slate-900">Bakso Solo Pak Kunto</span>
                <span class="block truncate text-xs text-slate-500">Bakso Sapi Urat &amp; Halus</span>
              </span>
            </a>
          </li>
      </ul>
    </aside>

    <section class="min-w-0 rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
          <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Tenant Terpilih</p>
          <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl" data-tenant-nama>Ayam Geprek Mas Joko</h1>
          <p class="mt-1 text-sm text-slate-500" data-tenant-desc>Spesialis kuliner ayam renyah digeprek dengan sambal bawang pedas segar khas Jawa Timur.</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-emerald-800"><span class="size-1.5 rounded-full bg-emerald-600"></span>Buka</span>
        </div>
      </div>

      <nav aria-label="Kategori menu" class="-mx-4 mt-4 flex gap-2 overflow-x-auto px-4 pb-1 text-sm sm:mx-0 sm:flex-wrap sm:px-0">
        <a href="?tenant=1&amp;kategori=Semua+Menu" data-filter-kategori="menu" data-kategori="" aria-current="page" class="shrink-0 rounded-full bg-slate-100 px-4 py-1.5 font-medium text-slate-600 hover:bg-slate-200 aria-[current=page]:bg-blue-600 aria-[current=page]:text-white">Semua Menu</a>
        <a href="?tenant=1&amp;kategori=Makanan" data-filter-kategori="menu" data-kategori="Makanan" class="shrink-0 rounded-full bg-slate-100 px-4 py-1.5 font-medium text-slate-600 hover:bg-slate-200 aria-[current=page]:bg-blue-600 aria-[current=page]:text-white">Makanan</a>
        <a href="?tenant=1&amp;kategori=Minuman" data-filter-kategori="menu" data-kategori="Minuman" class="shrink-0 rounded-full bg-slate-100 px-4 py-1.5 font-medium text-slate-600 hover:bg-slate-200 aria-[current=page]:bg-blue-600 aria-[current=page]:text-white">Minuman</a>
        <a href="?tenant=1&amp;kategori=Snack" data-filter-kategori="menu" data-kategori="Snack" class="shrink-0 rounded-full bg-slate-100 px-4 py-1.5 font-medium text-slate-600 hover:bg-slate-200 aria-[current=page]:bg-blue-600 aria-[current=page]:text-white">Snack</a>
      </nav>

      <hr class="my-4 border-slate-100">
      <h2 class="sr-only">Daftar Menu</h2>
      <ul id="daftarMenu" class="space-y-3">
        <li data-item data-tenant="1" data-filter-item="menu" data-kategori="Makanan" data-nama="Ayam Geprek Level 3" data-harga="15000" data-sel="true" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 transition data-[sel=true]:border-blue-300 data-[sel=true]:bg-blue-50/50">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Ayam Geprek Level 3 <span class="ml-1 rounded-md bg-rose-100 px-2 py-0.5 align-middle text-[11px] font-semibold text-rose-700">Pedas</span></p>
            <p class="mt-0.5 text-sm text-slate-500">Ayam crispy paha/dada geprek sambal rawit merah 8 cabai.</p>
            <p class="mt-1.5 font-bold text-slate-900">Rp15.000 <span class="ml-1 text-xs font-medium text-slate-400"></span></p>
          </div>
          <div class="shrink-0">
            <button type="button" data-d="1" data-add class="rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">+ Tambah</button>
            <div data-stepper hidden class="flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
              <button type="button" data-d="-1" aria-label="Kurangi Ayam Geprek Level 3" class="size-9 text-lg font-semibold text-slate-500 hover:bg-slate-100">&minus;</button>
              <input type="number" form="formKeranjang" name="jumlah[1]" min="0" max="30" value="2" aria-label="Jumlah Ayam Geprek Level 3"
                class="w-9 border-0 bg-transparent p-0 text-center text-sm font-bold focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" data-d="1" aria-label="Tambah Ayam Geprek Level 3" class="size-9 bg-blue-600 text-lg font-semibold text-white hover:bg-blue-700">+</button>
            </div>
          </div>
        </li>
        <li data-item data-tenant="1" data-filter-item="menu" data-kategori="Minuman" data-nama="Es Teh Manis" data-harga="4000" data-sel="true" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 transition data-[sel=true]:border-blue-300 data-[sel=true]:bg-blue-50/50">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Es Teh Manis</p>
            <p class="mt-0.5 text-sm text-slate-500">Es teh manis segar, pas untuk menemani pedasnya geprek.</p>
            <p class="mt-1.5 font-bold text-slate-900">Rp4.000 <span class="ml-1 text-xs font-medium text-slate-400"></span></p>
          </div>
          <div class="shrink-0">
            <button type="button" data-d="1" data-add class="rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">+ Tambah</button>
            <div data-stepper hidden class="flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
              <button type="button" data-d="-1" aria-label="Kurangi Es Teh Manis" class="size-9 text-lg font-semibold text-slate-500 hover:bg-slate-100">&minus;</button>
              <input type="number" form="formKeranjang" name="jumlah[2]" min="0" max="40" value="1" aria-label="Jumlah Es Teh Manis"
                class="w-9 border-0 bg-transparent p-0 text-center text-sm font-bold focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" data-d="1" aria-label="Tambah Es Teh Manis" class="size-9 bg-blue-600 text-lg font-semibold text-white hover:bg-blue-700">+</button>
            </div>
          </div>
        </li>
        <li data-item data-tenant="1" data-filter-item="menu" data-kategori="Snack" data-nama="Kulit Ayam Crispy" data-harga="10000" data-sel="false" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 transition data-[sel=true]:border-blue-300 data-[sel=true]:bg-blue-50/50">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Kulit Ayam Crispy</p>
            <p class="mt-0.5 text-sm text-slate-500">Kulit ayam goreng super renyah dibumbui bubuk cabai gurih.</p>
            <p class="mt-1.5 font-bold text-slate-900">Rp10.000 <span class="ml-1 text-xs font-medium text-slate-400"></span></p>
          </div>
          <div class="shrink-0">
            <button type="button" data-d="1" data-add class="rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">+ Tambah</button>
            <div data-stepper hidden class="flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
              <button type="button" data-d="-1" aria-label="Kurangi Kulit Ayam Crispy" class="size-9 text-lg font-semibold text-slate-500 hover:bg-slate-100">&minus;</button>
              <input type="number" form="formKeranjang" name="jumlah[3]" min="0" max="20" value="0" aria-label="Jumlah Kulit Ayam Crispy"
                class="w-9 border-0 bg-transparent p-0 text-center text-sm font-bold focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" data-d="1" aria-label="Tambah Kulit Ayam Crispy" class="size-9 bg-blue-600 text-lg font-semibold text-white hover:bg-blue-700">+</button>
            </div>
          </div>
        </li>
        <li data-item data-tenant="2" data-filter-item="menu" data-kategori="Makanan" data-nama="Bakso Sapi Urat" data-harga="18000" data-sel="false" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 transition data-[sel=true]:border-blue-300 data-[sel=true]:bg-blue-50/50">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Bakso Sapi Urat</p>
            <p class="mt-0.5 text-sm text-slate-500">Bakso sapi urat dengan mie, sayur, dan kuah kaldu gurih.</p>
            <p class="mt-1.5 font-bold text-slate-900">Rp18.000 <span class="ml-1 text-xs font-medium text-slate-400"></span></p>
          </div>
          <div class="shrink-0">
            <button type="button" data-d="1" data-add class="rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">+ Tambah</button>
            <div data-stepper hidden class="flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
              <button type="button" data-d="-1" aria-label="Kurangi Bakso Sapi Urat" class="size-9 text-lg font-semibold text-slate-500 hover:bg-slate-100">&minus;</button>
              <input type="number" form="formKeranjang" name="jumlah[4]" min="0" max="25" value="0" aria-label="Jumlah Bakso Sapi Urat"
                class="w-9 border-0 bg-transparent p-0 text-center text-sm font-bold focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" data-d="1" aria-label="Tambah Bakso Sapi Urat" class="size-9 bg-blue-600 text-lg font-semibold text-white hover:bg-blue-700">+</button>
            </div>
          </div>
        </li>
        <li data-item data-tenant="2" data-filter-item="menu" data-kategori="Minuman" data-nama="Es Jeruk" data-harga="5000" data-sel="false" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 transition data-[sel=true]:border-blue-300 data-[sel=true]:bg-blue-50/50">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Es Jeruk</p>
            <p class="mt-0.5 text-sm text-slate-500">Perasan jeruk segar dengan es batu.</p>
            <p class="mt-1.5 font-bold text-slate-900">Rp5.000 <span class="ml-1 text-xs font-medium text-slate-400"></span></p>
          </div>
          <div class="shrink-0">
            <button type="button" data-d="1" data-add class="rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">+ Tambah</button>
            <div data-stepper hidden class="flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
              <button type="button" data-d="-1" aria-label="Kurangi Es Jeruk" class="size-9 text-lg font-semibold text-slate-500 hover:bg-slate-100">&minus;</button>
              <input type="number" form="formKeranjang" name="jumlah[5]" min="0" max="30" value="0" aria-label="Jumlah Es Jeruk"
                class="w-9 border-0 bg-transparent p-0 text-center text-sm font-bold focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" data-d="1" aria-label="Tambah Es Jeruk" class="size-9 bg-blue-600 text-lg font-semibold text-white hover:bg-blue-700">+</button>
            </div>
          </div>
        </li>
        <li data-item data-tenant="2" data-filter-item="menu" data-kategori="Snack" data-nama="Pangsit Goreng" data-harga="6000" data-sel="false" class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 transition data-[sel=true]:border-blue-300 data-[sel=true]:bg-blue-50/50">
          <div class="min-w-0">
            <p class="font-semibold text-slate-900">Pangsit Goreng</p>
            <p class="mt-0.5 text-sm text-slate-500">Pangsit goreng isi ayam, renyah dan gurih.</p>
            <p class="mt-1.5 font-bold text-slate-900">Rp6.000 <span class="ml-1 text-xs font-medium text-slate-400"></span></p>
          </div>
          <div class="shrink-0">
            <button type="button" data-d="1" data-add class="rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">+ Tambah</button>
            <div data-stepper hidden class="flex items-center overflow-hidden rounded-lg border border-slate-200 bg-white">
              <button type="button" data-d="-1" aria-label="Kurangi Pangsit Goreng" class="size-9 text-lg font-semibold text-slate-500 hover:bg-slate-100">&minus;</button>
              <input type="number" form="formKeranjang" name="jumlah[6]" min="0" max="40" value="0" aria-label="Jumlah Pangsit Goreng"
                class="w-9 border-0 bg-transparent p-0 text-center text-sm font-bold focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" data-d="1" aria-label="Tambah Pangsit Goreng" class="size-9 bg-blue-600 text-lg font-semibold text-white hover:bg-blue-700">+</button>
            </div>
          </div>
        </li>
      </ul>
      <p data-filter-empty="menu" hidden class="py-6 text-center text-sm text-slate-500">Menu tidak ditemukan.</p>
    </section>

    <aside id="keranjang" class="h-fit scroll-mt-32 rounded-xl border-2 border-blue-500/70 bg-white p-4 shadow-sm sm:p-5 md:sticky md:top-24 md:self-start">
      <form id="formKeranjang" action="<?= url('pages/buyer/checkout.php') ?>" method="get">
        <div class="flex items-start justify-between gap-2">
          <div>
            <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-blue-700">Keranjang</span>
            <p class="mt-2 text-lg font-bold text-slate-900">Tenant: <span data-tenant-nama>Ayam Geprek Mas Joko</span></p>
          </div>
          <span class="whitespace-nowrap rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500"><span data-count>2</span> Jenis Menu</span>
        </div>

        <p class="mt-4 flex gap-2 rounded-lg bg-blue-50 p-3 text-xs leading-relaxed text-blue-800">
          <svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
          <span>Satu kali checkout hanya untuk satu tenant. Nomor antrean diberikan setelah pesanan diterima tenant.</span>
        </p>

        <ul id="cartList" class="mt-2 divide-y divide-dashed divide-slate-200">
            <li class="flex items-start justify-between gap-3 py-3 text-sm">
              <div><p class="font-semibold text-slate-900">2&times; Ayam Geprek Level 3</p><p class="text-xs text-slate-500">@ Rp15.000</p></div>
              <p class="font-semibold text-slate-900">Rp30.000</p>
            </li>
            <li class="flex items-start justify-between gap-3 py-3 text-sm">
              <div><p class="font-semibold text-slate-900">1&times; Es Teh Manis</p><p class="text-xs text-slate-500">@ Rp4.000</p></div>
              <p class="font-semibold text-slate-900">Rp4.000</p>
            </li>
        </ul>
        <p id="cartEmpty" hidden class="py-6 text-center text-sm text-slate-500">Belum ada menu yang dipilih.</p>

        <div class="mt-3 space-y-1.5 rounded-lg bg-slate-50 p-3 text-sm">
          <p class="flex justify-between text-base font-bold text-slate-900"><span>Total Tagihan</span><span class="text-blue-600" data-total>Rp34.000</span></p>
        </div>

        <input type="hidden" name="id_tenant" value="1">
        <button type="submit" data-checkout class="mt-4 flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-blue-600 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-300">
          Lanjut Pembayaran <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
      </form>

    </aside>
  </main>


