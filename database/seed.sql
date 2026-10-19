-- =====================================================================
-- Data dummy: Sistem Pemesanan Multi-Tenant Berbasis QR Code Meja
-- Syarat : schema.sql sudah dijalankan, dan tabel masih kosong
-- Cara   : jalankan sekali setelah schema.sql
--
-- Akun dummy (semua memakai password: password123)
--   admin@kantin.test   -> admin
--   joko@kantin.test    -> seller, Ayam Geprek Mas Joko
--   kunto@kantin.test   -> seller, Bakso Solo Pak Kunto
--   siti@kantin.test    -> seller, Es Teh & Minuman
--   asih@kantin.test    -> seller, Soto Madura Ibu Asih (tenant tutup)
--
-- Untuk mengulang dari awal (HAPUS SEMUA DATA, beri tahu tim dulu):
-- TRUNCATE pembayaran, detail_pesanan, pesanan, sesi_pembeli, menu,
--          tenant, pengguna RESTART IDENTITY CASCADE;
-- =====================================================================

BEGIN;

-- ---------------------------------------------------------------------
-- 1. Pengguna
-- ---------------------------------------------------------------------
INSERT INTO pengguna (nama, email, password_hash, role) VALUES
  ('Admin Kantin', 'admin@kantin.test', '$2b$12$fTQkZVbjudrbWOylkdGww.pYC2qI1/G9g312jNDjMpbOggvoudWKm', 'admin'),
  ('Joko Pranoto', 'joko@kantin.test',  '$2b$12$fTQkZVbjudrbWOylkdGww.pYC2qI1/G9g312jNDjMpbOggvoudWKm', 'seller'),
  ('Kunto Wibisono', 'kunto@kantin.test', '$2b$12$fTQkZVbjudrbWOylkdGww.pYC2qI1/G9g312jNDjMpbOggvoudWKm', 'seller'),
  ('Siti Rahma', 'siti@kantin.test',   '$2b$12$fTQkZVbjudrbWOylkdGww.pYC2qI1/G9g312jNDjMpbOggvoudWKm', 'seller'),
  ('Asih Rahayu', 'asih@kantin.test',  '$2b$12$fTQkZVbjudrbWOylkdGww.pYC2qI1/G9g312jNDjMpbOggvoudWKm', 'seller');

-- ---------------------------------------------------------------------
-- 2. Tenant (satu tenant per akun seller)
-- ---------------------------------------------------------------------
INSERT INTO tenant (nama_tenant, deskripsi, status_tenant, qr_pembayaran, id_pengguna)
SELECT v.nama_tenant, v.deskripsi, v.status_tenant, v.qr_pembayaran, p.id_pengguna
FROM (VALUES
  ('Ayam Geprek Mas Joko', 'Spesialis kuliner ayam renyah digeprek dengan sambal bawang pedas.', 'buka',  'uploads/qris/mas-joko.png',  'joko@kantin.test'),
  ('Bakso Solo Pak Kunto', 'Bakso urat dan bakso halus khas Solo dengan kuah gurih.',           'buka',  'uploads/qris/pak-kunto.png', 'kunto@kantin.test'),
  ('Es Teh & Minuman',     'Aneka minuman dingin untuk menemani makan siang.',                  'buka',  NULL,                         'siti@kantin.test'),
  ('Soto Madura Ibu Asih', 'Soto Madura dengan bumbu rempah pilihan.',                          'tutup', 'uploads/qris/ibu-asih.png',  'asih@kantin.test')
) AS v(nama_tenant, deskripsi, status_tenant, qr_pembayaran, email)
JOIN pengguna p ON p.email = v.email;

-- ---------------------------------------------------------------------
-- 3. Menu
-- ---------------------------------------------------------------------
INSERT INTO menu (nama_menu, deskripsi, harga, kategori, status_menu, id_tenant)
SELECT v.nama_menu, v.deskripsi, v.harga, v.kategori, v.status_menu, t.id_tenant
FROM (VALUES
  -- Ayam Geprek Mas Joko
  ('Ayam Geprek Level 3',          'Ayam crispy paha dan dada geprek sambal bawang level sedang.', 15000, 'Makanan Utama', 'tersedia', 'Ayam Geprek Mas Joko'),
  ('Ayam Geprek Keju Mozzarella',  'Geprek gurih dengan lelehan keju mozzarella.',            18000, 'Makanan Utama', 'tersedia', 'Ayam Geprek Mas Joko'),
  ('Paket Geprek Nasi + Es Teh',   'Ayam geprek, nasi putih, dan es teh manis.',              22000, 'Paket Hemat',   'tersedia', 'Ayam Geprek Mas Joko'),
  ('Kulit Ayam Crispy Pedas',      'Kulit ayam goreng super renyah dengan bumbu cabai.',      10000, 'Sisi & Ekstra', 'habis',    'Ayam Geprek Mas Joko'),
  ('Nasi Putih Pulen',             'Nasi putih hangat pulen.',                                 4000, 'Sisi & Ekstra', 'tersedia', 'Ayam Geprek Mas Joko'),
  -- Bakso Solo Pak Kunto
  ('Bakso Urat',                   'Bakso urat besar dengan kuah kaldu sapi.',                15000, 'Makanan Utama', 'tersedia', 'Bakso Solo Pak Kunto'),
  ('Bakso Campur',                 'Bakso halus, urat, tahu, dan pangsit.',                   18000, 'Makanan Utama', 'tersedia', 'Bakso Solo Pak Kunto'),
  ('Mie Ayam Bakso',               'Mie ayam dengan topping bakso.',                          17000, 'Makanan Utama', 'tersedia', 'Bakso Solo Pak Kunto'),
  ('Pangsit Goreng',               'Pangsit goreng isi ayam.',                                 8000, 'Sisi & Ekstra', 'tersedia', 'Bakso Solo Pak Kunto'),
  ('Teh Hangat',                   NULL,                                                       3000, 'Minuman',       'tersedia', 'Bakso Solo Pak Kunto'),
  -- Es Teh & Minuman
  ('Es Teh Manis Jumbo',           'Es teh manis ukuran jumbo.',                               4000, 'Minuman',       'tersedia', 'Es Teh & Minuman'),
  ('Es Jeruk',                     'Jeruk peras segar dengan es.',                             5000, 'Minuman',       'tersedia', 'Es Teh & Minuman'),
  ('Es Kopi Susu',                 'Kopi susu gula aren.',                                    12000, 'Minuman',       'tersedia', 'Es Teh & Minuman'),
  ('Jus Alpukat',                  'Jus alpukat dengan cokelat.',                             13000, 'Minuman',       'habis',    'Es Teh & Minuman'),
  ('Air Mineral',                  NULL,                                                       3000, 'Minuman',       'tersedia', 'Es Teh & Minuman'),
  -- Soto Madura Ibu Asih
  ('Soto Madura',                  'Soto daging dengan koya dan sambal.',                     17000, 'Makanan Utama', 'tersedia', 'Soto Madura Ibu Asih'),
  ('Soto Ayam',                    'Soto ayam kuah kuning.',                                  15000, 'Makanan Utama', 'tersedia', 'Soto Madura Ibu Asih'),
  ('Nasi Putih',                   NULL,                                                       4000, 'Sisi & Ekstra', 'tersedia', 'Soto Madura Ibu Asih'),
  ('Kerupuk',                      NULL,                                                       2000, 'Sisi & Ekstra', 'tersedia', 'Soto Madura Ibu Asih')
) AS v(nama_menu, deskripsi, harga, kategori, status_menu, nama_tenant)
JOIN tenant t ON t.nama_tenant = v.nama_tenant;

-- ---------------------------------------------------------------------
-- 4. Sesi pembeli (token_sesi_hash berisi hash dummy)
-- ---------------------------------------------------------------------
INSERT INTO sesi_pembeli (token_sesi_hash, dibuat_pada, kadaluwarsa_pada, status_sesi, no_meja, nama) VALUES
  ('3396e213d2123bceefec35a5194e49ff03db0347d7476d6a62b9160ff9779694', now() - interval '40 minutes',  now() + interval '80 minutes',  'aktif',       '12', 'Rizky Pratama'),
  ('11b3a961cc873069b9880fe2a58117a2ef12b7cc2e5e6dffd539ffb11c898c57', now() - interval '25 minutes',  now() + interval '95 minutes',  'aktif',       '5',  'Dewi Lestari'),
  ('11c65d9d7de876346e35f3c8f3f8d9af4714c0494ca883cfe7630e8c6b337091', now() - interval '3 hours',     now() - interval '1 hour',      'selesai',     '3',  'Bagas Saputra'),
  ('fba8758c9ac349ad764ba2493cccdf6c1223421c8f2215cd244c6f7e5576c87d', now() - interval '15 minutes',  now() + interval '105 minutes', 'aktif',       '7',  'Putri Ayu'),
  ('c199df25af435fd567de428a61f64523c4cb644aca6dcb47326464c1bfacdcc4', now() - interval '1 day',       now() - interval '22 hours',    'kedaluwarsa', '9',  NULL);

-- ---------------------------------------------------------------------
-- 5. Pesanan
--    Sesi meja 12 memesan ke dua tenant (contoh pemisahan pesanan)
-- ---------------------------------------------------------------------
INSERT INTO pesanan (tanggal_pesanan, no_antrean, status_pesanan, id_tenant, id_sesi)
SELECT v.tanggal_pesanan, v.no_antrean, v.status_pesanan, t.id_tenant, s.id_sesi
FROM (VALUES
  (now() - interval '30 minutes', 1, 'diproses',            'Ayam Geprek Mas Joko', '3396e213d2123bceefec35a5194e49ff03db0347d7476d6a62b9160ff9779694'),
  (now() - interval '20 minutes', 2, 'diterima',            'Ayam Geprek Mas Joko', '11b3a961cc873069b9880fe2a58117a2ef12b7cc2e5e6dffd539ffb11c898c57'),
  (now() - interval '28 minutes', 1, 'siap_diambil',        'Es Teh & Minuman',     '3396e213d2123bceefec35a5194e49ff03db0347d7476d6a62b9160ff9779694'),
  (now() - interval '2 hours',    1, 'selesai',             'Bakso Solo Pak Kunto', '11c65d9d7de876346e35f3c8f3f8d9af4714c0494ca883cfe7630e8c6b337091'),
  (now() - interval '10 minutes', 2, 'menunggu_pembayaran', 'Bakso Solo Pak Kunto', 'fba8758c9ac349ad764ba2493cccdf6c1223421c8f2215cd244c6f7e5576c87d'),
  (now() - interval '12 minutes', 1, 'dibatalkan',          'Soto Madura Ibu Asih', 'fba8758c9ac349ad764ba2493cccdf6c1223421c8f2215cd244c6f7e5576c87d')
) AS v(tanggal_pesanan, no_antrean, status_pesanan, nama_tenant, token)
JOIN tenant t       ON t.nama_tenant = v.nama_tenant
JOIN sesi_pembeli s ON s.token_sesi_hash = v.token;

-- ---------------------------------------------------------------------
-- 6. Detail pesanan (subtotal = harga_satuan x jumlah)
-- ---------------------------------------------------------------------
INSERT INTO detail_pesanan (nama_menu, harga_satuan, jumlah, subtotal, id_pesanan, id_menu)
SELECT v.nama_menu, v.harga_satuan, v.jumlah, v.harga_satuan * v.jumlah, p.id_pesanan, m.id_menu
FROM (VALUES
  ('Ayam Geprek Mas Joko', 1, 'Ayam Geprek Level 3',        15000, 2),
  ('Ayam Geprek Mas Joko', 1, 'Kulit Ayam Crispy Pedas',    10000, 1),
  ('Ayam Geprek Mas Joko', 2, 'Paket Geprek Nasi + Es Teh', 22000, 1),
  ('Es Teh & Minuman',     1, 'Es Teh Manis Jumbo',          4000, 2),
  ('Bakso Solo Pak Kunto', 1, 'Bakso Urat',                 15000, 2),
  ('Bakso Solo Pak Kunto', 2, 'Bakso Campur',               18000, 1),
  ('Soto Madura Ibu Asih', 1, 'Soto Madura',                17000, 1)
) AS v(nama_tenant, no_antrean, nama_menu, harga_satuan, jumlah)
JOIN tenant t  ON t.nama_tenant = v.nama_tenant
JOIN pesanan p ON p.id_tenant = t.id_tenant AND p.no_antrean = v.no_antrean
JOIN menu m    ON m.id_tenant = t.id_tenant AND m.nama_menu = v.nama_menu;

-- ---------------------------------------------------------------------
-- 7. Pembayaran (jumlah_bayar = total detail pesanan)
-- ---------------------------------------------------------------------
INSERT INTO pembayaran (metode_bayar, jumlah_bayar, bukti_bayar, status_pembayaran, waktu_konfirmasi, id_pesanan)
SELECT v.metode_bayar, v.jumlah_bayar, v.bukti_bayar, v.status_pembayaran, v.waktu_konfirmasi, p.id_pesanan
FROM (VALUES
  ('Ayam Geprek Mas Joko', 1, 'tunai', 40000, NULL,                            'lunas',        now() - interval '25 minutes'),
  ('Ayam Geprek Mas Joko', 2, 'qris',  22000, 'uploads/bukti/dewi-0001.jpg',   'lunas',        now() - interval '15 minutes'),
  ('Es Teh & Minuman',     1, 'qris',   8000, 'uploads/bukti/rizky-0002.jpg',  'lunas',        now() - interval '26 minutes'),
  ('Bakso Solo Pak Kunto', 1, 'qris',  30000, 'uploads/bukti/bagas-0003.jpg',  'lunas',        now() - interval '115 minutes'),
  ('Bakso Solo Pak Kunto', 2, 'tunai', 18000, NULL,                            'belum_lunas',  NULL::timestamptz),
  ('Soto Madura Ibu Asih', 1, 'tunai', 17000, NULL,                            'belum_lunas',  NULL::timestamptz)
) AS v(nama_tenant, no_antrean, metode_bayar, jumlah_bayar, bukti_bayar, status_pembayaran, waktu_konfirmasi)
JOIN tenant t  ON t.nama_tenant = v.nama_tenant
JOIN pesanan p ON p.id_tenant = t.id_tenant AND p.no_antrean = v.no_antrean;

COMMIT;

-- Pengecekan cepat (opsional):
-- SELECT t.nama_tenant, p.no_antrean, p.status_pesanan, pb.metode_bayar, pb.status_pembayaran
-- FROM pesanan p
-- JOIN tenant t ON t.id_tenant = p.id_tenant
-- JOIN pembayaran pb ON pb.id_pesanan = p.id_pesanan
-- ORDER BY t.nama_tenant, p.no_antrean;