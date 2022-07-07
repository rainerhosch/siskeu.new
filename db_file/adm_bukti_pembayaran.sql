-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2022 at 08:40 AM
-- Server version: 10.3.35-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wastudig_simak`
--

-- --------------------------------------------------------

--
-- Table structure for table `adm_bukti_pembayaran`
--

CREATE TABLE `adm_bukti_pembayaran` (
  `id_bukti_trf` int(11) NOT NULL,
  `smt` int(11) NOT NULL,
  `jenis_bayar` int(1) NOT NULL,
  `tgl_trf` date NOT NULL,
  `jam_trf` time NOT NULL,
  `rek_tujuan_trf` int(11) NOT NULL,
  `tgl_konfir` date NOT NULL,
  `jam_konfir` time NOT NULL,
  `nipd` int(11) NOT NULL,
  `id_jenis_bayar` varchar(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `img_trf` varchar(128) NOT NULL,
  `status` int(1) NOT NULL COMMENT '0=awal, 1=acc, 2=reject'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adm_bukti_pembayaran`
--

INSERT INTO `adm_bukti_pembayaran` (`id_bukti_trf`, `smt`, `jenis_bayar`, `tgl_trf`, `jam_trf`, `rek_tujuan_trf`, `tgl_konfir`, `jam_konfir`, `nipd`, `id_jenis_bayar`, `jumlah_bayar`, `img_trf`, `status`) VALUES
(1, 20212, 1, '2022-07-01', '17:03:42', 1, '2022-07-01', '17:04:01', 141351059, '2, 5', 1500000, 'bukti_trf_141351059_(Tanggal:2022-07-01).jpg', 0),
(3, 20212, 2, '2022-07-05', '15:41:39', 3, '2022-07-05', '15:41:42', 141351059, '12, 13', 750000, 'bukti_trf_141351059_(Tanggal:2022-07-05).jpg', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adm_bukti_pembayaran`
--
ALTER TABLE `adm_bukti_pembayaran`
  ADD PRIMARY KEY (`id_bukti_trf`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adm_bukti_pembayaran`
--
ALTER TABLE `adm_bukti_pembayaran`
  MODIFY `id_bukti_trf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
