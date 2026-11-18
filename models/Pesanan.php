<?php
// models/Pesanan.php

class Pesanan {
  public const STATUS_BERIKUTNYA = [
    'menunggu_pembayaran' => 'diterima',
    'diterima' => 'diproses',
    'diproses' => 'siap_diambil',
    'siap_diambil' => 'selesai',
  ];

  public const STATUS_LABEL = [
    'menunggu_pembayaran' => 'Menunggu Pembayaran',
    'diterima' => 'Diterima',
    'diproses' => 'Diproses',
    'siap_diambil' => 'Siap Diambil',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Ditolak',
  ];

  public const TAHAPAN = ['diterima', 'diproses', 'siap_diambil', 'selesai'];

  /** Cari id_tenant milik pengguna (seller) yang login. 0 jika tidak ada. */
  public static function idTenantByPengguna(PDO $pdo, int $idPengguna): int {
    $stmt = $pdo->prepare('SELECT id_tenant FROM tenant WHERE id_pengguna = ?');
    $stmt->execute([$idPengguna]);
    return (int) $stmt->fetchColumn();
  }

  /** Pendapatan, jumlah pesanan aktif, dan porsi terjual untuk hari ini. */
  public static function statistikHariIni(PDO $pdo, int $idTenant): array {
    $stmt = $pdo->prepare(
      "SELECT
            COALESCE(SUM(CASE WHEN b.status_pembayaran = 'lunas' AND p.status_pesanan <> 'dibatalkan' THEN d.subtotal ELSE 0 END), 0) AS pendapatan,
            COUNT(DISTINCT CASE WHEN p.status_pesanan NOT IN ('selesai', 'dibatalkan') THEN p.id_pesanan END) AS pesanan_aktif,
            COALESCE(SUM(CASE WHEN p.status_pesanan <> 'dibatalkan' THEN d.jumlah ELSE 0 END), 0) AS porsi
        FROM pesanan p
        LEFT JOIN pembayaran b ON b.id_pesanan = p.id_pesanan
        LEFT JOIN detail_pesanan d ON d.id_pesanan = p.id_pesanan
        WHERE p.id_tenant = ? AND p.tanggal_pesanan::date = CURRENT_DATE"
    );
    $stmt->execute([$idTenant]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['pendapatan' => 0, 'pesanan_aktif' => 0, 'porsi' => 0];
  }

  /** Daftar pesanan tenant, dengan filter status dan pencarian. */
  public static function daftar(PDO $pdo, int $idTenant, string $filterStatus = 'aktif', string $cari = ''): array {
    $where = ['p.id_tenant = ?'];
    $params = [$idTenant];

    if ($filterStatus === 'aktif') {
      $where[] = "p.status_pesanan NOT IN ('selesai', 'dibatalkan')";
    } elseif (in_array($filterStatus, array_keys(self::STATUS_LABEL), true)) {
      $where[] = 'p.status_pesanan = ?';
      $params[] = $filterStatus;
    }

    if ($cari !== '') {
      $where[] = '(s.nama ILIKE ? OR s.no_meja ILIKE ? OR CAST(p.no_antrean AS TEXT) ILIKE ? OR CAST(p.id_pesanan AS TEXT) ILIKE ?)';
      array_push($params, '%' . $cari . '%', '%' . $cari . '%', '%' . $cari . '%', '%' . $cari . '%');
    }

    $stmt = $pdo->prepare(
      "SELECT p.id_pesanan, p.no_antrean, p.status_pesanan, p.tanggal_pesanan,
            s.nama AS nama_pembeli, s.no_meja,
            b.metode_bayar, b.status_pembayaran,
            COALESCE(SUM(d.subtotal), 0) AS total,
            COALESCE(SUM(d.jumlah), 0) AS porsi,
            string_agg(d.nama_menu || ' × ' || d.jumlah, ', ' ORDER BY d.id_detail) AS ringkasan_menu
        FROM pesanan p
        JOIN sesi_pembeli s ON s.id_sesi = p.id_sesi
        LEFT JOIN pembayaran b ON b.id_pesanan = p.id_pesanan
        LEFT JOIN detail_pesanan d ON d.id_pesanan = p.id_pesanan
        WHERE " . implode(' AND ', $where) . "
        GROUP BY p.id_pesanan, p.no_antrean, p.status_pesanan, p.tanggal_pesanan,
            s.nama, s.no_meja, b.metode_bayar, b.status_pembayaran
        ORDER BY CASE p.status_pesanan
            WHEN 'menunggu_pembayaran' THEN 1 WHEN 'diterima' THEN 2
            WHEN 'diproses' THEN 3 WHEN 'siap_diambil' THEN 4 ELSE 5 END,
            p.tanggal_pesanan DESC"
    );
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** Detail satu pesanan milik tenant. null jika tidak ditemukan. */
  public static function detail(PDO $pdo, int $idPesanan, int $idTenant): ?array {
    $stmt = $pdo->prepare(
      "SELECT p.*, s.nama AS nama_pembeli, s.no_meja,
              b.metode_bayar, b.jumlah_bayar, b.status_pembayaran, b.bukti_bayar
          FROM pesanan p
          JOIN sesi_pembeli s ON s.id_sesi = p.id_sesi
          LEFT JOIN pembayaran b ON b.id_pesanan = p.id_pesanan
          WHERE p.id_pesanan = ? AND p.id_tenant = ?"
    );
    $stmt->execute([$idPesanan, $idTenant]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /** Pesanan terakhir milik sesi pembeli. null jika belum ada. */
  public static function terakhirBySesi(PDO $pdo, int $idSesi): ?array {
    $stmt = $pdo->prepare('SELECT * FROM pesanan WHERE id_sesi = ? ORDER BY id_pesanan DESC LIMIT 1');
    $stmt->execute([$idSesi]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /** Satu pesanan berdasarkan id, hanya jika milik sesi pembeli tersebut. */
  public static function byIdUntukSesi(PDO $pdo, int $idPesanan, int $idSesi): ?array {
    $stmt = $pdo->prepare('SELECT * FROM pesanan WHERE id_pesanan = ? AND id_sesi = ?');
    $stmt->execute([$idPesanan, $idSesi]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
  }

  /** Item menu dalam satu pesanan. */
  public static function item(PDO $pdo, int $idPesanan): array {
    $stmt = $pdo->prepare(
      'SELECT nama_menu, harga_satuan, jumlah, subtotal FROM detail_pesanan WHERE id_pesanan = ? ORDER BY id_detail ASC'
    );
    $stmt->execute([$idPesanan]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** Status pesanan saat ini. null jika pesanan tidak ada / bukan milik tenant. */
  public static function statusSaatIni(PDO $pdo, int $idPesanan, int $idTenant): ?string {
    $stmt = $pdo->prepare('SELECT status_pesanan FROM pesanan WHERE id_pesanan = ? AND id_tenant = ?');
    $stmt->execute([$idPesanan, $idTenant]);
    $status = $stmt->fetchColumn();
    return $status === false ? null : (string) $status;
  }

  /** Status pembayaran pesanan milik tenant ('lunas' / 'belum_lunas'). null jika tidak ada data pembayaran. */
  public static function statusPembayaran(PDO $pdo, int $idPesanan, int $idTenant): ?string {
    $stmt = $pdo->prepare(
      'SELECT b.status_pembayaran
         FROM pembayaran b
         JOIN pesanan p ON p.id_pesanan = b.id_pesanan
        WHERE b.id_pesanan = ? AND p.id_tenant = ?'
    );
    $stmt->execute([$idPesanan, $idTenant]);
    $status = $stmt->fetchColumn();
    return $status === false ? null : (string) $status;
  }

  /**
   * Apakah perubahan status dari $dari ke $ke diperbolehkan.
   * - Menolak (dibatalkan) boleh selama pesanan belum selesai/dibatalkan.
   * - 'diterima' hanya terjadi otomatis lewat konfirmasi pembayaran.
   * - Tahap berikutnya (diproses, siap diambil, selesai) wajib sudah lunas.
   */
  public static function bolehUbahStatus(string $dari, string $ke, bool $lunas): bool {
    if ($ke === 'dibatalkan') {
      return !in_array($dari, ['selesai', 'dibatalkan'], true);
    }
    if ($dari === 'menunggu_pembayaran') {
      return false;
    }
    return $lunas && (self::STATUS_BERIKUTNYA[$dari] ?? null) === $ke;
  }

  /** Ubah status. true jika berhasil (false jika status sudah berubah di tempat lain). */
  public static function ubahStatus(PDO $pdo, int $idPesanan, int $idTenant, string $dari, string $ke): bool {
    $stmt = $pdo->prepare(
      'UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ? AND id_tenant = ? AND status_pesanan = ?'
    );
    $stmt->execute([$ke, $idPesanan, $idTenant, $dari]);
    return $stmt->rowCount() > 0;
  }

  /** Konfirmasi pembayaran jadi lunas. true jika ada baris yang berubah. */
  public static function konfirmasiPembayaran(PDO $pdo, int $idPesanan, int $idTenant): bool {
    $stmt = $pdo->prepare(
      "UPDATE pembayaran b
              SET status_pembayaran = 'lunas', waktu_konfirmasi = now()
              FROM pesanan p
              WHERE b.id_pesanan = p.id_pesanan
                  AND b.id_pesanan = ? AND p.id_tenant = ?
                  AND p.status_pesanan <> 'dibatalkan'
                  AND b.status_pembayaran = 'belum_lunas'
                  AND (b.metode_bayar = 'tunai' OR b.bukti_bayar IS NOT NULL)"
    );
    $pdo->beginTransaction();
    try {
      $stmt->execute([$idPesanan, $idTenant]);
      $berhasil = $stmt->rowCount() > 0;

      if ($berhasil) {
        // Pesanan yang menunggu pembayaran otomatis masuk tahap 'diterima'
        $naik = $pdo->prepare(
          "UPDATE pesanan SET status_pesanan = 'diterima'
            WHERE id_pesanan = ? AND id_tenant = ? AND status_pesanan = 'menunggu_pembayaran'"
        );
        $naik->execute([$idPesanan, $idTenant]);
      }

      $pdo->commit();
      return $berhasil;
    } catch (Throwable $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
}
