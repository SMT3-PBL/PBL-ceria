<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PBL-Ceria · Pesan Lewat QR</title>

  <!-- Tailwind via CDN. Kalau nanti pakai CLI, ganti dengan:
       <link rel="stylesheet" href="assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-white text-gray-800">

  <!-- Navbar -->
  <header class="border-b border-gray-100">
    <div class="mx-auto max-w-6xl flex items-center justify-between px-4 py-4">
      <a href="index.php" class="text-lg font-bold text-blue-600">PBL-Ceria</a>
      <nav class="flex items-center gap-3 text-sm">
        <a href="pages/auth/login.php" class="px-3 py-2 text-gray-600 hover:text-gray-900">Login</a>
        <a href="pages/auth/register.php"
          class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 transition-colors">Daftar</a>
      </nav>
    </div>
  </header>

  <main>

    <!-- Hero -->
    <section class="bg-gradient-to-b from-blue-50 to-white">
      <div class="mx-auto max-w-6xl px-4 py-20 text-center">
        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-gray-900">
          Pesan makanan cukup<br class="hidden sm:block"> scan QR di meja
        </h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
          Satu platform untuk banyak tenant. Pembeli memesan langsung dari ponsel,
          penjual menerima pesanan tanpa antre dan tanpa ribet.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
          <a href="pages/auth/register.php"
            class="w-full sm:w-auto rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 transition-colors">
            Daftar sebagai Penjual
          </a>
          <a href="pages/auth/login.php"
            class="w-full sm:w-auto rounded-lg border border-gray-300 hover:bg-gray-50 font-medium px-6 py-3 transition-colors">
            Sudah punya akun? Login
          </a>
        </div>
      </div>
    </section>

    <!-- Cara kerja -->
    <section class="mx-auto max-w-6xl px-4 py-16">
      <h2 class="text-2xl font-bold text-center">Cara Kerjanya</h2>
      <p class="text-gray-500 text-center mt-1">Tiga langkah dari meja sampai dapur</p>

      <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-gray-200 p-6">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold">1
          </div>
          <h3 class="mt-4 font-semibold">Scan QR di meja</h3>
          <p class="mt-1 text-sm text-gray-600">Pembeli memindai kode QR yang tertempel di meja, tanpa perlu membuat
            akun.</p>
        </div>
        <div class="rounded-2xl border border-gray-200 p-6">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold">2
          </div>
          <h3 class="mt-4 font-semibold">Pilih menu dan pesan</h3>
          <p class="mt-1 text-sm text-gray-600">Daftar menu tenant tampil di ponsel. Pilih, tentukan jumlah, lalu kirim
            pesanan.</p>
        </div>
        <div class="rounded-2xl border border-gray-200 p-6">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold">3
          </div>
          <h3 class="mt-4 font-semibold">Penjual memproses</h3>
          <p class="mt-1 text-sm text-gray-600">Pesanan masuk ke dashboard penjual lengkap dengan nomor meja, siap
            diproses.</p>
        </div>
      </div>
    </section>

    <!-- Fitur -->
    <section class="bg-gray-50">
      <div class="mx-auto max-w-6xl px-4 py-16">
        <h2 class="text-2xl font-bold text-center">Untuk Penjual</h2>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Kelola Menu</h3>
            <p class="mt-1 text-sm text-gray-600">Tambah, ubah, dan hapus menu kapan saja.</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Pesanan Real-time</h3>
            <p class="mt-1 text-sm text-gray-600">Pantau pesanan baru, diproses, dan selesai.</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <h3 class="font-semibold">QR per Meja</h3>
            <p class="mt-1 text-sm text-gray-600">Buat dan unduh kode QR untuk setiap meja.</p>
          </div>
          <div class="rounded-xl bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Data Terpisah</h3>
            <p class="mt-1 text-sm text-gray-600">Tiap tenant hanya melihat datanya sendiri.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="mx-auto max-w-6xl px-4 py-16 text-center">
      <h2 class="text-2xl font-bold">Siap mencoba?</h2>
      <p class="mt-2 text-gray-600">Daftarkan tenant kamu dan mulai terima pesanan lewat QR.</p>
      <a href="pages/auth/register.php"
        class="mt-6 inline-block rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 transition-colors">
        Mulai Sekarang
      </a>
    </section>

  </main>

  <footer class="border-t border-gray-100">
    <div class="mx-auto max-w-6xl px-4 py-6 text-center text-sm text-gray-500">
      © 2026 PBL-Ceria · Proyek Pembelajaran Berbasis Proyek
    </div>
  </footer>

</body>

</html>