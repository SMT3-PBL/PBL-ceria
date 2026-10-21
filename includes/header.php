<?php
require_once __DIR__ . '/init.php';

// Menentukan root proyek
$projectRoot = dirname(__DIR__);

// Menentukan folder script yang sedang diakses
$scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);

// Menghitung posisi folder halaman terhadap root proyek
$relativeDir = ltrim(
  str_replace(
    '\\',
    '/',
    substr($scriptDir, strlen($projectRoot))
  ),
  '/'
);

// Menentukan prefix relatif untuk menuju root proyek
$base = $relativeDir === '' ? '' : str_repeat('../', substr_count($relativeDir, '/') + 1);

// Judul halaman default
$page_title = $page_title ?? 'PBL-Ceria';
?>

<!-- Header Sementara -->

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> <?php echo isset($page_title) ? ' | ' . $page_title : ''; ?> </title>
  <!-- <link rel="stylesheet" href="assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-white text-gray-800">

  <!-- Navbar -->
  <header class="border-b border-gray-100">
    <div class="mx-auto max-w-6xl flex items-center justify-between px-4 py-4">
      <a href="index.php" class="text-lg font-bold text-blue-600">PBL-Ceria</a>
      <nav class="flex items-center gap-3 text-sm">
        <a href="pages/auth/login.php" class="px-3 py-2 text-gray-600 hover:text-gray-900">Login</a>
        <a href="pages/auth/register.php"
          class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 transition-colors">Daftar</a>
      </nav>
    </div>
  </header>