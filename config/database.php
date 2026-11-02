<?php
$host = "ep-cold-hat-azf75aet-pooler.c-3.ap-southeast-1.aws.neon.tech";
$port = 5432;
$db   = "pbl-ceria";
$user = "anu";
$pass = "npg_sxHPjUCn2cr1";

// Ambil Endpoint ID dari subdomain host paling depan
$endpointId = "ep-cold-hat-azf75aet-pooler";

try {
    // Tambahkan parameter sslmode=require dan options='endpoint=...'
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require;options='endpoint=$endpointId'";

    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}