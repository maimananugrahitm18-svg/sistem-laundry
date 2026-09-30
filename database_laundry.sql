-- SISTEM LAUNDRY DATABASE DUMP FOR MYSQL / CPANEL
-- Generated on 2026-09-30 08:08:46
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'kasir',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE `pelanggan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pelanggan_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `layanan`;
CREATE TABLE `layanan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `estimasi_hari` int(11) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_invoice` varchar(50) NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `layanan_id` bigint(20) UNSIGNED NOT NULL,
  `berat_jumlah` decimal(8,2) NOT NULL,
  `total_biaya` decimal(12,2) NOT NULL,
  `jumlah_bayar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status_bayar` varchar(50) NOT NULL DEFAULT 'belum_bayar',
  `status_laundry` varchar(50) NOT NULL DEFAULT 'antrian',
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaksi_kode_invoice_unique` (`kode_invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengaturan_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `role`, `no_hp`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Owner / Admin', 'admin@gmail.com', 'owner', '081234567890', NULL, '$2y$12$Cx9.iDGG3odWpES6mH1wBOz9Pyy8cw7JqebVJ/OLnwGJtooJgvdU.', '99Uzqx6Ovi13tVEzu7OVmeAFVByCVETBUpdJFYS9nyrKBP70QLmCtyvynM4y', '2026-09-30 04:19:59', '2026-09-30 06:17:31');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `no_hp`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'Siti (Kasir / Petugas)', 'kasir@laundrypro.com', 'kasir', '082198765432', NULL, '$2y$12$O0l9lWsZuX104DuYNkYRlOI43wDuuf1dkQ/Bib/QJ5d6YjnyU58GC', 'm0yqtVVjzoB5pbqEppKAUTlOjCj2CKoEkVeRFTorYV9sPAJjhhHeMdmuBhaM', '2026-09-30 04:20:00', '2026-09-30 05:05:26');
INSERT INTO `users` (`id`, `name`, `email`, `role`, `no_hp`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('3', 'PEMILIK (Owner / Admin)', 'owner@laundrypro.com', 'owner', '081387654321', NULL, '$2y$12$c/UB7.P3mO21jAC/VTbhI.LPv7tGXaoimLVekR2T.QAHvsFs1vU7i', 'I6zeEs5Z0HTKEzy9u1JEeKE5U1QHXSqO8buHsd14WDltyotXYI8tKt83sD8w', '2026-09-30 04:20:01', '2026-09-30 06:18:34');

-- Data for table `pelanggan`
INSERT INTO `pelanggan` (`id`, `kode_pelanggan`, `nama`, `no_hp`, `alamat`, `email`, `jenis_kelamin`, `catatan`, `aktif`, `created_at`, `updated_at`) VALUES ('5', 'PLG-0001', 'Lili', '08657645453', 'Villa', NULL, 'P', NULL, '1', '2026-09-30 05:59:36', '2026-09-30 06:02:17');
INSERT INTO `pelanggan` (`id`, `kode_pelanggan`, `nama`, `no_hp`, `alamat`, `email`, `jenis_kelamin`, `catatan`, `aktif`, `created_at`, `updated_at`) VALUES ('6', 'PLG-0002', 'Mimin', '08657645453', 'THB', NULL, 'P', NULL, '1', '2026-09-30 06:03:05', '2026-09-30 06:03:05');

-- Data for table `layanan`
INSERT INTO `layanan` (`id`, `nama`, `ikon`, `harga_per_kg`, `estimasi_hari`, `deskripsi`, `status`, `urutan`, `created_at`, `updated_at`) VALUES ('1', 'Cuci Kering', '👕', '6500', '1', 'Cucian dicuci bersih menggunakan mesin, dikeringkan, dan dilipat rapi.', 'aktif', '1', '2026-09-30 04:20:01', '2026-09-30 05:57:59');
INSERT INTO `layanan` (`id`, `nama`, `ikon`, `harga_per_kg`, `estimasi_hari`, `deskripsi`, `status`, `urutan`, `created_at`, `updated_at`) VALUES ('2', 'Cuci + Setrika', '👔', '10000', '2', 'Paket komplit cuci bersih, kering, disetrika uap halus dan wangi.', 'aktif', '2', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `layanan` (`id`, `nama`, `ikon`, `harga_per_kg`, `estimasi_hari`, `deskripsi`, `status`, `urutan`, `created_at`, `updated_at`) VALUES ('3', 'Setrika Saja', '💨', '6000', '1', 'Hanya setrika uap rapi untuk pakaian yang sudah Anda cuci sendiri.', 'aktif', '3', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `layanan` (`id`, `nama`, `ikon`, `harga_per_kg`, `estimasi_hari`, `deskripsi`, `status`, `urutan`, `created_at`, `updated_at`) VALUES ('4', 'Express Kilat 6 Jam', '⚡', '15000', '1', 'Layanan kilat prioritas super cepat! Siap dalam 6 jam.', 'aktif', '4', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `layanan` (`id`, `nama`, `ikon`, `harga_per_kg`, `estimasi_hari`, `deskripsi`, `status`, `urutan`, `created_at`, `updated_at`) VALUES ('5', 'Bed Cover & Selimut', '🛏️', '25000', '2', 'Perawatan khusus bed cover, selimut tebal, sprei besar wangi higienis.', 'aktif', '5', '2026-09-30 04:20:01', '2026-09-30 04:20:01');

-- Data for table `transaksi`
INSERT INTO `transaksi` (`id`, `no_transaksi`, `pelanggan_id`, `layanan_id`, `user_id`, `berat`, `harga_per_kg`, `subtotal`, `diskon`, `total`, `metode_pembayaran`, `status_pembayaran`, `jumlah_dp`, `sisa_bayar`, `status`, `tanggal_masuk`, `estimasi_selesai`, `tanggal_selesai`, `catatan`, `catatan_batal`, `created_at`, `updated_at`) VALUES ('6', 'TRX-20260930-001', '5', '2', '2', '20', '10000', '200000', '0', '200000', 'Tunai', 'Lunas', '200000', '0', 'Selesai', '2026-09-30 00:00:00', '2026-10-02 00:00:00', NULL, 'Baju Putih Dipisah', NULL, '2026-09-30 06:01:08', '2026-09-30 06:19:49');
INSERT INTO `transaksi` (`id`, `no_transaksi`, `pelanggan_id`, `layanan_id`, `user_id`, `berat`, `harga_per_kg`, `subtotal`, `diskon`, `total`, `metode_pembayaran`, `status_pembayaran`, `jumlah_dp`, `sisa_bayar`, `status`, `tanggal_masuk`, `estimasi_selesai`, `tanggal_selesai`, `catatan`, `catatan_batal`, `created_at`, `updated_at`) VALUES ('7', 'TRX-20260930-002', '6', '5', '2', '2', '25000', '50000', '0', '50000', 'QRIS', 'Lunas', '50000', '0', 'Selesai', '2026-09-30 00:00:00', '2026-10-02 00:00:00', '2026-09-30 00:00:00', NULL, NULL, '2026-09-30 06:04:08', '2026-09-30 06:19:12');

-- Data for table `pengaturan`
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('1', 'nama_laundry', 'LaundryPro Express', '2026-09-30 04:20:01', '2026-09-30 05:05:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('2', 'slogan', 'Bersih, Wangi, Rapi, & Tepat Waktu', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('3', 'alamat', 'Jl. Merdeka Raya No. 45, Jakarta Pusat', '2026-09-30 04:20:01', '2026-09-30 05:05:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('4', 'telepon', '0812-3456-7890', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('5', 'email', 'support@laundrypro.com', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('6', 'footer_struk', 'Terima kasih atas kepercayaan Anda! Cucian bersih hidup ceria.', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('7', 'syarat_ketentuan', '1. Pengambilan cucian wajib membawa struk / nota ini. 2. Kerusakan/luntur akibat sifat bahan di luar tanggung jawab kami. 3. Komplain maksimal 1x24 jam setelah cucian diambil.', '2026-09-30 04:20:01', '2026-09-30 04:20:01');
INSERT INTO `pengaturan` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES ('8', 'logo', '/uploads/logo_1790749369.jpg', '2026-09-30 05:52:48', '2026-09-30 06:22:49');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
