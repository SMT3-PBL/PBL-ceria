<?php
session_start();
// Mundur 3 folder dari pages/admin/handlers/ ke root
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/Tenant.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php");
    exit;
}

// Tangkap Data
$id_tenant = trim($_POST['id_tenant']);
$id_pengguna = trim($_POST['id_pengguna']);
$nama_tenant = trim($_POST['nama_tenant']);
$nama_penjual = trim($_POST['nama_penjual']);
$email = trim($_POST['email']);
$password = trim($_POST['password']); // Bisa kosong
$qris_lama = trim($_POST['qris_lama']);

$tenantModel = new Tenant($pdo);

// 1. Validasi Email
if ($tenantModel->emailTerpakaiKecuali($email, $id_pengguna)) {
    $_SESSION['error'] = "Email sudah digunakan oleh akun lain.";
    header("Location: ../edit.php?id=" . $id_tenant);
    exit;
}

$namaFileQris = null;
$uploadDir = __DIR__ . '/../../../uploads/qris';

// 2. Cek Upload Gambar
if (isset($_FILES['gambar_qris']) && $_FILES['gambar_qris']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['gambar_qris']['tmp_name'];
    $fileName = $_FILES['gambar_qris']['name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
        
        $namaFileQris = 'qris_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        
        if (move_uploaded_file($fileTmpPath, $uploadDir . $namaFileQris)) {
            // Hapus gambar lama jika berhasil upload gambar baru
            if (!empty($qris_lama) && file_exists($uploadDir . $qris_lama)) {
                @unlink($uploadDir . $qris_lama);
            }
        } else {
            $namaFileQris = null; // Batalkan ubah gambar jika gagal pindah
        }
    } else {
        $_SESSION['error'] = "Format gambar harus JPG, PNG, atau WEBP.";
        header("Location: ../edit.php?id=" . $id_tenant);
        exit;
    }
}

// 3. Eksekusi ke Database
try {
    $tenantModel->update($id_tenant, $id_pengguna, $nama_tenant, $nama_penjual, $email, $password, $namaFileQris);
    $_SESSION['success'] = "Data tenant berhasil diperbarui.";
    header("Location: ../dashboard.php");
    exit;
} catch (Exception $e) {
    // Hapus gambar baru jika database gagal update
    if ($namaFileQris && file_exists($uploadDir . $namaFileQris)) {
        @unlink($uploadDir . $namaFileQris);
    }
    $_SESSION['error'] = "Gagal memperbarui data: " . $e->getMessage();
    header("Location: ../edit.php?id=" . $id_tenant);
    exit;
}