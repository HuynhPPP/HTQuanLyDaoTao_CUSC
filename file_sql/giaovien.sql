-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2025 at 01:51 AM
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
-- Table structure for table `giaovien`
--

CREATE TABLE `giaovien` (
  `MaGV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `HoTenGV` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `GioiTinh` tinyint(1) DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `EmailCUSC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sdt` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaHV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TenChucVu` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaDV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaBang` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `LoaiGV` enum('CoHuu','MoiGiang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CoHuu',
  `ChuyenNganh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `GhiChu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `NgayBatDauCongTac` date DEFAULT NULL,
  `NgayKetThucCongTac` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giaovien`
--

INSERT INTO `giaovien` (`MaGV`, `HoTenGV`, `GioiTinh`, `Email`, `EmailCUSC`, `Sdt`, `MaHV`, `TenChucVu`, `MaDV`, `MaBang`, `LoaiGV`, `ChuyenNganh`, `GhiChu`, `NgayBatDauCongTac`, `NgayKetThucCongTac`, `created_at`, `updated_at`) VALUES
('GV001', 'Nguyễn Văn An', 1, 'ngvanan@gmail.com', 'gv001nguyenvanan@cusc.ctu.vn', '0912345678', 'HV001', 'Giảng viên', 'DV003', 'BC001', 'CoHuu', 'Khoa học máy tính', 'Giảng viên chính', '2020-01-01', NULL, '2025-05-16 07:53:41', '2025-06-03 06:13:21'),
('GV002', 'Trần Thị Bình', 0, 'tranthib@gmail.com', 'gv002tranthibinh@cusc.ctu.vn', '0923456789', 'HV002', 'Trưởng khoa', 'DV002', 'BC002', 'MoiGiang', 'Kinh tế học', 'Giảng viên thỉnh giảng', '2018-05-15', NULL, '2025-05-16 07:53:41', '2025-06-03 06:13:21'),
('GV003', 'Phạm Đức Linh', 0, 'phamduclinh@cusc.vn', 'gv003phamduclinh@cusc.ctu.vn', '0943526107', 'HV002', 'Giảng viên', 'DV003', 'BC002', 'MoiGiang', 'Hệ thống thông tin', NULL, '2018-06-04', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:21'),
('GV004', 'Hoàng Quang Giang', 0, 'hoangquanggiang@cusc.vn', 'gv004hoangquanggiang@cusc.ctu.vn', '0938554271', 'HV003', 'Giảng viên', 'DV002', 'B001', 'CoHuu', 'Mạng máy tính', NULL, '2018-06-06', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:21'),
('GV005', 'Hoàng Công Nam', 0, 'hoangcongnam@cusc.vn', 'gv005hoangcongnam@cusc.ctu.vn', '0915354646', 'HV003', 'Giảng viên', 'DV003', 'BC003', 'MoiGiang', 'Khoa học dữ liệu', NULL, '2019-05-09', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22'),
('GV006', 'Phan Hữu Giang', 1, 'phanhuugiang@cusc.vn', 'gv006phanhuugiang@cusc.ctu.vn', '0987506679', 'HV001', 'Giảng viên', 'DV001', 'B001', 'CoHuu', 'Trí tuệ nhân tạo', NULL, '2021-12-19', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22'),
('GV007', 'Phan Đức Phúc', 1, 'phanducphuc@cusc.vn', 'gv007phanducphuc@cusc.ctu.vn', '0982049005', 'HV002', 'Giảng viên', 'DV001', 'B002', 'CoHuu', 'Trí tuệ nhân tạo', NULL, '2018-12-11', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22'),
('GV008', 'Đặng Hoàng Giang', 1, 'danghoanggiang@cusc.vn', 'gv008danghoanggiang@cusc.ctu.vn', '0981259529', 'HV002', 'Giảng viên', 'CNTT', 'B003', 'MoiGiang', 'Trí tuệ nhân tạo', NULL, '2020-06-14', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `giaovien`
--
ALTER TABLE `giaovien`
  ADD PRIMARY KEY (`MaGV`),
  ADD UNIQUE KEY `giaovien_email_unique` (`Email`),
  ADD KEY `giaovien_mahv_foreign` (`MaHV`),
  ADD KEY `giaovien_tenchucvu_foreign` (`TenChucVu`),
  ADD KEY `giaovien_madv_foreign` (`MaDV`),
  ADD KEY `giaovien_mabang_foreign` (`MaBang`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `giaovien`
--
ALTER TABLE `giaovien`
  ADD CONSTRAINT `giaovien_mabang_foreign` FOREIGN KEY (`MaBang`) REFERENCES `bangcapcanbo` (`MaBang`) ON DELETE SET NULL,
  ADD CONSTRAINT `giaovien_madv_foreign` FOREIGN KEY (`MaDV`) REFERENCES `donvi` (`MaDV`) ON DELETE SET NULL,
  ADD CONSTRAINT `giaovien_mahv_foreign` FOREIGN KEY (`MaHV`) REFERENCES `hocvi` (`MaHV`) ON DELETE SET NULL,
  ADD CONSTRAINT `giaovien_tenchucvu_foreign` FOREIGN KEY (`TenChucVu`) REFERENCES `chucvu` (`TenChucVu`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
