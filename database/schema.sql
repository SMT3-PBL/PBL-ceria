-- =====================================================================
-- Skema database: Sistem Pemesanan Multi-Tenant Berbasis QR Code Meja
-- DBMS : PostgreSQL 10 atau lebih baru (memakai GENERATED ... AS IDENTITY)
-- Cara : jalankan sekali pada database yang masih kosong
-- =====================================================================

BEGIN;

-- ---------------------------------------------------------------------
-- 1. Pengguna (akun admin dan seller)
-- ---------------------------------------------------------------------
CREATE TABLE pengguna (
  id_pengguna   INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nama          VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          VARCHAR(20)  NOT NULL DEFAULT 'seller'
                CHECK (role IN ('admin', 'seller'))
);

-- ---------------------------------------------------------------------
-- 2. Tenant (dikelola oleh satu akun pengguna)
-- ---------------------------------------------------------------------
CREATE TABLE tenant (
  id_tenant     INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nama_tenant   VARCHAR(100) NOT NULL,
  deskripsi     TEXT,
  status_tenant VARCHAR(10)  NOT NULL DEFAULT 'tutup'
                CHECK (status_tenant IN ('buka', 'tutup')),
  qr_pembayaran TEXT,
  id_pengguna   INTEGER NOT NULL UNIQUE
                REFERENCES pengguna (id_pengguna)
);

-- ---------------------------------------------------------------------
-- 3. Menu (milik satu tenant)
-- ---------------------------------------------------------------------
CREATE TABLE menu (
  id_menu     INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nama_menu   VARCHAR(100) NOT NULL,
  deskripsi   TEXT,
  harga       NUMERIC(12, 2) NOT NULL CHECK (harga >= 0),
  kategori    VARCHAR(50),
  status_menu VARCHAR(10) NOT NULL DEFAULT 'tersedia'
              CHECK (status_menu IN ('tersedia', 'habis')),
  id_tenant   INTEGER NOT NULL
              REFERENCES tenant (id_tenant) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------
-- 4. Sesi pembeli (dibuat saat QR code meja dipindai)
-- ---------------------------------------------------------------------
CREATE TABLE sesi_pembeli (
  id_sesi          INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  token_sesi_hash  VARCHAR(255) NOT NULL UNIQUE,
  dibuat_pada      TIMESTAMPTZ  NOT NULL DEFAULT now(),
  kadaluwarsa_pada TIMESTAMPTZ  NOT NULL,
  status_sesi      VARCHAR(15)  NOT NULL DEFAULT 'aktif'
                   CHECK (status_sesi IN ('aktif', 'selesai', 'kedaluwarsa')),
  no_meja          VARCHAR(10)  NOT NULL,
  nama             VARCHAR(100)
);

-- ---------------------------------------------------------------------
-- 5. Pesanan (satu pesanan = satu tenant = satu sesi pembeli)
-- ---------------------------------------------------------------------
CREATE TABLE pesanan (
  id_pesanan      INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  tanggal_pesanan TIMESTAMPTZ NOT NULL DEFAULT now(),
  no_antrean      INTEGER     NOT NULL CHECK (no_antrean > 0),
  tanggal_antrean DATE        NOT NULL DEFAULT CURRENT_DATE,
  status_pesanan  VARCHAR(25) NOT NULL DEFAULT 'menunggu_pembayaran'
                  CHECK (status_pesanan IN (
                    'menunggu_pembayaran',
                    'diterima',
                    'diproses',
                    'siap_diambil',
                    'selesai',
                    'dibatalkan'
                  )),
  id_tenant       INTEGER NOT NULL REFERENCES tenant (id_tenant),
  id_sesi         INTEGER NOT NULL REFERENCES sesi_pembeli (id_sesi),
  -- nomor antrean tidak boleh kembar pada tenant dan hari yang sama
  UNIQUE (id_tenant, tanggal_antrean, no_antrean)
);

-- ---------------------------------------------------------------------
-- 6. Detail pesanan (nama dan harga disalin saat pesanan dibuat)
-- ---------------------------------------------------------------------
CREATE TABLE detail_pesanan (
  id_detail    INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nama_menu    VARCHAR(100)   NOT NULL,
  harga_satuan NUMERIC(12, 2) NOT NULL CHECK (harga_satuan >= 0),
  jumlah       INTEGER        NOT NULL CHECK (jumlah > 0),
  subtotal     NUMERIC(12, 2) NOT NULL CHECK (subtotal >= 0),
  id_pesanan   INTEGER NOT NULL
               REFERENCES pesanan (id_pesanan) ON DELETE CASCADE,
  -- jika menu dihapus, riwayat pesanan tetap ada (id_menu menjadi NULL)
  id_menu      INTEGER
               REFERENCES menu (id_menu) ON DELETE SET NULL
);

-- ---------------------------------------------------------------------
-- 7. Pembayaran (satu pesanan memiliki satu pembayaran)
-- ---------------------------------------------------------------------
CREATE TABLE pembayaran (
  id_pembayaran     INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  metode_bayar      VARCHAR(10)    NOT NULL
                    CHECK (metode_bayar IN ('qris', 'tunai')),
  jumlah_bayar      NUMERIC(12, 2) NOT NULL CHECK (jumlah_bayar >= 0),
  bukti_bayar       TEXT,
  status_pembayaran VARCHAR(15)    NOT NULL DEFAULT 'belum_lunas'
                    CHECK (status_pembayaran IN ('belum_lunas', 'lunas')),
  waktu_konfirmasi  TIMESTAMPTZ,
  id_pesanan        INTEGER NOT NULL UNIQUE
                    REFERENCES pesanan (id_pesanan) ON DELETE CASCADE
);

-- ---------------------------------------------------------------------
-- Indeks untuk query yang sering dipakai
-- ---------------------------------------------------------------------
CREATE INDEX idx_menu_tenant           ON menu (id_tenant);
CREATE INDEX idx_pesanan_tenant_status ON pesanan (id_tenant, status_pesanan);
CREATE INDEX idx_pesanan_sesi          ON pesanan (id_sesi);
CREATE INDEX idx_detail_pesanan        ON detail_pesanan (id_pesanan);

COMMIT;