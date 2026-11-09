<?php

class Tenant {
  private PDO $pdo;

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function getTenantByUserId(string $id_pengguna) {
    try {
      // Ubah dari SELECT id_tenant menjadi SELECT * untuk mengambil seluruh kolom satu baris data
      $sql = "SELECT * FROM tenant WHERE id_pengguna = :id_pengguna LIMIT 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_pengguna' => $id_pengguna]);

      // Mengembalikan satu baris data lengkap dalam bentuk array asosiatif (misal: ['id_tenant' => 1, 'nama_tenant' => '...', 'id_pengguna' => '...', dsb])
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return null;
    }
  }

  public function updateOperationalStatus(string $id_tenant, string $status) {
    try {
      // Sesuaikan nama kolom status dan tabel dengan database kamu
      // Contoh: tabel 'tenant', kolom 'status' atau 'is_active', dan primary key 'id_tenant'
      $sql = "UPDATE tenant SET status_tenant = :status WHERE id_tenant = :id_tenant";
      $stmt = $this->pdo->prepare($sql);

      return $stmt->execute([
        ':status' => $status,
        ':id_tenant' => $id_tenant
      ]);
    } catch (PDOException $e) {
      return false;
    }
  }
}
