-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2024 at 11:02 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikan`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id_akun` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `id_keranjang` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(16) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id_akun`, `nama`, `id_keranjang`, `email`, `password`, `alamat`, `isAdmin`) VALUES
(8, 'Winayagatar', 8, '123', '123', '123', 0),
(9, 'Admin Akhtar ', 9, 'admin', 'admin', ':OOO', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ikan`
--

CREATE TABLE `ikan` (
  `id_ikan` int(11) NOT NULL,
  `harga_ikan` int(11) DEFAULT NULL,
  `nama_ikan` varchar(20) DEFAULT NULL,
  `jenis_ikan` varchar(20) DEFAULT NULL,
  `gambar_ikan` varchar(40) DEFAULT NULL,
  `deskripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ikan`
--

INSERT INTO `ikan` (`id_ikan`, `harga_ikan`, `nama_ikan`, `jenis_ikan`, `gambar_ikan`, `deskripsi`) VALUES
(1, 50000, 'African Tiger', 'Tawar', 'africantiger.png', 'Ikan paling enak'),
(2, 60000, 'Beluga', 'Laut', 'Beluga.png', ''),
(3, 70000, 'Catfish', 'Tawar', 'Catfish.png', ''),
(4, 80000, 'Dendrofin', 'Tawar', 'dendrofin.png', ''),
(5, 90000, 'Permit', 'Laut', 'Permit.png', ''),
(6, 100000, 'Walleye', 'Laut', 'Walleye.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `item_pesanan`
--

CREATE TABLE `item_pesanan` (
  `id_item_pesanan` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_ikan` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_pesanan`
--

INSERT INTO `item_pesanan` (`id_item_pesanan`, `id_pesanan`, `id_ikan`, `quantity`, `total_harga`) VALUES
(1, 0, 1, 12, 600000.00),
(2, 2, 4, 1, 80000.00),
(3, 3, 6, 1, 100000.00),
(4, 4, 2, 1, 60000.00),
(5, 5, 2, 1, 60000.00),
(6, 6, 2, 1, 60000.00),
(7, 7, 2, 1, 60000.00),
(8, 8, 1, 3, 150000.00),
(9, 9, 6, 12, 1200000.00),
(10, 10, 1, 1, 50000.00),
(11, 11, 2, 1, 60000.00),
(12, 12, 2, 1, 60000.00),
(13, 13, 2, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_akun` int(11) DEFAULT NULL,
  `id_ikan` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_akun` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_akun`, `order_date`, `status`) VALUES
(2, 9, '2024-01-01 09:50:16', 'Pending'),
(3, 9, '2024-01-01 09:51:25', 'Canceled'),
(9, 9, '2024-01-01 10:38:01', 'Paid'),
(10, 8, '2024-01-01 10:46:40', 'Paid'),
(11, 8, '2024-01-01 10:48:17', 'Paid'),
(12, 8, '2024-01-01 10:49:19', 'Paid'),
(13, 9, '2024-01-01 11:00:47', 'Paid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`),
  ADD KEY `id_keranjang` (`id_keranjang`);

--
-- Indexes for table `ikan`
--
ALTER TABLE `ikan`
  ADD PRIMARY KEY (`id_ikan`);

--
-- Indexes for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD PRIMARY KEY (`id_item_pesanan`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_ikan` (`id_ikan`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_akun` (`id_akun`),
  ADD KEY `id_ikan` (`id_ikan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_akun` (`id_akun`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ikan`
--
ALTER TABLE `ikan`
  MODIFY `id_ikan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  MODIFY `id_item_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD CONSTRAINT `item_pesanan_ibfk_2` FOREIGN KEY (`id_ikan`) REFERENCES `ikan` (`id_ikan`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_ikan`) REFERENCES `ikan` (`id_ikan`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
