<?php
require_once __DIR__ . '/../../includes/init.php';
$pageTitle = 'Daftar Tenant';
$role      = 'admin';
$active    = 'tenants';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/models/Tenant.php';

$id_tenant = $_GET['id'] ?? null;
if (!$id_tenant) {
  header("Location: dashboard.php");
  exit;
}

$tenantModel = new Tenant($pdo);
$tenant = $tenantModel->getById($id_tenant);

if (!$tenant) {
  die("Data Tenant tidak ditemukan.");
}
?>

<!-- <!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Tenant - Food Court Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif']
          },
          colors: {
            brand: '#0B4FD1',
            ink: '#0F1F3D',
            page: '#F5F7FC'
          }
        }
      }
    }
  </script>
</head> -->

<body class="bg-page font-sans text-ink min-h-screen">

  <!-- KONTEN -->
  <main class="w-full max-w-[400px] mx-auto px-4 py-5">
    <h1 class="text-2xl font-extrabold mb-4">Edit Tenant</h1>

    <!-- Tampilkan Error jika ada -->
    <?php if (isset($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form action="handlers/edit_process.php" method="POST" enctype="multipart/form-data" class="space-y-4">

      <!-- Input Tersembunyi  -->
      <input type="hidden" name="id_tenant" value="<?= htmlspecialchars($tenant['id_tenant']) ?>">
      <input type="hidden" name="id_pengguna" value="<?= htmlspecialchars($tenant['id_pengguna']) ?>">
      <input type="hidden" name="qris_lama" value="<?= htmlspecialchars($tenant['qris'] ?? '') ?>">

      <!-- Data Tenant -->
      <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <h2 class="text-sm font-bold mb-4">Data Tenant</h2>

        <label for="nama_tenant" class="block text-xs font-semibold mb-1.5">Nama Tenant</label>
        <input id="nama_tenant" name="nama_tenant" type="text" required value="<?= htmlspecialchars($tenant['nama_tenant']) ?>"
          class="w-full h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">

        <label for="qris" class="block text-xs font-semibold mt-4 mb-1.5">Ganti Gambar QRIS</label>
        <div class="flex items-center gap-3 mb-2">
          <label for="qris" class="cursor-pointer px-3.5 py-2 rounded-md bg-blue-50 text-brand text-xs font-semibold hover:bg-blue-100">
            Choose File
          </label>
          <span id="file-name" class="text-xs text-slate-500">No file chosen</span>
          <input id="qris" name="gambar_qris" type="file" accept="image/*" class="hidden"
            onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? 'No file chosen'">
        </div>

        <!-- Tampilkan hanya jika QRIS sudah ada: @if($tenant->gambar_qris) -->
        <?php if (!empty($tenant['qr_pembayaran'])): ?>
          <p class="flex items-center gap-1.5 text-[11px] text-slate-500">
            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            QRIS saat ini: <span class="font-bold text-slate-700"><?= htmlspecialchars($tenant['qr_pembayaran']) ?></span>. Kosongkan jika tidak ingin mengganti.
          </p>
        <?php endif; ?>
      </section>

      <!-- Akun Penjual Tenant -->
      <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <h2 class="text-sm font-bold mb-4">Akun Penjual Tenant</h2>

        <label for="nama_penjual" class="block text-xs font-semibold mb-1.5">Nama Penjual</label>
        <input id="nama_penjual" name="nama_penjual" type="text" required value="<?= htmlspecialchars($tenant['nama_penjual']) ?>"
          class="w-full h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">

        <label for="email" class="block text-xs font-semibold mt-4 mb-1.5">Email</label>
        <input id="email" name="email" type="email" required value="<?= htmlspecialchars($tenant['email']) ?>"
          class="w-full h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">

        <label for="password" class="block text-xs font-semibold mt-4 mb-1.5">Kata Sandi Baru</label>
        <input id="password" name="password" type="password" minlength="8"
          class="w-full h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
        <p class="text-[11px] text-slate-500 mt-1.5">Kosongkan jika tidak ingin mengganti kata sandi.</p>
      </section>

      <!-- Aksi -->
      <div class="flex items-center justify-end gap-5 pt-1">
        <a href="#" class="text-xs font-medium text-slate-700 hover:text-ink">Batal</a>
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-800 text-white text-xs font-semibold rounded-lg px-5 py-2.5 transition">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </main>

</body>

</html>