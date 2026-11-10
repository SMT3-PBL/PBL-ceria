<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Mulai Memesan';
$role      = 'start';
$active    = '';
require __DIR__ . '/../../includes/header.php';
?>
  <main class="mx-auto flex w-full max-w-md flex-col justify-center px-4 py-8 sm:py-14">
    <section class="rounded-2xl border border-line bg-white p-6 shadow-sm sm:p-8">
      <h1 class="text-2xl font-bold tracking-tight text-ink">Selamat Datang</h1>
      <p class="mb-6 mt-1 text-sm text-muted">Isi nomor meja dan nama Anda sebelum memilih tenant dan menu.</p>

<!-- Input No.Meja --> 
      <form id="formIdentitas" action="<?= url('pages/buyer/pilih_menu.php') ?>" method="post" class="space-y-4">
        <p>
          <label for="no_meja" class="mb-1.5 block text-sm font-semibold text-ink">Nomor Meja</label>
          <input type="text" id="no_meja" name="no_meja" inputmode="numeric" autocomplete="off" required
            class="h-11 w-full rounded-lg border border-line bg-white px-3 text-base text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
            placeholder="Contoh: 12">
          <small class="mt-1 block text-xs text-muted">Isi sesuai nomor meja tempat Anda duduk.</small>
        </p>
<!-- Input Nama --> 
        <p>
          <label for="nama_pembeli" class="mb-1.5 block text-sm font-semibold text-ink">Nama</label>
          <input type="text" id="nama_pembeli" name="nama_pembeli" autocomplete="name" required
            class="h-11 w-full rounded-lg border border-line bg-white px-3 text-base text-ink placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
            placeholder="Nama Anda">
        </p>
<!-- Submit --> 
        <button type="submit"
          class="flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-primary font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 shadow-sm">
          Mulai Memesan <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
      </form>
    </section>
  </main>
</body>
</html>