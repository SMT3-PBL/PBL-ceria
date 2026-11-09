<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Kelola Menu';
$role      = 'seller';
$active    = 'menus';
require __DIR__ . '/../../includes/header.php';
?>

  <main class="mx-auto max-w-4xl space-y-4 p-4 pb-10 sm:p-6 print:max-w-none print:p-0">

    <form method="get" action="" class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 flex flex-wrap items-end gap-3 print:hidden">
      <p class="min-w-[9rem] flex-1"><label for="dari" class="mb-1.5 block text-sm font-semibold text-ink">Dari Tanggal</label><input type="date" id="dari" name="dari" value="2026-10-01" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>
      <p class="min-w-[9rem] flex-1"><label for="sampai" class="mb-1.5 block text-sm font-semibold text-ink">Sampai Tanggal</label><input type="date" id="sampai" name="sampai" value="2026-10-21" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>
      <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 max-sm:flex-1">Terapkan</button>
      <button type="button" data-print class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-soft px-5 text-sm font-bold text-primary hover:bg-line max-sm:flex-1"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Cetak</button>
    </form>

    <article class="space-y-8 rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-8 print:rounded-none print:p-0 print:shadow-none print:ring-0">
      <header class="border-b-2 border-ink pb-4 text-center">
        <h1 class="text-2xl font-extrabold uppercase tracking-wide sm:text-3xl">Laporan Penjualan</h1>
        <p class="mt-1 text-sm text-muted">Periode <span data-periode>01/10/2026 &ndash; 21/10/2026</span></p>
      </header>

      <section aria-label="Ringkasan penjualan" class="grid gap-3 sm:grid-cols-3 print:break-inside-avoid">
        <div class="rounded-xl bg-surface p-4 ring-1 ring-line/60"><p class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Total Omset</p><p class="mt-1 text-xl font-extrabold sm:text-2xl">Rp53.000</p></div>
        <div class="rounded-xl bg-surface p-4 ring-1 ring-line/60"><p class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Pesanan Selesai</p><p class="mt-1 text-xl font-extrabold sm:text-2xl">2 <span class="text-base font-bold text-muted">Pesanan</span></p></div>
        <div class="rounded-xl bg-surface p-4 ring-1 ring-line/60"><p class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Porsi Terjual</p><p class="mt-1 text-xl font-extrabold sm:text-2xl">5 <span class="text-base font-bold text-muted">Porsi</span></p></div>
      </section>

      <section>
        <h2 class="mb-3 text-sm font-extrabold uppercase tracking-wider">Penjualan per Menu</h2>
        <table class="w-full text-left text-sm max-md:block">
          <thead class="max-md:hidden"><tr class="border-b border-line text-xs font-extrabold uppercase tracking-wider text-muted [&>th]:py-3 [&>th]:pr-4 [&>th:last-child]:pr-0"><th scope="col">Nama Barang</th><th scope="col" class="text-right">Terjual</th><th scope="col" class="text-right">Harga Satuan</th><th scope="col" class="text-right">Penjualan</th></tr></thead>
          <tbody class="max-md:block max-md:space-y-3">
          <tr class="max-md:block max-md:rounded-xl max-md:bg-surface max-md:p-4 max-md:ring-1 max-md:ring-line/60 md:border-b md:border-line/60 md:last:border-0 print:break-inside-avoid">
            <td data-label="Nama Barang" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:pr-4 max-md:text-right">Ayam Geprek Level 3</td>
            <td data-label="Terjual" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-right">3</td>
            <td data-label="Harga Satuan" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-right">Rp15.000</td>
            <td data-label="Penjualan" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:text-right">Rp45.000</td>
          </tr>
          <tr class="max-md:block max-md:rounded-xl max-md:bg-surface max-md:p-4 max-md:ring-1 max-md:ring-line/60 md:border-b md:border-line/60 md:last:border-0 print:break-inside-avoid">
            <td data-label="Nama Barang" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:pr-4 max-md:text-right">Es Teh Manis</td>
            <td data-label="Terjual" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-right">2</td>
            <td data-label="Harga Satuan" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-right">Rp4.000</td>
            <td data-label="Penjualan" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:text-right">Rp8.000</td>
          </tr>
          </tbody>
        </table>
      </section>

      <section>
        <h2 class="mb-3 text-sm font-extrabold uppercase tracking-wider">Riwayat Transaksi</h2>
        <table class="w-full text-left text-sm max-md:block">
          <thead class="max-md:hidden"><tr class="border-b border-line text-xs font-extrabold uppercase tracking-wider text-muted [&>th]:py-3 [&>th]:pr-4 [&>th:last-child]:pr-0"><th scope="col">Tanggal</th><th scope="col">Nama Pelanggan</th><th scope="col">Metode Pembayaran</th><th scope="col" class="text-right">Total</th></tr></thead>
          <tbody class="max-md:block max-md:space-y-3">
          <tr class="max-md:block max-md:rounded-xl max-md:bg-surface max-md:p-4 max-md:ring-1 max-md:ring-line/60 md:border-b md:border-line/60 md:last:border-0 print:break-inside-avoid">
            <td data-label="Tanggal" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-muted">11/10/2026</td>
            <td data-label="Nama Pelanggan" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:pr-4">Nara</td>
            <td data-label="Metode Pembayaran" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4"><span class="whitespace-nowrap rounded-full bg-soft px-2.5 py-1 text-xs font-bold text-primary">QRIS</span></td>
            <td data-label="Total" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:text-right">Rp34.000</td>
          </tr>
          <tr class="max-md:block max-md:rounded-xl max-md:bg-surface max-md:p-4 max-md:ring-1 max-md:ring-line/60 md:border-b md:border-line/60 md:last:border-0 print:break-inside-avoid">
            <td data-label="Tanggal" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4 md:text-muted">10/10/2026</td>
            <td data-label="Nama Pelanggan" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:pr-4">Angela Salma</td>
            <td data-label="Metode Pembayaran" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none md:pr-4"><span class="whitespace-nowrap rounded-full bg-soft px-2.5 py-1 text-xs font-bold text-primary">Tunai</span></td>
            <td data-label="Total" class="max-md:flex max-md:items-start max-md:justify-between max-md:gap-4 max-md:py-1.5 md:py-3 before:text-xs before:font-bold before:uppercase before:text-muted before:content-[attr(data-label)] md:before:content-none font-bold md:text-right">Rp19.000</td>
          </tr>
          </tbody>
        </table>
      </section>
    </article>
  </main>
</body>
</html>
