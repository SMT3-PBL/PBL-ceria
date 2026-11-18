<?php
require_once __DIR__ . '/../../../includes/init.php';

header('Content-Type: application/json');

$id_pesanan = $_GET['id_pesanan'] ?? null;
if (!$id_pesanan) {
  echo json_encode(['success' => false, 'message' => 'ID pesanan tidak valid']);
  exit;
}

$stmt = $pdo->prepare("SELECT status_pesanan FROM pesanan WHERE id_pesanan = ?");
$stmt->execute([$id_pesanan]);
$data = $stmt->fetch();

if ($data) {
  echo json_encode([
    'success' => true,
    'status_pesanan' => $data['status_pesanan']
  ]);
} else {
  echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
}