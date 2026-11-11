<?php
// 1. Pastikan jalur require_once ke init.php dan model Menu sudah benar
require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Menu.php';

require_role('seller');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 2. Tangkap semua data dari form
  $id_menu     = $_POST['id_menu'] ?? '';
  $id_tenant   = $_POST['id_tenant'] ?? '';
  $nama_menu   = trim($_POST['nama_menu'] ?? '');
  $deskripsi   = trim($_POST['deskripsi'] ?? '');
  $harga       = filter_var($_POST['harga'], FILTER_VALIDATE_FLOAT);
  $kategori    = trim($_POST['kategori'] ?? '');
  $status_menu = strtolower(trim($_POST['status_menu'] ?? 'tersedia'));
  // Hasilnya akan menjadi 'tersedia' atau 'habis' (huruf kecil semua)

  // 3. Validasi sederhana
  if (empty($nama_menu) || $harga === false || empty($kategori) || empty($id_tenant)) {
    $_SESSION['flash'] = ['tipe' => 'danger', 'pesan' => 'Mohon lengkapi semua data dengan benar!'];
    redirect('pages/seller/dashboard/kelola_menu.php');
    exit;
  }

  // 4. Inisialisasi object Menu
  $menuModel = new Menu($pdo);

  try {
    // Susun array data yang akan dikirim ke fungsi model
    $dataMenu = [
      'nama_menu'   => $nama_menu,
      'deskripsi'   => $deskripsi,
      'harga'       => $harga,
      'kategori'    => $kategori,
      'status_menu' => $status_menu,
      'id_tenant'   => $id_tenant
    ];

    if (!empty($id_menu)) {
      // --- MODE EDIT / UPDATE ---
      // Fungsi updateMenu milikmu otomatis mendeteksi array data dan membuat query UPDATE dinamis
      $sukses = $menuModel->updateMenu($id_menu, $dataMenu);
      $pesan  = $sukses ? 'Menu berhasil diperbarui!' : 'Gagal memperbarui menu.';
    } else {
      // --- MODE TAMBAH BARU / INSERT ---
      $sukses = $menuModel->addMenu($dataMenu);
      $pesan  = $sukses ? 'Menu baru berhasil ditambahkan!' : 'Gagal menambahkan menu baru.';
    }

    // Set pesan flash berdasarkan hasil eksekusi model
    $_SESSION['flash'] = [
      'tipe'  => $sukses ? 'ok' : 'danger',
      'pesan' => $pesan
    ];
  } catch (\Throwable $e) {
    $_SESSION['flash'] = [
      'tipe'  => 'danger',
      'pesan' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ];
  }

  // Kembalikan seller ke halaman kelola menu
  redirect('pages/seller/dashboard/kelola_menu.php');
  exit;
} else {
  redirect('pages/seller/dashboard/kelola_menu.php');
  exit;
}
