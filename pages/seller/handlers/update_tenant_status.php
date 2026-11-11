<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Tenant.php';

require_role('seller');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_pengguna = $_SESSION['user_id'] ?? null;

  if (!$id_pengguna) {
    $_SESSION['flash'] = ['tipe' => 'danger', 'pesan' => 'Sesi login tidak valid!'];
    redirect('pages/seller/dashboard/kelola_menu.php');
    exit;
  }

  $tenantModel = new Tenant($pdo);

  try {
    $tenantData = $tenantModel->getTenantByUserId($id_pengguna);
    $id_tenant = $tenantData['id_tenant'] ?? null;

    if (!$id_tenant) {
      $_SESSION['flash'] = ['tipe' => 'danger', 'pesan' => 'Data tenant tidak ditemukan!'];
      redirect('pages/seller/dashboard/kelola_menu.php');
      exit;
    }

    // MENYESUAIKAN DENGAN FORM: 
    // Karena form menggunakan name="status_buka", kita cek apakah variabel ini dikirim (dicentang)
    $status_tenant = isset($_POST['status_buka']) ? 'buka' : 'tutup';

    // Update status ke database
    $sukses = $tenantModel->updateOperationalStatus($id_tenant, $status_tenant);

    $_SESSION['flash'] = [
      'tipe'  => $sukses ? 'ok' : 'danger',
      'pesan' => $sukses ? "Status operasional tenant berhasil diubah menjadi " . strtoupper($status_tenant) . "!" : 'Gagal memperbarui status tenant.'
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
