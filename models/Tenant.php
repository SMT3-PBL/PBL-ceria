<?php
// models/Tenant.php

class Tenant
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {   $this->pdo = $pdo; }

    /** Semua tenant + akun penjualnya (untuk dashboard admin). */
    public function semua(): array
    {
        $sql = "SELECT t.id_tenant, t.nama_tenant, t.status_tenant, t.qr_pembayaran,
                       p.nama AS nama_penjual, p.email
                  FROM tenant t
                  JOIN pengguna p ON p.id_pengguna = t.id_pengguna
              ORDER BY t.id_tenant DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Satu tenant + akun penjualnya. */
    public function cari(int $idTenant): ?array
    {
        $st = $this->pdo->prepare(
            "SELECT t.id_tenant, t.nama_tenant, t.qr_pembayaran,
                    p.id_pengguna, p.nama AS nama_penjual, p.email
               FROM tenant t
               JOIN pengguna p ON p.id_pengguna = t.id_pengguna
              WHERE t.id_tenant = ?"
        );
        $st->execute([$idTenant]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /** Cek email sudah dipakai (abaikan akun tertentu saat edit). */
    public function emailTerpakai($email)
    {
        $stmt = $this->pdo->prepare("SELECT id_pengguna FROM pengguna WHERE email = ?");
        $stmt->execute([$email]);

        // Benar: fetch() akan menarik baris data. 
        // Jika data tidak ada, fetch() akan menghasilkan false.
        $hasil = $stmt->fetch();
        return $hasil ? true : false;
    }

    /** Buat akun seller + tenant sekaligus. Mengembalikan id_tenant. */
    public function buat(string $namaTenant, ?string $qr, string $namaPenjual, string $email, string $password): int
    {
        try {
            // mulai transaksi
            // $this->pdo->beginTransaction();
            // PostgreSQL: pakai RETURNING, bukan lastInsertId()
            $st = $this->pdo->prepare(
                "INSERT INTO pengguna (nama, email, password_hash, role)
                 VALUES (?, ?, ?, 'seller') RETURNING id_pengguna"
            );
            $st->execute([$namaPenjual, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $idPengguna = (int) $st->fetchColumn();

            $st = $this->pdo->prepare(
                "INSERT INTO tenant (nama_tenant, qr_pembayaran, id_pengguna)
                 VALUES (?, ?, ?) RETURNING id_tenant"
            );
            $st->execute([$namaTenant, $qr, $idPengguna]);
            $idTenant = (int) $st->fetchColumn();

            // $this->pdo->commit();
            return $idTenant;
        } catch (Throwable $e) {
            // $this->pdo->rollBack();
            throw $e;
        }
    }

    /** Update tenant + akun seller. $qr / $passwordBaru null = tidak diubah. */
    public function perbarui(
        int $idTenant,
        int $idPengguna,
        string $namaTenant,
        ?string $qr,
        string $namaPenjual,
        string $email,
        ?string $passwordBaru
    ): void {
        $this->pdo->beginTransaction();
        try {
            if ($qr !== null) {
                $this->pdo->prepare("UPDATE tenant SET nama_tenant = ?, qr_pembayaran = ? WHERE id_tenant = ?")
                    ->execute([$namaTenant, $qr, $idTenant]);
            } else {
                $this->pdo->prepare("UPDATE tenant SET nama_tenant = ? WHERE id_tenant = ?")
                    ->execute([$namaTenant, $idTenant]);
            }

            if ($passwordBaru !== null) {
                $this->pdo->prepare("UPDATE pengguna SET nama = ?, email = ?, password_hash = ? WHERE id_pengguna = ?")
                    ->execute([$namaPenjual, $email, password_hash($passwordBaru, PASSWORD_DEFAULT), $idPengguna]);
            } else {
                $this->pdo->prepare("UPDATE pengguna SET nama = ?, email = ? WHERE id_pengguna = ?")
                    ->execute([$namaPenjual, $email, $idPengguna]);
            }
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Mengambil data spesifik untuk diisi ke form
    public function getById($id_tenant)
    {
        $stmt = $this->pdo->prepare("
        SELECT t.id_tenant, t.nama_tenant, t.qr_pembayaran, p.id_pengguna, p.nama AS nama_penjual, p.email 
        FROM tenant t 
        JOIN pengguna p ON t.id_pengguna = p.id_pengguna 
        WHERE t.id_tenant = ?");
        $stmt->execute([$id_tenant]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Mengeksekusi proses update
    public function update($id_tenant, $id_pengguna, $namaTenant, $namaPenjual, $email, $passwordBaru = null, $namaFileQris = null)
    {
        try {
            $this->pdo->beginTransaction();
            // 1. Update tabel pengguna
            if (!empty($passwordBaru)) {
                $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);
                $stmtUser = $this->pdo->prepare("UPDATE pengguna SET nama = ?, email = ?, password_hash = ? WHERE id_pengguna = ?");
                $stmtUser->execute([$namaPenjual, $email, $hash, $id_pengguna]);
            } else {
                $stmtUser = $this->pdo->prepare("UPDATE pengguna SET nama = ?, email = ? WHERE id_pengguna = ?");
                $stmtUser->execute([$namaPenjual, $email, $id_pengguna]);
            }
            // 2. Update tabel tenant
            if ($namaFileQris) {
                $stmtTenant = $this->pdo->prepare("UPDATE tenant SET nama_tenant = ?, qr_pembayaran = ? WHERE id_tenant = ?");
                $stmtTenant->execute([$namaTenant, $namaFileQris, $id_tenant]);
            } else {
                $stmtTenant = $this->pdo->prepare("UPDATE tenant SET nama_tenant = ? WHERE id_tenant = ?");
                $stmtTenant->execute([$namaTenant, $id_tenant]);
            }
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Tambahkan pengecualian ID saat mengecek email agar bisa menyimpan email yang sama
    public function emailTerpakaiKecuali($email, $id_pengguna_saat_ini)
    {
        $stmt = $this->pdo->prepare("SELECT id_pengguna FROM pengguna WHERE email = ? AND id_pengguna != ?");
        $stmt->execute([trim($email), $id_pengguna_saat_ini]);
        return $stmt->fetch() ? true : false;
    }

 public function hapus($id_tenant) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // 1. SABUK PENGAMAN DIAKTIFKAN KEMBALI
            $this->pdo->beginTransaction();

            $stmtInfo = $this->pdo->prepare("SELECT id_pengguna, qr_pembayaran FROM tenant WHERE id_tenant = ?");
            $stmtInfo->execute([$id_tenant]);
            $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

            if (!$info) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                return false; 
            }

            // 2. HAPUS DATA ANAK
            $this->pdo->prepare("DELETE FROM detail_pesanan WHERE id_pesanan IN (SELECT id_pesanan FROM pesanan WHERE id_tenant = ?)")->execute([$id_tenant]);
            $this->pdo->prepare("DELETE FROM pembayaran WHERE id_pesanan IN (SELECT id_pesanan FROM pesanan WHERE id_tenant = ?)")->execute([$id_tenant]);
            $this->pdo->prepare("DELETE FROM pesanan WHERE id_tenant = ?")->execute([$id_tenant]);
            $this->pdo->prepare("DELETE FROM menu WHERE id_tenant = ?")->execute([$id_tenant]);

            // 3. HAPUS DATA INDUK
            $this->pdo->prepare("DELETE FROM tenant WHERE id_tenant = ?")->execute([$id_tenant]);
            $this->pdo->prepare("DELETE FROM pengguna WHERE id_pengguna = ?")->execute([$info['id_pengguna']]);

            // 4. SIMPAN PERUBAHAN SECARA PERMANEN
            $this->pdo->commit();

            return $info['qr_pembayaran'];

        } catch (Throwable $e) {
            // 5. BATALKAN SEMUA PENGHAPUSAN JIKA ADA 1 SAJA YANG GAGAL
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

  public function getTenantByUserId(string $id_pengguna) {
    try {
      // Ubah dari SELECT id_tenant menjadi SELECT * untuk mengambil seluruh kolom satu baris data
      $sql = "SELECT * FROM tenant WHERE id_pengguna = :id_pengguna LIMIT 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_pengguna' => $id_pengguna]);

      // Mengembalikan satu baris data lengkap dalam bentuk array asosiatif (misal: ['id_tenant' => 1, 'nama_tenant' => '...', 'id_pengguna' => '...', dsb])
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return null;
    }
  }

  public function updateOperationalStatus(string $id_tenant, string $status) {
    try {
      // Sesuaikan nama kolom status dan tabel dengan database kamu
      // Contoh: tabel 'tenant', kolom 'status' atau 'is_active', dan primary key 'id_tenant'
      $sql = "UPDATE tenant SET status_tenant = :status WHERE id_tenant = :id_tenant";
      $stmt = $this->pdo->prepare($sql);

      return $stmt->execute([
        ':status' => $status,
        ':id_tenant' => $id_tenant
      ]);
    } catch (PDOException $e) {
      return false;
    }
  }
}
