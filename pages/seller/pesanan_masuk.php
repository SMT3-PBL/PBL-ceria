<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Pesanan Masuk';
$role      = 'seller';
$active    = 'orders';
require __DIR__ . '/../../includes/header.php';
?>

  <main class="mx-auto max-w-6xl space-y-5 p-4 pb-10 sm:p-6">
    <h1 class="sr-only">Pesanan Masuk</h1>
    <div>
      <p class="mt-1 text-sm text-muted">Berikut adalah pesanan masuk dari pelanggan hari ini.</p>
    </div>

    <section aria-label="Ringkasan hari ini" class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
      <div class="col-span-2 lg:col-span-1 rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 !p-4 sm:!p-5"><p class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Pendapatan Hari Ini</p><p class="mt-2 text-2xl font-extrabold sm:text-3xl">Rp34.000</p></div>
      <div class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 !p-4 sm:!p-5"><p class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Total Pesanan Masuk</p><p class="mt-2 text-2xl font-extrabold sm:text-3xl">2 <span class="text-base font-bold text-muted">Pesanan</span></p></div>
      <div class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 !p-4 sm:!p-5"><p class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Total Porsi Dipesan</p><p class="mt-2 text-2xl font-extrabold sm:text-3xl">5 <span class="text-base font-bold text-muted">Porsi</span></p></div>
    </section>

    <section aria-labelledby="judul-antrean" class="space-y-4">
      <div class="flex items-center gap-2">
        <h2 id="judul-antrean" class="text-sm font-extrabold uppercase tracking-wider">Antrean Pesanan Masuk</h2>
        <span data-jumlah-antrean class="grid size-6 place-items-center rounded-full bg-primary text-xs font-bold text-white">2</span>
      </div>
      <p id="antreanKosong" hidden class="rounded-2xl bg-white p-8 text-center text-sm text-muted ring-1 ring-line/60">Belum ada pesanan masuk.</p>
      <div class="grid gap-4 xl:grid-cols-2 xl:items-start">
        
      <!-- Pesanan 1 -->
      <article data-pesanan="1" class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <span class="rounded-lg bg-primary px-3 py-2 text-sm font-extrabold uppercase text-white">Meja 12</span>
            <div class="leading-tight"><p class="font-bold">Nara</p><p data-antrean class="text-xs text-muted">Antrean 001</p></div>
          </div>
          <div class="flex items-center gap-2"><span data-badge-bayar class="rounded-md bg-ok-soft text-ok px-2.5 py-1 text-[11px] font-extrabold uppercase">QRIS Lunas</span><span class="text-xs text-muted">12:30 WIB</span></div>
        </div>

        <h3 class="mb-1 mt-5 text-[11px] font-extrabold uppercase tracking-wider text-muted">Rincian Menu Pesanan</h3>
        <ul class="divide-y divide-line/70"><li class="flex items-center justify-between gap-3 py-2 text-sm"><span class="flex items-center gap-2.5"><span class="grid min-w-7 place-items-center rounded-md bg-soft px-1.5 py-0.5 text-xs font-bold text-primary">2&times;</span>Ayam Geprek Level 3</span><span class="font-semibold">Rp30.000</span></li><li class="flex items-center justify-between gap-3 py-2 text-sm"><span class="flex items-center gap-2.5"><span class="grid min-w-7 place-items-center rounded-md bg-soft px-1.5 py-0.5 text-xs font-bold text-primary">1&times;</span>Es Teh Manis</span><span class="font-semibold">Rp4.000</span></li></ul>
        <p class="mt-1 flex items-center justify-between border-t border-line/70 pt-3"><span class="text-sm text-muted">Total Pembayaran</span><span class="text-xl font-extrabold">Rp34.000</span></p>

        <div class="mt-4 rounded-xl bg-surface p-3 ring-1 ring-line/60 sm:p-4">
          <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Bukti Pembayaran</h3>
          <details class="mt-2">
            <summary class="flex cursor-pointer list-none items-center gap-2 text-sm font-semibold text-primary [&::-webkit-details-marker]:hidden">Lihat bukti transfer</summary>
            <div class="mt-3 grid aspect-[4/3] max-w-xs place-items-center rounded-xl border-2 border-dashed border-outline bg-white text-muted">
              <div class="text-center"><svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg><p class="mt-1 text-xs font-semibold">Bukti transfer Nara</p></div>
            </div>
          </details>
        </div>
        <p data-bayar-ok class="mt-4 flex items-center gap-2 text-sm font-semibold text-ok"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Pembayaran sudah dikonfirmasi</p>

        <h3 class="mb-2 mt-5 text-[11px] font-extrabold uppercase tracking-wider text-muted">Ubah Status Pesanan</h3>
        <form method="post" action="" data-langkah class="grid grid-cols-2 gap-2 sm:grid-cols-4">
          <input type="hidden" name="id_pesanan" value="1">
          <button type="submit" name="status_pesanan" value="Diterima" data-done="true" class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">Diterima</span><span class="block text-[10px] opacity-80">Pesanan masuk</span></button>
          <button type="submit" name="status_pesanan" value="Diproses" aria-current="step" class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">2. Diproses</span><span class="block text-[10px] opacity-80">Sedang dimasak</span></button>
          <button type="submit" name="status_pesanan" value="Siap Diambil" class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">3. Siap Diambil</span><span class="block text-[10px] opacity-80">Panggil meja</span></button>
          <button type="submit" name="status_pesanan" value="Selesai" class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">4. Selesai</span><span class="block text-[10px] opacity-80">Arsip</span></button>
        </form>
      </article>

      <!-- Pesanan 2 -->
      <article data-pesanan="2" class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
          <div class="flex items-center gap-3">
            <span class="rounded-lg bg-primary px-3 py-2 text-sm font-extrabold uppercase text-white">Meja 05</span>
            <div class="leading-tight"><p class="font-bold">Angela Salma</p><p data-antrean class="text-xs text-muted">Belum diterima</p></div>
          </div>
          <div class="flex items-center gap-2"><span data-badge-bayar class="rounded-md bg-warn-soft text-warn px-2.5 py-1 text-[11px] font-extrabold uppercase">QRIS Belum Lunas</span><span class="text-xs text-muted">12:32 WIB</span></div>
        </div>

        <h3 class="mb-1 mt-5 text-[11px] font-extrabold uppercase tracking-wider text-muted">Rincian Menu Pesanan</h3>
        <ul class="divide-y divide-line/70"><li class="flex items-center justify-between gap-3 py-2 text-sm"><span class="flex items-center gap-2.5"><span class="grid min-w-7 place-items-center rounded-md bg-soft px-1.5 py-0.5 text-xs font-bold text-primary">1&times;</span>Ayam Geprek Level 3</span><span class="font-semibold">Rp15.000</span></li><li class="flex items-center justify-between gap-3 py-2 text-sm"><span class="flex items-center gap-2.5"><span class="grid min-w-7 place-items-center rounded-md bg-soft px-1.5 py-0.5 text-xs font-bold text-primary">1&times;</span>Es Teh Manis</span><span class="font-semibold">Rp4.000</span></li></ul>
        <p class="mt-1 flex items-center justify-between border-t border-line/70 pt-3"><span class="text-sm text-muted">Total Pembayaran</span><span class="text-xl font-extrabold">Rp19.000</span></p>

        <div class="mt-4 rounded-xl bg-surface p-3 ring-1 ring-line/60 sm:p-4">
          <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-muted">Bukti Pembayaran</h3>
          <details class="mt-2">
            <summary class="flex cursor-pointer list-none items-center gap-2 text-sm font-semibold text-primary [&::-webkit-details-marker]:hidden">Lihat bukti transfer</summary>
            <div class="mt-3 grid aspect-[4/3] max-w-xs place-items-center rounded-xl border-2 border-dashed border-outline bg-white text-muted">
              <div class="text-center"><svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg><p class="mt-1 text-xs font-semibold">Bukti transfer Angela Salma</p></div>
            </div>
          </details>
        </div>
        <form method="post" action="" data-bayar-aksi class="mt-4 flex flex-wrap items-center gap-2">
          <input type="hidden" name="id_pesanan" value="2">
          <button type="submit" name="status_bayar" value="Lunas" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Konfirmasi Pembayaran</button>
          <button type="submit" name="status_pesanan" value="Dibatalkan" data-confirm="Tolak pesanan ini?" class="h-10 rounded-lg bg-danger-soft px-4 text-sm font-semibold text-danger hover:brightness-95">Tolak</button>
        </form>
        <p data-bayar-ok hidden class="mt-4 flex items-center gap-2 text-sm font-semibold text-ok"><svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg> Pembayaran sudah dikonfirmasi</p>

        <h3 class="mb-2 mt-5 text-[11px] font-extrabold uppercase tracking-wider text-muted">Ubah Status Pesanan</h3>
        <form method="post" action="" data-langkah class="grid grid-cols-2 gap-2 sm:grid-cols-4">
          <input type="hidden" name="id_pesanan" value="2">
          <button type="submit" name="status_pesanan" value="Diterima" disabled class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">Diterima</span><span class="block text-[10px] opacity-80">Pesanan masuk</span></button>
          <button type="submit" name="status_pesanan" value="Diproses" disabled class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">2. Diproses</span><span class="block text-[10px] opacity-80">Sedang dimasak</span></button>
          <button type="submit" name="status_pesanan" value="Siap Diambil" disabled class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">3. Siap Diambil</span><span class="block text-[10px] opacity-80">Panggil meja</span></button>
          <button type="submit" name="status_pesanan" value="Selesai" disabled class="rounded-lg border border-line bg-surface px-2 py-2.5 text-center text-muted transition hover:bg-soft disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-surface aria-[current=step]:border-primary aria-[current=step]:bg-primary aria-[current=step]:text-white aria-[current=step]:hover:bg-primary-dark data-[done=true]:border-transparent data-[done=true]:bg-soft data-[done=true]:text-primary"><span class="block text-xs font-bold">4. Selesai</span><span class="block text-[10px] opacity-80">Arsip</span></button>
        </form>
        <p data-langkah-info class="mt-2 text-xs text-muted">Konfirmasi pembayaran terlebih dahulu sebelum mengubah status pesanan.</p>
      </article>

      </div>
    </section>

    <section class="flex gap-3 rounded-2xl bg-soft p-4 text-sm text-primary sm:p-5">
      <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
      <div><h2 class="font-bold">Petunjuk Antrean Tenant</h2>
        <p class="mt-1 text-primary/90">Setelah menekan &ldquo;Siap Diambil&rdquo;, nomor antrean dan meja pembeli akan menerima notifikasi pengambilan pesanan.</p></div>
    </section>
  </main>

</body>
</html>