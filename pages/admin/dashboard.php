<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Daftar Tenant';
$role      = 'admin';
$active    = 'tenants';
require __DIR__ . '/../../includes/header.php';
?>

  <main class="mx-auto max-w-6xl space-y-5 p-4 pb-10 sm:p-6">
    <div><h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Daftar Tenant</h1></div>
    
    <!--Cari nama tenant-->
    <form method="get" action="" class="relative max-w-md">
      <label for="cari" class="sr-only">Cari nama tenant</label>
      <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></span>
      <input type="search" id="cari" name="cari" data-filter-input="tenant" placeholder="Cari nama tenant..." class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 pl-10">
    </form>

    <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 max-md:bg-transparent max-md:!p-0 max-md:shadow-none max-md:ring-0 md:!p-3">
      <table class="w-full text-left text-sm max-md:block">
        <thead class="max-md:hidden"><tr class="border-b border-line text-xs font-extrabold uppercase tracking-wider text-muted [&>th]:px-0 [&>th]:py-3">
          <th scope="col">ID</th><th scope="col">Nama Tenant</th><th scope="col">Akun Penjual (Email)</th><th scope="col">QRIS</th><th scope="col">Status</th><th scope="col">Aksi</th></tr></thead>
        <tbody class="max-md:block max-md:space-y-3">
          <tr data-filter-item="tenant" class="max-md:block max-md:rounded-xl max-md:bg-surface max-md:p-4 max-md:ring-1 max-md:ring-line/60 md:border-b md:border-line/60 md:last:border-0">
            <td data-label="ID" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-muted">1</td>
            <td data-label="Nama Tenant" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:pr-4">Ayam Geprek Mas Joko</td>
            <td data-label="Akun Penjual" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4">joko@gmail.com</td>
            <td data-label="QRIS" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4"><span class="whitespace-nowrap rounded-md bg-ok-soft px-2.5 py-1 text-[11px] font-extrabold uppercase text-ok">Sudah diunggah</span></td>
            <td data-label="Status" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4"><span class="rounded-full bg-ok-soft px-2.5 py-1 text-xs font-bold text-ok">Buka</span></td>
            <td data-label="Aksi" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none">
              <div class="flex items-center gap-3">
                <a href="edit_tenant.html?id=1" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-semibold text-primary hover:bg-soft"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg> Edit</a>
                <form method="post" action="" data-confirm="Hapus tenant ini beserta akun penjualnya?">
                  <input type="hidden" name="id_tenant" value="1">
                  <button type="submit" name="hapus" value="1" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-semibold text-danger hover:bg-danger-soft"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Hapus</button>
                </form>
              </div>
            </td>
          </tr>
          <tr data-filter-item="tenant" class="max-md:block max-md:rounded-xl max-md:bg-surface max-md:p-4 max-md:ring-1 max-md:ring-line/60 md:border-b md:border-line/60 md:last:border-0">
            <td data-label="ID" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-muted">2</td>
            <td data-label="Nama Tenant" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:pr-4">Bakso Solo Pak Kunto</td>
            <td data-label="Akun Penjual" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4">kunto@gmail.com</td>
            <td data-label="QRIS" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4"><span class="whitespace-nowrap rounded-md bg-ok-soft px-2.5 py-1 text-[11px] font-extrabold uppercase text-ok">Sudah diunggah</span></td>
            <td data-label="Status" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4"><span class="rounded-full bg-ok-soft px-2.5 py-1 text-xs font-bold text-ok">Buka</span></td>
            <td data-label="Aksi" class="max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3.5 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none">
              <div class="flex items-center gap-3">
                <a href="edit_tenant.html?id=2" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-semibold text-primary hover:bg-soft"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg> Edit</a>
                <form method="post" action="" data-confirm="Hapus tenant ini beserta akun penjualnya?">
                  <input type="hidden" name="id_tenant" value="2">
                  <button type="submit" name="hapus" value="1" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-semibold text-danger hover:bg-danger-soft"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg> Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <p data-filter-empty="tenant" hidden class="py-6 text-center text-sm text-muted">Tenant tidak ditemukan.</p>
    </section>
  </main>
</body>
</html>
