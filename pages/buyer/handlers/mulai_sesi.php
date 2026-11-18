<?php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../models/SesiPembeli.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect('pages/buyer/dashboard.php');
}

$noMeja = trim($_POST['no_meja'] ?? '');
$nama   = trim($_POST['nama_pembeli'] ?? '');

if ($noMeja === '' || $nama === '') {
  redirect('pages/buyer/dashboard.php');
}
// Batas panjang: sesi_pembeli.nama adalah varchar(100)
$nama   = mb_substr($nama, 0, 100);
$noMeja = mb_substr($noMeja, 0, 10);   // ubah kalau panjang kolom no_meja di tabelmu berbeda

// Dibaca oleh sesi_pembeli() di includes/init.php
$_SESSION['sesi'] = SesiPembeli::buat($pdo, $noMeja, $nama);

// Sesi baru = keranjang baru
$_SESSION['cart'] = [];

redirect('pages/buyer/pilih_menu.php');