-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2025 at 02:04 AM
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
-- Table structure for table `hinh_thuc_danh_gia`
--

CREATE TABLE `hinh_thuc_danh_gia` (
  `id` int NOT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL,
  `HinhThuc` enum('Lý thuyết trắc nghiệm','Thực hành','Dự án') DEFAULT NULL,
  `TiLePhanTram` tinyint DEFAULT NULL,
  `SoBaiThi` int DEFAULT NULL,
  `DiemMoiBai` int DEFAULT NULL,
  `ThoiGian` int DEFAULT NULL,
  `DonViThoiGian` enum('phút','giờ') DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hinh_thuc_danh_gia`
--
ALTER TABLE `hinh_thuc_danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MaChuongTrinh` (`MaChuongTrinh`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hinh_thuc_danh_gia`
--
ALTER TABLE `hinh_thuc_danh_gia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hinh_thuc_danh_gia`
--
ALTER TABLE `hinh_thuc_danh_gia`
  ADD CONSTRAINT `hinh_thuc_danh_gia_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
