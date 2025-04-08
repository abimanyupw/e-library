-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 07:52 AM
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
-- Database: `data_perpus`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_pinjam`
--

CREATE TABLE `log_pinjam` (
  `id_log` int NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `id_anggota` varchar(10) NOT NULL,
  `tgl_pinjam` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_pinjam`
--

INSERT INTO `log_pinjam` (`id_log`, `id_buku`, `id_anggota`, `tgl_pinjam`) VALUES
(18, 'B001', 'B008', '2025-02-20'),
(19, 'B001', 'B006', '2025-02-20'),
(20, 'B001', 'B008', '2025-02-01'),
(21, 'B005', 'B008', '2025-01-27'),
(22, 'B005', 'B006', '2025-02-26'),
(23, 'B005', 'B008', '2025-02-01'),
(24, 'B005', 'B008', '2025-02-08'),
(25, 'B003', 'B009', '2025-02-24'),
(26, 'B001', 'B007', '2025-02-01'),
(27, 'B005', 'B007', '2025-04-08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_anggota`
--

CREATE TABLE `tb_anggota` (
  `id_anggota` varchar(10) NOT NULL,
  `nama` varchar(70) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jekel` enum('Laki-laki','Perempuan') NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_anggota`
--

INSERT INTO `tb_anggota` (`id_anggota`, `nama`, `jekel`, `kelas`, `no_hp`) VALUES
('B006', 'Novia Citra I', 'Perempuan', 'XII TKJ 2', '085645430702'),
('B007', 'Rinda Dwi A', 'Perempuan', 'XII TKJ 2', '0886416745435'),
('B008', 'Szeloica Ahmad Arya A', 'Laki-laki', 'XII TKJ 2', '0823645987'),
('B009', 'Mahardika Aditya E.P', 'Laki-laki', 'XII TKJ 2', '08145467989895'),
('B010', 'Faisal Rosyid S', 'Laki-laki', 'XII TKJ 2', '034354656454');

-- --------------------------------------------------------

--
-- Table structure for table `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id_buku` varchar(10) NOT NULL,
  `judul_buku` varchar(30) NOT NULL,
  `pengarang` varchar(30) NOT NULL,
  `penerbit` varchar(30) NOT NULL,
  `th_terbit` year NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `harga_beli` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_buku`
--

INSERT INTO `tb_buku` (`id_buku`, `judul_buku`, `pengarang`, `penerbit`, `th_terbit`, `stok`, `harga_beli`) VALUES
('B001', 'Matematika', 'Mahardika', 'Nofal Fajri', '2010', 3, 76000),
('B002', 'Python', 'Alip', 'Fahri', '2020', 5, 100000),
('B003', 'Sejarah', 'Hamdan', 'Sheva', '2010', 9, 50000),
('B004', 'Sytem Admin', 'Awi', 'Evan', '2009', 8, 90000),
('B005', 'Information Network Cabling', 'Hafid', 'Alip', '2020', 8, 70000),
('B006', 'belajar pemrograman web', 'abimanyu', 'abimanyu', '2025', 10, 600000),
('B007', 'PHP dasar', 'abimanyu', 'abimanyu', '2007', 10, 90000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(35) NOT NULL,
  `level` enum('Administrator','Petugas','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama_pengguna`, `username`, `password`, `level`) VALUES
(6, 'Abimanyu Pradipa W', 'abimanyu', '539fd53b59e3bb12d203f45a912eeaf2', 'Administrator'),
(7, 'Isfani  Dwi Budi J', 'isfani', '202cb962ac59075b964b07152d234b70', 'Petugas'),
(8, 'Ellen Benita A', 'ellen', '202cb962ac59075b964b07152d234b70', 'Petugas');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sirkulasi`
--

CREATE TABLE `tb_sirkulasi` (
  `id_sk` varchar(20) NOT NULL,
  `id_buku` varchar(10) NOT NULL,
  `id_anggota` varchar(10) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `tgl_dikembalikan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('PIN','KEM') NOT NULL,
  `tgl_pengembalian` date DEFAULT NULL,
  `kondisi_buku` varchar(20) DEFAULT NULL,
  `denda` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_sirkulasi`
--

INSERT INTO `tb_sirkulasi` (`id_sk`, `id_buku`, `id_anggota`, `tgl_pinjam`, `tgl_kembali`, `tgl_dikembalikan`, `status`, `tgl_pengembalian`, `kondisi_buku`, `denda`) VALUES
('S001', 'B005', 'B006', '2025-02-26', '2025-03-05', '2025-02-25 08:29:01', 'KEM', '2025-02-25', 'Rusak Berat', 20000),
('S002', 'B005', 'B008', '2025-02-01', '2025-02-08', '2025-02-25 08:32:37', 'KEM', '2025-02-25', 'Rusak Berat', 37000),
('S003', 'B005', 'B008', '2025-02-15', '2025-02-22', '2025-02-25 08:54:42', 'KEM', '2025-04-08', 'Hilang', 115000),
('S004', 'B003', 'B009', '2025-02-24', '2025-03-03', '2025-02-25 08:54:57', 'PIN', NULL, NULL, 0),
('S005', 'B001', 'B007', '2025-02-01', '2025-02-08', '2025-02-25 09:04:20', 'KEM', '2025-02-25', 'Rusak Berat', 37000),
('S006', 'B005', 'B007', '2025-04-08', '2025-04-15', '2025-04-08 10:26:14', 'PIN', NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_pinjam`
--
ALTER TABLE `log_pinjam`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `tb_sirkulasi`
--
ALTER TABLE `tb_sirkulasi`
  ADD PRIMARY KEY (`id_sk`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_pinjam`
--
ALTER TABLE `log_pinjam`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_pinjam`
--
ALTER TABLE `log_pinjam`
  ADD CONSTRAINT `log_pinjam_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tb_anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `log_pinjam_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `tb_buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_sirkulasi`
--
ALTER TABLE `tb_sirkulasi`
  ADD CONSTRAINT `tb_sirkulasi_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `tb_buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_sirkulasi_ibfk_2` FOREIGN KEY (`id_anggota`) REFERENCES `tb_anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
