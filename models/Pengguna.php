<?php

class Pengguna {
  private PDO $pdo;

  public function __construct(PDO $pdo) {
    $this->$pdo = $pdo;
  }
}
