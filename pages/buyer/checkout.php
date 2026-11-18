<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Tenant.php';

$pageTitle = 'Pembayaran Pesanan';
$role      = 'buyer';
$active    = 'menu';

$idTenant = (int) ($_GET['id_tenant'] ?? 0);

$tenantModel    = new Tenant($pdo);
$tenantTerpilih = $tenantModel->cari($idTenant);

// Ambil hanya item keranjang milik tenant ini
$itemCheckout = array_filter(
  $_SESSION['cart'] ?? [],
  fn($i) => (int)($i['id_tenant'] ?? 0) === $idTenant
);

// Tenant tidak ada atau keranjang kosong -> balik ke pilih menu
if (!$tenantTerpilih || empty($itemCheckout)) {
  header('Location: ' . url('pages/buyer/pilih_menu.php'));
  exit;
}

$totalTagihan = 0;
foreach ($itemCheckout as $item) {
  $totalTagihan += $item['harga'] * $item['jumlah'];
}

$tenantNama = $tenantTerpilih['nama_tenant'];
$qrPath     = !empty($tenantTerpilih['qr_pembayaran'])
  ? 'uploads/qris/' . $tenantTerpilih['qr_pembayaran']
  : null;

require __DIR__ . '/../../includes/header.php';
$sesi = sesi_pembeli();
?>

<main class="mx-auto max-w-4xl p-4 pb-20 sm:p-6">
  <!-- Stepper Navigasi Alur Pemesanan -->
  <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 sm:text-sm">
      <div class="flex items-center gap-2 text-blue-600">
        <span class="grid size-6 place-items-center rounded-full bg-blue-600 text-xs text-white">1</span>
        <span>Pilih Menu</span>
      </div>
      <div class="h-0.5 flex-1 bg-blue-600 mx-2 sm:mx-3"></div>
      <div class="flex items-center gap-2 text-blue-600 font-bold">
        <span class="grid size-6 place-items-center rounded-full bg-blue-600 text-xs text-white">2</span>
        <span>Pembayaran</span>
      </div>
      <div class="h-0.5 flex-1 bg-slate-200 mx-2 sm:mx-3"></div>
      <div class="flex items-center gap-2 text-slate-400">
        <span class="grid size-6 place-items-center rounded-full bg-slate-100 text-xs text-slate-500">3</span>
        <span>Status Pesanan</span>
      </div>
    </div>
  </div>

  <form action="<?= url('pages/buyer/handlers/checkout_process.php') ?>" method="post" enctype="multipart/form-data"
    class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_360px]">
    <input type="hidden" name="id_tenant" value="<?= $idTenant ?>">

    <!-- Form Pilihan Metode Pembayaran -->
    <div class="space-y-6">
      <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="text-xl font-bold tracking-tight text-slate-900">Pembayaran Stand: <?= e($tenantNama) ?></h1>
        <p class="mt-1 text-sm text-slate-500">Pilih metode pembayaran khusus untuk tenant ini.</p>

        <!-- Pilihan Radio Metode Bayar -->
        <div class="mt-5 grid grid-cols-2 gap-3">
          <label id="lblQris"
            class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-blue-600 bg-blue-50/60 p-3 text-sm font-semibold text-blue-700 transition">
            <input type="radio" name="metode_bayar" value="qris" checked onclick="switchMetode('qris')" class="sr-only">
            <span>QRIS Instant</span>
          </label>

          <label id="lblTunai"
            class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-slate-200 p-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            <input type="radio" name="metode_bayar" value="tunai" onclick="switchMetode('tunai')" class="sr-only">
            <span>Tunai di Stand</span>
          </label>
        </div>

        <!-- Opsi 1: QRIS -->
        <div id="boxQris" class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5 text-center">
          <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Scan QRIS Stand <?= e($tenantNama) ?></p>
          <div class="my-4 flex justify-center">
            <?php if ($qrPath): ?>
              <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <img src="<?= url($qrPath) ?>" alt="QRIS <?= e($tenantNama) ?>" class="size-48 object-contain">
              </div>
            <?php else: ?>
              <p class="text-sm text-rose-600">Tenant ini belum mengunggah QRIS. Silakan pilih pembayaran tunai.</p>
            <?php endif; ?>
          </div>
          <p class="text-xs text-slate-500">Transfer tepat sebesar:
            <b class="text-slate-900 text-sm">Rp<?= number_format($totalTagihan, 0, ',', '.') ?></b>
          </p>
          <div class="mt-5 text-left">
            <label for="bukti_bayar" class="mb-1.5 block text-sm font-semibold text-slate-700">Unggah Bukti Bayar QRIS
              <span class="text-rose-500">*</span></label>
            <input type="file" id="bukti_bayar" name="bukti_bayar" accept="image/*" required
              class="w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700">
            <small class="mt-1 block text-xs text-slate-400">Format: JPG, PNG, WEBP (Maksimal 2MB)</small>
          </div>
        </div>

        <!-- Opsi 2: Tunai -->
        <div id="boxTunai" class="mt-6 hidden rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
          <div class="flex items-start gap-3">
            <div>
              <h3 class="font-bold">Pembayaran Tunai Langsung di Stand</h3>
              <p class="mt-1 text-xs leading-relaxed text-amber-800">
                Silakan selesaikan pemesanan ini. Setelah itu, datangi kasir <b><?= e($tenantNama) ?></b> untuk membayar
                secara tunai dengan menyebutkan <b>Nomor Meja Anda (Meja <?= e($sesi['no_meja']) ?>)</b>.
              </p>
            </div>
          </div>
        </div>

        <button type="submit"
          class="mt-6 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
          Selesaikan & Buat Pesanan <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
          </svg>
        </button>
      </section>
    </div>

    <!-- Ringkasan Rincian Tagihan -->
    <aside class="h-fit rounded-xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
      <div>
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Identitas Pemesan</h2>
        <p class="mt-1 font-semibold text-slate-900"><?= e($sesi['nama_pembeli']) ?></p>
        <p class="text-sm text-slate-500">Meja No. <?= e($sesi['no_meja']) ?></p>
      </div>

      <hr class="border-slate-100">

      <div>
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Menu Yang Dipesan</h2>
        <ul class="mt-3 divide-y divide-slate-100 text-sm">
          <?php foreach ($itemCheckout as $item): ?>
            <li class="flex justify-between py-2">
              <div>
                <p class="font-semibold text-slate-900"><?= (int)$item['jumlah'] ?>x <?= e($item['nama_menu']) ?></p>
                <p class="text-xs text-slate-400">@ Rp<?= number_format($item['harga'], 0, ',', '.') ?></p>
              </div>
              <span class="font-semibold text-slate-900">
                Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="rounded-lg bg-slate-50 p-3">
        <div class="flex justify-between text-base font-bold text-slate-900">
          <span>Total Tagihan Stand</span>
          <span class="text-blue-600">Rp<?= number_format($totalTagihan, 0, ',', '.') ?></span>
        </div>
      </div>
    </aside>
  </form>
</main>

<script>
  function switchMetode(metode) {
    const boxQris = document.getElementById('boxQris');
    const boxTunai = document.getElementById('boxTunai');
    const inputBukti = document.getElementById('bukti_bayar');
    const lblQris = document.getElementById('lblQris');
    const lblTunai = document.getElementById('lblTunai');

    if (metode === 'qris') {
      boxQris.classList.remove('hidden');
      boxTunai.classList.add('hidden');
      inputBukti.required = true;

      lblQris.className =
        'flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-blue-600 bg-blue-50/60 p-3 text-sm font-semibold text-blue-700 transition';
      lblTunai.className =
        'flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-slate-200 p-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50';
    } else {
      boxQris.classList.add('hidden');
      boxTunai.classList.remove('hidden');
      inputBukti.required = false;

      lblTunai.className =
        'flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-blue-600 bg-blue-50/60 p-3 text-sm font-semibold text-blue-700 transition';
      lblQris.className =
        'flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-slate-200 p-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50';
    }
  }
</script>