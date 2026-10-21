<?php

require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Pengguna.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../register.php');
  exit;
}

$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($nama === '' || $email === '' || $password === '') {
  $_SESSION['pesan'] = 'Semua kolom wajib diisi.';
  header('Location: ../register.php');
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['pesan'] = 'Format email tidak valid.';
  header('Location: ../register.php');
  exit;
}

if (strlen($password) < 8) {
  $_SESSION['pesan'] = 'Password minimal 8 karakter.';
  header('Location: ../register.php');
  exit;
}

try {
  $pengguna = new Pengguna($pdo);

  if ($pengguna->emailExists($email)) {
    $_SESSION['pesan'] = 'Email sudah terdaftar.';
    header('Location: ../register.php');
    exit;
  }

  $pengguna->register($nama, $email, $password);

  $_SESSION['pesan'] = 'Registrasi berhasil!';
  $_SESSION['registrasi_berhasil'] = true;
} catch (PDOException $e) {
  error_log($e->getMessage());

  $_SESSION['pesan'] = $e->getCode() === '23505'
    ? 'Email sudah terdaftar.'
    : 'Registrasi gagal. Silakan coba lagi.';
}

header('Location: ../register.php');
exit;
