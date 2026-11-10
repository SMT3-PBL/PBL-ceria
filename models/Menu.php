<?php
class Menu {
  private PDO $pdo;

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function getAll(string $id_tenant) {
    try {
      $sql = "SELECT * FROM menu where id_tenant = :id_tenant";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_tenant' => $id_tenant]);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function getMenuById(string $id) {
    $sql = "SELECT * FROM menu WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(["id" => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Mengambil daftar menu berdasarkan id_tenant, filter kategori, dan pencarian keyword
  public function getMenusByTenant(string $id_tenant, string $kategori = '', string $keyword = '') {
    try {
      $sql = "SELECT * FROM menu WHERE id_tenant = :id_tenant";
      $params = [':id_tenant' => $id_tenant];

      if (!empty($kategori)) {
        $sql .= " AND kategori = :kategori";
        $params[':kategori'] = $kategori;
      }

      if (!empty($keyword)) {
        $sql .= " AND nama_menu ILIKE :keyword";
        $params[':keyword'] = '%' . $keyword . '%';
      }

      $sql .= " ORDER BY id_menu DESC";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  // Menghitung jumlah menu berdasarkan kategori tertentu
  public function countMenuByTenantAndCategory(string $id_tenant, string $kategori) {
    try {
      $sql = "SELECT COUNT(*) as total FROM menu WHERE id_tenant = :id_tenant AND kategori = :kategori";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_tenant' => $id_tenant, ':kategori' => $kategori]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      return 0;
    }
  }

  public function addMenu(array $data) {
    $sql = "INSERT INTO menu (nama_menu, deskripsi, harga, kategori, id_tenant) VALUES (:nama, :deskripsi, :harga, :kategori, :id_tenant)";
    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
      ':nama' => $data['nama_menu'],
      ':deskripsi' => $data['deskripsi'],
      ':harga' => $data['harga'],
      ':kategori' => $data['kategori'],
      ':id_tenant' => $data['id_tenant'],
    ]);
  }

  public function updateMenu(string $id, array $data) {
    if (empty($data)) return false;

    $fields = [];
    $params = [];

    foreach ($data as $kolom => $nilai) {
      $fields[] = "{$kolom} = :{$kolom}";
      $params[":{$kolom}"] = $nilai;
    }

    $setString = implode(", ", $fields);

    try {
      $sql = "UPDATE menu SET {$setString} WHERE id_menu = :id";
      $stmt = $this->pdo->prepare($sql);

      $params[':id'] = $id;

      // Coba jalankan dan kembalikan hasil eksekusinya
      return $stmt->execute($params);
    } catch (PDOException $e) {
      // CETAK ERROR ASLINYA DI SINI UNTUK MELIHAT PENYEBABNYA
      var_dump($e->getMessage());
      exit;
    }
  }

  public function deleteMenu(string $id) {
    try {
      $stmt = $this->pdo->prepare("DELETE FROM menu WHERE id_menu = :id");
      return $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
      return false;
    }
  }

  public function countMenuByTenant(string $id_tenant) {
    try {
      $sql = "SELECT COUNT(*) as total FROM menu WHERE id_tenant = :id_tenant";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_tenant' => $id_tenant]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      return 0;
    }
  }
}
