<?php
require_once __DIR__ . '/init.php';

$role       = $role ?? 'buyer';
$active     = $active ?? '';
$pageTitle  = $pageTitle ?? 'Food Court';
$buyerTheme = in_array($role, ['buyer', 'start'], true);   

$ikon = [
    'garpu'  => '<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
    'akun'   => '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'keluar' => '<svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="<?= $buyerTheme ? '#2563eb' : '#004ac6' ?>">
  <title><?= e($pageTitle) ?> | Food Court</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=<?= $buyerTheme ? 'Inter' : 'Plus+Jakarta+Sans' ?>:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style type="text/tailwindcss">
    @theme {
<?php if ($buyerTheme): ?>
      --font-sans: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
<?php else: ?>
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
<?php endif; ?>
    }
  </style>
  <style>[hidden]{display:none!important}</style>
</head>
<body class="min-h-screen <?= $buyerTheme ? 'bg-slate-50 font-sans text-slate-800' : 'bg-surface font-sans text-ink' ?> antialiased">

<!-- Header Isi Identitas Pembeli -->
<?php if ($role === 'start'): ?>
  <header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center px-4 py-3">
      <span class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-slate-900"><span class="grid size-9 place-items-center rounded-xl bg-blue-600 text-lg font-extrabold text-white">F</span><span>Food Court</span></span>
    </div>
  </header>

  <!-- Header Pembeli -->
<?php elseif ($role === 'buyer'):
    $sesi  = sesi_pembeli();
    $nama  = (string) ($sesi['nama_pembeli'] ?? '');
    $menuPembeli = [
        'menu'   => ['pages/buyer/pilih_menu.php',   'Pilih Menu &amp; Tenant'],
        'status' => ['pages/buyer/status.php', 'Status Pesanan'],
    ];
?>
  <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 px-4 pt-3 md:flex-nowrap md:py-3">
      <a href="<?= url('pages/buyer/menu.php') ?>" class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-slate-900">
        <span class="grid size-9 place-items-center rounded-xl bg-blue-600 text-lg font-extrabold text-white">F</span><span>Food Court</span>
      </a>

<!-- Navigasi Menu Pelanggan -->
      <nav aria-label="Navigasi utama" class="order-3 -mx-4 mt-2 flex w-[calc(100%+2rem)] border-t border-slate-100 px-4 text-sm font-medium md:order-none md:mx-0 md:ml-6 md:mt-0 md:w-auto md:gap-1 md:border-0 md:px-0">
<?php foreach ($menuPembeli as $kunci => [$tujuan, $label]): ?>
        <a href="<?= url($tujuan) ?>"<?= $active === $kunci ? ' aria-current="page"' : '' ?> class="flex-1 border-b-2 border-transparent px-3 py-3 text-center text-slate-500 hover:text-slate-900 aria-[current=page]:border-blue-600 aria-[current=page]:text-blue-600 md:flex-none md:rounded-lg md:border-0 md:py-2 md:aria-[current=page]:bg-blue-50"><?= $label ?></a>
<?php endforeach; ?>
      </nav>
<!-- Informasi Pelanggan-->
      <div class="ml-auto flex items-center gap-2">
        <p class="whitespace-nowrap rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm font-semibold text-slate-700">Meja No. <span data-pemesan-meja><?= e($sesi['no_meja'] ?? '-') ?></span></p>
        <p class="flex items-center gap-2 text-sm font-medium text-slate-700">
          <span class="grid size-8 place-items-center rounded-full bg-slate-100 text-xs font-bold text-slate-600" aria-hidden="true"><span data-pemesan-inisial><?= e(strtoupper(substr($nama, 0, 1))) ?></span></span>
          <span class="hidden sm:inline"><span data-pemesan-nama><?= e($nama) ?></span></span>
        </p>
      </div>
    </div>
  </header>

<!-- Header Penjual || Admin -->
<?php elseif ($role === 'seller' || $role === 'admin'):
    $penjual = $role === 'seller';
    $menuStaf = $penjual
        ? [
            'orders'  => ['pages/seller/pesanan_masuk.php',  'Pesanan Masuk'],
            'menus'   => ['pages/seller/kelola_menu.php',   'Kelola Menu'],
            'reports' => ['pages/seller/laporan.php', 'Laporan<span class="hidden sm:inline"> Penjualan</span>'],
        ]
        : [
            'tenants'    => ['pages/admin/tenant.php',    'Daftar Tenant'],
            'tenant_add' => ['pages/admin/tambahkan_tenant.php', 'Tambah Tenant'],
        ];
    $beranda = $penjual ? 'pages/seller/pesanan_masuk.php' : 'pages/admin/tenants.php';
    $tenant  = $penjual ? tenant_aktif() : null;
?>

<!-- Ditambahkan class `print:hidden` agar header tidak ikut tercetak saat cetak nota/laporan -->
  <header class="sticky top-0 z-40 border-b border-line/70 bg-white print:hidden">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 px-4 pt-3 lg:flex-nowrap lg:py-3">
      <a href="<?= url($beranda) ?>" class="flex shrink-0 items-center gap-3 whitespace-nowrap">
        <span class="grid size-10 place-items-center rounded-xl bg-primary text-white"><?= $ikon['garpu'] ?></span>
        <span class="leading-tight"><span class="block text-base font-extrabold text-ink">Food Court</span><span class="block text-[11px] text-muted"><?= $penjual ? 'Portal Penjual' : 'Portal Admin' ?></span></span>
      </a>

<!-- Navigasi Penjual --> 
      <nav aria-label="Navigasi utama" class="order-3 -mx-4 mt-2 flex w-[calc(100%+2rem)] border-t border-line/60 px-4 text-sm font-semibold lg:order-none lg:mx-0 lg:ml-auto lg:mt-0 lg:w-auto lg:border-0 lg:px-0">
<?php foreach ($menuStaf as $kunci => [$tujuan, $label]): ?>
        <a href="<?= url($tujuan) ?>"<?= $active === $kunci ? ' aria-current="page"' : '' ?> class="flex-1 border-b-2 border-transparent whitespace-nowrap px-1 py-3 text-center text-muted hover:text-primary aria-[current=page]:border-primary aria-[current=page]:text-primary lg:flex-none lg:px-1 lg:py-2 lg:mx-3"><?= $label ?></a>
<?php endforeach; ?>
      </nav>
      <div class="ml-auto flex items-center gap-3 lg:ml-auto">
<?php if ($penjual): ?>
        <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full bg-ok-soft px-3 py-1 text-[11px] font-extrabold uppercase tracking-wide text-ok"><span class="size-1.5 rounded-full bg-ok"></span><span><span class="hidden sm:inline">Tenant </span><?= $tenant['status_buka'] ? 'Buka' : 'Tutup' ?></span></span>
        <p class="hidden items-center gap-3 whitespace-nowrap border-l border-line pl-3 text-right text-sm leading-tight lg:flex">
          <span><span class="block font-bold text-ink"><?= e($tenant['nama_tenant']) ?></span><span class="block text-xs text-muted">Tenant #<?= (int) $tenant['id_tenant'] ?></span></span>
          <span class="grid size-9 place-items-center rounded-full bg-primary text-white"><?= $ikon['akun'] ?></span>
        </p>
<?php else: ?>
        <p class="flex items-center gap-3 text-right text-sm leading-tight">
          <span class="hidden sm:block"><span class="block font-bold text-ink"><?= e(user()['nama'] ?? 'Admin') ?></span><span class="block text-xs text-muted">Food Court</span></span>
          <span class="grid size-9 place-items-center rounded-full bg-primary text-white"><?= $ikon['akun'] ?></span>
        </p>
<?php endif; ?>
        <a href="<?= url('/PBL-ceria/pages/auth/handlers/logout.php') ?>" aria-label="Keluar" title="Keluar" class="grid size-10 place-items-center rounded-lg text-muted hover:bg-danger-soft hover:text-danger"><?= $ikon['keluar'] ?></a>
      </div>
    </div>
  </header>

<?php endif; ?>
