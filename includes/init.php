<?php
// includes/init.php: dimuat paling awal oleh setiap halaman.
// Berisi sesi, koneksi database, dan helper tampilan.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_RUNNING', true);
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/database.php';

// true  = halaman bisa dibuka tanpa login dan memakai data contoh.
// false = pakai data asli dari database (butuh login).
if (!defined('FC_DEMO')) define('FC_DEMO', false);

// Alamat dasar proyek, terdeteksi otomatis (contoh: /PBL-ceria).
if (!defined('BASE_URL')) {
    $root = str_replace('\\', '/', (string) realpath(__DIR__ . '/..'));
    $doc  = str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    define('BASE_URL', ($doc !== '' && strpos($root, $doc) === 0) ? substr($root, strlen($doc)) : '');
}

if (!function_exists('e')) {
    function e($v): string {
        return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('url')) {
    function url(string $path = ''): string {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}
if (!function_exists('redirect')) {
    function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }
}
if (!function_exists('rupiah')) {
    function rupiah($n): string {
        return 'Rp' . number_format((int) $n, 0, ',', '.');
    }
}

// Pengguna yang sedang masuk, dibentuk dari session yang diisi handler login
// (user_id, user_name, user_email, user_role).
if (!function_exists('user')) {
    function user(): ?array {
        if (empty($_SESSION['user_id'])) return null;

        return [
            'id_pengguna' => (int) $_SESSION['user_id'],
            'nama'        => (string) ($_SESSION['user_name'] ?? ''),
            'email'       => (string) ($_SESSION['user_email'] ?? ''),
            'role'        => (string) ($_SESSION['user_role'] ?? ''),
        ];
    }
}
if (!function_exists('require_role')) {
    function require_role(string $role): void {
        if (FC_DEMO) return;
        if ((user()['role'] ?? null) !== $role) redirect('pages/auth/login.php');
    }
}

// Data untuk header pembeli.
if (!function_exists('sesi_pembeli')) {
    function sesi_pembeli(): array {
        return $_SESSION['sesi'] ?? (FC_DEMO ? ['no_meja' => '12', 'nama_pembeli' => 'Nara'] : ['no_meja' => '-', 'nama_pembeli' => 'Pengunjung']);
    }
}

// Data tenant milik penjual yang sedang login (kolom: id_tenant, nama_tenant,
// deskripsi, status_tenant, qr_pembayaran, id_pengguna).
if (!function_exists('tenant_aktif')) {
    function tenant_aktif(): array {
        if (FC_DEMO) {
            return ['id_tenant' => 1, 'nama_tenant' => 'Ayam Geprek Mas Joko', 'status_tenant' => 'buka'];
        }

        // Tidak di-cache, supaya setelah status diubah datanya langsung segar.
        global $pdo;
        require_once BASE_PATH . '/models/Tenant.php';

        $idPengguna = (int) (user()['id_pengguna'] ?? 0);
        if ($idPengguna === 0) redirect('pages/auth/login.php');

        $row = (new Tenant($pdo))->getTenantByUserId($idPengguna);
        if (!$row) redirect('pages/auth/login.php');

        return $row;
    }
}
