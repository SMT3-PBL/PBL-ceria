<?php

require_once __DIR__ . '/../../../includes/init.php';
require_once BASE_PATH . '/models/Pengguna.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../login.php');
  exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
  $_SESSION['pesan'] = 'Email dan password wajib diisi.';
  header('Location: ../login.php');
  exit;
}

try {
  $penggunaModel = new Pengguna($pdo);
  $pengguna = $penggunaModel->login($email);

  if (
    $pengguna === false ||
    !password_verify($password, $pengguna['password_hash'])
  ) {
    $_SESSION['pesan'] = 'Email atau password salah.';
    header('Location: ../login.php');
    exit;
  }

  // Membuat ID session baru setelah login berhasil
  session_regenerate_id(true);

  $_SESSION['user_id'] = $pengguna['id_pengguna'];
  $_SESSION['user_name'] = $pengguna['nama'];
  $_SESSION['user_email'] = $pengguna['email'];
  $_SESSION['user_role'] = $pengguna['role'];

  // header('Content-Type: application/json');

  // echo json_encode([
  //   'success' => true,
  //   'message' => 'Login berhasil',
  //   'user' => [
  //     'id' => $_SESSION['user_id'],
  //     'email' => $_SESSION['user_email'],
  //     'nama' => $_SESSION['user_name'],
  //     'role' => $_SESSION['user_role']
  //   ]
  // ]);

  // exit;

  if ($pengguna['role'] === 'seller') {
    header('Location: ../../seller/dashboard.php');
    exit;
  }

  if ($pengguna['role'] === 'admin') {
    header('Location: ../../admin/dashboard.php');
    exit;
  }
} catch (PDOException $e) {
  error_log($e->getMessage());

  $_SESSION['pesan'] = 'Terjadi kesalahan. Silakan coba lagi.';
  header('Location: ../login.php');
  exit;
}
