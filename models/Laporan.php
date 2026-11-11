<?php

/**
 * Query laporan penjualan per tenant.
 *
 * Penjualan = pesanan berstatus 'selesai' milik tenant (pesanan.id_tenant).
 * Nama menu diambil dari detail_pesanan.nama_menu (salinan saat pesanan dibuat),
 * jadi laporan tetap utuh walaupun menunya sudah dihapus (id_menu = NULL).
 * Tanggal dihitung dalam zona waktu WIB karena pesanan.tanggal_pesanan bertipe TIMESTAMPTZ.
 */
class Laporan {
  private const STATUS_SELESAI = 'selesai';
  private const ZONA = 'Asia/Jakarta';

  public function __construct(private PDO $pdo) {
  }

  private function params(string $idTenant, string $dari, string $sampai): array {
    return [':t' => $idTenant, ':s' => self::STATUS_SELESAI, ':dari' => $dari, ':sampai' => $sampai, ':z' => self::ZONA];
  }

  private const FILTER = "p.id_tenant = :t
          AND p.status_pesanan = :s
          AND (p.tanggal_pesanan AT TIME ZONE :z)::date BETWEEN :dari AND :sampai";

  public function ringkasan(string $idTenant, string $dari, string $sampai): array {
    $st = $this->pdo->prepare(
      "SELECT COUNT(DISTINCT p.id_pesanan)  AS jumlah_pesanan,
              COALESCE(SUM(d.subtotal), 0)  AS total_pendapatan,
              COALESCE(SUM(d.jumlah), 0)    AS total_item
         FROM pesanan p
         JOIN detail_pesanan d ON d.id_pesanan = p.id_pesanan
        WHERE " . self::FILTER
    );
    $st->execute($this->params($idTenant, $dari, $sampai));
    return $st->fetch(PDO::FETCH_ASSOC) ?: ['jumlah_pesanan' => 0, 'total_pendapatan' => 0, 'total_item' => 0];
  }

  public function menuTerlaris(string $idTenant, string $dari, string $sampai, int $limit = 5): array {
    $st = $this->pdo->prepare(
      "SELECT d.nama_menu,
              SUM(d.jumlah)   AS terjual,
              SUM(d.subtotal) AS pendapatan
         FROM pesanan p
         JOIN detail_pesanan d ON d.id_pesanan = p.id_pesanan
        WHERE " . self::FILTER . "
        GROUP BY d.nama_menu
        ORDER BY terjual DESC, pendapatan DESC
        LIMIT " . (int) $limit
    );
    $st->execute($this->params($idTenant, $dari, $sampai));
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  public function riwayat(string $idTenant, string $dari, string $sampai): array {
    $st = $this->pdo->prepare(
      "SELECT p.id_pesanan, p.no_antrean,
              (p.tanggal_pesanan AT TIME ZONE :z) AS waktu_pesan,
              sp.nama AS nama_pembeli, sp.no_meja,
              pb.metode_bayar,
              SUM(d.jumlah)   AS total_item,
              SUM(d.subtotal) AS total
         FROM pesanan p
         JOIN detail_pesanan d      ON d.id_pesanan = p.id_pesanan
         JOIN sesi_pembeli sp       ON sp.id_sesi = p.id_sesi
         LEFT JOIN pembayaran pb    ON pb.id_pesanan = p.id_pesanan
        WHERE " . self::FILTER . "
        GROUP BY p.id_pesanan, p.no_antrean, p.tanggal_pesanan, sp.nama, sp.no_meja, pb.metode_bayar
        ORDER BY p.tanggal_pesanan DESC"
    );
    $st->execute($this->params($idTenant, $dari, $sampai));
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }
}
