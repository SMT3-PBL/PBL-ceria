<?php
// pages/admin/handlers/tambah_process.php
// Tugas file ini: MEMPROSES form tambah tenant. Tidak ada HTML di sini.

// ---------------------------------------------------------------
// 1. MEMUAT FILE YANG DIBUTUHKAN
// ---------------------------------------------------------------
// handlers -> admin -> pages -> root, jadi naik 3 tingkat untuk ke includes/
require_once __DIR__ . '/../../../includes/init.php';   // session, BASE_PATH, $pdo
require_once __DIR__ . '/../../../models/Tenant.php';


// ---------------------------------------------------------------
// 2. PROTEKSI: hanya admin yang boleh
// ---------------------------------------------------------------
$role = $_SESSION['user_role'] ?? null;
if ($role !== 'admin') {
    header('Location: ../../auth/login.php');
    exit;
}
// echo 'handler lolos cek admin';
// exit;

// ---------------------------------------------------------------
// 3. HANYA TERIMA METODE POST
// ---------------------------------------------------------------
// Kalau file ini dibuka langsung lewat URL (GET), kembalikan ke form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tambah.php');
    exit;
}

// ---------------------------------------------------------------
// 4. FUNGSI BANTU: kembali ke form membawa pesan error
// ---------------------------------------------------------------
function kembaliDenganError(array $errors, array $old): void
{
    $_SESSION['errors'] = $errors;   // dibaca oleh tambah.php
    $_SESSION['old']    = $old;      // supaya isian tidak hilang
    header('Location: ../tambah.php');
    exit;
}

// ---------------------------------------------------------------
// 5. AMBIL DATA DARI FORM
// ---------------------------------------------------------------
$old = [
    'nama_tenant'  => trim($_POST['nama_tenant'] ?? ''),
    'nama_penjual' => trim($_POST['nama_penjual'] ?? ''),
    'email'        => trim($_POST['email'] ?? ''),
];
$password = $_POST['password'] ?? '';   // password tidak di-trim dan tidak dikembalikan ke form

// ---------------------------------------------------------------
// 6. VALIDASI INPUT
// ---------------------------------------------------------------
$errors = [];

if ($old['nama_tenant'] === '')  $errors[] = 'Nama tenant wajib diisi.';
if ($old['nama_penjual'] === '') $errors[] = 'Nama penjual wajib diisi.';
if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
if (strlen($password) < 8)       $errors[] = 'Kata sandi minimal 8 karakter.';

$tenantModel = new Tenant($pdo);
if (!$errors && $tenantModel->emailTerpakai($old['email'])) {
    $errors[] = 'Email sudah terdaftar.';
}

if ($errors) {
    kembaliDenganError($errors, $old);
}

// ---------------------------------------------------------------
// 7. PROSES UPLOAD GAMBAR QRIS (opsional)
// ---------------------------------------------------------------
$namaFile = null;

if (!empty($_FILES['gambar_qris']['name'])) {
    $f = $_FILES['gambar_qris'];

    // Cek jenis file dari ISINYA (bukan dari nama/ekstensi yang bisa dipalsukan)
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;

    if ($f['error'] !== UPLOAD_ERR_OK) {
        kembaliDenganError(['Upload gambar QRIS gagal.'], $old);
    }
    if (!$ext) {
        kembaliDenganError(['Gambar QRIS harus JPG, PNG, atau WEBP.'], $old);
    }
    if ($f['size'] > 2 * 1024 * 1024) {
        kembaliDenganError(['Ukuran gambar QRIS maksimal 2 MB.'], $old);
    }

    $dir = __DIR__ . '/../../../uploads/qris/';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    // Nama acak supaya tidak bentrok dan tidak bisa ditebak
    $namaFile = 'qris_' . bin2hex(random_bytes(8)) . '.' . $ext;
    move_uploaded_file($f['tmp_name'], $dir . $namaFile);
}

// ---------------------------------------------------------------
// 8. SIMPAN KE DATABASE (lewat model)
// ---------------------------------------------------------------
try {
    $tenantModel->buat(
        $old['nama_tenant'],
        $namaFile,
        $old['nama_penjual'],
        $old['email'],
        $password
    );
} catch (Throwable $ex) {
    // Database gagal: hapus file yang sudah terlanjur diupload
    if ($namaFile) {
        @unlink(__DIR__ . '/../../../uploads/qris/' . $namaFile);
    }
    error_log('tambah_process: ' . $ex->getMessage());   // detail error hanya ke log
    kembaliDenganError(['DEBUG: ' . $ex->getMessage()], $old);
}

// ---------------------------------------------------------------
// 9. SUKSES: kembali ke dashboard dengan pesan
// ---------------------------------------------------------------
$_SESSION['flash'] = 'Tenant berhasil ditambahkan.';
header('Location: ../dashboard.php');
exit;