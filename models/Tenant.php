<?php

class Tenant {
  private PDO $pdo;

  public function __construct(PDO $pdo) {
    $this->$pdo = $pdo;
  }
  
}