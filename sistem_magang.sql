-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 05:31 PM
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
-- Database: `sistem_magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `konversi_nilai`
--

CREATE TABLE `konversi_nilai` (
  `ID_Nilai` int(11) NOT NULL,
  `NIM` varchar(20) DEFAULT NULL,
  `Nilai_MK_Etika_Profesi` decimal(4,2) DEFAULT NULL,
  `Nilai_Magang` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konversi_nilai`
--

INSERT INTO `konversi_nilai` (`ID_Nilai`, `NIM`, `Nilai_MK_Etika_Profesi`, `Nilai_Magang`) VALUES
(1, '20232059', 80.00, 90.00),
(2, '202432080', 90.00, 95.00);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `NIM` varchar(20) NOT NULL,
  `Nama_Mahasiswa` varchar(100) NOT NULL,
  `ID_Lokasi_Magang` int(11) DEFAULT NULL,
  `Mulai_Magang` date DEFAULT NULL,
  `Selesai_Magang` date DEFAULT NULL,
  `Nomor_Telp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`NIM`, `Nama_Mahasiswa`, `ID_Lokasi_Magang`, `Mulai_Magang`, `Selesai_Magang`, `Nomor_Telp`) VALUES
('20232059', 'Muchtar Fauzan Aminuddin', 1, '2025-07-20', '2025-10-20', '089659766251'),
('202432080', 'Amelia Grascella Nainggolan', 1, '2025-07-20', '2025-09-20', '0866358263');

-- --------------------------------------------------------

--
-- Table structure for table `tempat_magang`
--

CREATE TABLE `tempat_magang` (
  `ID_Lokasi_Magang` int(11) NOT NULL,
  `Nama_Perusahaan` varchar(100) NOT NULL,
  `Alamat_Perusahaan` text NOT NULL,
  `Total_Mahasiswa` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tempat_magang`
--

INSERT INTO `tempat_magang` (`ID_Lokasi_Magang`, `Nama_Perusahaan`, `Alamat_Perusahaan`, `Total_Mahasiswa`) VALUES
(1, 'PT.PLN Enjiniring', 'Jl. KS Tubun I No.2 3, RT.3/RW.2, Kota Bambu Sel., Kec. Palmerah, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11420', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `konversi_nilai`
--
ALTER TABLE `konversi_nilai`
  ADD PRIMARY KEY (`ID_Nilai`),
  ADD KEY `NIM` (`NIM`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`NIM`),
  ADD KEY `ID_Lokasi_Magang` (`ID_Lokasi_Magang`);

--
-- Indexes for table `tempat_magang`
--
ALTER TABLE `tempat_magang`
  ADD PRIMARY KEY (`ID_Lokasi_Magang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `konversi_nilai`
--
ALTER TABLE `konversi_nilai`
  MODIFY `ID_Nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tempat_magang`
--
ALTER TABLE `tempat_magang`
  MODIFY `ID_Lokasi_Magang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `konversi_nilai`
--
ALTER TABLE `konversi_nilai`
  ADD CONSTRAINT `konversi_nilai_ibfk_1` FOREIGN KEY (`NIM`) REFERENCES `mahasiswa` (`NIM`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`ID_Lokasi_Magang`) REFERENCES `tempat_magang` (`ID_Lokasi_Magang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
