-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 13, 2025 at 02:34 PM
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
-- Table structure for table `bangcapcanbo`
--

CREATE TABLE `bangcapcanbo` (
  `MaBang` varchar(12) NOT NULL,
  `TenBang` varchar(50) DEFAULT NULL,
  `ThoiGianCap` date DEFAULT NULL,
  `DonViCap` varchar(255) DEFAULT NULL,
  `SoHieu` varchar(30) DEFAULT NULL,
  `SoVaoSo` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bangcapcanbo`
--

INSERT INTO `bangcapcanbo` (`MaBang`, `TenBang`, `ThoiGianCap`, `DonViCap`, `SoHieu`, `SoVaoSo`, `created_at`, `updated_at`) VALUES
('B001', 'Cử nhân Công nghệ thông tin', '2015-06-20', 'Đại học Cần Thơ', 'CN-2015-001', 'VS-001', '2025-05-15 23:35:16', '2025-05-15 23:35:16'),
('B002', 'Thạc sĩ Quản trị kinh doanh', '2018-09-15', 'Đại học Kinh tế TP.HCM', 'TS-2018-002', 'VS-002', '2025-05-15 23:35:16', '2025-05-15 23:35:16'),
('B003', 'Tiến sĩ Giáo dục học', '2022-12-10', 'Đại học Sư phạm Hà Nội', 'TS-2022-003', 'VS-003', '2025-05-15 23:35:16', '2025-05-15 23:35:16'),
('BC001', 'Bằng TSKH', NULL, NULL, NULL, NULL, '2025-05-16 09:50:25', '2025-05-16 09:50:25'),
('BC002', 'Bằng ThS', NULL, NULL, NULL, NULL, '2025-05-16 09:50:25', '2025-05-16 09:50:25'),
('BC003', 'Bằng ĐH', NULL, NULL, NULL, NULL, '2025-05-16 09:50:25', '2025-05-16 09:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `canbo`
--

CREATE TABLE `canbo` (
  `MaCB` varchar(12) NOT NULL,
  `HoTenCB` varchar(30) DEFAULT NULL,
  `GioiTinh` tinyint(1) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `EmailCUSC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sdt` varchar(15) DEFAULT NULL,
  `MaHV` varchar(12) DEFAULT NULL,
  `TenChucVu` varchar(30) DEFAULT NULL,
  `CongViecPhuTrach` varchar(255) DEFAULT NULL,
  `MaDV` varchar(12) DEFAULT NULL,
  `MaBang` varchar(12) DEFAULT NULL,
  `MaTapHuan` varchar(12) DEFAULT NULL,
  `ThoiGianBDCongTacCUSC` date DEFAULT NULL,
  `ThoiGianKTCongTacCUSC` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `canbo`
--

INSERT INTO `canbo` (`MaCB`, `HoTenCB`, `GioiTinh`, `Email`, `EmailCUSC`, `Sdt`, `MaHV`, `TenChucVu`, `CongViecPhuTrach`, `MaDV`, `MaBang`, `MaTapHuan`, `ThoiGianBDCongTacCUSC`, `ThoiGianKTCongTacCUSC`, `created_at`, `updated_at`) VALUES
('CB003', 'Lê Văn Cường', 1, 'levcuong@gmail.com', NULL, '0934567890', 'HV003', 'Chuyên viên', 'Quản trị hệ thống', 'DV003', 'BC003', 'TH003', '2019-08-20', '2022-12-31', '2025-05-16 13:36:49', '2025-05-16 13:36:49');

-- --------------------------------------------------------

--
-- Table structure for table `chucvu`
--

CREATE TABLE `chucvu` (
  `TenChucVu` varchar(30) NOT NULL,
  `ThoiGianBatDauCV` varchar(50) DEFAULT NULL,
  `ThoiGianKTCV` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chucvu`
--

INSERT INTO `chucvu` (`TenChucVu`, `ThoiGianBatDauCV`, `ThoiGianKTCV`, `created_at`, `updated_at`) VALUES
('Chuyên viên', NULL, NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25'),
('Giảng viên', NULL, NULL, '2025-05-15 16:36:26', '2025-05-15 16:36:26'),
('Trưởng khoa', NULL, NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `chuongtrinh`
--

CREATE TABLE `chuongtrinh` (
  `MaChuongTrinh` varchar(12) NOT NULL,
  `TenChuongTrinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `PhienBan` varchar(12) DEFAULT NULL,
  `NgayTrienKhaiPB` date DEFAULT NULL,
  `TenKhoaDaoTao` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chuongtrinh`
--

INSERT INTO `chuongtrinh` (`MaChuongTrinh`, `TenChuongTrinh`, `PhienBan`, `NgayTrienKhaiPB`, `TenKhoaDaoTao`, `created_at`, `updated_at`) VALUES
('OV-6062', 'An toàn an ninh thông tin (Hacker mũ trắng)', NULL, NULL, 'Ngắn hạn', '2025-05-21 10:14:30', '2025-05-21 10:14:30'),
('OV-7023', 'Lập trình viên Quốc tế – Aptech', '1.0', '2025-06-24', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:30:03'),
('OV-7096', 'ACN Pro (CPIDA) - Khóa học chuyên ngành về Khoa học Dữ liệu', '1.0', '2023-08-01', 'Ngắn hạn', '2025-05-19 14:38:55', '2025-06-07 06:20:47'),
('OV9001', 'Mỹ thuật Đa phương tiện – Arena', '2.0', '2025-06-16', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:31:16');

-- --------------------------------------------------------

--
-- Table structure for table `chuongtrinh_monhoc`
--

CREATE TABLE `chuongtrinh_monhoc` (
  `MaChuongTrinh` varchar(12) NOT NULL,
  `MaMH` varchar(12) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chuongtrinh_monhoc`
--

INSERT INTO `chuongtrinh_monhoc` (`MaChuongTrinh`, `MaMH`, `created_at`, `updated_at`) VALUES
('OV-7023', 'MH21', '2025-06-27 15:31:08', '2025-06-27 15:31:08'),
('OV-7096', 'MH15', '2025-06-12 13:03:41', '2025-06-12 13:03:41'),
('OV-7096', 'MH16', '2025-06-12 13:03:41', '2025-06-12 13:03:41'),
('OV-7096', 'MH17', '2025-06-12 13:03:41', '2025-06-12 13:03:41'),
('OV-7096', 'MH18', '2025-06-12 13:03:41', '2025-06-12 13:03:41'),
('OV-7096', 'MH19', '2025-06-12 13:03:41', '2025-06-12 13:03:41'),
('OV-7096', 'MH20', '2025-06-12 13:03:41', '2025-06-12 13:03:41'),
('OV-7096', 'MH21', '2025-06-12 13:03:41', '2025-06-12 13:03:41');

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
(35, 'OV-7023-HK IV', 'MH01', NULL, NULL, 'AngularJS', '16'),
(36, 'OV-7023-HK IV', 'MH02', NULL, NULL, 'Application Programming with C#', '38'),
(40, 'OV-7023-HK III', 'MH20', 'Sáng', NULL, 'Lập trình Ứng dụng bằng Python', '36'),
(43, 'OV-7023-HK II', 'MH21', 'Tối 18h-20h', NULL, 'Dự án - Phân tích Dữ liệu với R', '24'),
(46, 'OV-7023-HK V', 'MH02', NULL, NULL, 'Application Programming with C#', '38'),
(50, 'OV-7023-HK I', 'MH21', 'Sáng 7h-9h', NULL, 'Dự án - Phân tích Dữ liệu với R', '24'),
(51, 'OV-7096-HK I', 'MH15', 'Chiều 13h-15h', NULL, 'Phân tích Dữ liệu với MS Excel', '16'),
(52, 'OV-7096-HK I', 'MH16', NULL, NULL, 'Xử lý Dữ liệu bằng T-SQL', '16'),
(53, 'OV-7096-HK I', 'MH17', NULL, NULL, 'Quản lý Tập dữ liệu lớn với MongoDB', '32'),
(54, 'OV-7096-HK I', 'MH18', NULL, NULL, 'Phân tích Thống kê Suy luận', '16'),
(55, 'OV-7096-HK I', 'MH19', NULL, NULL, 'Khoa học Dữ liệu sử dụng R Programming', '36');

-- --------------------------------------------------------

--
-- Table structure for table `danhsachngaynghi`
--

CREATE TABLE `danhsachngaynghi` (
  `TenTKB` varchar(255) DEFAULT NULL,
  `MaNgayNghi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danhsachphong`
--

CREATE TABLE `danhsachphong` (
  `MaLop` varchar(12) NOT NULL,
  `TenTKB` varchar(255) DEFAULT NULL,
  `TenPhong` varchar(20) NOT NULL,
  `NgaySuDung` date DEFAULT NULL,
  `Ca` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsachphong`
--

INSERT INTO `danhsachphong` (`MaLop`, `TenTKB`, `TenPhong`, `NgaySuDung`, `Ca`, `TrangThai`) VALUES
('CP2296H07', NULL, 'Class1', '2025-08-10', 'Chiều 13h-15h', 'Đang sử dụng'),
('CP2396G11', NULL, 'Class3', '2025-07-08', 'Chiều 13h-15h', 'Đang sử dụng'),
('CP2396M02', NULL, 'Class1', '2025-07-08', 'Chiều 13h-15h', 'Đang sử dụng');

-- --------------------------------------------------------

--
-- Table structure for table `danhsachsv`
--

CREATE TABLE `danhsachsv` (
  `MaLop` varchar(12) NOT NULL,
  `MaSV` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsachsv`
--

INSERT INTO `danhsachsv` (`MaLop`, `MaSV`) VALUES
('CP2296H07', '21010001'),
('CP2296H07', '21010002'),
('CP2296H07', '21010003'),
('CP2396G11', '23000029'),
('CP2396G11', '23000036'),
('CP2396G11', '23000047'),
('CP2296H07', '23000054'),
('CP2296H07', '23000055'),
('CP2296H07', '23000057'),
('CP2396G11', '23000082'),
('CP2396G11', '23000086'),
('CP2396G11', '23000092'),
('CP2396G11', '23000094'),
('CP2296H07', '23000096'),
('CP2296H07', '23000097'),
('CP2296H07', '23000098'),
('CP2296H07', '23000099');

-- --------------------------------------------------------

--
-- Table structure for table `diemthi`
--

CREATE TABLE `diemthi` (
  `MaSV` varchar(12) NOT NULL,
  `MaMH` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `MaLop` varchar(12) NOT NULL,
  `DiemTong` float DEFAULT NULL,
  `DiemThucHanh` float DEFAULT NULL,
  `DiemLyThuyet` float DEFAULT NULL,
  `DiemDuAn` float DEFAULT NULL,
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diemthi`
--

INSERT INTO `diemthi` (`MaSV`, `MaMH`, `MaLop`, `DiemTong`, `DiemThucHanh`, `DiemLyThuyet`, `DiemDuAn`, `GhiChu`, `created_at`, `updated_at`) VALUES
('21010001', 'MH15', 'CP2296H07', 75, 70, 70, 95, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('21010001', 'MH16', 'CP2296H07', 72, 70, 70, 80, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('21010001', 'MH17', 'CP2296H07', 82, 80, 80, 90, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('21010001', 'MH18', 'CP2296H07', 47.5, 50, 45, 50, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('21010001', 'MH19', 'CP2296H07', 45, 50, 40, 50, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('21010001', 'MH20', 'CP2296H07', 87, 80, 90, 90, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('21010001', 'MH21', 'CP2296H07', 93.33, 95, 95, 90, NULL, '2025-06-09 11:23:17', '2025-06-10 05:40:35'),
('21010002', 'MH15', 'CP2296H07', 79.5, 80, 75, 90, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('21010002', 'MH16', 'CP2296H07', 84.5, 80, 85, 90, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('21010002', 'MH17', 'CP2296H07', 92, 90, 90, 100, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('21010002', 'MH18', 'CP2296H07', 64.5, 60, 65, 70, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('21010002', 'MH19', 'CP2296H07', 69, 60, 70, 80, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('21010002', 'MH20', 'CP2296H07', 87, 80, 90, 90, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('21010002', 'MH21', 'CP2296H07', 86.67, 90, 90, 80, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('21010003', 'MH15', 'CP2296H07', 71, 60, 70, 90, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('21010003', 'MH16', 'CP2296H07', 87, 80, 90, 90, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('21010003', 'MH17', 'CP2296H07', 94, 95, 95, 90, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('21010003', 'MH18', 'CP2296H07', 43, 40, 50, 30, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('21010003', 'MH19', 'CP2296H07', 87, 80, 90, 90, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('21010003', 'MH20', 'CP2296H07', 79, 70, 80, 90, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('21010003', 'MH21', 'CP2296H07', 95, 100, 95, 90, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000029', 'MH21', 'CP2396G11', 96.67, 95, 95, 100, NULL, '2025-06-27 08:28:45', '2025-06-27 08:32:20'),
('23000036', 'MH21', 'CP2396G11', 91.67, 95, 90, 90, NULL, '2025-06-27 08:28:45', '2025-06-27 08:28:45'),
('23000047', 'MH21', 'CP2396G11', 83.33, 85, 85, 80, NULL, '2025-06-27 08:28:45', '2025-06-27 08:28:45'),
('23000054', 'MH15', 'CP2296H07', 92.5, 90, 95, 90, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('23000054', 'MH16', 'CP2296H07', 94.5, 90, 95, 100, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('23000054', 'MH17', 'CP2296H07', 64.5, 60, 65, 70, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('23000054', 'MH18', 'CP2296H07', 56, 60, 50, 65, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('23000054', 'MH19', 'CP2296H07', 46, 45, 45, 50, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('23000054', 'MH20', 'CP2296H07', 77, 60, 80, 95, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('23000054', 'MH21', 'CP2296H07', 85, 100, 95, 60, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000055', 'MH15', 'CP2296H07', 81.5, 85, 80, 80, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('23000055', 'MH16', 'CP2296H07', 91, 90, 90, 95, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('23000055', 'MH17', 'CP2296H07', 53, 60, 50, 50, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('23000055', 'MH18', 'CP2296H07', 63.5, 65, 70, 45, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('23000055', 'MH19', 'CP2296H07', 48, 50, 50, 40, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('23000055', 'MH20', 'CP2296H07', 94.5, 90, 95, 100, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('23000055', 'MH21', 'CP2296H07', 66.67, 70, 60, 70, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000057', 'MH15', 'CP2296H07', 88, 90, 90, 80, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('23000057', 'MH16', 'CP2296H07', 80, 80, 80, 80, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('23000057', 'MH17', 'CP2296H07', 48, 60, 40, 50, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('23000057', 'MH18', 'CP2296H07', 59.5, 65, 60, 50, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('23000057', 'MH19', 'CP2296H07', 64.5, 60, 65, 70, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('23000057', 'MH20', 'CP2296H07', 98, 100, 100, 90, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('23000057', 'MH21', 'CP2296H07', 80, 75, 80, 85, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000082', 'MH21', 'CP2396G11', 86.67, 80, 80, 100, NULL, '2025-06-27 08:28:45', '2025-06-27 08:28:45'),
('23000086', 'MH21', 'CP2396G11', 91.67, 90, 90, 95, NULL, '2025-06-27 08:28:45', '2025-06-27 08:28:45'),
('23000092', 'MH21', 'CP2396G11', 63.33, 50, 60, 80, NULL, '2025-06-27 08:28:45', '2025-06-27 08:28:45'),
('23000094', 'MH21', 'CP2396G11', 65, 60, 65, 70, NULL, '2025-06-27 08:28:45', '2025-06-27 08:28:45'),
('23000096', 'MH15', 'CP2296H07', 96, 95, 95, 100, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('23000096', 'MH16', 'CP2296H07', 92.5, 90, 95, 90, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('23000096', 'MH17', 'CP2296H07', 42, 30, 50, 40, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('23000096', 'MH18', 'CP2296H07', 42, 50, 30, 60, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('23000096', 'MH19', 'CP2296H07', 47, 50, 40, 60, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('23000096', 'MH20', 'CP2296H07', 86.5, 80, 85, 100, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('23000096', 'MH21', 'CP2296H07', 86.67, 80, 90, 90, NULL, '2025-06-09 11:23:17', '2025-06-09 11:23:17'),
('23000097', 'MH15', 'CP2296H07', 84.5, 90, 75, 100, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('23000097', 'MH16', 'CP2296H07', 78, 80, 80, 70, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('23000097', 'MH17', 'CP2296H07', 46.5, 50, 45, 45, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('23000097', 'MH18', 'CP2296H07', 54, 40, 60, 60, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('23000097', 'MH19', 'CP2296H07', 88, 80, 90, 95, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('23000097', 'MH20', 'CP2296H07', 96.5, 95, 100, 90, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('23000097', 'MH21', 'CP2296H07', 26.67, 0, 0, 80, 'Gian lận thi cử', '2025-06-09 11:23:17', '2025-06-10 05:40:35'),
('23000098', 'MH15', 'CP2296H07', 98.5, 95, 100, 100, NULL, '2025-06-27 08:28:05', '2025-07-01 02:08:47'),
('23000098', 'MH16', 'CP2296H07', 86, 85, 85, 90, NULL, '2025-07-01 05:36:22', '2025-07-01 05:36:22'),
('23000098', 'MH17', 'CP2296H07', 64.5, 60, 65, 70, NULL, '2025-07-01 05:40:22', '2025-07-01 05:40:22'),
('23000098', 'MH18', 'CP2296H07', 53, 40, 50, 80, NULL, '2025-07-01 05:42:21', '2025-07-01 05:42:21'),
('23000098', 'MH19', 'CP2296H07', 59, 50, 70, 45, NULL, '2025-07-01 05:43:52', '2025-07-01 05:43:52'),
('23000098', 'MH20', 'CP2296H07', 93, 90, 100, 80, NULL, '2025-07-01 05:45:54', '2025-07-01 05:45:54'),
('23000098', 'MH21', 'CP2296H07', 96.67, 90, 100, 100, NULL, '2025-06-09 11:23:17', '2025-06-10 05:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `donvi`
--

CREATE TABLE `donvi` (
  `MaDV` varchar(12) NOT NULL,
  `TenDVHienTai` varchar(255) DEFAULT NULL,
  `TenDVTungCongTac` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `donvi`
--

INSERT INTO `donvi` (`MaDV`, `TenDVHienTai`, `TenDVTungCongTac`, `created_at`, `updated_at`) VALUES
('CNTT', 'Khoa CNTT', NULL, '2025-05-15 16:36:26', '2025-05-15 16:36:26'),
('DV001', 'Khoa CNTT', NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25'),
('DV002', 'Khoa Kinh tế', NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25'),
('DV003', 'Phòng CNTT', NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25');

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
('GV001', 'CP2296H07', 'MH15', NULL, NULL, NULL, '2025-06-07 06:09:01', '2025-06-07 06:09:01'),
('GV001', 'CP2296H07', 'MH21', NULL, NULL, NULL, '2025-06-11 07:01:40', '2025-06-11 07:01:40'),
('GV001', 'CP2396G11', 'MH21', NULL, NULL, NULL, '2025-06-11 07:01:40', '2025-06-11 07:01:40'),
('GV004', 'CP2396M02', 'MH21', NULL, NULL, NULL, '2025-06-11 07:15:36', '2025-06-11 07:15:36'),
('GV002', 'CP2296H07', 'MH16', '2025-08-01', '2025-08-30', NULL, '2025-07-01 05:31:10', '2025-07-01 05:31:10'),
('GV003', 'CP2296H07', 'MH17', '2025-08-01', '2025-08-30', NULL, '2025-07-01 05:31:27', '2025-07-01 05:31:27'),
('GV005', 'CP2296H07', 'MH18', '2025-08-01', '2025-08-31', NULL, '2025-07-01 05:32:09', '2025-07-01 05:32:09'),
('GV006', 'CP2296H07', 'MH19', '2025-08-01', '2025-08-30', NULL, '2025-07-01 05:32:27', '2025-07-01 05:32:27'),
('GV007', 'CP2296H07', 'MH20', '2025-08-01', '2025-08-30', NULL, '2025-07-01 05:32:44', '2025-07-01 05:32:44');

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
  `HocVi` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TenChucVu` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `DonViCongTac` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `BangCap` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `LoaiGV` enum('CoHuu','MoiGiang') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CoHuu',
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

INSERT INTO `giaovien` (`MaGV`, `HoTenGV`, `GioiTinh`, `Email`, `EmailCUSC`, `Sdt`, `HocVi`, `TenChucVu`, `DonViCongTac`, `BangCap`, `LoaiGV`, `ChuyenNganh`, `GhiChu`, `NgayBatDauCongTac`, `NgayKetThucCongTac`, `created_at`, `updated_at`) VALUES
('GV001', 'Nguyễn Văn An', 1, 'ngvanan@gmail.com', 'gv001nguyenvanan@cusc.ctu.vn', '0912345678', NULL, NULL, NULL, NULL, 'CoHuu', 'Khoa học máy tính', 'Giảng viên chính', '2020-01-01', NULL, '2025-05-16 07:53:41', '2025-06-03 06:13:21'),
('GV002', 'Trần Thị Bình', 0, 'tranthib@gmail.com', 'gv002tranthibinh@cusc.ctu.vn', '0923456789', NULL, NULL, NULL, NULL, 'MoiGiang', 'Kinh tế học', 'Giảng viên thỉnh giảng', '2018-05-15', NULL, '2025-05-16 07:53:41', '2025-06-03 06:13:21'),
('GV003', 'Phạm Đức Linh', 0, 'phamduclinh@cusc.vn', 'gv003phamduclinh@cusc.ctu.vn', '0943526107', NULL, NULL, NULL, NULL, 'MoiGiang', 'Hệ thống thông tin', NULL, '2018-06-04', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:21'),
('GV004', 'Hoàng Quang Giang', 0, 'hoangquanggiang@cusc.vn', 'gv004hoangquanggiang@cusc.ctu.vn', '0938554271', NULL, NULL, NULL, NULL, 'CoHuu', 'Mạng máy tính', NULL, '2018-06-06', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:21'),
('GV005', 'Hoàng Công Nam', 0, 'hoangcongnam@cusc.vn', 'gv005hoangcongnam@cusc.ctu.vn', '0915354646', NULL, NULL, NULL, NULL, 'MoiGiang', 'Khoa học dữ liệu', NULL, '2019-05-09', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22'),
('GV006', 'Phan Hữu Giang', 1, 'phanhuugiang@cusc.vn', 'gv006phanhuugiang@cusc.ctu.vn', '0987506679', NULL, NULL, NULL, NULL, 'CoHuu', 'Trí tuệ nhân tạo', NULL, '2021-12-19', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22'),
('GV007', 'Phan Đức Phúc', 1, 'phanducphuc@cusc.vn', 'gv007phanducphuc@cusc.ctu.vn', '0982049005', NULL, NULL, NULL, NULL, 'CoHuu', 'Trí tuệ nhân tạo', NULL, '2018-12-11', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22'),
('GV008', 'Đặng Hoàng Giang', 1, 'danghoanggiang@cusc.vn', 'gv008danghoanggiang@cusc.ctu.vn', '0981259529', NULL, NULL, NULL, NULL, 'MoiGiang', 'Trí tuệ nhân tạo', NULL, '2020-06-14', NULL, '2025-05-22 07:40:53', '2025-06-03 06:13:22');

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
-- Dumping data for table `hinh_thuc_danh_gia`
--

INSERT INTO `hinh_thuc_danh_gia` (`id`, `MaChuongTrinh`, `HinhThuc`, `TiLePhanTram`, `SoBaiThi`, `DiemMoiBai`, `ThoiGian`, `DonViThoiGian`, `created_at`, `updated_at`) VALUES
(1, 'OV-7096', 'Lý thuyết trắc nghiệm', 50, 6, 20, 40, 'phút', '2025-06-08 02:57:57', '2025-06-08 02:57:57'),
(2, 'OV-7096', 'Thực hành', 30, 5, 20, 60, 'phút', '2025-06-08 02:57:57', '2025-06-08 02:57:57'),
(3, 'OV-7096', 'Dự án', 20, 1, 100, 24, 'giờ', '2025-06-08 02:57:57', '2025-06-08 02:57:57'),
(4, 'OV-7023', 'Lý thuyết trắc nghiệm', 50, 6, 20, 40, 'phút', '2025-06-27 08:35:31', '2025-06-27 08:35:31'),
(5, 'OV-7023', 'Thực hành', 30, 5, 20, 60, 'phút', '2025-06-27 08:35:31', '2025-06-27 08:35:31'),
(6, 'OV-7023', 'Dự án', 20, 1, 100, 24, 'giờ', '2025-06-27 08:35:31', '2025-06-27 08:35:31'),
(8, 'OV-6062', 'Lý thuyết trắc nghiệm', 100, 5, 100, 40, 'phút', '2025-07-01 01:19:15', '2025-07-01 01:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `hocki`
--

CREATE TABLE `hocki` (
  `MaHK` varchar(50) NOT NULL,
  `TenHK` varchar(30) DEFAULT NULL,
  `TongGioGoc` int DEFAULT NULL,
  `TongGioTrienKhai` int DEFAULT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hocki`
--

INSERT INTO `hocki` (`MaHK`, `TenHK`, `TongGioGoc`, `TongGioTrienKhai`, `MaChuongTrinh`, `created_at`, `updated_at`) VALUES
('OV-7023-HK I', 'HỌC KỲ I', 168, 172, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK II', 'HỌC KỲ II', 218, 200, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK III', 'HỌC KỲ III', 170, 168, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK IV', 'HỌC KỲ IV', 194, 208, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7023-HK V', 'HỌC KỲ V', 168, 172, 'OV-7023', '2025-05-22 15:37:10', '2025-05-22 15:37:10'),
('OV-7096-HK I', 'Học kỳ I', 176, 176, 'OV-7096', '2025-05-22 22:19:42', '2025-05-22 22:19:42');

-- --------------------------------------------------------

--
-- Table structure for table `hocvi`
--

CREATE TABLE `hocvi` (
  `MaHV` varchar(12) NOT NULL,
  `TenHocVi` varchar(50) DEFAULT NULL,
  `NganhHoc` varchar(255) DEFAULT NULL,
  `ChuyenNganh` varchar(255) DEFAULT NULL,
  `CoSoDaoTao` varchar(255) DEFAULT NULL,
  `NamCap` date DEFAULT NULL,
  `HinhThucDaoTao` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hocvi`
--

INSERT INTO `hocvi` (`MaHV`, `TenHocVi`, `NganhHoc`, `ChuyenNganh`, `CoSoDaoTao`, `NamCap`, `HinhThucDaoTao`, `created_at`, `updated_at`) VALUES
('HV001', 'Tiến sĩ', NULL, NULL, NULL, NULL, NULL, '2025-05-15 16:36:26', '2025-05-15 16:36:26'),
('HV002', 'Thạc sĩ', NULL, NULL, NULL, NULL, NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25'),
('HV003', 'Cử nhân', NULL, NULL, NULL, NULL, NULL, '2025-05-16 02:50:25', '2025-05-16 02:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `khoadaotao`
--

CREATE TABLE `khoadaotao` (
  `TenKhoaDaoTao` varchar(20) NOT NULL,
  `ThoiGianDaoTao` varchar(10) DEFAULT NULL,
  `create_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khoadaotao`
--

INSERT INTO `khoadaotao` (`TenKhoaDaoTao`, `ThoiGianDaoTao`, `create_at`, `update_at`) VALUES
('Dài hạn', '2 năm', '2025-05-18 21:26:47', '2025-05-18 21:26:47'),
('Ngắn hạn', '1 Học Kỳ', '2025-05-18 21:26:47', '2025-05-18 21:26:47'),
('Steam', '1 học kỳ', '2025-05-19 15:08:26', '2025-06-25 14:09:53');

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
('ca 1', '08:00 - 11:00'),
('Chiều', '13:00 - 16:00'),
('Chiều 13h-15h', '13:00 - 15:00'),
('Chiều 15h-17h', '15:00 - 17:00'),
('Sáng', '07:00 - 09:00'),
('Sáng 7h-9h', '07:00 - 09:00'),
('Sáng 9h-11h', '09:00 - 11:00'),
('Tối', '18:00 - 21:00'),
('Tối 18h-20h', '18:00 - 20:00'),
('Tối 20h-22h', '20:00 - 22:00');

-- --------------------------------------------------------

--
-- Table structure for table `ldap_accounts`
--

CREATE TABLE `ldap_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `MaTaiKhoan` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','student','teacher','staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'student',
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ldap_accounts`
--

INSERT INTO `ldap_accounts` (`id`, `MaTaiKhoan`, `username`, `email`, `full_name`, `initial_password`, `role`, `is_sent`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin01', 'admin', 'admin@gmail.com', 'admin', 'admin', 'admin', 0, 1, '2025-07-01 08:23:53', '2025-07-01 08:23:53'),
(13, '21010003', '21010003levanc', '21010003levanc@cusc.ctu.vn', 'Lê Văn C', '$2y$12$MfIGAS.bEtBUYiEl.VEI1u.wYdnrLHWcmnyrjYOWkZrL0ptFEroTK', 'student', 1, 1, '2025-06-02 23:55:48', '2025-07-07 23:29:41'),
(14, '23000001', '23000001ovanan', '23000001ovanan@cusc.ctu.vn', 'Đỗ Văn An', '7@lwrYtMzb4@', 'student', 1, 1, '2025-06-02 23:55:48', '2025-06-14 00:08:50'),
(15, '23000002', '23000002ohuuhung', '23000002ohuuhung@cusc.ctu.vn', 'Đỗ Hữu Hùng', 'PkP++4z;lh(Z', 'student', 1, 1, '2025-06-02 23:55:48', '2025-07-06 09:09:54'),
(32, 'GV001', 'gv001nguyenvanan', 'gv001nguyenvanan@cusc.ctu.vn', 'Nguyễn Văn An', '$2y$12$k99GuVyQPJ5khWkfUWLE8.SIyeddGhB4qqnSaYrXaH0UgeStCQLG6', 'teacher', 1, 1, '2025-06-03 06:13:21', '2025-06-03 19:32:06'),
(33, 'GV002', 'gv002tranthibinh', 'gv002tranthibinh@cusc.ctu.vn', 'Trần Thị Bình', '6#u&ev4.Yxt#', 'teacher', 1, 1, '2025-06-03 06:13:21', '2025-06-14 00:15:39'),
(34, 'GV003', 'gv003phamduclinh', 'gv003phamduclinh@cusc.ctu.vn', 'Phạm Đức Linh', '7S>1#:Y7E4En', 'teacher', 1, 1, '2025-06-03 06:13:21', '2025-06-30 22:28:55'),
(35, 'GV004', 'gv004hoangquanggiang', 'gv004hoangquanggiang@cusc.ctu.vn', 'Hoàng Quang Giang', '|L}D@4oq4}wZ', 'teacher', 1, 1, '2025-06-03 06:13:21', '2025-06-30 22:28:57'),
(36, 'GV005', 'gv005hoangcongnam', 'gv005hoangcongnam@cusc.ctu.vn', 'Hoàng Công Nam', '20qr(=f,&6Lo', 'teacher', 1, 1, '2025-06-03 06:13:22', '2025-06-14 00:15:34'),
(37, 'GV006', 'gv006phanhuugiang', 'gv006phanhuugiang@cusc.ctu.vn', 'Phan Hữu Giang', 'Pa)T2^bTR{E?', 'teacher', 1, 1, '2025-06-03 06:13:22', '2025-07-07 08:45:24'),
(38, 'GV007', 'gv007phanducphuc', 'gv007phanducphuc@cusc.ctu.vn', 'Phan Đức Phúc', '^%Ag80SPSQFf', 'teacher', 1, 1, '2025-06-03 06:13:22', '2025-06-14 00:15:37'),
(39, 'GV008', 'gv008danghoanggiang', 'gv008danghoanggiang@cusc.ctu.vn', 'Đặng Hoàng Giang', ';6+399Oxc@4i', 'teacher', 1, 1, '2025-06-03 06:13:22', '2025-06-14 00:15:38');

-- --------------------------------------------------------

--
-- Table structure for table `lichthi`
--

CREATE TABLE `lichthi` (
  `MaLichThi` varchar(12) NOT NULL,
  `MaLop` varchar(12) DEFAULT NULL,
  `MaMH` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `NgayThi` date DEFAULT NULL,
  `KhungGio` varchar(100) DEFAULT NULL,
  `PhongThi` varchar(20) DEFAULT NULL,
  `HinhThucThi` enum('Tự luận','Trắc nghiệm','Thực hành','Bài tập lớn') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Trắc nghiệm',
  `LanThi` int DEFAULT NULL,
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lichthi`
--

INSERT INTO `lichthi` (`MaLichThi`, `MaLop`, `MaMH`, `NgayThi`, `KhungGio`, `PhongThi`, `HinhThucThi`, `LanThi`, `GhiChu`, `created_at`, `updated_at`) VALUES
('LT2506061451', 'CP2296H07', 'MH15', '2025-07-26', '13:00 - 13:40', 'Class1', 'Trắc nghiệm', 1, NULL, '2025-06-06 14:51:16', '2025-06-06 14:51:16'),
('LT2506061453', 'CP2296H07', 'MH15', '2025-07-27', '13:00 - 14:00', 'Lab1', 'Thực hành', 1, NULL, '2025-06-06 14:53:23', '2025-06-06 14:53:23'),
('LT2506061454', 'CP2396G11', 'MH21', '2025-07-31', '13:00 - 13:40', 'Class2', 'Trắc nghiệm', 1, NULL, '2025-06-06 14:54:02', '2025-06-08 01:54:11'),
('LT25060634', 'CP2296H07', 'MH16', '2025-08-30', '09:00 - 10:00', 'Lab3', 'Thực hành', 1, NULL, '2025-06-06 15:03:34', '2025-06-06 15:03:34');

-- --------------------------------------------------------

--
-- Table structure for table `lophoc`
--

CREATE TABLE `lophoc` (
  `MaLop` varchar(12) NOT NULL,
  `TenLop` varchar(100) DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lophoc`
--

INSERT INTO `lophoc` (`MaLop`, `TenLop`, `NgayBatDau`, `MaChuongTrinh`, `created_at`, `updated_at`) VALUES
('CP2296H07', 'Khoa học dữ liệu', NULL, 'OV-7096', '2025-05-22 14:15:43', '2025-05-25 04:25:02'),
('CP2396G11', 'Lập trình viên', NULL, 'OV-7023', '2025-05-22 14:15:43', '2025-05-22 14:15:43'),
('CP2396M02', 'Quản trị mạng', NULL, 'OV-7023', '2025-05-22 14:15:43', '2025-05-22 14:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '2025_05_16_140909_create_giaovien_table', 2),
(8, '2025_05_19_083122_add_hoso_columns_to_hosotuyensinh', 3),
(9, '2025_05_19_134207_create_feedback_table', 4),
(10, '2025_05_19_134626_create_thietbi_table', 4),
(11, '2025_05_19_134816_create_tainguyen_hoctap_table', 4),
(12, '2025_05_19_134831_create_tuvan_tuyensinh_table', 5),
(13, '2025_05_28_082349_update_diemthi_table_with_detailed_scoring', 6),
(14, '2025_05_29_063302_create_sinh_vien_du_this_table', 7),
(15, '2025_05_30_092315_create_call_records_table', 8),
(16, '2024_03_21_000000_add_status_to_phonghoc_table', 9),
(17, '2025_05_30_075534_add_columns_to_danh_sach_phong_table', 9),
(18, '2025_05_31_060619_create_ldap_accounts_table', 9),
(19, '2025_06_03_161614_add_time_columns_to_hocki_table', 10),
(20, '2025_06_03_173050_alter_khung_gio_thoi_gian_column', 10),
(21, '2025_06_12_020943_create_sessions_table', 11),
(22, '2025_06_30_052339_create_thong_ke_hoc_taps_table', 12),
(23, '2025_07_01_100846_create_thong_ke_bao_cao_do_ans_table', 13),
(24, '2025_07_01_104023_create_thong_ke_bao_cao_do_ans_table', 14),
(25, '2025_07_01_131245_create_bao_cao_do_an_uploads_table', 15),
(26, '2025_07_01_162004_create_bao_cao_do_an_uploads_table', 16);

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
('eProject-Website Development', 'MH06', 2, 8, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('HTML5,CSS and Javascript', 'MH07', 40, 44, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Information Systems Analysis(Core)', 'MH08', 24, 12, 1, 0, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Java Programming - I', 'MH09', 36, 40, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Java Programming -II', 'MH10', 40, 42, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Logic Building and Elementary Programing', 'MH11', 40, 42, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Markup Language & JSON ', 'MH12', 16, 16, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('PHP Web Development with Laravel Framework', 'MH13', 40, 40, 0, 1, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Project-Java Application Development', 'MH14', 2, 12, 1, 0, NULL, '2025-05-23 06:20:04', '2025-05-23 06:20:04'),
('Phân tích Dữ liệu với MS Excel', 'MH15', 16, 16, NULL, NULL, 1, '2025-05-22 23:20:18', '2025-05-22 23:20:18'),
('Xử lý Dữ liệu bằng T-SQL', 'MH16', 16, 16, NULL, NULL, 1, '2025-05-22 23:20:57', '2025-05-22 23:20:57'),
('Quản lý Tập dữ liệu lớn với MongoDB', 'MH17', 32, 32, NULL, NULL, 1, '2025-05-22 23:21:21', '2025-05-22 23:21:21'),
('Phân tích Thống kê Suy luận', 'MH18', 16, 16, 1, NULL, NULL, '2025-05-22 23:21:39', '2025-05-22 23:21:39'),
('Khoa học Dữ liệu sử dụng R Programming', 'MH19', 36, 36, NULL, 1, NULL, '2025-05-22 23:22:07', '2025-05-22 23:22:07'),
('Lập trình Ứng dụng bằng Python', 'MH20', 36, 36, NULL, 1, NULL, '2025-05-22 23:22:25', '2025-05-22 23:22:25'),
('Dự án - Phân tích Dữ liệu với R', 'MH21', 24, 24, 1, NULL, NULL, '2025-05-22 23:22:44', '2025-05-22 23:22:44');

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

-- --------------------------------------------------------

--
-- Table structure for table `ngaytuhoc`
--

CREATE TABLE `ngaytuhoc` (
  `MaNgayTuHoc` int NOT NULL,
  `TenNgayTuHoc` varchar(50) DEFAULT NULL,
  `NgayBDTuHoc` date DEFAULT NULL,
  `NgayKTTuHoc` date DEFAULT NULL,
  `TenTKB` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieuphancongthi`
--

CREATE TABLE `phieuphancongthi` (
  `MaPhanCong` int NOT NULL,
  `MaLichThi` varchar(12) DEFAULT NULL,
  `MaGV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `VaiTro` enum('Cán bộ coi thi','Giám sát','Chấm thi') DEFAULT 'Cán bộ coi thi',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phieuphancongthi`
--

INSERT INTO `phieuphancongthi` (`MaPhanCong`, `MaLichThi`, `MaGV`, `VaiTro`, `created_at`, `updated_at`) VALUES
(9, 'LT25060634', 'GV008', 'Cán bộ coi thi', '2025-07-06 07:18:39', '2025-07-06 07:18:39'),
(11, 'LT25060634', 'GV005', 'Cán bộ coi thi', '2025-07-06 07:19:14', '2025-07-06 07:19:14'),
(12, 'LT2506061453', 'GV004', 'Cán bộ coi thi', '2025-07-06 07:21:17', '2025-07-06 07:21:17'),
(13, 'LT2506061453', 'GV001', 'Cán bộ coi thi', '2025-07-06 07:21:17', '2025-07-06 07:21:17');

-- --------------------------------------------------------

--
-- Table structure for table `phonghoc`
--

CREATE TABLE `phonghoc` (
  `TenPhong` varchar(20) NOT NULL,
  `LoaiPhong` varchar(255) DEFAULT NULL,
  `SucChua` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TrangThai` enum('Trống','Đang sử dụng','Bảo trì') NOT NULL DEFAULT 'Trống'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phonghoc`
--

INSERT INTO `phonghoc` (`TenPhong`, `LoaiPhong`, `SucChua`, `created_at`, `updated_at`, `TrangThai`) VALUES
('Class1', 'Phòng lý thuyết 1', NULL, '2025-05-22 09:28:21', '2025-05-22 06:55:12', 'Trống'),
('Class2', 'Phòng lý thuyết 2', NULL, '2025-05-22 09:28:21', '2025-05-22 06:55:20', 'Trống'),
('Class3', 'Phòng lý thuyết 3', NULL, '2025-05-22 02:28:29', '2025-05-22 06:55:30', 'Trống'),
('Class4', 'Phòng lý thuyết 4', NULL, '2025-05-22 06:55:45', '2025-05-22 06:55:45', 'Trống'),
('Lab1', 'Phòng thực hành 1', NULL, '2025-05-22 09:28:21', '2025-06-27 07:47:53', 'Đang sử dụng'),
('Lab2', 'Phòng thực hành 2', NULL, '2025-05-22 09:28:21', '2025-06-27 07:47:56', 'Đang sử dụng'),
('Lab3', 'Phòng thực hành 3', NULL, '2025-05-22 06:56:10', '2025-05-22 06:56:10', 'Trống'),
('Lab4', 'Phòng thực hành 4', NULL, '2025-05-22 06:56:20', '2025-05-22 06:56:20', 'Trống');

-- --------------------------------------------------------

--
-- Table structure for table `phutrach`
--

CREATE TABLE `phutrach` (
  `CongViecPhuTrach` varchar(255) NOT NULL,
  `MieuTaChiTiet` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phutrach`
--

INSERT INTO `phutrach` (`CongViecPhuTrach`, `MieuTaChiTiet`, `created_at`, `updated_at`) VALUES
('Giảng dạy', 'Giảng dạy các bộ môn', '2025-05-16 20:21:20', '2025-05-16 20:28:49'),
('Quản lý khoa', NULL, '2025-05-16 20:35:29', '2025-05-16 20:35:29'),
('Quản trị hệ thống', NULL, '2025-05-16 20:35:37', '2025-05-16 20:35:37');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('70GIB0XYNTAeeZdjrtVtYaiQ2NjQ2cdZlQoWahID', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo2NDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3Rob25nLWtlL2V4cG9ydC10aG9uZy1rZS9DUDIyOTZIMDcvT1YtNzA5NiI7fXM6NjoiX3Rva2VuIjtzOjQwOiJmQmZQV2NPSGJGcWFSMWlzV1pqMXhUOVZiYVlVMnd3czRHajJvVExPIjtzOjE0OiJjYXB0Y2hhX3BocmFzZSI7czo1OiI3UGhxcSI7czoyOiJpZCI7czo3OiJhZG1pbjAxIjtzOjQ6InVzZXIiO3M6NToiYWRtaW4iO3M6MTE6ImRpc3BsYXluYW1lIjtzOjU6ImFkbWluIjtzOjQ6InJvbGUiO3M6NToiYWRtaW4iO3M6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fX0=', 1752417151);

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `MaSV` varchar(12) NOT NULL,
  `MaEnroll` varchar(6) DEFAULT NULL,
  `HoTen` varchar(30) DEFAULT NULL,
  `InDebt` varchar(255) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` tinyint(1) DEFAULT NULL,
  `SoCCCD` int DEFAULT NULL,
  `NgayCap` date DEFAULT NULL,
  `NoiCap` varchar(80) DEFAULT NULL,
  `Sdt` varchar(15) DEFAULT NULL,
  `NoiSinh` varchar(50) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `Zalo` int DEFAULT NULL,
  `Receipt` int DEFAULT NULL,
  `Invoice` int DEFAULT NULL,
  `Billing` float(10,2) DEFAULT NULL,
  `Coll` float(10,2) DEFAULT NULL,
  `Billing(VND)` int DEFAULT NULL,
  `Coll(VND)` int DEFAULT NULL,
  `Discount` decimal(3,2) DEFAULT NULL,
  `LiDo` varchar(255) DEFAULT NULL,
  `NgayDangKi` date DEFAULT NULL,
  `HoTenNguoiThan` varchar(30) DEFAULT NULL,
  `MoiQuanHe` varchar(15) DEFAULT NULL,
  `SdtNguoiThan` int DEFAULT NULL,
  `ZaloNguoiThan` int DEFAULT NULL,
  `EmailNguoiThan` varchar(40) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `EmailCUSC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_CUSC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Size` varchar(12) DEFAULT NULL,
  `TinhTrangHocTap` enum('DangHoc','DaTotNghiep','DaNghiHoc') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`MaSV`, `MaEnroll`, `HoTen`, `InDebt`, `NgaySinh`, `GioiTinh`, `SoCCCD`, `NgayCap`, `NoiCap`, `Sdt`, `NoiSinh`, `DiaChi`, `Zalo`, `Receipt`, `Invoice`, `Billing`, `Coll`, `Billing(VND)`, `Coll(VND)`, `Discount`, `LiDo`, `NgayDangKi`, `HoTenNguoiThan`, `MoiQuanHe`, `SdtNguoiThan`, `ZaloNguoiThan`, `EmailNguoiThan`, `Email`, `EmailCUSC`, `password_CUSC`, `Size`, `TinhTrangHocTap`, `created_at`, `updated_at`) VALUES
('21010001', NULL, 'Nguyễn Văn A', NULL, '2003-06-12', 1, 12345678, NULL, NULL, '0944902423', NULL, 'Ninh Kiều', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nva1@gmail.com', '21010001nguyenva@cusc.ctu.vn', NULL, NULL, 'DangHoc', '2025-05-11 06:31:03', '2025-05-31 16:14:19'),
('21010002', NULL, 'Trần Thị B', NULL, '1970-01-01', 0, 12345679, NULL, NULL, '0912345679', NULL, 'Bình Thủy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ttb2@gmail.com', '21010002tranthib@cusc.ctu.vn', NULL, NULL, 'DangHoc', '2025-05-11 06:31:03', '2025-05-31 16:14:19'),
('21010003', NULL, 'Lê Văn C', NULL, '1970-01-01', 1, 12345680, NULL, NULL, '0912345680', NULL, 'Cái Răng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lvc3@gmail.com', '21010003levanc@cusc.ctu.vn', NULL, NULL, 'DangHoc', '2025-05-11 06:31:03', '2025-06-03 06:55:48'),
('23000001', NULL, 'Đỗ Văn An', NULL, '2005-07-12', 1, 222742174, '2023-09-05', 'Công an Tiền Giang', '0862455565', 'Vĩnh Long', '989 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Minh Giang', 'Chị', 823642868, NULL, 'rosalinda41@zulauf.net', 'dovanan@gmail.com', '23000001ovanan@cusc.ctu.vn', NULL, NULL, 'DangHoc', NULL, '2025-06-03 06:55:48'),
('23000002', NULL, 'Đỗ Hữu Hùng', NULL, '2006-08-19', 1, 218008983, '2023-06-30', 'Công an Trà Vinh', '0705117568', 'Cà Mau', '976 3/2, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Hữu Mai', 'Em', 886874835, NULL, 'monte.casper@hotmail.com', 'dohuudung@gmail.com', '23000002ohuuhung@cusc.ctu.vn', NULL, NULL, 'DaTotNghiep', NULL, '2025-06-03 06:55:48'),
('23000003', NULL, 'Huỳnh Đức Bình', NULL, '2003-10-31', 0, 613326769, '2023-09-18', 'Công an Hậu Giang', '0332699777', 'Sóc Trăng', '835 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Đức An', 'Chị', 841161800, NULL, 'pearline.pfeffer@yahoo.com', 'huỳnhdứcbình@gmail.com', '23000003@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000004', NULL, 'Phan Đức Em', NULL, '2003-08-25', 1, 211193627, '2024-12-19', 'Công an Sóc Trăng', '0868728484', 'Bạc Liêu', '354 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Quang Mai', 'Chị', 349820726, NULL, 'wiegand.mariam@reichert.com', 'phandứcem@gmail.com', '23000004@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000005', NULL, 'Võ Quang Phúc', NULL, '2003-12-03', 1, 139868417, '2025-04-03', 'Công an Hậu Giang', '0398780304', 'Hậu Giang', '864 Nguyễn Văn Linh, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Văn Giang', 'Chị', 706580553, NULL, 'esperanza50@hotmail.com', 'võquangphúc@gmail.com', '23000005@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000006', NULL, 'Đỗ Công An', NULL, '2006-10-31', 1, 631494395, '2024-05-13', 'Công an Hậu Giang', '0825018724', 'Tiền Giang', '641 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Công Bình', 'Mẹ', 815752896, NULL, 'frami.gabriella@hotmail.com', 'dỗcôngan@gmail.com', '23000006@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000007', NULL, 'Vũ Quang Hùng', NULL, '2006-12-22', 1, 432494396, '2025-02-17', 'Công an Vĩnh Long', '0783434137', 'Sóc Trăng', '548 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Công An', 'Em', 930534705, NULL, 'klein.marjorie@hotmail.com', 'vũquanghùng@gmail.com', '23000007@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000008', NULL, 'Phạm Thành Linh', NULL, '2006-06-09', 1, 231601113, '2025-05-19', 'Công an Đồng Tháp', '0989362414', 'Tiền Giang', '505 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Hoàng Em', 'Cha', 386533654, NULL, 'ryan41@gmail.com', 'phạmthànhlinh@gmail.com', '23000008@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000009', NULL, 'Phạm Thành Phúc', NULL, '2004-06-16', 0, 219171756, '2023-09-01', 'Công an An Giang', '0970190165', 'Long An', '91 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Hữu Mai', 'Anh', 868472800, NULL, 'tremblay.ethyl@dare.net', 'phạmthànhphúc@gmail.com', '23000009@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000010', NULL, 'Huỳnh Công Mai', NULL, '2007-04-20', 1, 139271516, '2023-07-16', 'Công an Bạc Liêu', '0969550111', 'Trà Vinh', '991 Mậu Thân, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Nguyễn Văn Khang', 'Mẹ', 766768869, NULL, 'mireille.mayert@gmail.com', 'huỳnhcôngmai@gmail.com', '23000010@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000011', NULL, 'Vũ Quang Giang', NULL, '2003-10-27', 1, 528350724, '2023-11-11', 'Công an Trà Vinh', '0379431176', 'Bạc Liêu', '66 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Quang Linh', 'Anh', 350173268, NULL, 'qkuvalis@crist.com', 'vũquanggiang@gmail.com', '23000011@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000012', NULL, 'Võ Hữu Hùng', NULL, '2006-07-24', 1, 415935900, '2024-06-05', 'Công an Đồng Tháp', '0963756323', 'An Giang', '318 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Minh Giang', 'Em', 966881236, NULL, 'breanne.leannon@gmail.com', 'võhữuhùng@gmail.com', '23000012@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000013', NULL, 'Hoàng Văn Em', NULL, '2003-11-30', 1, 530020089, '2024-08-16', 'Công an Bến Tre', '0855812605', 'Bạc Liêu', '810 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Hoàng Em', 'Em', 971394841, NULL, 'edonnelly@yahoo.com', 'hoàngvănem@gmail.com', '23000013@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000014', NULL, 'Hoàng Minh Giang', NULL, '2005-06-02', 0, 435243208, '2023-07-31', 'Công an Sóc Trăng', '0372717995', 'Vĩnh Long', '963 Nguyễn Văn Linh, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Hữu Linh', 'Mẹ', 333336310, NULL, 'sblick@yahoo.com', 'hoàngminhgiang@gmail.com', '23000014@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000015', NULL, 'Nguyễn Hoàng Em', NULL, '2006-01-15', 1, 538959364, '2023-09-17', 'Công an Cà Mau', '0779341262', 'An Giang', '696 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Hoàng Nam', 'Chị', 324822475, NULL, 'ptowne@stroman.info', 'nguyễnhoàngem@gmail.com', '23000015@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000016', NULL, 'Bùi Minh Mai', NULL, '2005-05-22', 0, 612424890, '2024-11-25', 'Công an Cà Mau', '0892988536', 'Hậu Giang', '90 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Minh Em', 'Chị', 986597018, NULL, 'zlang@bartell.com', 'bùiminhmai@gmail.com', '23000016@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000017', NULL, 'Bùi Thị Bình', NULL, '2005-11-02', 0, 218121998, '2025-04-05', 'Công an Tiền Giang', '0344592343', 'Tiền Giang', '489 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Minh Khang', 'Mẹ', 995573785, NULL, 'dibbert.jettie@parisian.com', 'bùithịbình@gmail.com', '23000017@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000018', NULL, 'Phan Đức Dũng', NULL, '2004-08-27', 0, 628585995, '2023-05-20', 'Công an Sóc Trăng', '0848054031', 'Đồng Tháp', '572 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Quang Linh', 'Mẹ', 974912627, NULL, 'lamont18@becker.net', 'phandứcdũng@gmail.com', '23000018@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000019', NULL, 'Vũ Quang Giang', NULL, '2006-10-26', 1, 513491111, '2024-05-11', 'Công an Vĩnh Long', '0774567812', 'Cần Thơ', '433 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Quang Em', 'Chị', 777464114, NULL, 'marion.weimann@gmail.com', 'vũquanggiang@gmail.com', '23000019@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000020', NULL, 'Trần Thành Mai', NULL, '2003-12-23', 1, 229447939, '2025-03-24', 'Công an Bến Tre', '0326426298', 'Bạc Liêu', '534 Nguyễn Văn Linh, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Công Phúc', 'Em', 935915054, NULL, 'gregoria30@gmail.com', 'trầnthànhmai@gmail.com', '23000020@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000021', NULL, 'Phan Hữu Hùng', NULL, '2007-01-29', 0, 315625960, '2024-05-09', 'Công an Long An', '0370436666', 'Vĩnh Long', '485 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Hữu Khang', 'Mẹ', 779106851, NULL, 'isom.bartoletti@mitchell.biz', 'phanhữuhùng@gmail.com', '23000021@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000022', NULL, 'Đặng Minh Giang', NULL, '2003-08-06', 1, 611151177, '2024-10-17', 'Công an Cần Thơ', '0325114720', 'Vĩnh Long', '556 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Văn An', 'Anh', 706761372, NULL, 'maida.hegmann@russel.com', 'dặngminhgiang@gmail.com', '23000022@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000023', NULL, 'Bùi Đức Mai', NULL, '2005-01-11', 1, 238128340, '2023-06-18', 'Công an Vĩnh Long', '0795312964', 'An Giang', '582 Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Quang Linh', 'Em', 828114347, NULL, 'jeanie.doyle@hotmail.com', 'bùidứcmai@gmail.com', '23000023@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000024', NULL, 'Đặng Đức Nam', NULL, '2003-05-23', 0, 627996487, '2024-04-06', 'Công an Vĩnh Long', '0331566911', 'Hậu Giang', '904 Mậu Thân, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Minh Dũng', 'Cha', 795962272, NULL, 'edmond84@luettgen.com', 'dặngdứcnam@gmail.com', '23000024@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000025', NULL, 'Võ Hữu Nam', NULL, '2004-09-09', 0, 314880664, '2023-09-10', 'Công an An Giang', '0337461276', 'Đồng Tháp', '867 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Công Em', 'Anh', 386478829, NULL, 'tblanda@hotmail.com', 'võhữunam@gmail.com', '23000025@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000026', NULL, 'Phan Hữu Dũng', NULL, '2006-12-05', 1, 334917817, '2024-03-26', 'Công an Vĩnh Long', '0816445344', 'Sóc Trăng', '380 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Minh Phúc', 'Chị', 862980452, NULL, 'addie70@hotmail.com', 'phanhữudũng@gmail.com', '23000026@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000027', NULL, 'Huỳnh Văn Phúc', NULL, '2006-08-07', 1, 122416011, '2025-04-20', 'Công an Tiền Giang', '0393291190', 'Long An', '76 Nguyễn Văn Cừ, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Quang An', 'Cha', 860208830, NULL, 'broderick.johnston@hotmail.com', 'huỳnhvănphúc@gmail.com', '23000027@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000028', NULL, 'Đỗ Thị Nam', NULL, '2005-02-24', 0, 229420630, '2025-04-01', 'Công an Sóc Trăng', '0358356921', 'Kiên Giang', '37 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Văn Nam', 'Cha', 362668106, NULL, 'uhoeger@nolan.com', 'dỗthịnam@gmail.com', '23000028@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000029', NULL, 'Đỗ Công Khang', NULL, '2005-07-26', 0, 629933286, '2025-01-21', 'Công an Sóc Trăng', '0342250181', 'Vĩnh Long', '344 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Hoàng Hùng', 'Anh', 965122310, NULL, 'tremblay.kirk@terry.com', 'dỗcôngkhang@gmail.com', '23000029@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000030', NULL, 'Trần Văn Nam', NULL, '2006-12-10', 0, 221735165, '2023-05-23', 'Công an Đồng Tháp', '0862981501', 'Kiên Giang', '297 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Minh Bình', 'Mẹ', 373410491, NULL, 'sterling.dickens@schmitt.info', 'trầnvănnam@gmail.com', '23000030@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000031', NULL, 'Đỗ Công Linh', NULL, '2005-09-18', 0, 612856019, '2024-04-22', 'Công an An Giang', '0880490891', 'Bạc Liêu', '62 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Nguyễn Thành Phúc', 'Chị', 868938133, NULL, 'slegros@yahoo.com', 'dỗcônglinh@gmail.com', '23000031@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000032', NULL, 'Lê Công Linh', NULL, '2005-08-02', 1, 336375902, '2023-08-31', 'Công an Bạc Liêu', '0884832090', 'Bạc Liêu', '536 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Bình', 'Em', 818247185, NULL, 'rashawn48@schimmel.com', 'lêcônglinh@gmail.com', '23000032@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000033', NULL, 'Trần Quang Cường', NULL, '2003-07-13', 0, 213303281, '2025-05-11', 'Công an Cà Mau', '0937281055', 'Tiền Giang', '723 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Em', 'Mẹ', 347403800, NULL, 'benton.walter@hotmail.com', 'trầnquangcường@gmail.com', '23000033@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000034', NULL, 'Nguyễn Thành Linh', NULL, '2005-11-05', 1, 215790808, '2023-07-17', 'Công an Kiên Giang', '0369710389', 'Đồng Tháp', '528 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Đức Hùng', 'Em', 381356201, NULL, 'jones.darlene@stoltenberg.com', 'nguyễnthànhlinh@gmail.com', '23000034@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000035', NULL, 'Vũ Công Hùng', NULL, '2005-04-29', 1, 130301974, '2023-08-15', 'Công an Hậu Giang', '0837022546', 'Trà Vinh', '368 Nguyễn Văn Cừ, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Hữu Cường', 'Em', 770271581, NULL, 'alejandrin.jast@yahoo.com', 'vũcônghùng@gmail.com', '23000035@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000036', NULL, 'Trần Hoàng Khang', NULL, '2006-03-17', 1, 221502787, '2024-11-24', 'Công an Kiên Giang', '0896079962', 'Sóc Trăng', '635 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Hoàng Phúc', 'Cha', 996104338, NULL, 'nola30@bradtke.org', 'trầnhoàngkhang@gmail.com', '23000036@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000037', NULL, 'Vũ Thành Cường', NULL, '2003-06-13', 1, 339145156, '2023-10-18', 'Công an Cần Thơ', '0773029160', 'Kiên Giang', '83 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Thị Bình', 'Chị', 764682357, NULL, 'ayla69@morar.com', 'vũthànhcường@gmail.com', '23000037@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000038', NULL, 'Vũ Hoàng Hùng', NULL, '2004-02-12', 1, 129453558, '2024-11-24', 'Công an Bạc Liêu', '0771852519', 'An Giang', '773 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Thành Mai', 'Mẹ', 784351467, NULL, 'electa42@hotmail.com', 'vũhoànghùng@gmail.com', '23000038@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000039', NULL, 'Võ Minh Mai', NULL, '2005-08-08', 1, 129487516, '2024-08-03', 'Công an Hậu Giang', '0332489345', 'Tiền Giang', '136 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Hoàng Bình', 'Anh', 346774698, NULL, 'julius.gislason@schmitt.net', 'võminhmai@gmail.com', '23000039@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000040', NULL, 'Phan Hữu Phúc', NULL, '2007-01-21', 1, 518827139, '2025-04-24', 'Công an Cần Thơ', '0840027653', 'Sóc Trăng', '770 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thị Khang', 'Cha', 383512078, NULL, 'streich.waino@raynor.com', 'phanhữuphúc@gmail.com', '23000040@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000041', NULL, 'Đỗ Đức Linh', NULL, '2004-12-28', 0, 415723949, '2025-03-10', 'Công an Bạc Liêu', '0901534038', 'Hậu Giang', '485 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Minh Linh', 'Mẹ', 357814492, NULL, 'goreilly@gmail.com', 'dỗdứclinh@gmail.com', '23000041@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000042', NULL, 'Nguyễn Hữu Bình', NULL, '2004-04-03', 0, 533609104, '2025-04-20', 'Công an Bạc Liêu', '0355102213', 'Cần Thơ', '487 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Văn Giang', 'Cha', 865189101, NULL, 'ferne.haag@hilpert.com', 'nguyễnhữubình@gmail.com', '23000042@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000043', NULL, 'Phạm Hữu Khang', NULL, '2003-12-06', 0, 139377617, '2024-01-27', 'Công an Hậu Giang', '0843539393', 'Vĩnh Long', '148 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Văn Phúc', 'Anh', 901700850, NULL, 'garnet07@hotmail.com', 'phạmhữukhang@gmail.com', '23000043@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000044', NULL, 'Phan Văn Mai', NULL, '2003-09-29', 1, 333628781, '2023-09-25', 'Công an Sóc Trăng', '0700470647', 'Bạc Liêu', '321 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Công Em', 'Em', 811746137, NULL, 'liza.huels@sipes.com', 'phanvănmai@gmail.com', '23000044@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000045', NULL, 'Lê Văn Bình', NULL, '2004-02-18', 1, 228945302, '2024-03-16', 'Công an Hậu Giang', '0814008345', 'Tiền Giang', '496 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Thành Nam', 'Chị', 998880801, NULL, 'tconn@yahoo.com', 'lêvănbình@gmail.com', '23000045@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000046', NULL, 'Lê Thành Hùng', NULL, '2004-10-12', 1, 424361386, '2024-04-25', 'Công an Sóc Trăng', '0333752925', 'Bạc Liêu', '918 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Văn Mai', 'Em', 964384784, NULL, 'rosella.hauck@collins.com', 'lêthànhhùng@gmail.com', '23000046@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000047', NULL, 'Phạm Văn An', NULL, '2004-05-05', 1, 631214545, '2023-05-27', 'Công an Vĩnh Long', '0821675324', 'Cà Mau', '291 Mậu Thân, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Minh Linh', 'Chị', 359788377, NULL, 'pedro14@hotmail.com', 'phạmvănan@gmail.com', '23000047@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000048', NULL, 'Bùi Minh Linh', NULL, '2006-11-20', 0, 138441154, '2024-02-08', 'Công an Hậu Giang', '0838612275', 'Sóc Trăng', '163 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Đức An', 'Cha', 817297042, NULL, 'koepp.oma@gmail.com', 'bùiminhlinh@gmail.com', '23000048@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000049', NULL, 'Hoàng Hoàng Linh', NULL, '2003-10-18', 0, 338370166, '2024-06-17', 'Công an Hậu Giang', '0373069092', 'Vĩnh Long', '608 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Thị Phúc', 'Cha', 785797144, NULL, 'jast.hershel@gmail.com', 'hoànghoànglinh@gmail.com', '23000049@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000050', NULL, 'Vũ Minh Cường', NULL, '2006-09-08', 0, 117147994, '2024-06-01', 'Công an Cần Thơ', '0391778133', 'An Giang', '84 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Quang Nam', 'Chị', 781584475, NULL, 'harvey.darby@larson.com', 'vũminhcường@gmail.com', '23000050@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000051', NULL, 'Hoàng Hoàng An', NULL, '2003-06-14', 0, 613913392, '2024-07-21', 'Công an Trà Vinh', '0984041095', 'Bạc Liêu', '653 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Văn Cường', 'Chị', 901495468, NULL, 'ressie34@kilback.com', 'hoànghoàngan@gmail.com', '23000051@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000052', NULL, 'Huỳnh Minh Phúc', NULL, '2006-03-01', 1, 519810376, '2025-03-08', 'Công an Kiên Giang', '0354131244', 'Hậu Giang', '259 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Văn Linh', 'Mẹ', 989738792, NULL, 'cummerata.brooks@medhurst.com', 'huỳnhminhphúc@gmail.com', '23000052@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000053', NULL, 'Nguyễn Hữu Mai', NULL, '2004-06-25', 1, 210645569, '2024-12-20', 'Công an Bạc Liêu', '0898545357', 'An Giang', '920 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Công Bình', 'Cha', 382763987, NULL, 'terry.forrest@powlowski.com', 'nguyễnhữumai@gmail.com', '23000053@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000054', NULL, 'Huỳnh Đức Linh', NULL, '2003-12-29', 0, 637280264, '2025-03-11', 'Công an Cà Mau', '0838363698', 'Trà Vinh', '367 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Đức Nam', 'Chị', 397565383, NULL, 'cristopher.donnelly@ratke.com', 'huỳnhdứclinh@gmail.com', '23000054@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000055', NULL, 'Phạm Công Mai', NULL, '2004-03-28', 0, 624824391, '2025-03-21', 'Công an Bạc Liêu', '0885807146', 'Cà Mau', '860 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Quang An', 'Em', 937037237, NULL, 'ldeckow@wolf.com', 'phạmcôngmai@gmail.com', '23000055@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000056', NULL, 'Hoàng Đức Em', NULL, '2004-10-18', 0, 410890725, '2023-05-24', 'Công an Kiên Giang', '0882721526', 'Trà Vinh', '491 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Hoàng An', 'Cha', 844199100, NULL, 'anibal.gottlieb@yahoo.com', 'hoàngdứcem@gmail.com', '23000056@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000057', NULL, 'Huỳnh Thị Linh', NULL, '2004-11-17', 1, 139125985, '2024-07-31', 'Công an Đồng Tháp', '0903539508', 'Kiên Giang', '646 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Thị Dũng', 'Anh', 775022692, NULL, 'stark.cara@hotmail.com', 'huỳnhthịlinh@gmail.com', '23000057@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000058', NULL, 'Huỳnh Quang Phúc', NULL, '2003-09-15', 0, 418518946, '2024-03-14', 'Công an Kiên Giang', '0363313073', 'An Giang', '893 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thị Bình', 'Em', 899078860, NULL, 'brain51@yahoo.com', 'huỳnhquangphúc@gmail.com', '23000058@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000059', NULL, 'Võ Thị Nam', NULL, '2004-12-19', 0, 113493714, '2024-07-24', 'Công an Vĩnh Long', '0899583529', 'An Giang', '343 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Đức Giang', 'Em', 846560644, NULL, 'filiberto90@hotmail.com', 'võthịnam@gmail.com', '23000059@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000060', NULL, 'Bùi Công Khang', NULL, '2006-01-18', 1, 117925110, '2024-01-27', 'Công an Kiên Giang', '0972654688', 'Cần Thơ', '33 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Công Giang', 'Chị', 975686836, NULL, 'magnus41@hotmail.com', 'bùicôngkhang@gmail.com', '23000060@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000061', NULL, 'Huỳnh Hữu Hùng', NULL, '2003-09-15', 0, 217960251, '2025-04-30', 'Công an Hậu Giang', '0834761254', 'Cà Mau', '793 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Minh Khang', 'Anh', 975025518, NULL, 'mckayla.moen@hotmail.com', 'huỳnhhữuhùng@gmail.com', '23000061@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000062', NULL, 'Đỗ Văn Mai', NULL, '2003-12-30', 0, 532179938, '2024-04-02', 'Công an Vĩnh Long', '0795505503', 'Bến Tre', '812 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Công Dũng', 'Anh', 338123968, NULL, 'dooley.ozella@hotmail.com', 'dỗvănmai@gmail.com', '23000062@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000063', NULL, 'Hoàng Đức Phúc', NULL, '2006-06-27', 1, 225326480, '2025-05-06', 'Công an Vĩnh Long', '0848828056', 'Cà Mau', '439 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Đức Giang', 'Mẹ', 792471159, NULL, 'keeling.alexandro@hotmail.com', 'hoàngdứcphúc@gmail.com', '23000063@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000064', NULL, 'Bùi Hữu Khang', NULL, '2007-01-09', 0, 621802533, '2024-11-11', 'Công an Cần Thơ', '0356610877', 'Cần Thơ', '767 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Minh Phúc', 'Cha', 344231009, NULL, 'mrodriguez@gleason.com', 'bùihữukhang@gmail.com', '23000064@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000065', NULL, 'Đỗ Văn Hùng', NULL, '2007-03-22', 1, 630610723, '2023-09-14', 'Công an Kiên Giang', '0855909387', 'An Giang', '453 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Công Phúc', 'Mẹ', 982156889, NULL, 'korbin.mayert@gmail.com', 'dỗvănhùng@gmail.com', '23000065@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000066', NULL, 'Lê Hoàng Giang', NULL, '2005-04-26', 1, 634254463, '2023-07-23', 'Công an Trà Vinh', '0839659754', 'Trà Vinh', '243 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Công An', 'Mẹ', 793747464, NULL, 'britney.rice@hotmail.com', 'lêhoànggiang@gmail.com', '23000066@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000067', NULL, 'Hoàng Hoàng Em', NULL, '2004-01-03', 0, 420467573, '2023-07-31', 'Công an An Giang', '0841618932', 'Hậu Giang', '720 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Dũng', 'Mẹ', 889939914, NULL, 'leslie68@gmail.com', 'hoànghoàngem@gmail.com', '23000067@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000068', NULL, 'Huỳnh Hữu Hùng', NULL, '2007-03-29', 1, 512765926, '2024-11-22', 'Công an Trà Vinh', '0975521711', 'Sóc Trăng', '664 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Quang Phúc', 'Chị', 368077982, NULL, 'ivah.cremin@yahoo.com', 'huỳnhhữuhùng@gmail.com', '23000068@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000069', NULL, 'Lê Hữu Phúc', NULL, '2006-04-17', 1, 231550325, '2025-01-04', 'Công an An Giang', '0838583683', 'Hậu Giang', '827 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Văn An', 'Em', 817781923, NULL, 'stephen74@hotmail.com', 'lêhữuphúc@gmail.com', '23000069@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000070', NULL, 'Lê Đức Cường', NULL, '2005-07-24', 0, 635617180, '2024-02-03', 'Công an Bạc Liêu', '0322054803', 'Bến Tre', '709 Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Thành Em', 'Em', 814549358, NULL, 'muriel.murphy@boyer.com', 'lêdứccường@gmail.com', '23000070@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000071', NULL, 'Lê Đức Khang', NULL, '2003-09-28', 0, 337569956, '2024-02-24', 'Công an Hậu Giang', '0328269953', 'Sóc Trăng', '176 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Hữu Mai', 'Mẹ', 838480576, NULL, 'jrippin@halvorson.com', 'lêdứckhang@gmail.com', '23000071@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000072', NULL, 'Đỗ Văn Khang', NULL, '2004-08-01', 1, 136518232, '2023-06-03', 'Công an Vĩnh Long', '0866806545', 'Trà Vinh', '912 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Quang Mai', 'Mẹ', 865286041, NULL, 'bailee.sipes@fadel.com', 'dỗvănkhang@gmail.com', '23000072@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000073', NULL, 'Nguyễn Đức Linh', NULL, '2007-01-18', 1, 217465832, '2025-01-07', 'Công an Sóc Trăng', '0891662809', 'Tiền Giang', '264 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Quang Em', 'Mẹ', 790073502, NULL, 'olen.stanton@gmail.com', 'nguyễndứclinh@gmail.com', '23000073@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000074', NULL, 'Nguyễn Văn Em', NULL, '2005-11-30', 0, 222319580, '2023-08-26', 'Công an Sóc Trăng', '0345023556', 'Bến Tre', '755 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Hoàng Giang', 'Em', 882320593, NULL, 'pollich.susanna@yahoo.com', 'nguyễnvănem@gmail.com', '23000074@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000075', NULL, 'Trần Công Cường', NULL, '2006-07-28', 1, 633479443, '2024-05-09', 'Công an Kiên Giang', '0868004431', 'Kiên Giang', '221 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Thành Mai', 'Chị', 898817611, NULL, 'jesus.feil@yahoo.com', 'trầncôngcường@gmail.com', '23000075@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000076', NULL, 'Phạm Hoàng Mai', NULL, '2004-09-23', 1, 124237399, '2024-05-07', 'Công an Bạc Liêu', '0848639924', 'An Giang', '233 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Hoàng Giang', 'Anh', 843470222, NULL, 'murphy.edythe@walsh.com', 'phạmhoàngmai@gmail.com', '23000076@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000077', NULL, 'Hoàng Quang Dũng', NULL, '2004-11-25', 0, 323009306, '2024-06-01', 'Công an Đồng Tháp', '0936232959', 'Cần Thơ', '532 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Hoàng An', 'Mẹ', 889981948, NULL, 'pmayert@hotmail.com', 'hoàngquangdũng@gmail.com', '23000077@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000078', NULL, 'Lê Đức Bình', NULL, '2006-08-13', 1, 229579046, '2025-04-23', 'Công an Bến Tre', '0320571416', 'Trà Vinh', '644 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Khang', 'Cha', 340150752, NULL, 'tromp.darrin@gmail.com', 'lêdứcbình@gmail.com', '23000078@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000079', NULL, 'Phan Công Dũng', NULL, '2006-10-11', 1, 628346257, '2023-07-11', 'Công an An Giang', '0352200861', 'Bạc Liêu', '289 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Công Mai', 'Em', 880598764, NULL, 'mitchell.lexie@dach.org', 'phancôngdũng@gmail.com', '23000079@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000080', NULL, 'Nguyễn Thành Hùng', NULL, '2006-02-24', 1, 336635923, '2024-06-03', 'Công an Vĩnh Long', '0794848934', 'Cà Mau', '446 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Công Giang', 'Mẹ', 356207086, NULL, 'lorena.rolfson@parisian.com', 'nguyễnthànhhùng@gmail.com', '23000080@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000081', NULL, 'Bùi Quang Giang', NULL, '2004-04-19', 0, 419416609, '2024-12-13', 'Công an An Giang', '0374463797', 'Tiền Giang', '155 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Thị Giang', 'Em', 860210552, NULL, 'lockman.fredrick@gmail.com', 'bùiquanggiang@gmail.com', '23000081@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000082', NULL, 'Huỳnh Hữu An', NULL, '2006-05-13', 1, 130639051, '2024-12-11', 'Công an Cà Mau', '0391795573', 'Cà Mau', '396 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Đức Dũng', 'Anh', 702514622, NULL, 'rylan.kautzer@zboncak.com', 'huỳnhhữuan@gmail.com', '23000082@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000083', NULL, 'Phạm Thành Hùng', NULL, '2003-07-27', 1, 525665616, '2024-08-06', 'Công an Vĩnh Long', '0931119943', 'Tiền Giang', '638 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Hoàng Khang', 'Cha', 782383434, NULL, 'elena.gorczany@hotmail.com', 'phạmthànhhùng@gmail.com', '23000083@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000084', NULL, 'Đặng Thành Mai', NULL, '2006-03-10', 1, 212418191, '2024-08-14', 'Công an Sóc Trăng', '0764426300', 'Cà Mau', '646 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Quang Linh', 'Cha', 324627684, NULL, 'misael36@schmidt.biz', 'dặngthànhmai@gmail.com', '23000084@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000085', NULL, 'Đỗ Hoàng Bình', NULL, '2005-04-09', 1, 432849557, '2024-03-23', 'Công an Trà Vinh', '0367417520', 'Vĩnh Long', '953 3/2, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Văn Dũng', 'Cha', 931343135, NULL, 'anais.cummings@yahoo.com', 'dỗhoàngbình@gmail.com', '23000085@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000086', NULL, 'Phạm Công An', NULL, '2006-07-04', 0, 315941084, '2024-03-05', 'Công an Tiền Giang', '0772490922', 'Tiền Giang', '171 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Hữu Giang', 'Mẹ', 883317726, NULL, 'mark66@yahoo.com', 'phạmcôngan@gmail.com', '23000086@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000087', NULL, 'Phạm Hoàng Linh', NULL, '2005-03-31', 1, 532764901, '2023-06-06', 'Công an Kiên Giang', '0968509543', 'Trà Vinh', '899 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Hoàng Em', 'Cha', 868032557, NULL, 'upton.arely@green.biz', 'phạmhoànglinh@gmail.com', '23000087@student.cusc.vn', NULL, NULL, 'DaNghiHoc', NULL, NULL),
('23000088', NULL, 'Trần Công Cường', NULL, '2007-02-27', 1, 236032348, '2023-06-25', 'Công an Sóc Trăng', '0371594250', 'Cần Thơ', '381 Mậu Thân, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Công An', 'Mẹ', 869866587, NULL, 'zena87@gmail.com', 'trầncôngcường@gmail.com', '23000088@student.cusc.vn', NULL, NULL, 'DaNghiHoc', NULL, NULL),
('23000089', NULL, 'Phan Đức Linh', NULL, '2004-07-18', 1, 531602009, '2024-05-28', 'Công an Cần Thơ', '0771107439', 'An Giang', '417 3/2, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Thị Cường', 'Em', 368327156, NULL, 'cierra78@krajcik.net', 'phandứclinh@gmail.com', '23000089@student.cusc.vn', NULL, NULL, 'DaTotNghiep', NULL, NULL),
('23000090', NULL, 'Phạm Thành Dũng', NULL, '2005-03-21', 0, 327999049, '2024-06-14', 'Công an Sóc Trăng', '0885597664', 'Bạc Liêu', '810 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Đức Em', 'Anh', 973002244, NULL, 'raegan70@osinski.com', 'phạmthànhdũng@gmail.com', '23000090@student.cusc.vn', NULL, NULL, 'DaTotNghiep', NULL, NULL),
('23000091', NULL, 'Võ Hữu Mai', NULL, '2005-05-07', 0, 533907493, '2024-04-06', 'Công an Vĩnh Long', '0810857494', 'Bến Tre', '389 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Thị Em', 'Anh', 932188375, NULL, 'coleman20@stokes.com', 'võhữumai@gmail.com', '23000091@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000092', NULL, 'Vũ Đức An', NULL, '2006-06-12', 1, 316644441, '2025-04-18', 'Công an Vĩnh Long', '0789039355', 'An Giang', '790 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Thành Dũng', 'Anh', 792109706, NULL, 'lockman.leonard@gleichner.com', 'vũdứcan@gmail.com', '23000092@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000093', NULL, 'Nguyễn Thành Cường', NULL, '2004-10-28', 1, 210521149, '2024-11-20', 'Công an Trà Vinh', '0780079367', 'Kiên Giang', '705 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Văn An', 'Mẹ', 867561655, NULL, 'kris63@nitzsche.com', 'nguyễnthànhcường@gmail.com', '23000093@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000094', NULL, 'Phan Minh Khang', NULL, '2004-04-04', 1, 114053507, '2024-01-08', 'Công an Vĩnh Long', '0704721922', 'Vĩnh Long', '602 Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Quang Hùng', 'Anh', 844001099, NULL, 'isenger@hotmail.com', 'phanminhkhang@gmail.com', '23000094@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000095', NULL, 'Vũ Văn An', NULL, '2005-01-27', 0, 514276556, '2024-06-19', 'Công an Tiền Giang', '0828370685', 'Vĩnh Long', '526 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Hoàng Phúc', 'Mẹ', 369739964, NULL, 'buford98@hotmail.com', 'vũvănan@gmail.com', '23000095@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000096', NULL, 'Đỗ Hữu An', NULL, '2003-09-11', 1, 523619722, '2023-05-26', 'Công an Long An', '0972035892', 'Kiên Giang', '720 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Minh Cường', 'Chị', 780548664, NULL, 'boyd93@hotmail.com', 'dỗhữuan@gmail.com', '23000096@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000097', NULL, 'Hoàng Thành Bình', NULL, '2005-09-17', 0, 229959399, '2025-05-07', 'Công an Bạc Liêu', '0827266190', 'Cà Mau', '368 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Đức An', 'Cha', 969182505, NULL, 'cremin.myra@gmail.com', 'hoàngthànhbình@gmail.com', '23000097@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000098', NULL, 'Bùi Đức Khang', NULL, '2005-07-11', 0, 239642676, '2024-04-15', 'Công an Vĩnh Long', '0895108298', 'Trà Vinh', '414 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Văn Mai', 'Em', 821110077, NULL, 'arvid12@mann.info', 'bùidứckhang@gmail.com', '23000098@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000099', NULL, 'Vũ Công Linh', NULL, '2007-04-06', 0, 437802714, '2024-08-23', 'Công an Bến Tre', '0766180647', 'Bến Tre', '541 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Thành An', 'Chị', 783225818, NULL, 'milan03@gmail.com', 'vũcônglinh@gmail.com', '23000099@student.cusc.vn', NULL, NULL, NULL, NULL, NULL),
('23000100', NULL, 'Bùi Thị Hùng', NULL, '2004-11-17', 0, 323372439, '2025-03-01', 'Công an Vĩnh Long', '0788330434', 'Đồng Tháp', '885 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Quang Nam', 'Cha', 335441984, NULL, 'odeckow@witting.com', 'bùithịhùng@gmail.com', '23000100@student.cusc.vn', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien_duthi`
--

CREATE TABLE `sinhvien_duthi` (
  `id` bigint UNSIGNED NOT NULL,
  `MaSV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `MaLichThi` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `MaLop` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `TrangThaiDuThi` enum('ChuaDangKy','DuThi','VangMat','KhongDuThi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ChuaDangKy',
  `GhiChu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sinhvien_duthi`
--

INSERT INTO `sinhvien_duthi` (`id`, `MaSV`, `MaLichThi`, `MaLop`, `TrangThaiDuThi`, `GhiChu`, `created_at`, `updated_at`) VALUES
(41, '21010001', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:13', '2025-06-06 23:12:13'),
(42, '21010002', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(43, '21010003', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(44, '23000054', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(45, '23000055', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(46, '23000057', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(47, '23000096', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(48, '23000097', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(49, '23000098', 'LT2506061451', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(50, '23000099', 'LT2506061451', 'CP2296H07', 'KhongDuThi', 'Không đủ điều kiện dự thi', '2025-06-06 23:12:14', '2025-06-06 23:12:14'),
(51, '21010001', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(52, '21010002', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(53, '21010003', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(54, '23000054', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(55, '23000055', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(56, '23000057', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(57, '23000096', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(58, '23000097', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(59, '23000098', 'LT2506061453', 'CP2296H07', 'DuThi', NULL, '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(60, '23000099', 'LT2506061453', 'CP2296H07', 'KhongDuThi', 'Không đủ điều kiện dự thi', '2025-06-06 23:13:28', '2025-06-06 23:13:28'),
(61, '23000029', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(62, '23000036', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(63, '23000047', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(64, '23000082', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(65, '23000086', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(66, '23000092', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(67, '23000094', 'LT2506061454', 'CP2396G11', 'DuThi', NULL, '2025-06-07 18:54:48', '2025-06-07 18:54:48'),
(68, '21010001', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(69, '21010002', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(70, '21010003', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(71, '23000054', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(72, '23000055', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(73, '23000057', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(74, '23000096', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(75, '23000097', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(76, '23000098', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51'),
(77, '23000099', 'LT25060634', 'CP2296H07', 'DuThi', NULL, '2025-07-02 07:45:51', '2025-07-02 07:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `taphuan`
--

CREATE TABLE `taphuan` (
  `MaTapHuan` varchar(12) NOT NULL,
  `TenKhoaTapHuan` varchar(30) DEFAULT NULL,
  `ThoiGianBatDau` date DEFAULT NULL,
  `ThoiGianKetThuc` date DEFAULT NULL,
  `DiaDiem` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `taphuan`
--

INSERT INTO `taphuan` (`MaTapHuan`, `TenKhoaTapHuan`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `DiaDiem`, `created_at`, `updated_at`) VALUES
('TH001', 'Khoá đào tạo GV', NULL, NULL, NULL, '2025-05-15 23:36:26', '2025-05-15 23:36:26'),
('TH002', 'Khóa QLNN', NULL, NULL, NULL, '2025-05-16 09:50:25', '2025-05-16 09:50:25'),
('TH003', 'Khóa ATTT', NULL, NULL, NULL, '2025-05-16 09:50:25', '2025-05-16 09:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `thong_ke_hoc_tap`
--

CREATE TABLE `thong_ke_hoc_tap` (
  `id` bigint UNSIGNED NOT NULL,
  `ma_chuong_trinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hoc_ki` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tong_sinh_vien` int NOT NULL DEFAULT '0',
  `sinh_vien_gioi` int NOT NULL DEFAULT '0',
  `sinh_vien_kha` int NOT NULL DEFAULT '0',
  `sinh_vien_trung_binh` int NOT NULL DEFAULT '0',
  `sinh_vien_yeu` int NOT NULL DEFAULT '0',
  `diem_trung_binh_tong_khoa` double NOT NULL DEFAULT '0',
  `ty_le_tot_nghiep` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tieu_chi_xep_loai`
--

CREATE TABLE `tieu_chi_xep_loai` (
  `id` int NOT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL,
  `XepLoai` enum('Đạt','Khá','Giỏi') DEFAULT NULL,
  `DiemTu` float DEFAULT NULL,
  `DiemDen` float DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tieu_chi_xep_loai`
--

INSERT INTO `tieu_chi_xep_loai` (`id`, `MaChuongTrinh`, `XepLoai`, `DiemTu`, `DiemDen`, `created_at`, `updated_at`) VALUES
(1, 'OV-7096', 'Đạt', 40, 60, '2025-06-08 02:57:57', '2025-06-08 02:57:57'),
(2, 'OV-7096', 'Khá', 60, 75, '2025-06-08 02:57:57', '2025-06-08 02:57:57'),
(3, 'OV-7096', 'Giỏi', 75, 100, '2025-06-08 02:57:57', '2025-06-08 02:57:57'),
(4, 'OV-7023', 'Đạt', 40, 60, '2025-06-27 08:35:31', '2025-06-27 08:35:31'),
(5, 'OV-7023', 'Khá', 60, 75, '2025-06-27 08:35:31', '2025-06-27 08:35:31'),
(6, 'OV-7023', 'Giỏi', 70, 100, '2025-06-27 08:35:31', '2025-06-27 08:35:31');

-- --------------------------------------------------------

--
-- Table structure for table `tkb`
--

CREATE TABLE `tkb` (
  `TenTKB` varchar(255) NOT NULL,
  `MaLop` varchar(12) DEFAULT NULL,
  `MaHK` varchar(50) DEFAULT NULL,
  `NgayHoc` date DEFAULT NULL,
  `ngayHocType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `NgayPhienBan` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tkb`
--

INSERT INTO `tkb` (`TenTKB`, `MaLop`, `MaHK`, `NgayHoc`, `ngayHocType`, `NgayPhienBan`) VALUES
('THỜI KHÓA BIỂU LỚP CP2296H07 - Học kỳ I (OV-7096)', 'CP2296H07', 'OV-7096-HK I', '2025-08-10', NULL, NULL),
('THỜI KHÓA BIỂU LỚP CP2396G11 - HỌC KỲ I (OV-7023)', 'CP2396G11', 'OV-7096-HK I', '2025-07-08', 'all', NULL),
('THỜI KHÓA BIỂU LỚP CP2396M02 - HỌC KỲ II (OV-7023)', 'CP2396M02', 'OV-7023-HK I', '2025-07-07', 'all', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bangcapcanbo`
--
ALTER TABLE `bangcapcanbo`
  ADD PRIMARY KEY (`MaBang`);

--
-- Indexes for table `canbo`
--
ALTER TABLE `canbo`
  ADD PRIMARY KEY (`MaCB`),
  ADD KEY `MaDV` (`MaDV`),
  ADD KEY `CongViecPhuTrach` (`CongViecPhuTrach`),
  ADD KEY `MaHV` (`MaHV`),
  ADD KEY `TenChucVu` (`TenChucVu`),
  ADD KEY `MaBang` (`MaBang`),
  ADD KEY `MaTapHuan` (`MaTapHuan`);

--
-- Indexes for table `chucvu`
--
ALTER TABLE `chucvu`
  ADD PRIMARY KEY (`TenChucVu`);

--
-- Indexes for table `chuongtrinh`
--
ALTER TABLE `chuongtrinh`
  ADD PRIMARY KEY (`MaChuongTrinh`),
  ADD KEY `fk_tenkhoadaotao` (`TenKhoaDaoTao`);

--
-- Indexes for table `chuongtrinh_monhoc`
--
ALTER TABLE `chuongtrinh_monhoc`
  ADD PRIMARY KEY (`MaChuongTrinh`,`MaMH`) USING BTREE,
  ADD KEY `chuongtrinh_monhoc_ibfk_2` (`MaMH`);

--
-- Indexes for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TenKhungGio` (`TenKhungGio`),
  ADD KEY `MaHK` (`MaHK`),
  ADD KEY `danhsachmh_ibfk_3` (`MaMH`);

--
-- Indexes for table `danhsachngaynghi`
--
ALTER TABLE `danhsachngaynghi`
  ADD PRIMARY KEY (`MaNgayNghi`),
  ADD KEY `TenTKB` (`TenTKB`);

--
-- Indexes for table `danhsachphong`
--
ALTER TABLE `danhsachphong`
  ADD PRIMARY KEY (`MaLop`,`TenPhong`),
  ADD KEY `TenPhong` (`TenPhong`);

--
-- Indexes for table `danhsachsv`
--
ALTER TABLE `danhsachsv`
  ADD PRIMARY KEY (`MaLop`,`MaSV`),
  ADD KEY `MaSV` (`MaSV`);

--
-- Indexes for table `diemthi`
--
ALTER TABLE `diemthi`
  ADD PRIMARY KEY (`MaSV`,`MaMH`,`MaLop`) USING BTREE,
  ADD KEY `MaLop` (`MaLop`),
  ADD KEY `diemthi_ibfk_2` (`MaMH`);

--
-- Indexes for table `donvi`
--
ALTER TABLE `donvi`
  ADD PRIMARY KEY (`MaDV`);

--
-- Indexes for table `giangday`
--
ALTER TABLE `giangday`
  ADD KEY `giangday_ibfk_2` (`MaMH`);

--
-- Indexes for table `giaovien`
--
ALTER TABLE `giaovien`
  ADD PRIMARY KEY (`MaGV`),
  ADD UNIQUE KEY `giaovien_email_unique` (`Email`);

--
-- Indexes for table `hinh_thuc_danh_gia`
--
ALTER TABLE `hinh_thuc_danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MaChuongTrinh` (`MaChuongTrinh`);

--
-- Indexes for table `hocki`
--
ALTER TABLE `hocki`
  ADD PRIMARY KEY (`MaHK`),
  ADD KEY `MaChuongTrinh` (`MaChuongTrinh`);

--
-- Indexes for table `hocvi`
--
ALTER TABLE `hocvi`
  ADD PRIMARY KEY (`MaHV`);

--
-- Indexes for table `khoadaotao`
--
ALTER TABLE `khoadaotao`
  ADD PRIMARY KEY (`TenKhoaDaoTao`);

--
-- Indexes for table `khunggio`
--
ALTER TABLE `khunggio`
  ADD PRIMARY KEY (`TenKhungGio`);

--
-- Indexes for table `ldap_accounts`
--
ALTER TABLE `ldap_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ldap_accounts_username_unique` (`username`),
  ADD UNIQUE KEY `ldap_accounts_email_unique` (`email`),
  ADD KEY `ldap_accounts_masv_index` (`MaTaiKhoan`),
  ADD KEY `ldap_accounts_username_index` (`username`),
  ADD KEY `ldap_accounts_email_index` (`email`);

--
-- Indexes for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD PRIMARY KEY (`MaLichThi`),
  ADD KEY `lichthi_ibfk_1` (`MaLop`),
  ADD KEY `lichthi_ibfk_3` (`PhongThi`),
  ADD KEY `lichthi_ibfk_2` (`MaMH`);

--
-- Indexes for table `lophoc`
--
ALTER TABLE `lophoc`
  ADD PRIMARY KEY (`MaLop`),
  ADD KEY `lophoc_ibfk_1` (`MaChuongTrinh`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`MaMH`) USING BTREE,
  ADD KEY `TenMH` (`TenMH`) USING BTREE;

--
-- Indexes for table `ngaynghi`
--
ALTER TABLE `ngaynghi`
  ADD PRIMARY KEY (`MaNgayNghi`);

--
-- Indexes for table `ngaytuhoc`
--
ALTER TABLE `ngaytuhoc`
  ADD PRIMARY KEY (`MaNgayTuHoc`),
  ADD KEY `TenTKB` (`TenTKB`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  ADD PRIMARY KEY (`MaPhanCong`),
  ADD KEY `phieuphancongthi_ibfk_1` (`MaLichThi`);

--
-- Indexes for table `phonghoc`
--
ALTER TABLE `phonghoc`
  ADD PRIMARY KEY (`TenPhong`);

--
-- Indexes for table `phutrach`
--
ALTER TABLE `phutrach`
  ADD PRIMARY KEY (`CongViecPhuTrach`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`MaSV`);

--
-- Indexes for table `sinhvien_duthi`
--
ALTER TABLE `sinhvien_duthi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sinhvien_duthi_masv_malichthi_unique` (`MaSV`,`MaLichThi`),
  ADD KEY `sinhvien_duthi_malichthi_foreign` (`MaLichThi`),
  ADD KEY `sinhvien_duthi_malop_foreign` (`MaLop`);

--
-- Indexes for table `taphuan`
--
ALTER TABLE `taphuan`
  ADD PRIMARY KEY (`MaTapHuan`);

--
-- Indexes for table `thong_ke_hoc_tap`
--
ALTER TABLE `thong_ke_hoc_tap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thong_ke_hoc_tap_ma_chuong_trinh_foreign` (`ma_chuong_trinh`);

--
-- Indexes for table `tieu_chi_xep_loai`
--
ALTER TABLE `tieu_chi_xep_loai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MaChuongTrinh` (`MaChuongTrinh`);

--
-- Indexes for table `tkb`
--
ALTER TABLE `tkb`
  ADD PRIMARY KEY (`TenTKB`),
  ADD KEY `MaHK` (`MaHK`),
  ADD KEY `MaLop` (`MaLop`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `hinh_thuc_danh_gia`
--
ALTER TABLE `hinh_thuc_danh_gia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ldap_accounts`
--
ALTER TABLE `ldap_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ngaynghi`
--
ALTER TABLE `ngaynghi`
  MODIFY `MaNgayNghi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ngaytuhoc`
--
ALTER TABLE `ngaytuhoc`
  MODIFY `MaNgayTuHoc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  MODIFY `MaPhanCong` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sinhvien_duthi`
--
ALTER TABLE `sinhvien_duthi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `thong_ke_hoc_tap`
--
ALTER TABLE `thong_ke_hoc_tap`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tieu_chi_xep_loai`
--
ALTER TABLE `tieu_chi_xep_loai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `canbo`
--
ALTER TABLE `canbo`
  ADD CONSTRAINT `canbo_ibfk_1` FOREIGN KEY (`MaDV`) REFERENCES `donvi` (`MaDV`),
  ADD CONSTRAINT `canbo_ibfk_2` FOREIGN KEY (`CongViecPhuTrach`) REFERENCES `phutrach` (`CongViecPhuTrach`),
  ADD CONSTRAINT `canbo_ibfk_3` FOREIGN KEY (`MaHV`) REFERENCES `hocvi` (`MaHV`),
  ADD CONSTRAINT `canbo_ibfk_4` FOREIGN KEY (`TenChucVu`) REFERENCES `chucvu` (`TenChucVu`),
  ADD CONSTRAINT `canbo_ibfk_5` FOREIGN KEY (`MaBang`) REFERENCES `bangcapcanbo` (`MaBang`),
  ADD CONSTRAINT `canbo_ibfk_6` FOREIGN KEY (`MaTapHuan`) REFERENCES `taphuan` (`MaTapHuan`);

--
-- Constraints for table `chuongtrinh`
--
ALTER TABLE `chuongtrinh`
  ADD CONSTRAINT `fk_tenkhoadaotao` FOREIGN KEY (`TenKhoaDaoTao`) REFERENCES `khoadaotao` (`TenKhoaDaoTao`);

--
-- Constraints for table `chuongtrinh_monhoc`
--
ALTER TABLE `chuongtrinh_monhoc`
  ADD CONSTRAINT `chuongtrinh_monhoc_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`),
  ADD CONSTRAINT `chuongtrinh_monhoc_ibfk_2` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  ADD CONSTRAINT `danhsachmh_ibfk_1` FOREIGN KEY (`MaHK`) REFERENCES `hocki` (`MaHK`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `danhsachmh_ibfk_2` FOREIGN KEY (`TenKhungGio`) REFERENCES `khunggio` (`TenKhungGio`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `danhsachmh_ibfk_3` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `danhsachngaynghi`
--
ALTER TABLE `danhsachngaynghi`
  ADD CONSTRAINT `danhsachngaynghi_ibfk_1` FOREIGN KEY (`TenTKB`) REFERENCES `tkb` (`TenTKB`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `danhsachngaynghi_ibfk_2` FOREIGN KEY (`MaNgayNghi`) REFERENCES `ngaynghi` (`MaNgayNghi`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `danhsachphong`
--
ALTER TABLE `danhsachphong`
  ADD CONSTRAINT `danhsachphong_ibfk_1` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`),
  ADD CONSTRAINT `danhsachphong_ibfk_2` FOREIGN KEY (`TenPhong`) REFERENCES `phonghoc` (`TenPhong`);

--
-- Constraints for table `danhsachsv`
--
ALTER TABLE `danhsachsv`
  ADD CONSTRAINT `danhsachsv_ibfk_1` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`),
  ADD CONSTRAINT `danhsachsv_ibfk_2` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`);

--
-- Constraints for table `diemthi`
--
ALTER TABLE `diemthi`
  ADD CONSTRAINT `diemthi_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`),
  ADD CONSTRAINT `diemthi_ibfk_2` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `diemthi_ibfk_3` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`);

--
-- Constraints for table `giangday`
--
ALTER TABLE `giangday`
  ADD CONSTRAINT `giangday_ibfk_2` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `hinh_thuc_danh_gia`
--
ALTER TABLE `hinh_thuc_danh_gia`
  ADD CONSTRAINT `hinh_thuc_danh_gia_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);

--
-- Constraints for table `hocki`
--
ALTER TABLE `hocki`
  ADD CONSTRAINT `hocki_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);

--
-- Constraints for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD CONSTRAINT `lichthi_ibfk_1` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lichthi_ibfk_2` FOREIGN KEY (`MaMH`) REFERENCES `monhoc` (`MaMH`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `lichthi_ibfk_3` FOREIGN KEY (`PhongThi`) REFERENCES `phonghoc` (`TenPhong`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lophoc`
--
ALTER TABLE `lophoc`
  ADD CONSTRAINT `lophoc_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ngaytuhoc`
--
ALTER TABLE `ngaytuhoc`
  ADD CONSTRAINT `ngaytuhoc_ibfk_1` FOREIGN KEY (`TenTKB`) REFERENCES `tkb` (`TenTKB`);

--
-- Constraints for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  ADD CONSTRAINT `phieuphancongthi_ibfk_1` FOREIGN KEY (`MaLichThi`) REFERENCES `lichthi` (`MaLichThi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sinhvien_duthi`
--
ALTER TABLE `sinhvien_duthi`
  ADD CONSTRAINT `sinhvien_duthi_malichthi_foreign` FOREIGN KEY (`MaLichThi`) REFERENCES `lichthi` (`MaLichThi`),
  ADD CONSTRAINT `sinhvien_duthi_malop_foreign` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`),
  ADD CONSTRAINT `sinhvien_duthi_masv_foreign` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`);

--
-- Constraints for table `thong_ke_hoc_tap`
--
ALTER TABLE `thong_ke_hoc_tap`
  ADD CONSTRAINT `thong_ke_hoc_tap_ma_chuong_trinh_foreign` FOREIGN KEY (`ma_chuong_trinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`) ON DELETE CASCADE;

--
-- Constraints for table `tieu_chi_xep_loai`
--
ALTER TABLE `tieu_chi_xep_loai`
  ADD CONSTRAINT `tieu_chi_xep_loai_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);

--
-- Constraints for table `tkb`
--
ALTER TABLE `tkb`
  ADD CONSTRAINT `tkb_ibfk_1` FOREIGN KEY (`MaHK`) REFERENCES `hocki` (`MaHK`),
  ADD CONSTRAINT `tkb_ibfk_2` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
