-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 09, 2025 at 04:31 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lapanganbola`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(3, 'test', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_buku`
--

INSERT INTO `tb_buku` (`id`, `judul`) VALUES
(1, 'Test 1'),
(2, 'Test 2');

-- --------------------------------------------------------

--
-- Table structure for table `tb_lapangan`
--

CREATE TABLE `tb_lapangan` (
  `id_lapangan` int NOT NULL,
  `nama_lapangan` varchar(255) NOT NULL,
  `tipe_lapangan` varchar(255) NOT NULL,
  `lokasi` text NOT NULL,
  `harga_per_jam` int NOT NULL,
  `status_lapangan` varchar(255) NOT NULL,
  `gambar_lapangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_lapangan`
--

INSERT INTO `tb_lapangan` (`id_lapangan`, `nama_lapangan`, `tipe_lapangan`, `lokasi`, `harga_per_jam`, `status_lapangan`, `gambar_lapangan`) VALUES
(6, 'Lapangan Dubai', 'Rumput Sintetis', 'Sukamaju 13', 50000, 'Tersedia', '673993bca41d9.jpeg'),
(10, 'Lapangan Maroko', 'Vinyl', 'Btp', 75000, 'Pemeliharaan', '6737826bbb3b8.jpeg'),
(11, 'Lapangan NTI', 'Parquette', 'NTI', 85000, 'Tersedia', '673783365d817.jpeg'),
(12, 'Lapangan Blok H', 'Semen', 'Btp', 35000, 'Tersedia', '67378351c0e49.jpeg'),
(13, 'Lapangan Sukaria', 'Parket Kayu', 'Sukaria', 65000, 'Pemeliharaan', '673783faf169d.webp'),
(14, 'Lapangan NHP', 'Karpet Plastik', 'NHP', 77000, 'Pemeliharaan', '673784b94e462.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_user` int NOT NULL,
  `status_pembayaran` varchar(255) NOT NULL,
  `total_bayar` int NOT NULL,
  `metode_pembayaran` varchar(255) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `id_pesanan`, `id_user`, `status_pembayaran`, `total_bayar`, `metode_pembayaran`, `tanggal_pembayaran`, `bukti_pembayaran`) VALUES
(4, 34, 15, 'Dikonfirmasi', 150000, '', '2024-11-07', '1730965225_logo_undipa.png'),
(8, 38, 15, 'Dikonfirmasi', 200000, '', '2024-11-17', '1731826694_test.jpg'),
(9, 39, 15, 'Dikonfirmasi', 255000, '', '2024-11-17', '1731827820_american-football-5524917.png'),
(10, 40, 17, 'Dikonfirmasi', 105000, '', '2024-11-17', '1731840482_Clock.png'),
(12, 43, 17, 'Pending', 100000, '', '2024-11-20', '1732063942_american-football-5524917.png'),
(13, 44, 17, 'Dikonfirmasi', 100000, '', '2024-11-22', '1732219464_american-football-5524917.png'),
(14, 45, 17, 'Dikonfirmasi', 100000, '', '2024-11-22', '1732219532_weed-aesthetic-apo7wt6sih9fmdyj.jpg'),
(15, 46, 17, 'Pending', 100000, '', '2024-11-22', '1732220336_american-football-5524917.png'),
(16, 47, 17, 'Pending', 100000, '', '2024-11-22', '1732220375_parquette.jpeg'),
(17, 48, 17, 'Pending', 100000, '', '2024-11-22', '1732220531_Clock.png'),
(18, 49, 17, 'Pending', 100000, '', '2024-11-22', '1732220733_Clock.png'),
(19, 50, 17, 'Pending', 100000, '', '2024-11-22', '1732220776_Clock.png'),
(20, 51, 15, 'Dikonfirmasi', 100000, '', '2024-11-22', '1732220845_Clock.png'),
(21, 52, 17, 'Pending', 170000, '', '2024-11-22', '1732222043_american-football-5524917.png'),
(23, 54, 17, 'Dikonfirmasi', 100000, '', '2024-12-04', '1733269285_claudio-luiz-castro-_R95VMWyn7A-unsplash.jpg'),
(24, 55, 15, 'Dikonfirmasi', 100000, '', '2024-12-04', '1733269973_akun git.png'),
(25, 56, 15, 'Dikonfirmasi', 105000, '', '2024-12-07', '1733529167_Cuplikan layar 2023-05-28 200653.png'),
(26, 64, 13, 'Pending', 85000, '', '2024-12-27', '1735315127_duku.jpg'),
(27, 65, 13, 'Pending', 70000, '', '2024-12-28', '1735315642_melon.jpg'),
(28, 66, 13, 'Pending', 35000, '', '2024-12-28', '1735315858_nanas.jpg'),
(29, 67, 13, 'Pending', 35000, '', '2024-12-28', '1735316267_logo.png'),
(30, 68, 13, 'Pending', 100000, 'E-Wallet', '2024-12-28', '1735316483_duku.jpg'),
(31, 69, 13, 'Pending', 50000, 'DANA', '2024-12-28', '1735316672_duku.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int NOT NULL,
  `id_user` int NOT NULL,
  `id_lapangan` int NOT NULL,
  `tanggal_pesanan` date NOT NULL,
  `jam_mulai` varchar(255) NOT NULL,
  `jam_selesai` varchar(255) NOT NULL,
  `total_biaya` double NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pesanan`
--

INSERT INTO `tb_pesanan` (`id_pesanan`, `id_user`, `id_lapangan`, `tanggal_pesanan`, `jam_mulai`, `jam_selesai`, `total_biaya`, `status`) VALUES
(34, 15, 10, '2024-11-07', '15:40', '18:40', 150000, 'Sudah Main'),
(38, 15, 6, '2024-11-19', '14:57', '18:57', 200000, 'Sudah Main'),
(39, 15, 11, '2024-11-18', '15:16', '18:16', 255000, 'Belum Main'),
(40, 17, 12, '2024-11-22', '18:47', '21:47', 105000, 'Belum Main'),
(43, 17, 6, '2024-11-21', '07:51', '09:51', 100000, 'Belum Main'),
(44, 17, 6, '2024-11-22', '19:03', '21:03', 100000, 'Belum Main'),
(45, 17, 6, '2024-11-22', '19:04', '21:04', 100000, 'Belum Main'),
(46, 17, 6, '2024-11-22', '07:18', '09:18', 100000, 'Belum Main'),
(47, 17, 6, '2024-11-22', '07:19', '09:19', 100000, 'Belum Main'),
(48, 17, 6, '2024-11-22', '07:21', '09:21', 100000, 'Belum Main'),
(49, 17, 6, '2024-11-22', '07:25', '09:25', 100000, 'Belum Main'),
(50, 17, 6, '2024-11-22', '07:25', '09:25', 100000, 'Belum Main'),
(51, 15, 6, '2024-11-22', '07:25', '09:25', 100000, 'Belum Main'),
(52, 17, 11, '2024-11-22', '07:46', '09:46', 170000, 'Belum Main'),
(53, 17, 12, '2024-11-22', '04:48', '05:48', 35000, 'Belum Main'),
(54, 17, 6, '2024-12-04', '07:40', '09:40', 100000, 'Sudah Main'),
(55, 15, 6, '2024-12-04', '07:52', '09:52', 100000, 'Belum Main'),
(56, 15, 12, '2025-01-01', '07:52', '10:52', 105000, 'Belum Main'),
(64, 13, 11, '2024-12-27', '15:58', '16:58', 85000, 'Belum Main'),
(65, 13, 12, '2024-12-29', '01:06', '03:06', 70000, 'Belum Main'),
(66, 13, 12, '2024-12-28', '05:10', '06:10', 35000, 'Belum Main'),
(67, 13, 12, '2024-12-28', '06:14', '07:14', 35000, 'Belum Main'),
(68, 13, 6, '2024-12-28', '01:21', '03:21', 100000, 'Belum Main'),
(69, 13, 6, '2024-12-28', '05:24', '06:24', 50000, 'Belum Main');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ulasan`
--

CREATE TABLE `tb_ulasan` (
  `id_ulasan` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `ulasan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_ulasan`
--

INSERT INTO `tb_ulasan` (`id_ulasan`, `nama`, `email`, `no_telp`, `ulasan`) VALUES
(2, 'awal', 'aku@gmail.com', '0981921921', 'mantap');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL,
  `no_telp` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama`, `email`, `no_telp`, `password`) VALUES
(13, 'Abdull', 'test@gmail.com', '087766554433', '$2y$10$gLEzCBOEJNf1Z0ivPcsF.upVaU53bHKA4ryGEHbICSmWPRqbX5/yW'),
(15, 'awal', 'awal@gmail.com', '0899776655', '$2y$10$pj7ZuzODcL6Stx1kkLLFOOaPWKFSDtJZDWm1STBIMHTgvrM2GUE2S'),
(16, 'oca', 'oca@gmail.com', '08123456789', '$2y$10$2cWjgjTW7SOXtdcFn7mjEeioeB.W6P98V2dg3DVhprk0aMwNhzbmS'),
(17, 'Muhammad Abdul Rozzaq', 'abdulrozzaqmuh@gmail.com', '081262210147', '$2y$10$gpLbDpQphU4KhLbAhPG03.9SweKmXNJ3B514NLTcQoy5s.uKVjuFW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_lapangan`
--
ALTER TABLE `tb_lapangan`
  ADD PRIMARY KEY (`id_lapangan`);

--
-- Indexes for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indexes for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  ADD PRIMARY KEY (`id_ulasan`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_buku`
--
ALTER TABLE `tb_buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_lapangan`
--
ALTER TABLE `tb_lapangan`
  MODIFY `id_lapangan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tb_pembayaran_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD CONSTRAINT `tb_pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_pesanan_ibfk_2` FOREIGN KEY (`id_lapangan`) REFERENCES `tb_lapangan` (`id_lapangan`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
