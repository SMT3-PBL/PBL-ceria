<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Menu.php';

require_role('seller');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_menu = $_POST['id_menu'] ?? '';

  if (empty($id_menu)) {
    $_SESSION['flash'] = ['tipe' => 'danger', 'pesan' => 'ID Menu tidak valid!'];
    redirect('pages/seller/dashboard/kelola_menu.php');
    exit;
  }

  $menuModel = new Menu($pdo);

  try {
    $sukses = $menuModel->deleteMenu($id_menu);

    $_SESSION['flash'] = [
      'tipe'  => $sukses ? 'ok' : 'danger',
      'pesan' => $sukses ? 'Menu berhasil dihapus!' : 'Gagal menghapus menu.'
    ];
  } catch (\Throwable $e) {
    $_SESSION['flash'] = [
      'tipe'  => 'danger',
      'pesan' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ];
  }

  redirect('pages/seller/dashboard/kelola_menu.php');
  exit;
} else {
  redirect('pages/seller/dashboard/kelola_menu.php');
  exit;
}
