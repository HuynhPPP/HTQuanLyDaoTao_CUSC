-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2025 at 06:33 AM
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
-- Table structure for table `hocki`
--

CREATE TABLE `hocki` (
  `MaHK` varchar(50) NOT NULL,
  `TenHK` varchar(30) DEFAULT NULL,
  `TongGioGoc` int DEFAULT NULL,
  `TongGioTrienKhai` int DEFAULT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hocki`
--

INSERT INTO `hocki` (`MaHK`, `TenHK`, `TongGioGoc`, `TongGioTrienKhai`, `MaChuongTrinh`, `created_at`, `updated_at`) VALUES
('OV-7023-HK I', 'HỌC KỲ I', 168, 172, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK II', 'HỌC KỲ II', 218, 200, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK III', 'HỌC KỲ III', 170, 168, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK IV', 'HỌC KỲ IV', 194, 208, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK V', 'HỌC KỲ V', 168, 172, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7096-HK I', 'Học kỳ I', 176, 176, 'OV-7096', '2025-05-22 22:19:42', '2025-05-22 22:19:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hocki`
--
ALTER TABLE `hocki`
  ADD PRIMARY KEY (`MaHK`),
  ADD KEY `MaChuongTrinh` (`MaChuongTrinh`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hocki`
--
ALTER TABLE `hocki`
  ADD CONSTRAINT `hocki_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
