<?php

class Pengguna {
  private PDO $pdo;

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function emailExists(string $email): bool {
    $sql = "SELECT 1 FROM pengguna WHERE email = :email LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);

    return $stmt->fetchColumn() !== false;
  }

  public function register(
    string $nama,
    string $email,
    string $password
  ): bool {
    $passwordHash = password_hash(
      $password,
      PASSWORD_DEFAULT
    );

    $sql = "INSERT INTO pengguna (nama, email, password_hash)
            VALUES (:nama, :email, :password)";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
      ':nama'     => $nama,
      ':email'    => $email,
      ':password' => $passwordHash
    ]);
  }

  public function login(string $email): array|false {
    $sql = "SELECT id_pengguna, nama, email, password_hash, role
            FROM pengguna
            WHERE email = :email
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':email' => $email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}