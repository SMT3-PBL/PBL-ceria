<?php
// includes/init.php: dimuat paling awal oleh setiap halaman.
// Hanya berisi helper tampilan. Gabungkan dengan init.php milik ketua (sesi, koneksi database).

if (session_status() === PHP_SESSION_NONE) session_start();

// true = halaman bisa dibuka tanpa login dan memakai data contoh.
// Ubah ke false setelah login dan database sungguhan aktif.
if (!defined('FC_DEMO')) define('FC_DEMO', true);

// Alamat dasar proyek, terdeteksi otomatis (contoh: /PBL-ceria).
// GANTI DENGAN KODE INI
if (!defined('BASE_URL')) {
    // Kunci langsung nama folder proyek Anda agar fungsinya tidak pernah meleset
    define('BASE_URL', '/PBL-ceria');
}

if (!function_exists('e')) {
    function e($v): string { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('url')) {
    function url(string $path = ''): string { return BASE_URL . '/' . ltrim($path, '/'); }
}
if (!function_exists('redirect')) {
    function redirect(string $path): void { header('Location: ' . url($path)); exit; }
}
if (!function_exists('rupiah')) {
    function rupiah($n): string { return 'Rp' . number_format((int) $n, 0, ',', '.'); }
}

// Peran yang sedang masuk: ['nama' => ..., 'role' => 'seller'|'admin', 'id_tenant' => ...]
if (!function_exists('user')) {
    function user(): ?array { return $_SESSION['user'] ?? null; }
}
if (!function_exists('require_role')) {
    function require_role(string $role): void {
        if (FC_DEMO) return;
        if ((user()['role'] ?? null) !== $role) redirect('pages/auth/login.php');
    }
}

// Data untuk header. Ganti isinya dengan data dari model setelah database siap.
if (!function_exists('sesi_pembeli')) {
    function sesi_pembeli(): array {
        return $_SESSION['sesi'] ?? (FC_DEMO ? ['no_meja' => '12', 'nama_pembeli' => 'Nara'] : ['no_meja' => '-', 'nama_pembeli' => 'Pengunjung']);
    }
}
if (!function_exists('tenant_aktif')) {
    function tenant_aktif(): array {
        return FC_DEMO ? ['id_tenant' => 1, 'nama_tenant' => 'Ayam Geprek Mas Joko', 'status_buka' => true] : ['id_tenant' => (int) (user()['id_tenant'] ?? 0), 'nama_tenant' => (string) (user()['nama_tenant'] ?? '-'), 'status_buka' => true];
    }
}
