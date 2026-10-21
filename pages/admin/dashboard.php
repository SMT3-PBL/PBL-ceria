<?php

require_once __DIR__ . '/../../includes/init.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit;
}

if ($_SESSION['user_role'] !== 'admin') {
  header('Location: ../../index.php');
  exit;
}

$nama = $_SESSION['user_name'];
?>

<!-- Dashboardn Admin Sementara -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>

  <!-- <link rel="stylesheet" href="../../assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <div class="min-h-screen md:flex">

    <!-- Sidebar -->
    <aside class="hidden md:flex md:w-64 flex-col bg-gray-900 text-gray-200">
      <div class="px-6 py-5 text-lg font-bold text-white border-b border-gray-800">
        PBL-Ceria <span class="block text-xs font-normal text-gray-400">Panel Admin</span>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
        <a href="dashboard.php" class="block rounded-lg bg-gray-800 px-3 py-2 text-white font-medium">Dashboard</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Tenant</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Pengguna</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Pesanan</a>
        <a href="#" class="block rounded-lg px-3 py-2 hover:bg-gray-800">Laporan</a>
      </nav>

      <div class="px-3 py-4 border-t border-gray-800">
        <a href="../auth/handlers/logout.php"
          class="block rounded-lg px-3 py-2 text-sm text-red-300 hover:bg-gray-800">Keluar</a>
      </div>
    </aside>

    <!-- Konten -->
    <div class="flex-1 min-w-0">

      <!-- Topbar -->
      <header class="flex items-center justify-between bg-white border-b border-gray-200 px-4 md:px-8 py-4">
        <h1 class="text-xl font-bold">Dashboard</h1>
        <div class="flex items-center gap-3">
          <span class="text-sm text-gray-500 hidden sm:inline">Halo, <?= htmlspecialchars($nama) ?></span>
          <a href="../auth/process/logout.php" class="md:hidden text-sm text-red-600">Keluar</a>
        </div>
      </header>

      <main class="px-4 md:px-8 py-6 space-y-6">

        <!-- Kartu statistik -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Total Tenant</p>
            <p class="text-3xl font-bold mt-1">24</p>
            <p class="text-xs text-green-600 mt-1">+3 bulan ini</p>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Total Menu</p>
            <p class="text-3xl font-bold mt-1">312</p>
            <p class="text-xs text-gray-400 mt-1">dari semua tenant</p>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
            <p class="text-3xl font-bold mt-1">87</p>
            <p class="text-xs text-green-600 mt-1">+12% dari kemarin</p>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold mt-1">Rp 4,2 jt</p>
            <p class="text-xs text-gray-400 mt-1">total semua tenant</p>
          </div>
        </section>

        <!-- Tabel tenant -->
        <section class="bg-white rounded-xl shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold">Tenant Terbaru</h2>
            <a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
              <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                  <th class="px-5 py-3">Tenant</th>
                  <th class="px-5 py-3">Pemilik</th>
                  <th class="px-5 py-3">Menu</th>
                  <th class="px-5 py-3">Status</th>
                  <th class="px-5 py-3">Terdaftar</th>
                  <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr>
                  <td class="px-5 py-3 font-medium">Warung Bu Ani</td>
                  <td class="px-5 py-3">Ani Wijaya</td>
                  <td class="px-5 py-3">18</td>
                  <td class="px-5 py-3"><span
                      class="rounded-full bg-green-100 text-green-700 px-2.5 py-0.5 text-xs">Aktif</span></td>
                  <td class="px-5 py-3 text-gray-500">12 Okt 2026</td>
                  <td class="px-5 py-3 text-right"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
                </tr>
                <tr>
                  <td class="px-5 py-3 font-medium">Kopi Senja</td>
                  <td class="px-5 py-3">Budi Santoso</td>
                  <td class="px-5 py-3">24</td>
                  <td class="px-5 py-3"><span
                      class="rounded-full bg-green-100 text-green-700 px-2.5 py-0.5 text-xs">Aktif</span></td>
                  <td class="px-5 py-3 text-gray-500">10 Okt 2026</td>
                  <td class="px-5 py-3 text-right"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
                </tr>
                <tr>
                  <td class="px-5 py-3 font-medium">Bakso Pak Joko</td>
                  <td class="px-5 py-3">Joko Pranoto</td>
                  <td class="px-5 py-3">9</td>
                  <td class="px-5 py-3"><span
                      class="rounded-full bg-yellow-100 text-yellow-700 px-2.5 py-0.5 text-xs">Menunggu</span></td>
                  <td class="px-5 py-3 text-gray-500">08 Okt 2026</td>
                  <td class="px-5 py-3 text-right"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
                </tr>
                <tr>
                  <td class="px-5 py-3 font-medium">Es Teh Mantap</td>
                  <td class="px-5 py-3">Siti Rahma</td>
                  <td class="px-5 py-3">6</td>
                  <td class="px-5 py-3"><span
                      class="rounded-full bg-gray-200 text-gray-600 px-2.5 py-0.5 text-xs">Nonaktif</span></td>
                  <td class="px-5 py-3 text-gray-500">02 Okt 2026</td>
                  <td class="px-5 py-3 text-right"><a href="#" class="text-blue-600 hover:underline">Detail</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Pesanan terbaru -->
        <section class="bg-white rounded-xl shadow-sm">
          <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold">Pesanan Terbaru</h2>
            <a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
          </div>

          <ul class="divide-y divide-gray-100 text-sm">
            <li class="flex items-center justify-between px-5 py-3">
              <div>
                <p class="font-medium">#ORD-1042 · Kopi Senja</p>
                <p class="text-gray-500 text-xs">Meja 5 · 2 item</p>
              </div>
              <div class="text-right">
                <p class="font-medium">Rp 48.000</p>
                <span class="rounded-full bg-blue-100 text-blue-700 px-2.5 py-0.5 text-xs">Diproses</span>
              </div>
            </li>
            <li class="flex items-center justify-between px-5 py-3">
              <div>
                <p class="font-medium">#ORD-1041 · Warung Bu Ani</p>
                <p class="text-gray-500 text-xs">Meja 2 · 3 item</p>
              </div>
              <div class="text-right">
                <p class="font-medium">Rp 65.000</p>
                <span class="rounded-full bg-green-100 text-green-700 px-2.5 py-0.5 text-xs">Selesai</span>
              </div>
            </li>
            <li class="flex items-center justify-between px-5 py-3">
              <div>
                <p class="font-medium">#ORD-1040 · Bakso Pak Joko</p>
                <p class="text-gray-500 text-xs">Meja 1 · 1 item</p>
              </div>
              <div class="text-right">
                <p class="font-medium">Rp 18.000</p>
                <span class="rounded-full bg-yellow-100 text-yellow-700 px-2.5 py-0.5 text-xs">Menunggu</span>
              </div>
            </li>
          </ul>
        </section>

      </main>
    </div>
  </div>

</body>

</html>