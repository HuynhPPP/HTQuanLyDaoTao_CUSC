-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2025 at 07:01 AM
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
-- Table structure for table `chuongtrinh`
--

CREATE TABLE `chuongtrinh` (
  `MaChuongTrinh` varchar(12) NOT NULL,
  `TenChuongTrinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `PhienBan` varchar(12) DEFAULT NULL,
  `NgayTrienKhaiPB` date DEFAULT NULL,
  `TenKhoaDaoTao` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chuongtrinh`
--

INSERT INTO `chuongtrinh` (`MaChuongTrinh`, `TenChuongTrinh`, `PhienBan`, `NgayTrienKhaiPB`, `TenKhoaDaoTao`, `created_at`, `updated_at`) VALUES
('OV-6062', 'An toàn an ninh thông tin (Hacker mũ trắng)', NULL, NULL, 'Ngắn hạn', '2025-05-21 10:14:30', '2025-05-21 10:14:30'),
('OV-7023', 'Lập trình viên Quốc tế – Aptech', '1.0', '2025-06-24', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:30:03'),
('OV-7096', 'ACN Pro (CPIDA) - Khóa học chuyên ngành về Khoa học Dữ liệu', '1.0', '2023-08-01', 'Ngắn hạn', '2025-05-19 14:38:55', '2025-06-07 06:20:47'),
('OV9001', 'Mỹ thuật Đa phương tiện – Arena', '2.0', '2025-06-16', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:31:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chuongtrinh`
--
ALTER TABLE `chuongtrinh`
  ADD PRIMARY KEY (`MaChuongTrinh`),
  ADD KEY `fk_tenkhoadaotao` (`TenKhoaDaoTao`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chuongtrinh`
--
ALTER TABLE `chuongtrinh`
  ADD CONSTRAINT `fk_tenkhoadaotao` FOREIGN KEY (`TenKhoaDaoTao`) REFERENCES `khoadaotao` (`TenKhoaDaoTao`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
