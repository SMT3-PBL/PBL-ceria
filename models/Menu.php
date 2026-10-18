<?php
class Menu {
  private PDO $pdo;

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function getAll() {
    try {
      $sql = "SELECT * FROM menu";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function getMenuById($id) {
    $sql = "SELECT * FROM menu WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(["id" => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function addMenu(array $data) {
    $sql = "INSERT INTO menu (nama_menu, harga, stok, kategori) VALUES (:nama, :harga, :stok, :kategori)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      ':nama' => $data['nama_menu'],
      ':harga' => $data['harga'],
      ':stok' => $data['stok'],
      ':kategori' => $data['kategori'],
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
      $sql = "UPDATE menu SET {$setString} WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);

      $params[':id'] = $id;

      return $stmt->execute($params);
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }
}
