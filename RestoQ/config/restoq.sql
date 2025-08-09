-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2025 at 06:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restoq`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(100) NOT NULL,
  `stok` decimal(10,2) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `stok`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 'Daging Sapi', 50.00, 'kg', '2025-08-06 13:00:18', '2025-08-06 13:00:18');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `kuantitas`, `catatan`) VALUES
(1, 2, 4, 1, ''),
(2, 3, 1, 1, ''),
(4, 5, 1, 1, ''),
(5, 5, 2, 1, ''),
(6, 5, 18, 1, ''),
(7, 6, 3, 1, ''),
(8, 7, 2, 1, ''),
(9, 8, 1, 1, ''),
(10, 8, 6, 1, ''),
(11, 9, 1, 1, ''),
(12, 10, 3, 1, ''),
(13, 10, 5, 1, ''),
(14, 11, 7, 1, ''),
(15, 11, 15, 1, ''),
(16, 12, 1, 1, ''),
(17, 13, 3, 1, ''),
(18, 14, 3, 1, ''),
(19, 15, 3, 1, ''),
(20, 16, 3, 1, ''),
(21, 17, 3, 1, ''),
(22, 18, 1, 1, ''),
(23, 18, 7, 1, ''),
(24, 19, 3, 1, ''),
(25, 20, 3, 1, ''),
(26, 20, 17, 1, ''),
(27, 20, 27, 1, ''),
(28, 21, 1, 1, ''),
(29, 22, 1, 1, ''),
(30, 22, 16, 1, ''),
(31, 22, 26, 1, ''),
(32, 23, 1, 1, ''),
(33, 23, 16, 1, ''),
(34, 23, 26, 1, ''),
(35, 24, 1, 1, ''),
(36, 24, 16, 1, ''),
(37, 24, 26, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `id_meja` int(11) NOT NULL,
  `nomor_meja` int(11) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `status` enum('tersedia','terpakai') NOT NULL DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id_meja`, `nomor_meja`, `kapasitas`, `status`) VALUES
(1, 1, 1, 'terpakai'),
(2, 2, 1, 'terpakai'),
(3, 3, 1, 'terpakai'),
(4, 4, 1, 'terpakai'),
(5, 5, 1, 'terpakai'),
(6, 6, 2, 'terpakai'),
(7, 7, 2, 'terpakai'),
(8, 8, 2, 'terpakai'),
(9, 9, 2, 'terpakai'),
(10, 10, 2, 'terpakai'),
(11, 11, 2, 'terpakai'),
(12, 12, 2, 'terpakai'),
(13, 13, 2, 'terpakai'),
(14, 14, 4, 'terpakai'),
(15, 15, 4, 'terpakai'),
(16, 16, 4, 'terpakai'),
(17, 17, 4, 'terpakai'),
(18, 18, 4, 'terpakai'),
(19, 19, 4, 'terpakai'),
(20, 20, 4, 'terpakai'),
(21, 21, 6, 'terpakai'),
(22, 22, 6, 'terpakai'),
(23, 23, 6, 'terpakai'),
(24, 24, 6, 'terpakai'),
(25, 25, 6, 'tersedia'),
(26, 26, 8, 'tersedia'),
(27, 27, 8, 'tersedia'),
(28, 28, 8, 'tersedia'),
(29, 29, 10, 'tersedia'),
(30, 30, 10, 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `kategori` enum('makanan','minuman','snack') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','tidak tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `harga`, `kategori`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nasi Goreng Special', 25000.00, 'makanan', 'Nasi goreng dengan telur, ayam, dan sayuran', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(2, 'Mie Ayam', 20000.00, 'makanan', 'Mie dengan potongan ayam dan bakso', 'tersedia', '2025-08-06 07:21:24', '2025-08-09 13:53:21'),
(3, 'Ayam Bakar', 35000.00, 'makanan', 'Ayam bakar bumbu kecap', 'tersedia', '2025-08-06 07:21:24', '2025-08-09 15:21:37'),
(4, 'Nasi Uduk', 18000.00, 'makanan', 'Nasi uduk dengan lauk pilihan', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(5, 'Soto Ayam', 22000.00, 'makanan', 'Soto ayam dengan kuah kuning', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(6, 'Bakso Special', 25000.00, 'makanan', 'Bakso daging dengan mie dan pangsit', 'tersedia', '2025-08-06 07:21:24', '2025-08-09 04:40:09'),
(7, 'Nasi Campur', 28000.00, 'makanan', 'Nasi dengan berbagai lauk pilihan', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(8, 'Gado-Gado', 20000.00, 'makanan', 'Sayuran dengan bumbu kacang', 'tersedia', '2025-08-06 07:21:24', '2025-08-09 12:54:26'),
(9, 'Rawon', 30000.00, 'makanan', 'Rawon daging dengan kuah hitam', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(10, 'Sate Ayam', 28000.00, 'makanan', 'Sate ayam dengan bumbu kacang', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(11, 'Nasi Kuning', 20000.00, 'makanan', 'Nasi kuning dengan lauk tradisional', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(12, 'Ikan Bakar', 40000.00, 'makanan', 'Ikan bakar bumbu special', 'tersedia', '2025-08-06 07:21:24', '2025-08-09 04:40:15'),
(13, 'Rendang', 35000.00, 'makanan', 'Rendang daging sapi asli Padang', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(14, 'Capcay', 25000.00, 'makanan', 'Capcay sayuran dengan seafood', 'tersedia', '2025-08-06 07:21:24', '2025-08-09 04:40:11'),
(15, 'Nasi Goreng Seafood', 30000.00, 'makanan', 'Nasi goreng dengan campuran seafood', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(16, 'Es Teh Manis', 5000.00, 'minuman', 'Teh manis dingin segar', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(17, 'Jus Jeruk', 12000.00, 'minuman', 'Jus jeruk segar tanpa gula tambahan', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(18, 'Kopi Hitam', 8000.00, 'minuman', 'Kopi hitam tanpa gula', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(19, 'Es Jeruk', 10000.00, 'minuman', 'Es jeruk segar dengan gula', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(20, 'Jus Alpukat', 15000.00, 'minuman', 'Jus alpukat dengan susu kental', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(21, 'Es Campur', 12000.00, 'minuman', 'Es campur dengan berbagai buah', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(22, 'Teh Tarik', 10000.00, 'minuman', 'Teh tarik khas Malaysia', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(23, 'Jus Mangga', 15000.00, 'minuman', 'Jus mangga segar', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(24, 'Air Mineral', 5000.00, 'minuman', 'Air mineral kemasan', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(25, 'Es Cincau', 8000.00, 'minuman', 'Es cincau dengan gula merah', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(26, 'Risol Mayo', 5000.00, 'snack', 'Risol isi mayo dan sosis', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(27, 'Pisang Goreng', 10000.00, 'snack', 'Pisang goreng crispy', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(28, 'Tahu Isi', 12000.00, 'snack', 'Tahu goreng dengan isi sayuran', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(29, 'Lumpia', 15000.00, 'snack', 'Lumpia goreng dengan daging', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24'),
(30, 'Kentang Goreng', 12000.00, 'snack', 'Kentang goreng dengan saus', 'tersedia', '2025-08-06 07:21:24', '2025-08-06 07:21:24');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_meja` int(11) NOT NULL,
  `tanggal_pesanan` datetime DEFAULT current_timestamp(),
  `status` enum('pending','diproses','selesai','dibatalkan') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_meja`, `tanggal_pesanan`, `status`) VALUES
(2, 3, '2025-08-08 10:33:14', 'selesai'),
(3, 3, '2025-08-08 13:53:15', 'selesai'),
(5, 3, '2025-08-08 20:55:20', 'selesai'),
(6, 2, '2025-08-08 22:29:58', 'selesai'),
(7, 2, '2025-08-09 09:03:16', 'selesai'),
(8, 1, '2025-08-09 09:41:11', 'diproses'),
(9, 3, '2025-08-09 13:06:06', 'diproses'),
(10, 4, '2025-08-09 13:06:48', 'pending'),
(11, 5, '2025-08-09 13:22:10', 'pending'),
(12, 6, '2025-08-09 15:41:15', 'pending'),
(13, 7, '2025-08-09 15:47:24', 'pending'),
(14, 9, '2025-08-09 16:23:48', 'pending'),
(15, 11, '2025-08-09 16:37:46', 'pending'),
(16, 11, '2025-08-09 16:38:27', 'pending'),
(17, 13, '2025-08-09 16:52:53', 'pending'),
(18, 18, '2025-08-09 19:14:39', 'pending'),
(19, 19, '2025-08-09 19:21:39', 'pending'),
(20, 20, '2025-08-09 19:25:34', 'pending'),
(21, 21, '2025-08-09 21:40:20', 'pending'),
(22, 22, '2025-08-09 21:45:51', 'pending'),
(23, 23, '2025-08-09 21:54:36', 'pending'),
(24, 24, '2025-08-09 22:20:34', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id_rating` int(11) NOT NULL,
  `id_meja` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id_rating`, `id_meja`, `id_pesanan`, `rating`, `komentar`, `tanggal`) VALUES
(2, 5, 11, 5, '0', '2025-08-09 10:21:18'),
(3, 6, 12, 5, '0', '2025-08-09 10:41:47'),
(4, 20, 20, 5, '0', '2025-08-09 14:25:47'),
(5, 21, 21, 5, '0', '2025-08-09 16:40:27'),
(6, 22, 22, 5, '0', '2025-08-09 16:46:07'),
(7, 23, 23, 4, '0', '2025-08-09 16:54:57'),
(8, 24, 24, 5, '0', '2025-08-09 17:20:55');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `tanggal_transaksi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_pesanan`, `metode_pembayaran`, `total_bayar`, `tanggal_transaksi`) VALUES
(1, 2, 'tunai', 18000.00, '2025-08-08 18:44:59'),
(2, 3, 'tunai', 25000.00, '2025-08-08 18:45:30'),
(4, 5, 'tunai', 53000.00, '2025-08-09 20:47:53'),
(5, 6, 'kartu', 35000.00, '2025-08-09 20:47:56'),
(6, 7, 'qris', 20000.00, '2025-08-09 20:47:58'),
(7, 8, 'tunai', 50000.00, '2025-08-09 21:41:51'),
(8, 9, 'tunai', 25000.00, '2025-08-09 21:47:08'),
(9, 10, 'kartu', 57000.00, '2025-08-09 21:56:01'),
(10, 11, 'tunai', 58000.00, '2025-08-09 22:22:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('pelayan','koki','kasir','manajer','pelanggan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'pelayan', 'pelayan12345', 'pelayan', '2025-07-30 11:11:44', '2025-07-30 11:11:44'),
(2, 'koki', 'koki12345', 'koki', '2025-07-30 11:11:44', '2025-07-30 11:11:44'),
(3, 'kasir', 'kasir12345', 'kasir', '2025-07-30 11:11:44', '2025-07-30 11:11:44'),
(4, 'manajer', 'manajer12345', 'manajer', '2025-07-30 11:11:44', '2025-07-30 11:11:44'),
(5, 'pelanggan', 'pelanggan12345', 'pelanggan', '2025-07-30 11:11:44', '2025-07-30 11:11:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id_meja`),
  ADD UNIQUE KEY `nomor_meja` (`nomor_meja`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_meja` (`id_meja`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id_rating`),
  ADD KEY `id_meja` (`id_meja`),
  ADD KEY `fk_rating_pesanan` (`id_pesanan`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `id_meja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id_rating` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `fk_rating_pesanan` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE SET NULL,
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
