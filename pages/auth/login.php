<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Masuk';
$role      = 'login';
$active    = '';
require __DIR__ . '/../../includes/header.php';
?>
  <main class="mx-auto flex min-h-screen w-full max-w-md flex-col justify-center px-4 py-10">
    <div class="mb-6 flex items-center justify-center gap-3">
      <span class="grid size-10 place-items-center rounded-xl bg-primary text-white"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg></span>
      <span class="text-xl font-extrabold tracking-tight">Food Court</span>
    </div>

    <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 sm:!p-8">
      <h1 class="text-2xl font-extrabold tracking-tight">Masuk</h1>

      <form id="formLogin" action="" method="post" class="space-y-4">

        <!--Validasi admin atau penjual-->
        <fieldset>
          <div class="grid grid-cols-2 gap-3 text-sm font-bold">
            <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-outline bg-white px-3 py-3 text-muted hover:bg-soft has-[:checked]:border-primary has-[:checked]:bg-soft has-[:checked]:text-primary has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-primary/30">
              <input type="radio" name="role" value="penjual" checked class="sr-only">Penjual
            </label>
            <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-outline bg-white px-3 py-3 text-muted hover:bg-soft has-[:checked]:border-primary has-[:checked]:bg-soft has-[:checked]:text-primary has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-primary/30">
              <input type="radio" name="role" value="admin" class="sr-only">Admin
            </label>
          </div>
        </fieldset>
        
        <!--Input email-->
        <p><label for="email" class="mb-1.5 block text-sm font-semibold text-ink">Email</label>
          <input type="email" id="email" name="email" required autocomplete="username" placeholder="nama@email.com" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>

        <!-- Input Password-->
        <p><label for="password" class="mb-1.5 block text-sm font-semibold text-ink">Kata Sandi</label>
          <span class="relative block">
            <input type="password" id="password" name="password" required autocomplete="current-password" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 pr-12">
            <button type="button" id="lihat" data-toggle-password="#password" aria-label="Tampilkan kata sandi" class="absolute right-1 top-1/2 grid size-9 -translate-y-1/2 place-items-center rounded-lg text-muted hover:bg-soft"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></button>
          </span></p>
          
        <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 h-12 w-full">Masuk</button>
      </form>
    </section>
  </main>
</body>
</html>
