-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 15, 2025 at 07:42 AM
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
-- Table structure for table `khunggio`
--

CREATE TABLE `khunggio` (
  `TenKhungGio` varchar(100) NOT NULL,
  `ThoiGian` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khunggio`
--

INSERT INTO `khunggio` (`TenKhungGio`, `ThoiGian`) VALUES
('13:00-15:00', '2'),
('15:00-17:00', '2'),
('17:30-19:30', '2'),
('19:30-21:30', '2'),
('7:00-9:00', '2'),
('9:00-11:00', '2'),
('ca 1', '08:00 - 11:00'),
('Chiều', '13:00 - 16:00'),
('Sáng', '07:00 - 11:00'),
('Tối', '18:00 - 21:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `khunggio`
--
ALTER TABLE `khunggio`
  ADD PRIMARY KEY (`TenKhungGio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
