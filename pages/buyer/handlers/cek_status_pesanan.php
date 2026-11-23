<?php
// pages/buyer/handlers/cek_status_pesanan.php
require_once __DIR__ . '/../../../includes/init.php';
require_once __DIR__ . '/../../../models/Pesanan.php';

header('Content-Type: application/json');

$sesi   = sesi_pembeli();
$idSesi = (int) ($sesi['id_sesi'] ?? 0);

if ($idSesi <= 0) {
  http_response_code(403);
  echo json_encode(['success' => false, 'message' => 'Sesi pembeli tidak ditemukan']);
  exit;
}

// Hanya pesanan milik sesi ini (semua tenant)
$daftar = Pesanan::daftarBySesi($pdo, $idSesi);

echo json_encode([
  'success' => true,
  'tanda'   => Pesanan::tandaDari($daftar),
]);