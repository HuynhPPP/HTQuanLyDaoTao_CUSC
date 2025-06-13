-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2025 at 07:28 AM
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
-- Table structure for table `hosotuyensinh`
--

CREATE TABLE `hosotuyensinh` (
  `MaHoSo` varchar(12) NOT NULL,
  `MaSV` varchar(12) DEFAULT NULL,
  `MaTS` varchar(12) NOT NULL,
  `NgayNopHS` date DEFAULT NULL,
  `TrangThaiHS` enum('DaNop','DaXet','DaTrungTuyen','KhongTrungTuyen') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Hinh3X4` tinyint(1) DEFAULT NULL,
  `HinhCCCD` tinyint(1) DEFAULT NULL,
  `ToDangKi` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hosotuyensinh`
--

INSERT INTO `hosotuyensinh` (`MaHoSo`, `MaSV`, `MaTS`, `NgayNopHS`, `TrangThaiHS`, `Hinh3X4`, `HinhCCCD`, `ToDangKi`, `created_at`, `updated_at`) VALUES
('HS1747584061', '21010001', 'TS20251', '2025-05-18', 'DaXet', NULL, NULL, NULL, '2025-05-18 16:01:01', '2025-05-19 08:22:50'),
('HS1747585057', '21010002', 'TS20251', '2025-05-18', 'DaNop', NULL, NULL, NULL, '2025-05-18 16:17:37', '2025-05-18 16:17:37'),
('HS1747585066', '21010003', 'TS20251', '2025-05-18', 'DaNop', NULL, NULL, NULL, '2025-05-18 16:17:46', '2025-05-18 16:17:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hosotuyensinh`
--
ALTER TABLE `hosotuyensinh`
  ADD PRIMARY KEY (`MaHoSo`),
  ADD KEY `MaSV` (`MaSV`),
  ADD KEY `fk_MaTS` (`MaTS`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hosotuyensinh`
--
ALTER TABLE `hosotuyensinh`
  ADD CONSTRAINT `fk_MaTS` FOREIGN KEY (`MaTS`) REFERENCES `thongtintuyensinh` (`MaTS`),
  ADD CONSTRAINT `hosotuyensinh_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
