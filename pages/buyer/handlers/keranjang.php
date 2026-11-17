<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../models/Menu.php';

$menuModel = new Menu($pdo);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id_menu   = (int)($_POST['id_menu'] ?? 0);
$id_tenant = (int)($_POST['id_tenant'] ?? 0);
$kategori  = $_POST['kategori'] ?? 'Semua Menu';
$action    = $_POST['action'] ?? '';

if ($action === 'add') {
    $menu = $menuModel->getMenuById((string)$id_menu);

    if ($menu) {
        // Kalau keranjang berisi tenant lain (atau item lama tanpa id_tenant), kosongkan dulu
        foreach ($_SESSION['cart'] as $item) {
            if ((int)($item['id_tenant'] ?? 0) !== (int)$menu['id_tenant']) {
                $_SESSION['cart'] = [];
                break;
            }
        }

        if (isset($_SESSION['cart'][$id_menu])) {
            $_SESSION['cart'][$id_menu]['jumlah']++;
        } else {
            $_SESSION['cart'][$id_menu] = [
                'nama_menu' => $menu['nama_menu'],
                'harga'     => $menu['harga'],
                'id_tenant' => (int)$menu['id_tenant'],
                'jumlah'    => 1,
            ];
        }
    }
} elseif ($action === 'decrease' && isset($_SESSION['cart'][$id_menu])) {
    $_SESSION['cart'][$id_menu]['jumlah']--;
    if ($_SESSION['cart'][$id_menu]['jumlah'] <= 0) {
        unset($_SESSION['cart'][$id_menu]);
    }
}

header('Location: ../pilih_menu.php?tenant=' . $id_tenant . '&kategori=' . urlencode($kategori));
exit;