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
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>
    <?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
</head>

<body>
  <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 px-4 pt-3 md:flex-nowrap md:py-3">
      <a href="menu.html" class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-slate-900">
        <span class="grid size-9 place-items-center rounded-xl bg-blue-600 text-lg font-extrabold text-white">F</span><span>Food Court</span>
      </a>
      <nav aria-label="Navigasi utama" class="order-3 -mx-4 mt-2 flex w-[calc(100%+2rem)] border-t border-slate-100 px-4 text-sm font-medium md:order-none md:mx-0 md:ml-6 md:mt-0 md:w-auto md:gap-1 md:border-0 md:px-0">
        <a href="menu.html" aria-current="page" class="flex-1 border-b-2 border-transparent px-3 py-3 text-center text-slate-500 hover:text-slate-900 aria-[current=page]:border-blue-600 aria-[current=page]:text-blue-600 md:flex-none md:rounded-lg md:border-0 md:py-2 md:aria-[current=page]:bg-blue-50">Pilih Menu &amp; Tenant</a>
        <a href="status.html" class="flex-1 border-b-2 border-transparent px-3 py-3 text-center text-slate-500 hover:text-slate-900 aria-[current=page]:border-blue-600 aria-[current=page]:text-blue-600 md:flex-none md:rounded-lg md:border-0 md:py-2 md:aria-[current=page]:bg-blue-50">Status Pesanan</a>
      </nav>
      <div class="ml-auto flex items-center gap-2">
        <p class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm font-semibold text-slate-700">Meja No. <span data-pemesan-meja>12</span></p>
        <p class="flex items-center gap-2 text-sm font-medium text-slate-700">
          <span class="grid size-8 place-items-center rounded-full bg-slate-100 text-xs font-bold text-slate-600" aria-hidden="true"><span data-pemesan-inisial>N</span></span>
          <span class="hidden sm:inline"><span data-pemesan-nama>Nara</span></span>
        </p>
      </div>
    </div>
  </header>
</body>