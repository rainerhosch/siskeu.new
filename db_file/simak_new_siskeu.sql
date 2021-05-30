-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Bulan Mei 2021 pada 11.17
-- Versi server: 10.4.19-MariaDB
-- Versi PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_newsiskeu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `biaya_angkatan`
--

CREATE TABLE `biaya_angkatan` (
  `id` int(11) NOT NULL,
  `angkatan` int(11) NOT NULL,
  `UB` int(9) NOT NULL,
  `kmhs` int(9) NOT NULL,
  `kmhs_D3` int(9) NOT NULL,
  `CS` int(9) NOT NULL,
  `CS_D3` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `biaya_angkatan`
--

INSERT INTO `biaya_angkatan` (`id`, `angkatan`, `UB`, `kmhs`, `kmhs_D3`, `CS`, `CS_D3`) VALUES
(1, 2007, 0, 75000, 75000, 2400000, 2550000),
(2, 2008, 0, 80000, 80000, 2550000, 2700000),
(3, 2009, 0, 85000, 85000, 2700000, 2550000),
(4, 2010, 0, 90000, 90000, 2700000, 2550000),
(5, 2011, 100000, 90000, 90000, 3000000, 2700000),
(6, 2012, 500000, 100000, 100000, 3000000, 2700000),
(7, 2013, 1000000, 125000, 125000, 3750000, 3000000),
(8, 2014, 1000000, 150000, 150000, 4050000, 3300000),
(10, 2015, 2000000, 150000, 150000, 4350000, 3600000),
(11, 2016, 2500000, 200000, 200000, 4800000, 3750000),
(14, 2018, 2500000, 200000, 200000, 4800000, 3750000),
(13, 2017, 2500000, 200000, 200000, 4800000, 3750000),
(15, 2019, 0, 200000, 200000, 5250000, 3750000),
(16, 2020, 0, 200000, 200000, 6000000, 3750000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_jenis_pembayaran`
--

CREATE TABLE `master_jenis_pembayaran` (
  `id_jenis_pembayaran` int(11) NOT NULL,
  `nm_jenis_pembayaran` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_jenis_pembayaran`
--

INSERT INTO `master_jenis_pembayaran` (`id_jenis_pembayaran`, `nm_jenis_pembayaran`) VALUES
(1, 'PENGEMBANGAN KAMPUS'),
(2, 'KEMAHASISWAAN'),
(3, 'CICILAN SEMESTER'),
(4, 'CUTI'),
(5, 'KONVERSI'),
(6, 'TOEFL'),
(7, 'KERJA PRAKTEK'),
(8, 'PROPOSAL SKRIPSI'),
(9, 'SEMINAR SKRIPSI'),
(10, 'SIDANG AKHIR SKRIPSI'),
(11, 'WISUDA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(20) NOT NULL,
  `link_menu` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `link_menu`, `type`, `icon`, `is_active`) VALUES
(1, 'Dashboard', 'dashboard', 'statis', 'gi gi-dashboard', 1),
(2, 'Transaksi', 'transaksi', 'statis', 'gi gi-usd', 1),
(3, 'Manajemen', 'manajemen', 'dinamis', 'gi gi-adjust_alt', 1),
(4, 'Master', 'master', 'dinamis', 'gi gi-adjust_alt', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `submenu`
--

CREATE TABLE `submenu` (
  `id_submenu` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `nama_submenu` varchar(26) NOT NULL,
  `url` varchar(128) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL COMMENT 'untuk status menu'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `submenu`
--

INSERT INTO `submenu` (`id_submenu`, `id_menu`, `nama_submenu`, `url`, `icon`, `is_active`) VALUES
(1, 2, 'Pembayaran SPP', 'transaksi/pembayaranspp', 'fa fa-circle', 1),
(2, 2, 'Pembayaran Cuti', 'transaksi/pembayarancuti', 'fa fa-circle', 1),
(3, 3, 'Manajemen Menu', 'manajemen/manajemen-menu', 'fa fa-circle', 1),
(4, 3, 'Manajemen SubMenu', 'manajemen/manajemen-submenu', 'fa fa-circle', 1),
(5, 3, 'Manajemen User', 'manajemen/manajemen-user', 'fa fa-circle', 1),
(6, 4, 'Master Uang SPP', 'MasterUangSpp', 'fa fa-circle', 1),
(7, 4, 'Master Uang Bangunan', 'MasterPengembanganKampus', 'fa fa-circle', 1),
(8, 4, 'Master Kerja Praktek', 'MasterKerjaPraktek', 'fa fa-circle', 1),
(9, 4, 'Master Seminar Skripsi', 'MasterSeminarSkripsi', 'fa fa-circle', 1),
(10, 4, 'Master Sidang Skripsi', 'MasterSidangSkripsi', 'fa fa-circle', 1),
(11, 4, 'Master Wisuda', 'MasterWisuda', 'fa fa-circle', 1),
(12, 4, 'Master TOEFL', 'MasterTOEFL', 'fa fa-circle', 1),
(13, 4, 'Master Kemahasiswaan', 'MasterKemahasiswaan', 'fa fa-circle', 1),
(14, 4, 'Master Konversi', 'MasterKonversi', 'fa fa-circle', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(25) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `username`, `password`, `role`) VALUES
(1, 'System Devloper', 'devstt', '202cb962ac59075b964b07152d234b70', 1),
(2, 'Master Admin', 'admin', '202cb962ac59075b964b07152d234b70', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 1, 3),
(5, 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role`
--

CREATE TABLE `user_role` (
  `id_role` int(11) NOT NULL,
  `role_type` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user_role`
--

INSERT INTO `user_role` (`id_role`, `role_type`) VALUES
(1, 'Master Admin'),
(2, 'Admin'),
(3, 'User');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `master_jenis_pembayaran`
--
ALTER TABLE `master_jenis_pembayaran`
  ADD PRIMARY KEY (`id_jenis_pembayaran`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`id_submenu`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `id_submenu` (`id_submenu`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `nama_user` (`nama_user`);

--
-- Indeks untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `master_jenis_pembayaran`
--
ALTER TABLE `master_jenis_pembayaran`
  MODIFY `id_jenis_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `submenu`
--
ALTER TABLE `submenu`
  MODIFY `id_submenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
