<?php
session_start();

// Panggil konfigurasi
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/Tenant.php';

// Proses hanya jika form dikirim (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tenant = $_POST['id_tenant'] ?? null;

    if ($id_tenant) {
        $TenantModel = new Tenant($pdo);

        try {
            $fileQris = $TenantModel->hapus($id_tenant);

            if ($fileQris !== false) {
                if (!empty($fileQris)) {
                    $pathGambar = __DIR__ . '/../../../uploads/qris/' . $fileQris;
                    if (file_exists($pathGambar)) {
                        @unlink($pathGambar);
                    }
                }
                $_SESSION['success'] = "Tenant dan akun penjual berhasil dihapus permanen.";
            } else {
                $_SESSION['error'] = "Penghapusan dibatalkan: ID Tenant tidak ditemukan di database.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Gagal menghapus data: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "ID Tenant tidak valid.";
    }
}

// ==== SISTEM REDIRECT GANDA (ANTI LAYAR PUTIH) ====
// Jika jalur PHP lancar, gunakan PHP
if (!headers_sent()) {
    header("Location: ../dashboard.php");
    exit;
} 
// Jika PHP macet (layar putih), paksa pindah pakai JavaScript
else {
    echo "<script>window.location.href = '../dashboard.php';</script>";
    exit;
}