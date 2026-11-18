<?php
// models/SesiPembeli.php

class SesiPembeli {
  /**
   * Buat sesi pembeli baru (berlaku 12 jam).
   * Token acak dibuat di sini; yang disimpan di database hanya hash-nya.
   * Mengembalikan data yang siap disimpan ke $_SESSION['sesi'].
   */
  public static function buat(PDO $pdo, string $noMeja, string $nama): array {
    $token = bin2hex(random_bytes(32));

    $st = $pdo->prepare(
      "INSERT INTO sesi_pembeli (token_sesi_hash, kadaluwarsa_pada, no_meja, nama)
         VALUES (?, now() + interval '12 hours', ?, ?)
         RETURNING id_sesi"
    );
    $st->execute([hash('sha256', $token), $noMeja, $nama]);

    return [
      'id_sesi'      => (int) $st->fetchColumn(),
      'no_meja'      => $noMeja,
      'nama_pembeli' => $nama,
      'token'        => $token,
    ];
  }
}