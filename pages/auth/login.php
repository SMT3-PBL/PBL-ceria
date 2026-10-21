<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Tailwind via CDN. Kalau nanti pakai CLI, ganti dengan:
       <link rel="stylesheet" href="../../assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style type="text/tailwindcss">
    @theme {
      --font-sans: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      --color-primary: #004ac6;
      --color-primary-dark: #003ea8;
      --color-soft: #eff4ff;
      --color-surface: #f8f9ff;
      --color-ink: #0b1c30;
      --color-muted: #434655;
      --color-line: #d3e4fe;
      --color-outline: #c3c6d7;
      --color-ok: #002113;
      --color-ok-soft: #6ffbbe;
      --color-warn: #9d4300;
      --color-warn-soft: #ffdbca;
      --color-danger: #ba1a1a;
      --color-danger-soft: #ffdad6;
    }
  </style>
</head> 

<body class="min-h-screen bg-surface font-sans text-ink antialiased">
  <main class="mx-auto flex min-h-screen w-full max-w-md flex-col justify-center px-4 py-10">
    <div class="mb-6 flex items-center justify-center gap-3">
      <span class="grid size-10 place-items-center rounded-xl bg-primary text-white"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg></span>
      <span class="text-xl font-extrabold tracking-tight">Food Court</span>
    </div>

    <section class="rounded-2xl bg-white p-4 shadow-[0_2px_12px_rgba(11,28,48,0.06)] ring-1 ring-line/60 sm:p-6 sm:!p-8">
      <h1 class="text-2xl font-extrabold tracking-tight">Login</h1>

      <form id="formLogin" action="" method="post" class="space-y-4">
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

        <p><label for="email" class="mb-1.5 block text-sm font-semibold text-ink">Email</label>
          <input type="email" id="email" name="email" required autocomplete="username" placeholder="nama@email.com" class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink placeholder:text-muted/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></p>
        
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