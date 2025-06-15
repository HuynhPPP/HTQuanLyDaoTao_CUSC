-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 15, 2025 at 07:38 AM
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
-- Database: `qldaotao_vi`
--

-- --------------------------------------------------------

--
-- Table structure for table `danhsachmh`
--

CREATE TABLE `danhsachmh` (
  `id` bigint UNSIGNED NOT NULL,
  `MaHK` varchar(50) DEFAULT NULL,
  `MaMH` varchar(255) DEFAULT NULL,
  `TenKhungGio` varchar(100) DEFAULT NULL,
  `SttMH` int DEFAULT NULL,
  `TenMH` varchar(255) DEFAULT NULL,
  `GioTrienKhai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsachmh`
--

INSERT INTO `danhsachmh` (`id`, `MaHK`, `MaMH`, `TenKhungGio`, `SttMH`, `TenMH`, `GioTrienKhai`) VALUES
(1, 'OV-7023-HK I', NULL, NULL, 1, 'Computer fundamentals', NULL),
(2, 'OV-7023-HK I', NULL, NULL, 2, 'Logic Building and Elementary Programing', NULL),
(3, 'OV-7023-HK I', NULL, NULL, 3, 'HTML5,CSS and Javascript', NULL),
(4, 'OV-7023-HK I', NULL, NULL, 4, 'AngularJS', NULL),
(5, 'OV-7023-HK I', NULL, NULL, 5, 'eProject-Website Development', NULL),
(6, 'OV-7023-HK I', NULL, NULL, 6, 'Database Design and Development(core)', NULL),
(7, 'OV-7023-HK I', NULL, NULL, 7, 'Data Management with SQL server', NULL),
(19, 'OV-7096-HK I', 'MH19', 'Sáng', NULL, 'Khoa học Dữ liệu sử dụng R Programming', '36'),
(20, 'OV-7096-HK I', 'MH20', NULL, NULL, 'Lập trình Ứng dụng bằng Python', '36'),
(21, 'OV-7096-HK I', 'MH21', NULL, NULL, 'Dự án - Phân tích Dữ liệu với R', '24'),
(22, 'OV-7023-HK II', 'MH20', NULL, NULL, 'Lập trình Ứng dụng bằng Python', '36'),
(23, 'OV-7023-HK II', 'MH21', NULL, NULL, 'Dự án - Phân tích Dữ liệu với R', '24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TenKhungGio` (`TenKhungGio`),
  ADD KEY `MaHK` (`MaHK`),
  ADD KEY `TenMH` (`TenMH`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  ADD CONSTRAINT `danhsachmh_ibfk_1` FOREIGN KEY (`TenKhungGio`) REFERENCES `khunggio` (`TenKhungGio`),
  ADD CONSTRAINT `danhsachmh_ibfk_2` FOREIGN KEY (`MaHK`) REFERENCES `hocki` (`MaHK`),
  ADD CONSTRAINT `danhsachmh_ibfk_3` FOREIGN KEY (`TenMH`) REFERENCES `monhoc` (`TenMH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
