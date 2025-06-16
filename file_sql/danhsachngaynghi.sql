-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 15, 2025 at 07:46 AM
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
-- Table structure for table `danhsachngaynghi`
--

CREATE TABLE `danhsachngaynghi` (
  `TenTKB` varchar(255) DEFAULT NULL,
  `MaNgayNghi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsachngaynghi`
--

INSERT INTO `danhsachngaynghi` (`TenTKB`, `MaNgayNghi`) VALUES
('THỜI KHÓA BIỂU LỚP CP2296H07 - Học kỳ I (OV-7096)', 8),
('THỜI KHÓA BIỂU LỚP CP2296H07 - Học kỳ I (OV-7096)', 9),
('THỜI KHÓA BIỂU LỚP CP2296H07 - Học kỳ I (OV-7096)', 10),
('THỜI KHÓA BIỂU LỚP CP2296H07 - Học kỳ I (OV-7096)', 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `danhsachngaynghi`
--
ALTER TABLE `danhsachngaynghi`
  ADD PRIMARY KEY (`MaNgayNghi`),
  ADD KEY `TenTKB` (`TenTKB`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `danhsachngaynghi`
--
ALTER TABLE `danhsachngaynghi`
  ADD CONSTRAINT `danhsachngaynghi_ibfk_1` FOREIGN KEY (`TenTKB`) REFERENCES `tkb` (`TenTKB`),
  ADD CONSTRAINT `danhsachngaynghi_ibfk_2` FOREIGN KEY (`MaNgayNghi`) REFERENCES `ngaynghi` (`MaNgayNghi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
