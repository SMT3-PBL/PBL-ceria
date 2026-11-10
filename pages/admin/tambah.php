<?php
// pages/admin/tambah.php
// Tugas file ini: MENAMPILKAN form. Pemrosesan ada di handlers/tambah_process.php

require_once __DIR__ . '/../../includes/init.php';
require_once BASE_PATH . '/includes/header.php';
// echo '<pre>'; var_dump($_SESSION); exit;

// Proteksi halaman: hanya admin
$role = $_SESSION['user_role'] ?? null;
if ($role !== 'admin') {
  header('Location: ../auth/login.php');
  exit;
}

// Fungsi untuk mengamankan teks sebelum dicetak ke HTML (cegah XSS)
function e($v)
{
  return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Ambil error & isian lama yang dititipkan handler, lalu hapus (sekali pakai)
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? ['nama_tenant' => '', 'nama_penjual' => '', 'email' => ''];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!-- <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Tenant - Food Court Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = { theme: { extend: {
      fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
      colors: { brand: '#0B4FD1', ink: '#0F1F3D', page: '#F5F7FC' } } } }
  </script>
</head> -->

<body class="bg-page font-sans text-ink min-h-screen">

  <main class="w-full max-w-[400px] mx-auto px-4 py-5">
    <h1 class="text-2xl font-extrabold mb-4">Daftarkan Tenant Baru</h1>

    <?php if ($errors): ?>
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700">
        <ul class="list-disc pl-4 space-y-0.5">
          <?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Form dikirim ke handler, bukan ke file ini -->
    <form action="handlers/tambah_process.php" method="POST" enctype="multipart/form-data" class="space-y-4">
      <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <h2 class="text-xs font-bold mb-4">Data Tenant</h2>

        <label for="nama_tenant" class="block text-[11px] font-semibold mb-1.5">Nama Tenant</label>
        <input id="nama_tenant" name="nama_tenant" type="text" required value="<?= e($old['nama_tenant']) ?>"
          class="w-full h-10 rounded-lg border border-slate-200 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">

        <label for="qris" class="block text-[11px] font-semibold mt-4 mb-1.5">Gambar QRIS Tenant</label>
        <div class="flex items-center gap-3">
          <label for="qris" class="cursor-pointer px-3 py-1.5 rounded-md bg-blue-50 text-brand text-[11px] font-semibold hover:bg-blue-100">Choose File</label>
          <span id="file-name" class="text-[11px] text-slate-500">No file chosen</span>
          <input id="qris" name="gambar_qris" type="file" accept="image/png,image/jpeg,image/webp" class="hidden"
            onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? 'No file chosen'">
        </div>
      </section>

      <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
        <h2 class="text-xs font-bold mb-4">Akun Tenant</h2>

        <label for="nama_penjual" class="block text-[11px] font-semibold mb-1.5">Nama Penjual</label>
        <input id="nama_penjual" name="nama_penjual" type="text" required value="<?= e($old['nama_penjual']) ?>"
          class="w-full h-10 rounded-lg border border-slate-200 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">

        <label for="email" class="block text-[11px] font-semibold mt-4 mb-1.5">Email</label>
        <input id="email" name="email" type="email" required value="<?= e($old['email']) ?>"
          class="w-full h-10 rounded-lg border border-slate-200 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">

        <label for="password" class="block text-[11px] font-semibold mt-4 mb-1.5">Kata Sandi</label>
        <input id="password" name="password" type="password" minlength="8" required
          class="w-full h-10 rounded-lg border border-slate-200 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
        <p class="text-[10px] text-brand mt-1.5">Minimal 8 karakter.</p>
      </section>

      <div class="flex items-center justify-end gap-5 pt-1">
        <a href="dashboard.php" class="text-xs font-medium text-slate-600 hover:text-ink">Batal</a>
<button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white text-xs font-semibold rounded-lg px-6 py-2.5 transition">Simpan</button>
      </div>
    </form>
  </main>
</body>

</html>