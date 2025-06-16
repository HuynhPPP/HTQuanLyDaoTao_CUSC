-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 15, 2025 at 07:50 AM
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
-- Table structure for table `ngaynghi`
--

CREATE TABLE `ngaynghi` (
  `MaNgayNghi` int NOT NULL,
  `TenNgayNghi` varchar(50) DEFAULT NULL,
  `NgayBDNghi` date DEFAULT NULL,
  `NgayKT` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ngaynghi`
--

INSERT INTO `ngaynghi` (`MaNgayNghi`, `TenNgayNghi`, `NgayBDNghi`, `NgayKT`) VALUES
(1, 'Nghỉ Tết DL', '2024-01-01', '2024-01-01'),
(2, 'Nghỉ Tết Nguyên Đán 2024', '2024-02-05', '2024-02-16'),
(3, 'Nghỉ - Giỗ Tổ Hùng Vương', '2024-04-18', '2024-04-18'),
(4, 'Nghỉ 30/04-01/05', '2024-04-29', '2024-05-01'),
(5, 'Nghỉ hè', '2024-07-15', '2024-07-26'),
(6, 'aaa', '2025-09-02', '2025-09-16'),
(7, 'nghỉ lễ', '2025-07-31', '2025-08-01'),
(8, 'nghỉ lễ', '2025-08-09', '2025-08-09'),
(9, 'nghỉ lễ', '2025-08-09', '2025-08-09'),
(10, 'nghỉ lễ', '2025-07-27', '2025-07-28'),
(11, 'nghỉ lễ', '2025-07-28', '2025-07-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ngaynghi`
--
ALTER TABLE `ngaynghi`
  ADD PRIMARY KEY (`MaNgayNghi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ngaynghi`
--
ALTER TABLE `ngaynghi`
  MODIFY `MaNgayNghi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
