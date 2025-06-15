-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2025 at 07:00 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qldaotao`
--

-- --------------------------------------------------------

--
-- Table structure for table `diemthi`
--

CREATE TABLE `diemthi` (
  `MaSV` varchar(12) NOT NULL,
  `MaMH` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `MaLop` varchar(12) NOT NULL,
  `DiemTong` float DEFAULT NULL,
  `DiemThucHanh` float DEFAULT NULL,
  `DiemLyThuyet` float DEFAULT NULL,
  `DiemDuAn` float DEFAULT NULL,
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diemthi`
--

INSERT INTO `diemthi` (`MaSV`, `MaMH`, `MaLop`, `DiemTong`, `DiemThucHanh`, `DiemLyThuyet`, `DiemDuAn`, `GhiChu`, `created_at`, `updated_at`) VALUES
('21010001', 'MH21', 'CP2296H07', 93.33, 95, 95, 90, NULL, '2025-06-09 11:23:17', '2025-06-10 05:40:35'),
('21010002', 'MH21', 'CP2296H07', 86.67, 90, 90, 80, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('21010003', 'MH21', 'CP2296H07', 95, 100, 95, 90, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000054', 'MH21', 'CP2296H07', 85, 100, 95, 60, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000055', 'MH21', 'CP2296H07', 66.67, 70, 60, 70, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000057', 'MH21', 'CP2296H07', 80, 75, 80, 85, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000096', 'MH21', 'CP2296H07', 86.67, 80, 90, 90, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000097', 'MH21', 'CP2296H07', 26.67, 0, 0, 80, 'Gian lận thi cử', '2025-06-09 11:23:17', '2025-06-10 05:40:35'),
('23000098', 'MH21', 'CP2296H07', 96.67, 90, 100, 100, NULL, '2025-06-09 11:23:17', '2025-06-10 05:40:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diemthi`
--
ALTER TABLE `diemthi`
  ADD PRIMARY KEY (`MaSV`,`MaMH`,`MaLop`) USING BTREE,
  ADD KEY `MaLop` (`MaLop`),
  ADD KEY `diemthi_ibfk_2` (`MaMH`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diemthi`
--
ALTER TABLE `diemthi`
  ADD CONSTRAINT `diemthi_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`),
  ADD CONSTRAINT `diemthi_ibfk_2` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `diemthi_ibfk_3` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
