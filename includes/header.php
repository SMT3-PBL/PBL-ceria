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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
</head>

<body>
  <section>
    <h1>include dari header</h1>
  </section>