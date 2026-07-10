-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 10, 2026 at 10:58 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.32

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
(1, 'admin', 'admin');

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
  `total_bayar` decimal(12,2) NOT NULL DEFAULT '0.00',
  `metode_pembayaran` varchar(255) NOT NULL,
  `tanggal_pembayaran` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bukti_pembayaran` varchar(255) NOT NULL DEFAULT '',
  `snap_token` varchar(255) DEFAULT '',
  `transaksi_id_midtrans` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

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
-- AUTO_INCREMENT for table `tb_lapangan`
--
ALTER TABLE `tb_lapangan`
  MODIFY `id_lapangan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tb_ulasan`
--
ALTER TABLE `tb_ulasan`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `tb_pembayaran_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD CONSTRAINT `tb_pesanan_ibfk_2` FOREIGN KEY (`id_lapangan`) REFERENCES `tb_lapangan` (`id_lapangan`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tb_pesanan_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
