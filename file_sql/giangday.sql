-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2025 at 02:54 PM
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
-- Table structure for table `giangday`
--

CREATE TABLE `giangday` (
  `MaGV` varchar(12) NOT NULL,
  `MaLop` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaMH` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giangday`
--

INSERT INTO `giangday` (`MaGV`, `MaLop`, `MaMH`, `NgayBatDau`, `NgayKetThuc`, `GhiChu`, `created_at`, `updated_at`) VALUES
('GV002', NULL, 'MH03', NULL, NULL, NULL, '2025-06-05 02:48:09', '2025-06-05 02:48:09'),
('GV003', NULL, 'MH02', NULL, NULL, 'Giảng viên thỉnh giảng', '2025-06-05 02:47:56', '2025-06-05 02:47:56'),
('GV006', NULL, 'MH01', NULL, NULL, NULL, '2025-06-05 02:51:29', '2025-06-05 02:51:29'),
('GV001', NULL, 'MH15', NULL, NULL, NULL, '2025-06-05 07:50:02', '2025-06-05 07:50:02'),
('GV004', NULL, 'MH01', NULL, NULL, 'Giảng viên chính', '2025-06-05 08:04:12', '2025-06-05 08:04:12'),
('GV001', NULL, 'MH21', NULL, NULL, NULL, '2025-06-05 08:04:56', '2025-06-05 08:04:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `giangday`
--
ALTER TABLE `giangday`
  ADD KEY `giangday_ibfk_2` (`MaMH`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `giangday`
--
ALTER TABLE `giangday`
  ADD CONSTRAINT `giangday_ibfk_2` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
