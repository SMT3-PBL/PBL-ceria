<?php
// pages/buyer/handlers/keranjang.php
require_once __DIR__ . '/../../../includes/init.php';

// Inisialisasi keranjang di session jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Proses jika ada aksi tambah/kurang dari form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_menu = $_POST['id_menu'] ?? null;
    $nama_menu = $_POST['nama_menu'] ?? '';
    $harga = (int)($_POST['harga'] ?? 0);
    $id_tenant = $_POST['id_tenant'] ?? 1;
    $kategori = $_POST['kategori'] ?? 'Semua Menu';

    // Set tenant aktif di session keranjang
    $_SESSION['cart_tenant'] = $id_tenant;

    if ($action === 'add' && $id_menu) {
        if (isset($_SESSION['cart'][$id_menu])) {
            $_SESSION['cart'][$id_menu]['jumlah'] += 1;
        } else {
            $_SESSION['cart'][$id_menu] = [
                'id_menu' => $id_menu,
                'nama_menu' => $nama_menu,
                'harga' => $harga,
                'jumlah' => 1
            ];
        }
    } elseif ($action === 'decrease' && $id_menu) {
        if (isset($_SESSION['cart'][$id_menu])) {
            $_SESSION['cart'][$id_menu]['jumlah'] -= 1;
            if ($_SESSION['cart'][$id_menu]['jumlah'] <= 0) {
                unset($_SESSION['cart'][$id_menu]);
            }
        }
    }
    
    // Redirect kembali ke halaman pilih_menu.php dengan tenant dan kategori aktif
    header("Location: ../pilih_menu.php?tenant=" . $id_tenant . "&kategori=" . urlencode($kategori));
    exit;
}

// Jika diakses langsung tanpa POST, kembalikan ke halaman pilih menu utama
header("Location: ../pilih_menu.php");
exit;