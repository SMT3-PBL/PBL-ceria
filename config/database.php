<?php
$host = "ep-cold-hat-azf75aet-pooler.c-3.ap-southeast-1.aws.neon.tech";
$port = 5432;
$db   = "pbl-ceria";
$user = "anu";
$pass = "npg_sxHPjUCn2cr1";

try {
  $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Koneksi database gagal: " . $e->getMessage());
}
