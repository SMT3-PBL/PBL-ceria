<?php
require_once __DIR__ . '/init.php';

$role      = $role ?? 'buyer';
$active    = $active ?? '';
$pageTitle  = $pageTitle ?? 'Food Court';
$buyerTheme = in_array($role, ['buyer', 'start'], true);

$ikon = [
  'garpu'  => '<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
  'akun'   => '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
  'keluar' => '<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>',
];

// Data tenant dari database (lewat tenant_aktif() di init.php), hanya untuk penjual.
$tenant = ($role === 'seller') ? tenant_aktif() : null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#004ac6">
  <title><?= e($pageTitle) ?> | Food Court</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style type="text/tailwindcss">
    @theme {
      --font-sans: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      --color-primary: #004ac6;
      --color-primary-dark: #003ea8;
      --color-soft: #eff4ff;
      --color-surface: #f8f9ff;
      --color-ink: #0b1c30;
      --color-muted: #434655;
      --color-line: #d3e4fe;
      --color-outline: #c3c6d7;
      --color-ok: #002113;
      --color-ok-soft: #6ffbbe;
      --color-warn: #9d4300;
      --color-warn-soft: #ffdbca;
      --color-danger: #ba1a1a;
      --color-danger-soft: #ffdad6;
    }
  </style>
  <style>
    [hidden] {
      display: none !important
    }
  </style>
</head>

<body class="min-h-screen bg-surface font-sans text-ink antialiased">

  <!-- Identitas-->
  <?php if ($role === 'start'): ?>
    <header class="border-b border-line/70 bg-white print:hidden">
      <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
        <span class="flex items-center gap-3 text-lg font-bold tracking-tight text-ink">
          <span class="grid size-10 place-items-center rounded-xl bg-primary text-white"><?= $ikon['garpu'] ?></span>
          <span>Food Court</span>
        </span>

        <!-- Tombol Login Admin / Penjual di Pojok Kanan Atas -->
        <div>
          <a href="<?= url('pages/auth/login.php') ?>" 
             class="inline-flex items-center justify-center rounded-lg border border-line bg-surface px-3.5 py-2 text-xs font-semibold text-ink transition hover:bg-line sm:text-sm">
            Login
          </a>
        </div>
      </div>
    </header>

  <!-- Header Buyer / Pembeli -->
  <?php elseif ($role === 'buyer'):
    $sesi  = sesi_pembeli();
    $nama  = (string) ($sesi['nama_pembeli'] ?? '');
    $menuPembeli = [
      'menu'   => ['pages/buyer/pilih_menu.php',   'Pilih Menu &amp; Tenant'],
      'status' => ['pages/buyer/status.php', 'Status Pesanan'],
    ];
  ?>
    <header class="sticky top-0 z-40 border-b border-line/70 bg-white print:hidden">
      <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 px-4 pt-3 lg:flex-nowrap lg:py-3">
        <a href="<?= url('pages/buyer/pilih_menu.php') ?>" class="flex shrink-0 items-center gap-3 whitespace-nowrap">
          <span class="grid size-10 place-items-center rounded-xl bg-primary text-white shadow-sm"><?= $ikon['garpu'] ?></span>
          <span class="leading-tight">
            <span class="block text-base font-extrabold text-ink">Food Court</span>
            <span class="block text-[11px] text-muted">Portal Pembeli</span>
          </span>
        </a>

        <!-- Navigasi Menu Pelanggan -->
        <nav aria-label="Navigasi utama"
          class="order-3 -mx-4 mt-2 flex w-[calc(100%+2rem)] border-t border-line/60 px-4 text-sm font-semibold lg:order-none lg:mx-0 lg:ml-auto lg:mt-0 lg:w-auto lg:border-0 lg:px-0">
          <?php foreach ($menuPembeli as $kunci => [$tujuan, $label]): ?>
            <a href="<?= url($tujuan) ?>" <?= $active === $kunci ? ' aria-current="page"' : '' ?>
              class="flex-1 border-b-2 border-transparent whitespace-nowrap px-1 py-3 text-center text-muted hover:text-primary aria-[current=page]:border-primary aria-[current=page]:text-primary lg:flex-none lg:px-1 lg:py-2 lg:mx-3"><?= $label ?></a>
          <?php endforeach; ?>
        </nav>

        <!-- Informasi Pelanggan & Tombol Keluar -->
        <div class="ml-auto flex items-center gap-3 lg:ml-auto">
          <span class="whitespace-nowrap rounded-full border border-line bg-soft px-3 py-1 text-xs font-bold text-ink">
            Meja No. <span data-pemesan-meja><?= e($sesi['no_meja'] ?? '-') ?></span>
          </span>
          
          <p class="hidden items-center gap-3 whitespace-nowrap border-l border-line pl-3 text-right text-sm leading-tight lg:flex">
            <span>
              <span class="block font-bold text-ink" data-pemesan-nama><?= e($nama) ?></span>
            </span>
            <span class="grid size-9 place-items-center rounded-full bg-primary text-white"><?= $ikon['akun'] ?></span>
          </p>

          <a href="<?= url('pages/auth/handlers/logout.php') ?>" aria-label="Keluar" title="Keluar"
            class="grid size-10 place-items-center rounded-lg text-muted hover:bg-danger-soft hover:text-danger"><?= $ikon['keluar'] ?></a>
        </div>
      </div>
    </header>

  <!-- Header Seller || Admin -->
  <?php elseif ($role === 'seller' || $role === 'admin'):
    $penjual = $role === 'seller';
    $menuStaf = $penjual
      ? [
        'orders'  => ['pages/seller/dashboard/pesanan_masuk.php',  'Pesanan Masuk'],
        'menus'   => ['pages/seller/kelola_menu/kelola_menu.php',   'Kelola Menu'],
        'reports' => ['pages/seller/laporan_penjualan.php', 'Laporan<span class="hidden sm:inline"> Penjualan</span>'],
      ]
      : [
        'tenants'    => ['pages/admin/dashboard.php',    'Daftar Tenant'],
        'tenant_add' => ['pages/admin/tambah.php', 'Tambah Tenant'],
      ];
    $beranda = $penjual ? 'pages/seller/pesanan_masuk.php' : 'pages/admin/tenant.php';
    $tenantBuka = $penjual && strtolower((string) ($tenant['status_tenant'] ?? '')) === 'buka';
  ?>

    <header class="sticky top-0 z-40 border-b border-line/70 bg-white print:hidden">
      <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 px-4 pt-3 lg:flex-nowrap lg:py-3">
        <a href="<?= url($beranda) ?>" class="flex shrink-0 items-center gap-3 whitespace-nowrap">
          <span class="grid size-10 place-items-center rounded-xl bg-primary text-white"><?= $ikon['garpu'] ?></span>
          <span class="leading-tight">
            <span class="block text-base font-extrabold text-ink">Food Court</span>
            <span class="block text-[11px] text-muted"><?= $penjual ? 'Portal Penjual' : 'Portal Admin' ?></span>
          </span>
        </a>
        
        <!-- Navigasi Staf -->
        <nav aria-label="Navigasi utama"
          class="order-3 -mx-4 mt-2 flex w-[calc(100%+2rem)] border-t border-line/60 px-4 text-sm font-semibold lg:order-none lg:mx-0 lg:ml-auto lg:mt-0 lg:w-auto lg:border-0 lg:px-0">
          <?php foreach ($menuStaf as $kunci => [$tujuan, $label]): ?>
            <a href="<?= url($tujuan) ?>" <?= $active === $kunci ? ' aria-current="page"' : '' ?>
              class="flex-1 border-b-2 border-transparent whitespace-nowrap px-1 py-3 text-center text-muted hover:text-primary aria-[current=page]:border-primary aria-[current=page]:text-primary lg:flex-none lg:px-1 lg:py-2 lg:mx-3"><?= $label ?></a>
          <?php endforeach; ?>
        </nav>

        <div class="ml-auto flex items-center gap-3 lg:ml-auto">
          <?php if ($penjual): ?>
            <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-[11px] font-extrabold uppercase tracking-wide <?= $tenantBuka ? 'bg-ok-soft text-ok' : 'bg-danger-soft text-danger' ?>">
              <span class="size-1.5 rounded-full <?= $tenantBuka ? 'bg-ok' : 'bg-danger' ?>"></span>
              <span><span class="hidden sm:inline">Tenant </span><?= $tenantBuka ? 'Buka' : 'Tutup' ?></span>
            </span>
            <p class="hidden items-center gap-3 whitespace-nowrap border-l border-line pl-3 text-right text-sm leading-tight lg:flex">
              <span>
                <span class="block font-bold text-ink"><?= e($tenant['nama_tenant'] ?? '-') ?></span>
                <span class="block text-xs text-muted">Tenant #<?= (int) ($tenant['id_tenant'] ?? 0) ?></span>
              </span>
              <span class="grid size-9 place-items-center rounded-full bg-primary text-white"><?= $ikon['akun'] ?></span>
            </p>
          <?php else: ?>
            <p class="flex items-center gap-3 text-right text-sm leading-tight">
              <span class="hidden sm:block">
                <span class="block font-bold text-ink"><?= e(user()['nama'] ?? 'Admin') ?></span>
                <span class="block text-xs text-muted">Food Court</span>
              </span>
              <span class="grid size-9 place-items-center rounded-full bg-primary text-white"><?= $ikon['akun'] ?></span>
            </p>
          <?php endif; ?>

          <a href="<?= url('pages/auth/handlers/logout.php') ?>" aria-label="Keluar" title="Keluar"
            class="grid size-10 place-items-center rounded-lg text-muted hover:bg-danger-soft hover:text-danger"><?= $ikon['keluar'] ?></a>
        </div>
      </div>
    </header>

  <?php endif; ?>