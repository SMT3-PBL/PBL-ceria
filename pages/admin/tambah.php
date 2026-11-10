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

  <header class="bg-white border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-lg bg-brand flex items-center justify-center">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3v8a2 2 0 0 0 2 2v8M5 3v5M9 3v5M17 3c-2 2-2 6 0 8v10" />
          </svg>
        </div>
        <div class="leading-tight">
          <p class="text-sm font-bold">Food Court Admin</p>
          <p class="text-[10px] text-slate-500">Portal Administrator</p>
        </div>
      </div>
      <nav class="flex items-center gap-8 text-xs font-medium">
        <a href="dashboard.php" class="text-slate-600 hover:text-brand py-4">Daftar Tenant</a>
        <a href="tambah.php" class="text-brand border-b-2 border-brand py-4">Tambah Tenant</a>
      </nav>
      <div class="flex items-center gap-2.5">
        <div class="text-right leading-tight">
          <p class="text-xs font-bold">Administrator</p>
          <p class="text-[10px] text-slate-500">Food Court</p>
        </div>
        <div class="w-9 h-9 rounded-full bg-brand flex items-center justify-center">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM4 21a8 8 0 0 1 16 0" />
          </svg>
        </div>
      </div>
    </div>
  </header>

  <main class="w-full max-w-[400px] mx-auto px-4 py-8">
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
        <button type="submit" class="bg-brand hover:bg-blue-800 text-white text-xs font-semibold rounded-lg px-6 py-2.5 transition">Simpan</button>
      </div>
    </form>
  </main>
</body>

</html>