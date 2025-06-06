-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2025 at 01:10 PM
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
-- Table structure for table `monhoc`
--

CREATE TABLE `monhoc` (
  `TenMH` varchar(255) NOT NULL,
  `MaMH` varchar(12) NOT NULL,
  `GioGoc` int DEFAULT NULL,
  `GioTrienKhai` int DEFAULT NULL,
  `TietLT` tinyint(1) DEFAULT NULL,
  `TietTH` tinyint(1) DEFAULT NULL,
  `TietLTvaTH` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monhoc`
--

INSERT INTO `monhoc` (`TenMH`, `MaMH`, `GioGoc`, `GioTrienKhai`, `TietLT`, `TietTH`, `TietLTvaTH`, `created_at`, `updated_at`) VALUES
('AngularJS', 'MH01', 16, 16, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Application Programming with C#', 'MH02', 36, 38, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Computer fundamentals', 'MH03', 0, 8, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Data Management with SQL server', 'MH04', 40, 40, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Database Design and Development(core)', 'MH05', 24, 16, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Dự án - Phân tích Dữ liệu với R', 'MH21', 24, 24, 1, NULL, NULL, '2025-05-22 23:22:44', '2025-05-22 23:22:44'),
('eProject-Website Development', 'MH06', 2, 8, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('HTML5,CSS and Javascript', 'MH07', 40, 44, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Information Systems Analysis(Core)', 'MH08', 24, 12, 1, 0, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Java Programming - I', 'MH09', 36, 40, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Java Programming -II', 'MH10', 40, 42, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Khoa học Dữ liệu sử dụng R Programming', 'MH19', 36, 36, NULL, 1, NULL, '2025-05-22 23:22:07', '2025-05-22 23:22:07'),
('Lập trình Ứng dụng bằng Python', 'MH20', 36, 36, NULL, 1, NULL, '2025-05-22 23:22:25', '2025-05-22 23:22:25'),
('Logic Building and Elementary Programing', 'MH11', 40, 42, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Markup Language & JSON ', 'MH12', 16, 16, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Phân tích Dữ liệu với MS Excel', 'MH15', 16, 16, NULL, NULL, 1, '2025-05-22 23:20:18', '2025-05-22 23:20:18'),
('Phân tích Thống kê Suy luận', 'MH18', 16, 16, 1, NULL, NULL, '2025-05-22 23:21:39', '2025-05-22 23:21:39'),
('PHP Web Development with Laravel Framework', 'MH13', 40, 40, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Project-Java Application Development', 'MH14', 2, 12, 1, 0, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Quản lý Tập dữ liệu lớn với MongoDB', 'MH17', 32, 32, NULL, NULL, 1, '2025-05-22 23:21:21', '2025-05-22 23:21:21'),
('Xử lý Dữ liệu bằng T-SQL', 'MH16', 16, 16, NULL, NULL, 1, '2025-05-22 23:20:57', '2025-05-22 23:20:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`TenMH`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
