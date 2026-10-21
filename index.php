<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PBL-Ceria · Pesan Lewat QR</title>

  <!-- Tailwind via CDN. Kalau nanti pakai CLI, ganti dengan:
       <link rel="stylesheet" href="assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style type="text/tailwindcss">
    @theme {
      --font-sans: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
    }
  </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
  <header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center px-4 py-3">
      <span class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-slate-900"><span class="grid size-9 place-items-center rounded-xl bg-blue-600 text-lg font-extrabold text-white">F</span><span>Food Court</span></span>
    </div>
  </header>

  <main class="mx-auto flex w-full max-w-md flex-col justify-center px-4 py-8 sm:py-14">
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Selamat Datang</h1>
      <p class="mb-6 mt-1 text-sm text-slate-500">Isi nomor meja dan nama Anda sebelum memilih tenant dan menu.</p>

      <form id="formIdentitas" action="menu.html" method="post" class="space-y-4">
        <p>
          <label for="no_meja" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor Meja</label>
          <input type="text" id="no_meja" name="no_meja" inputmode="numeric" autocomplete="off" required
            class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-base placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
            placeholder="Contoh: 12">
          <small class="mt-1 block text-xs text-slate-500">Isi sesuai nomor meja tempat Anda duduk.</small>
        </p>
        <p>
          <label for="nama_pembeli" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama</label>
          <input type="text" id="nama_pembeli" name="nama_pembeli" autocomplete="name" required
            class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-base placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200"
            placeholder="Nama Anda">
        </p>
        <button type="submit"
          class="flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-blue-600 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
          Mulai Memesan <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
      </form>
    </section>
  </main>
</body>

</html>