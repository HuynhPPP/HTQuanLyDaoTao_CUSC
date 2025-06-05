-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2025 at 08:56 AM
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
-- Table structure for table `phieuphancongthi`
--

CREATE TABLE `phieuphancongthi` (
  `MaPhanCong` int NOT NULL,
  `MaLichThi` varchar(12) DEFAULT NULL,
  `MaCB` varchar(12) DEFAULT NULL,
  `VaiTro` enum('Cán bộ coi thi','Giám sát','Chấm thi') DEFAULT 'Cán bộ coi thi',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  ADD PRIMARY KEY (`MaPhanCong`),
  ADD KEY `phieuphancongthi_ibfk_1` (`MaLichThi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  MODIFY `MaPhanCong` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  ADD CONSTRAINT `phieuphancongthi_ibfk_1` FOREIGN KEY (`MaLichThi`) REFERENCES `lichthi` (`MaLichThi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
