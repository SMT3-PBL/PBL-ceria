<?php
$host = "ep-cold-hat-azf75aet.c-3.ap-southeast-1.aws.neon.tech";
$port = 5432;
$db   = "pbl-ceria";
$user = "anu";
$pass = "npg_sxHPjUCn2cr1";

// Ambil Endpoint ID dari subdomain host paling depan
$endpointId = "ep-cold-hat-azf75aet";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require;options='endpoint=$endpointId'";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => true,
    ]);
    $pdo->exec('SET search_path TO public');
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}