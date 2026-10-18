<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Seller</title>

  <!-- Tailwind via CDN. Kalau nanti pakai CLI, ganti dengan:
       <link rel="stylesheet" href="../../assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <div class="min-h-screen md:flex">

    <!-- Sidebar -->
    <aside class="hidden md:flex md:w-64 flex-col bg-gray-900 text-gray-200">
      <div class="px-6 py-5 text-lg font-bold text-white border-b border-gray-800">
        Warung Bu Ani
        <span class="block text-xs font-normal text-gray-400">Panel Seller</span>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
        <a href="dashboard.php" class="block rounded-lg bg-gray-800 px-3 py-2 text-white font-medium">Dashboard</a>
        <a href="menu/index.php" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Menu</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Pesanan</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">QR Meja</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Pengaturan Tenant</a>
      </nav>

      <div class="px-3 py-4 border-t border-gray-800">
        <a href="../auth/process/logout.php"
          class="block rounded-lg px-3 py-2 text-sm text-red-300 hover:bg-gray-800">Keluar</a>
      </div>
    </aside>

    <!-- Konten -->
    <div class="flex-1 min-w-0">

      <!-- Topbar -->
      <header class="flex items-center justify-between bg-white border-b border-gray-200 px-4 md:px-8 py-4">
        <h1 class="text-xl font-bold">Dashboard</h1>
        <div class="flex items-center gap-3">
          <span class="text-sm text-gray-500 hidden sm:inline">Halo, Ani</span>
          <a href="../auth/process/logout.php" class="md:hidden text-sm text-red-600">Keluar</a>
        </div>
      </header>

      <main class="px-4 md:px-8 py-6 space-y-6">

        <!-- Kartu statistik -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
            <p class="text-3xl font-bold mt-1">23</p>
            <p class="text-xs text-green-600 mt-1">+5 dari kemarin</p>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold mt-1">Rp 1,1 jt</p>
            <p class="text-xs text-gray-400 mt-1">dari pesanan selesai</p>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Total Menu</p>
            <p class="text-3xl font-bold mt-1">18</p>
            <p class="text-xs text-gray-400 mt-1">menu aktif</p>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Menu Habis</p>
            <p class="text-3xl font-bold mt-1">2</p>
            <p class="text-xs text-red-600 mt-1">perlu diisi ulang</p>
          </div>
        </section>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

          <!-- Pesanan masuk -->
          <section class="xl:col-span-2 bg-white rounded-xl shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
              <h2 class="font-semibold">Pesanan Masuk</h2>
              <a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                  <tr>
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Meja</th>
                    <th class="px-5 py-3">Item</th>
                    <th class="px-5 py-3">Total</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                  <tr>
                    <td class="px-5 py-3 font-medium">#1042</td>
                    <td class="px-5 py-3">5</td>
                    <td class="px-5 py-3">2</td>
                    <td class="px-5 py-3">Rp 48.000</td>
                    <td class="px-5 py-3"><span
                        class="rounded-full bg-yellow-100 text-yellow-700 px-2.5 py-0.5 text-xs">Baru</span></td>
                    <td class="px-5 py-3 text-right"><a href="#" class="text-blue-600 hover:underline">Proses</a></td>
                  </tr>
                  <tr>
                    <td class="px-5 py-3 font-medium">#1041</td>
                    <td class="px-5 py-3">2</td>
                    <td class="px-5 py-3">3</td>
                    <td class="px-5 py-3">Rp 65.000</td>
                    <td class="px-5 py-3"><span
                        class="rounded-full bg-blue-100 text-blue-700 px-2.5 py-0.5 text-xs">Diproses</span></td>
                    <td class="px-5 py-3 text-right"><a href="#" class="text-blue-600 hover:underline">Selesaikan</a>
                    </td>
                  </tr>
                  <tr>
                    <td class="px-5 py-3 font-medium">#1040</td>
                    <td class="px-5 py-3">1</td>
                    <td class="px-5 py-3">1</td>
                    <td class="px-5 py-3">Rp 18.000</td>
                    <td class="px-5 py-3"><span
                        class="rounded-full bg-green-100 text-green-700 px-2.5 py-0.5 text-xs">Selesai</span></td>
                    <td class="px-5 py-3 text-right"><a href="#" class="text-gray-400">Detail</a></td>
                  </tr>
                  <tr>
                    <td class="px-5 py-3 font-medium">#1039</td>
                    <td class="px-5 py-3">7</td>
                    <td class="px-5 py-3">4</td>
                    <td class="px-5 py-3">Rp 92.000</td>
                    <td class="px-5 py-3"><span
                        class="rounded-full bg-green-100 text-green-700 px-2.5 py-0.5 text-xs">Selesai</span></td>
                    <td class="px-5 py-3 text-right"><a href="#" class="text-gray-400">Detail</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          <!-- Kolom kanan -->
          <div class="space-y-6">

            <!-- Menu terlaris -->
            <section class="bg-white rounded-xl shadow-sm">
              <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold">Menu Terlaris</h2>
              </div>
              <ul class="divide-y divide-gray-100 text-sm">
                <li class="flex items-center justify-between px-5 py-3">
                  <span>Nasi Goreng Spesial</span>
                  <span class="text-gray-500">32 terjual</span>
                </li>
                <li class="flex items-center justify-between px-5 py-3">
                  <span>Es Teh Manis</span>
                  <span class="text-gray-500">28 terjual</span>
                </li>
                <li class="flex items-center justify-between px-5 py-3">
                  <span>Mie Ayam</span>
                  <span class="text-gray-500">21 terjual</span>
                </li>
              </ul>
            </section>

            <!-- Aksi cepat -->
            <section class="bg-white rounded-xl shadow-sm p-5 space-y-3">
              <h2 class="font-semibold">Aksi Cepat</h2>
              <a href="menu/tambah.php"
                class="block w-full text-center rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 text-sm transition-colors">
                + Tambah Menu
              </a>
              <a href="#"
                class="block w-full text-center rounded-lg border border-gray-300 hover:bg-gray-50 font-medium py-2.5 text-sm transition-colors">
                Unduh QR Meja
              </a>
            </section>

          </div>
        </div>

      </main>
    </div>
  </div>

</body>

</html>