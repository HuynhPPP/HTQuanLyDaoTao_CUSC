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
-- Table structure for table `chuongtrinh_monhoc`
--

CREATE TABLE `chuongtrinh_monhoc` (
  `MaChuongTrinh` varchar(12) NOT NULL,
  `TenMH` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chuongtrinh_monhoc`
--

INSERT INTO `chuongtrinh_monhoc` (`MaChuongTrinh`, `TenMH`, `created_at`, `updated_at`) VALUES
('OV-7096', 'Dự án - Phân tích Dữ liệu với R', '2025-05-23 13:31:40', '2025-05-23 13:31:40'),
('OV-7096', 'Khoa học Dữ liệu sử dụng R Programming', '2025-05-23 13:31:40', '2025-05-23 13:31:40'),
('OV-7096', 'Lập trình Ứng dụng bằng Python', '2025-05-23 13:31:40', '2025-05-23 13:31:40'),
('OV-7096', 'Phân tích Dữ liệu với MS Excel', '2025-05-23 13:28:54', '2025-05-23 13:28:54'),
('OV-7096', 'Phân tích Thống kê Suy luận', '2025-05-23 13:31:40', '2025-05-23 13:31:40'),
('OV-7096', 'Quản lý Tập dữ liệu lớn với MongoDB', '2025-05-23 13:31:40', '2025-05-23 13:31:40'),
('OV-7096', 'Xử lý Dữ liệu bằng T-SQL', '2025-05-23 13:28:54', '2025-05-23 13:28:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chuongtrinh_monhoc`
--
ALTER TABLE `chuongtrinh_monhoc`
  ADD PRIMARY KEY (`MaChuongTrinh`,`TenMH`),
  ADD KEY `TenMH` (`TenMH`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chuongtrinh_monhoc`
--
ALTER TABLE `chuongtrinh_monhoc`
  ADD CONSTRAINT `chuongtrinh_monhoc_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`),
  ADD CONSTRAINT `chuongtrinh_monhoc_ibfk_2` FOREIGN KEY (`TenMH`) REFERENCES `monhoc` (`TenMH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
