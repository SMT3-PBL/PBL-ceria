<?php
define('APP_RUNNING', true);
require_once __DIR__ . '/../../includes/init.php';

$pageTitle = 'Status Pesanan';
$role      = 'buyer';
$active    = 'status';

require __DIR__ . '/../../includes/header.php';

$sesi = sesi_pembeli();
?>

<main class="mx-auto max-w-6xl p-4 pb-20 sm:p-6 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_340px]">

    <!-- Section Status Real-Time -->
    <div class="space-y-6">
        <!-- Banner Estimasi -->
        <section class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-blue-100 bg-blue-50/70 p-5 sm:p-6">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-blue-950">Pesanan Anda Sedang Diproses!</h1>
                <p class="mt-1 text-sm text-blue-800">Silakan duduk santai di <b>Meja No. <?= e($sesi['no_meja']) ?></b>. Status pesanan akan diperbarui secara otomatis.</p>
            </div>
            <div class="rounded-xl bg-white px-4 py-2.5 text-right shadow-sm border border-blue-100 shrink-0">
                <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400">Estimasi Penyajian</span>
                <span class="text-lg font-extrabold text-blue-600">10-15 Menit</span>
            </div>
        </section>

        <!-- Progress Tracking Per Tenant -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Proses Stand Makanan & Minuman</h2>

            <!-- Tenant 1: Ayam Geprek Mas Joko -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-bold text-slate-900">1. Ayam Geprek Mas Joko</span>
                    <span class="rounded-full bg-amber-100 px-3 py-0.5 text-xs font-bold text-amber-800">Diproses / Dimasak</span>
                </div>
                <!-- Progress Steps -->
                <div class="grid grid-cols-4 gap-2">
                    <div class="h-2 rounded-full bg-blue-600"></div>
                    <div class="h-2 rounded-full bg-blue-600 animate-pulse"></div>
                    <div class="h-2 rounded-full bg-slate-200"></div>
                    <div class="h-2 rounded-full bg-slate-200"></div>
                </div>
                <div class="grid grid-cols-4 text-center text-[11px] font-semibold text-slate-400">
                    <span class="text-blue-600">Diterima</span>
                    <span class="text-blue-600 font-bold">Diproses</span>
                    <span>Siap Diambil</span>
                    <span>Selesai</span>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Alert Pesanan Tunai / Belum Lunas (Jika Ada) -->
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900 flex gap-3">
                <span class="text-lg">💵</span>
                <div>
                    <b class="block font-bold text-amber-950">Menunggu Pembayaran Tunai di Stand</b>
                    <p class="mt-0.5 text-amber-800 leading-relaxed">
                        Jika kamu memilih bayar <b>Tunai</b>, silakan menuju ke kasir stand <b>Ayam Geprek Mas Joko</b> untuk menyelesaikan pembayaran sebesar <b>Rp34.000</b>.
                    </p>
                </div>
            </div>
        </section>
    </div>

    <!-- Rincian Ringkasan Pesanan (Nota Digital) -->
    <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Detail Pesanan</h2>
            <span class="text-xs font-mono font-semibold text-slate-500">#KTN-40021</span>
        </div>

        <div class="text-xs space-y-1.5 text-slate-600">
            <p class="flex justify-between"><span>Pemesan:</span> <b class="text-slate-900"><?= e($sesi['nama_pembeli']) ?></b></p>
            <p class="flex justify-between"><span>Nomor Meja:</span> <b class="text-slate-900">Meja No. <?= e($sesi['no_meja']) ?></b></p>
            <p class="flex justify-between"><span>Status Pembayaran:</span> <span class="rounded bg-amber-100 px-2 py-0.5 font-bold text-amber-800 text-[10px]">Belum Lunas</span></p>
        </div>

        <hr class="border-slate-100">

        <ul class="space-y-3 text-xs">
            <li class="flex justify-between">
                <div>
                    <p class="font-bold text-slate-900">Ayam Geprek Level 3</p>
                    <p class="text-slate-400">2x @ Rp15.000</p>
                </div>
                <span class="font-semibold text-slate-900">Rp30.000</span>
            </li>
            <li class="flex justify-between">
                <div>
                    <p class="font-bold text-slate-900">Es Teh Manis</p>
                    <p class="text-slate-400">1x @ Rp4.000</p>
                </div>
                <span class="font-semibold text-slate-900">Rp4.000</span>
            </li>
        </ul>

        <hr class="border-slate-100">

        <div class="space-y-1 text-xs">
            <div class="flex justify-between text-base font-bold text-slate-900 pt-1">
                <span>Total Akhir</span>
                <span class="text-blue-600">Rp34.000</span>
            </div>
        </div>
    </aside>
</main>