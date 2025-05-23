-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 23, 2025 at 05:56 AM
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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `canbo`
--

CREATE TABLE `canbo` (
  `MaCB` varchar(12) NOT NULL,
  `HoTenCB` varchar(30) DEFAULT NULL,
  `GioiTinh` tinyint(1) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
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

INSERT INTO `canbo` (`MaCB`, `HoTenCB`, `GioiTinh`, `Email`, `Sdt`, `MaHV`, `TenChucVu`, `CongViecPhuTrach`, `MaDV`, `MaBang`, `MaTapHuan`, `ThoiGianBDCongTacCUSC`, `ThoiGianKTCongTacCUSC`, `created_at`, `updated_at`) VALUES
('CB001', 'Nguyễn Văn An', 1, 'ngvanan@gmail.com', '0912345678', 'HV001', 'Giảng viên', 'Giảng dạy', 'DV001', 'BC001', 'TH001', '2020-01-01', NULL, '2025-05-16 13:36:49', '2025-05-16 13:36:49'),
('CB003', 'Lê Văn Cường', 1, 'levcuong@gmail.com', '0934567890', 'HV003', 'Chuyên viên', 'Quản trị hệ thống', 'DV003', 'BC003', 'TH003', '2019-08-20', '2022-12-31', '2025-05-16 13:36:49', '2025-05-16 13:36:49');

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
('OV-7096', 'ACN Pro (CPIDA) - Khóa học chuyên ngành về Khoa học Dữ liệu', '1.0', '2023-08-01', 'Dài hạn', '2025-05-19 14:38:55', '2025-05-23 05:04:35'),
('OV9001', 'Mỹ thuật Đa phương tiện – Arena', '2.0', '2025-06-16', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:31:16');

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

-- --------------------------------------------------------

--
-- Table structure for table `danhsachmh`
--

CREATE TABLE `danhsachmh` (
  `MaHK` varchar(50) DEFAULT NULL,
  `TenKhungGio` varchar(100) DEFAULT NULL,
  `SttMH` int DEFAULT NULL,
  `TenMH` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsachmh`
--

INSERT INTO `danhsachmh` (`MaHK`, `TenKhungGio`, `SttMH`, `TenMH`) VALUES
('OV-7023-HK I', NULL, 1, 'Computer fundamentals'),
('OV-7023-HK I', NULL, 2, 'Logic Building and Elementary Programing'),
('OV-7023-HK I', NULL, 3, 'HTML5,CSS and Javascript'),
('OV-7023-HK I', NULL, 4, 'AngularJS'),
('OV-7023-HK I', NULL, 5, 'eProject-Website Development'),
('OV-7023-HK I', NULL, 6, 'Database Design and Development(core)'),
('OV-7023-HK I', NULL, 7, 'Data Management with SQL server'),
('OV-7023-HK II', '15:00-17:00', 8, 'Markup Language & JSON '),
('OV-7023-HK II', '15:00-17:00', 9, 'Java Programming - I'),
('OV-7023-HK II', '15:00-17:00', 10, 'Java Programming -II'),
('OV-7023-HK II', '15:00-17:00', 11, 'Information Systems Analysis(Core)'),
('OV-7023-HK II', '15:00-17:00', 12, 'Project-Java Application Development'),
('OV-7023-HK II', '15:00-17:00', 13, 'Application Programming with C#'),
('OV-7023-HK II', '15:00-17:00', 14, 'PHP Web Development with Laravel Framework');

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
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 1),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 2),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 3),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 4),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 5),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 6);

-- --------------------------------------------------------

--
-- Table structure for table `danhsachphong`
--

CREATE TABLE `danhsachphong` (
  `MaLop` varchar(12) NOT NULL,
  `TenPhong` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsachphong`
--

INSERT INTO `danhsachphong` (`MaLop`, `TenPhong`) VALUES
('CP2396G11', 'Class1'),
('CP2296H07', 'Class2'),
('CP2396M02', 'Class2'),
('CP2396M02', 'Lab2');

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
('CP2396G11', '23000047');

-- --------------------------------------------------------

--
-- Table structure for table `diemthi`
--

CREATE TABLE `diemthi` (
  `MaSV` varchar(12) NOT NULL,
  `TenMH` varchar(255) NOT NULL,
  `MaLop` varchar(12) DEFAULT NULL,
  `LanThi` tinyint NOT NULL DEFAULT '1',
  `Diem` float DEFAULT NULL,
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `MaFeedback` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MaSV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MaGV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci,
  `TrangThai` enum('DaXuLy','ChuaXuLy') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ChuaXuLy',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giangday`
--

CREATE TABLE `giangday` (
  `MaGV` varchar(12) NOT NULL,
  `MaLop` varchar(12) NOT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giangday`
--

INSERT INTO `giangday` (`MaGV`, `MaLop`, `NgayBatDau`, `NgayKetThuc`, `GhiChu`, `created_at`, `updated_at`) VALUES
('GV001', 'CP2296H07', '2025-05-22', '2025-07-24', NULL, '2025-05-21 17:37:57', '2025-05-21 17:37:57'),
('GV002', 'CP2396G11', '2025-05-22', '2025-07-23', NULL, '2025-05-21 17:43:58', '2025-05-21 17:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `giaovien`
--

CREATE TABLE `giaovien` (
  `MaGV` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `HoTenGV` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `GioiTinh` tinyint(1) DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
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

INSERT INTO `giaovien` (`MaGV`, `HoTenGV`, `GioiTinh`, `Email`, `Sdt`, `MaHV`, `TenChucVu`, `MaDV`, `MaBang`, `LoaiGV`, `ChuyenNganh`, `GhiChu`, `NgayBatDauCongTac`, `NgayKetThucCongTac`, `created_at`, `updated_at`) VALUES
('GV001', 'Nguyễn Văn An', 1, 'ngvanan@gmail.com', '0912345678', 'HV001', 'Giảng viên', 'DV001', 'BC001', 'CoHuu', 'Khoa học máy tính', 'Giảng viên chính', '2020-01-01', NULL, '2025-05-16 07:53:41', '2025-05-16 07:53:41'),
('GV002', 'Trần Thị Bình', 0, 'tranthib@gmail.com', '0923456789', 'HV002', 'Trưởng khoa', 'DV002', 'BC002', 'MoiGiang', 'Kinh tế học', 'Giảng viên thỉnh giảng', '2018-05-15', NULL, '2025-05-16 07:53:41', '2025-05-16 07:53:41'),
('GV003', 'Phạm Đức Linh', 0, 'phamduclinh@cusc.vn', '0943526107', 'HV002', 'Giảng viên', 'DV003', 'BC002', 'MoiGiang', 'Hệ thống thông tin', NULL, '2018-06-04', NULL, '2025-05-22 07:40:53', '2025-05-22 07:40:53'),
('GV004', 'Hoàng Quang Giang', 0, 'hoangquanggiang@cusc.vn', '0938554271', 'HV003', 'Giảng viên', 'DV002', 'B001', 'CoHuu', 'Mạng máy tính', NULL, '2018-06-06', NULL, '2025-05-22 07:40:53', '2025-05-22 07:40:53'),
('GV005', 'Hoàng Công Nam', 0, 'hoangcongnam@cusc.vn', '0915354646', 'HV003', 'Giảng viên', 'DV003', 'BC003', 'MoiGiang', 'Trí tuệ nhân tạo', NULL, '2019-05-09', NULL, '2025-05-22 07:40:53', '2025-05-22 07:40:53'),
('GV006', 'Phan Hữu Giang', 1, 'phanhuugiang@cusc.vn', '0987506679', 'HV001', 'Giảng viên', 'DV001', 'B001', 'CoHuu', 'Trí tuệ nhân tạo', NULL, '2021-12-19', NULL, '2025-05-22 07:40:53', '2025-05-22 07:40:53'),
('GV007', 'Phan Đức Phúc', 1, 'phanducphuc@cusc.vn', '0982049005', 'HV002', 'Giảng viên', 'DV001', 'B002', 'CoHuu', 'Trí tuệ nhân tạo', NULL, '2018-12-11', NULL, '2025-05-22 07:40:53', '2025-05-22 07:40:53'),
('GV008', 'Đặng Hoàng Giang', 1, 'danghoanggiang@cusc.vn', '0981259529', 'HV002', 'Giảng viên', 'CNTT', 'B003', 'MoiGiang', 'Trí tuệ nhân tạo', NULL, '2020-06-14', NULL, '2025-05-22 07:40:53', '2025-05-22 07:40:53');

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
-- Table structure for table `hosotuyensinh`
--

CREATE TABLE `hosotuyensinh` (
  `MaHoSo` varchar(12) NOT NULL,
  `MaSV` varchar(12) DEFAULT NULL,
  `MaTS` varchar(12) NOT NULL,
  `NgayNopHS` date DEFAULT NULL,
  `TrangThaiHS` enum('DaNop','DaXet','DaTrungTuyen','KhongTrungTuyen') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Hinh3X4` tinyint(1) DEFAULT NULL,
  `HinhCCCD` tinyint(1) DEFAULT NULL,
  `ToDangKi` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hosotuyensinh`
--

INSERT INTO `hosotuyensinh` (`MaHoSo`, `MaSV`, `MaTS`, `NgayNopHS`, `TrangThaiHS`, `Hinh3X4`, `HinhCCCD`, `ToDangKi`, `created_at`, `updated_at`) VALUES
('HS1747584061', '21010001', 'TS20251', '2025-05-18', 'DaXet', NULL, NULL, NULL, '2025-05-18 16:01:01', '2025-05-19 08:22:50'),
('HS1747585057', '21010002', 'TS20251', '2025-05-18', 'DaNop', NULL, NULL, NULL, '2025-05-18 16:17:37', '2025-05-18 16:17:37'),
('HS1747585066', '21010003', 'TS20251', '2025-05-18', 'DaNop', NULL, NULL, NULL, '2025-05-18 16:17:46', '2025-05-18 16:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('Theo yêu cầu', '1 học kỳ', '2025-05-19 15:08:26', '2025-05-19 15:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `khunggio`
--

CREATE TABLE `khunggio` (
  `TenKhungGio` varchar(100) NOT NULL,
  `ThoiGian` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khunggio`
--

INSERT INTO `khunggio` (`TenKhungGio`, `ThoiGian`) VALUES
('13:00-15:00', 2),
('15:00-17:00', 2),
('17:30-19:30', 2),
('19:30-21:30', 2),
('7:00-9:00', 2),
('9:00-11:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `lichthi`
--

CREATE TABLE `lichthi` (
  `MaLichThi` int NOT NULL,
  `MaLop` varchar(12) DEFAULT NULL,
  `TenMH` varchar(12) DEFAULT NULL,
  `NgayThi` date DEFAULT NULL,
  `KhungGio` varchar(100) DEFAULT NULL,
  `PhongThi` varchar(20) DEFAULT NULL,
  `LoaiThi` enum('Lý thuyết','Thực hành','Bài tập lớn') DEFAULT 'Lý thuyết',
  `GhiChu` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
('CP2296H07', 'Truyền thông đa phương tiện', NULL, 'OV-7096', '2025-05-22 14:15:43', '2025-05-22 07:17:30'),
('CP2396G11', 'Lập trình viên', NULL, 'OV-7023', '2025-05-22 14:15:43', '2025-05-22 14:15:43'),
('CP2396M02', 'Quản trị mạng', NULL, 'OV-7023', '2025-05-22 14:15:43', '2025-05-22 14:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(12, '2025_05_19_134831_create_tuvan_tuyensinh_table', 5);

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
  `HTDanhGia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monhoc`
--

INSERT INTO `monhoc` (`TenMH`, `MaMH`, `GioGoc`, `GioTrienKhai`, `TietLT`, `TietTH`, `TietLTvaTH`, `HTDanhGia`) VALUES
('AngularJS', 'MH01', 16, 16, 0, 1, NULL, NULL),
('Application Programming with C#', 'MH02', 36, 38, 0, 1, NULL, NULL),
('Computer fundamentals', 'MH03', 0, 8, 0, 1, NULL, NULL),
('Data Management with SQL server', 'MH04', 40, 40, 0, 1, NULL, NULL),
('Database Design and Development(core)', 'MH05', 24, 16, 0, 1, NULL, NULL),
('eProject-Website Development', 'MH06', 2, 8, 0, 1, NULL, NULL),
('HTML5,CSS and Javascript', 'MH07', 40, 44, 0, 1, NULL, NULL),
('Information Systems Analysis(Core)', 'MH08', 24, 12, 1, 0, NULL, NULL),
('Java Programming - I', 'MH09', 36, 40, 0, 1, NULL, NULL),
('Java Programming -II', 'MH10', 40, 42, 0, 1, NULL, NULL),
('Logic Building and Elementary Programing', 'MH11', 40, 42, 0, 1, NULL, NULL),
('Markup Language & JSON ', 'MH12', 16, 16, 0, 1, NULL, NULL),
('PHP Web Development with Laravel Framework', 'MH13', 40, 40, 0, 1, NULL, NULL),
('Project-Java Application Development', 'MH14', 2, 12, 1, 0, NULL, NULL);

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
(6, 'aaa', '2025-09-02', '2025-09-16');

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

--
-- Dumping data for table `ngaytuhoc`
--

INSERT INTO `ngaytuhoc` (`MaNgayTuHoc`, `TenNgayTuHoc`, `NgayBDTuHoc`, `NgayKTTuHoc`, `TenTKB`) VALUES
(1, 'self study', '2023-12-28', '2023-12-28', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(2, 'self study', '2024-01-26', '2024-01-26', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(3, 'self study', '2024-02-29', '2024-02-29', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(4, 'self study', '2024-03-06', '2024-03-06', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(5, 'self study', '2024-03-13', '2024-03-13', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(6, 'self study', '2024-03-20', '2024-03-20', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(7, 'self study', '2024-03-27', '2024-03-27', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(8, 'self study', '2024-04-03', '2024-04-03', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(9, 'Team works', '2024-04-24', '2024-04-24', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(10, 'Team works', '2024-04-26', '2024-04-26', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(11, 'Team works', '2024-05-03', '2024-05-03', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(12, 'Team works', '2024-05-06', '2024-05-08', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(13, 'Team works', '2024-05-10', '2024-05-10', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(14, 'Team works', '2024-05-13', '2024-05-15', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(15, 'Team works', '2024-05-17', '2024-05-17', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(16, 'Team works', '2024-05-20', '2024-05-22', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(17, 'Team works', '2024-05-24', '2024-05-24', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(18, 'Team works', '2024-05-27', '2024-05-29', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(19, 'Team works', '2024-05-31', '2024-05-31', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(20, 'Team works', '2024-06-03', '2024-06-06', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
(21, 'Báo cáo đồ án', '2024-06-07', '2024-06-07', 'THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieulido`
--

CREATE TABLE `phieulido` (
  `MaPhieuLiDo` int NOT NULL,
  `ThoiGianBD` date DEFAULT NULL,
  `ThoiGianKT` date DEFAULT NULL,
  `MonBD` varchar(255) DEFAULT NULL,
  `MonSau` varchar(255) DEFAULT NULL,
  `LiDo` varchar(255) DEFAULT NULL,
  `NgayDuyetDon` date DEFAULT NULL,
  `NguoiDuyetDon` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieuphancongthi`
--

CREATE TABLE `phieuphancongthi` (
  `MaPhanCong` int NOT NULL,
  `MaLichThi` int DEFAULT NULL,
  `MaCB` varchar(12) DEFAULT NULL,
  `VaiTro` enum('Cán bộ coi thi','Giám sát','Chấm thi') DEFAULT 'Cán bộ coi thi',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phonghoc`
--

CREATE TABLE `phonghoc` (
  `TenPhong` varchar(20) NOT NULL,
  `LoaiPhong` varchar(255) DEFAULT NULL,
  `SucChua` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phonghoc`
--

INSERT INTO `phonghoc` (`TenPhong`, `LoaiPhong`, `SucChua`, `created_at`, `updated_at`) VALUES
('Class1', 'Phòng lý thuyết 1', NULL, '2025-05-22 09:28:21', '2025-05-22 06:55:12'),
('Class2', 'Phòng lý thuyết 2', NULL, '2025-05-22 09:28:21', '2025-05-22 06:55:20'),
('Class3', 'Phòng lý thuyết 3', NULL, '2025-05-22 02:28:29', '2025-05-22 06:55:30'),
('Class4', 'Phòng lý thuyết 4', NULL, '2025-05-22 06:55:45', '2025-05-22 06:55:45'),
('Lab1', 'Phòng thực hành 1', NULL, '2025-05-22 09:28:21', '2025-05-22 06:55:53'),
('Lab2', 'Phòng thực hành 2', NULL, '2025-05-22 09:28:21', '2025-05-22 06:56:01'),
('Lab3', 'Phòng thực hành 3', NULL, '2025-05-22 06:56:10', '2025-05-22 06:56:10'),
('Lab4', 'Phòng thực hành 4', NULL, '2025-05-22 06:56:20', '2025-05-22 06:56:20');

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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hYvtZ4Gq5R8Mw8wljqt8lTnkX4yq9guzHj6pC9SA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.98.2 Chrome/132.0.6834.196 Electron/34.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGdJaUdzQndLdk5UcWtlM2liaG5CUWl3Yk53bmFwUkdCd0hQcFlSaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC8/aWRlX3dlYnZpZXdfcmVxdWVzdF90aW1lPTE3NDc5MjY0NTMyMTkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1747926453),
('pyF0mZaXIoAbRmkfbLNYdfmHwvOIwyVm2ac2RL6e', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiTTVzT01KSFc2MDg0SVU2MllNbEF5NFJOTmloNDRJaUR1Vjl1ZFlhVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaHVvbmd0cmluaC9saXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxNDoiY2FwdGNoYV9waHJhc2UiO3M6NToieTNQRVQiO3M6NDoidXNlciI7czoxMjoiYWRtaW4ua2h1b25nIjtzOjExOiJkaXNwbGF5bmFtZSI7czoxMDoiVGFuIEtodW9uZyI7czo0OiJyb2xlIjtzOjU6ImFkbWluIjt9', 1747979773),
('zkCVjB0hKtMN3LQf9AUIh5A7HkQIF86gepCrqpUm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSFNHWlVwYU1oZG1sVWRUNEZYQlNKWGVMeXlwenFDbTU1VnU2Q2FGbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50L2xpc3QiO31zOjE0OiJjYXB0Y2hhX3BocmFzZSI7czo1OiI5TkE1dCI7czo0OiJ1c2VyIjtzOjEyOiJhZG1pbi5raHVvbmciO3M6MTE6ImRpc3BsYXluYW1lIjtzOjEwOiJUYW4gS2h1b25nIjtzOjQ6InJvbGUiO3M6NToiYWRtaW4iO30=', 1747932358);

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
  `EmailCUSC` varchar(40) DEFAULT NULL,
  `Size` varchar(12) DEFAULT NULL,
  `TinhTrangHocTap` enum('DangHoc','DaTotNghiep','DaNghiHoc') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`MaSV`, `MaEnroll`, `HoTen`, `InDebt`, `NgaySinh`, `GioiTinh`, `SoCCCD`, `NgayCap`, `NoiCap`, `Sdt`, `NoiSinh`, `DiaChi`, `Zalo`, `Receipt`, `Invoice`, `Billing`, `Coll`, `Billing(VND)`, `Coll(VND)`, `Discount`, `LiDo`, `NgayDangKi`, `HoTenNguoiThan`, `MoiQuanHe`, `SdtNguoiThan`, `ZaloNguoiThan`, `EmailNguoiThan`, `Email`, `EmailCUSC`, `Size`, `TinhTrangHocTap`, `created_at`, `updated_at`) VALUES
('21010001', NULL, 'Nguyễn Văn A', NULL, '2003-06-12', 1, 12345678, NULL, NULL, '0944902423', NULL, 'Ninh Kiều', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nva1@gmail.com', NULL, NULL, NULL, '2025-05-11 06:31:03', '2025-05-12 14:07:51'),
('21010002', NULL, 'Trần Thị B', NULL, '1970-01-01', 0, 12345679, NULL, NULL, '0912345679', NULL, 'Bình Thủy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ttb2@gmail.com', NULL, NULL, NULL, '2025-05-11 06:31:03', '2025-05-12 14:07:57'),
('21010003', NULL, 'Lê Văn C', NULL, '1970-01-01', 1, 12345680, NULL, NULL, '0912345680', NULL, 'Cái Răng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lvc3@gmail.com', NULL, NULL, NULL, '2025-05-11 06:31:03', '2025-05-12 14:08:03'),
('23000001', NULL, 'Đỗ Văn An', NULL, '2005-07-12', 1, 222742174, '2023-09-05', 'Công an Tiền Giang', '0862455565', 'Vĩnh Long', '989 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Minh Giang', 'Chị', 823642868, NULL, 'rosalinda41@zulauf.net', 'dỗvănan@gmail.com', '23000001@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000002', NULL, 'Đỗ Hữu Hùng', NULL, '2006-08-19', 1, 218008983, '2023-06-30', 'Công an Trà Vinh', '0705117568', 'Cà Mau', '976 3/2, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Hữu Mai', 'Em', 886874835, NULL, 'monte.casper@hotmail.com', 'dohuudung@gmail.com', 'dohuudung23000002@student.cusc.vn', NULL, NULL, NULL, '2025-05-20 09:56:00'),
('23000003', NULL, 'Huỳnh Đức Bình', NULL, '2003-10-31', 0, 613326769, '2023-09-18', 'Công an Hậu Giang', '0332699777', 'Sóc Trăng', '835 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Đức An', 'Chị', 841161800, NULL, 'pearline.pfeffer@yahoo.com', 'huỳnhdứcbình@gmail.com', '23000003@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000004', NULL, 'Phan Đức Em', NULL, '2003-08-25', 1, 211193627, '2024-12-19', 'Công an Sóc Trăng', '0868728484', 'Bạc Liêu', '354 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Quang Mai', 'Chị', 349820726, NULL, 'wiegand.mariam@reichert.com', 'phandứcem@gmail.com', '23000004@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000005', NULL, 'Võ Quang Phúc', NULL, '2003-12-03', 1, 139868417, '2025-04-03', 'Công an Hậu Giang', '0398780304', 'Hậu Giang', '864 Nguyễn Văn Linh, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Văn Giang', 'Chị', 706580553, NULL, 'esperanza50@hotmail.com', 'võquangphúc@gmail.com', '23000005@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000006', NULL, 'Đỗ Công An', NULL, '2006-10-31', 1, 631494395, '2024-05-13', 'Công an Hậu Giang', '0825018724', 'Tiền Giang', '641 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Công Bình', 'Mẹ', 815752896, NULL, 'frami.gabriella@hotmail.com', 'dỗcôngan@gmail.com', '23000006@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000007', NULL, 'Vũ Quang Hùng', NULL, '2006-12-22', 1, 432494396, '2025-02-17', 'Công an Vĩnh Long', '0783434137', 'Sóc Trăng', '548 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Công An', 'Em', 930534705, NULL, 'klein.marjorie@hotmail.com', 'vũquanghùng@gmail.com', '23000007@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000008', NULL, 'Phạm Thành Linh', NULL, '2006-06-09', 1, 231601113, '2025-05-19', 'Công an Đồng Tháp', '0989362414', 'Tiền Giang', '505 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Hoàng Em', 'Cha', 386533654, NULL, 'ryan41@gmail.com', 'phạmthànhlinh@gmail.com', '23000008@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000009', NULL, 'Phạm Thành Phúc', NULL, '2004-06-16', 0, 219171756, '2023-09-01', 'Công an An Giang', '0970190165', 'Long An', '91 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Hữu Mai', 'Anh', 868472800, NULL, 'tremblay.ethyl@dare.net', 'phạmthànhphúc@gmail.com', '23000009@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000010', NULL, 'Huỳnh Công Mai', NULL, '2007-04-20', 1, 139271516, '2023-07-16', 'Công an Bạc Liêu', '0969550111', 'Trà Vinh', '991 Mậu Thân, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Nguyễn Văn Khang', 'Mẹ', 766768869, NULL, 'mireille.mayert@gmail.com', 'huỳnhcôngmai@gmail.com', '23000010@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000011', NULL, 'Vũ Quang Giang', NULL, '2003-10-27', 1, 528350724, '2023-11-11', 'Công an Trà Vinh', '0379431176', 'Bạc Liêu', '66 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Quang Linh', 'Anh', 350173268, NULL, 'qkuvalis@crist.com', 'vũquanggiang@gmail.com', '23000011@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000012', NULL, 'Võ Hữu Hùng', NULL, '2006-07-24', 1, 415935900, '2024-06-05', 'Công an Đồng Tháp', '0963756323', 'An Giang', '318 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Minh Giang', 'Em', 966881236, NULL, 'breanne.leannon@gmail.com', 'võhữuhùng@gmail.com', '23000012@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000013', NULL, 'Hoàng Văn Em', NULL, '2003-11-30', 1, 530020089, '2024-08-16', 'Công an Bến Tre', '0855812605', 'Bạc Liêu', '810 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Hoàng Em', 'Em', 971394841, NULL, 'edonnelly@yahoo.com', 'hoàngvănem@gmail.com', '23000013@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000014', NULL, 'Hoàng Minh Giang', NULL, '2005-06-02', 0, 435243208, '2023-07-31', 'Công an Sóc Trăng', '0372717995', 'Vĩnh Long', '963 Nguyễn Văn Linh, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Hữu Linh', 'Mẹ', 333336310, NULL, 'sblick@yahoo.com', 'hoàngminhgiang@gmail.com', '23000014@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000015', NULL, 'Nguyễn Hoàng Em', NULL, '2006-01-15', 1, 538959364, '2023-09-17', 'Công an Cà Mau', '0779341262', 'An Giang', '696 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Hoàng Nam', 'Chị', 324822475, NULL, 'ptowne@stroman.info', 'nguyễnhoàngem@gmail.com', '23000015@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000016', NULL, 'Bùi Minh Mai', NULL, '2005-05-22', 0, 612424890, '2024-11-25', 'Công an Cà Mau', '0892988536', 'Hậu Giang', '90 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Minh Em', 'Chị', 986597018, NULL, 'zlang@bartell.com', 'bùiminhmai@gmail.com', '23000016@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000017', NULL, 'Bùi Thị Bình', NULL, '2005-11-02', 0, 218121998, '2025-04-05', 'Công an Tiền Giang', '0344592343', 'Tiền Giang', '489 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Minh Khang', 'Mẹ', 995573785, NULL, 'dibbert.jettie@parisian.com', 'bùithịbình@gmail.com', '23000017@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000018', NULL, 'Phan Đức Dũng', NULL, '2004-08-27', 0, 628585995, '2023-05-20', 'Công an Sóc Trăng', '0848054031', 'Đồng Tháp', '572 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Quang Linh', 'Mẹ', 974912627, NULL, 'lamont18@becker.net', 'phandứcdũng@gmail.com', '23000018@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000019', NULL, 'Vũ Quang Giang', NULL, '2006-10-26', 1, 513491111, '2024-05-11', 'Công an Vĩnh Long', '0774567812', 'Cần Thơ', '433 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Quang Em', 'Chị', 777464114, NULL, 'marion.weimann@gmail.com', 'vũquanggiang@gmail.com', '23000019@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000020', NULL, 'Trần Thành Mai', NULL, '2003-12-23', 1, 229447939, '2025-03-24', 'Công an Bến Tre', '0326426298', 'Bạc Liêu', '534 Nguyễn Văn Linh, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Công Phúc', 'Em', 935915054, NULL, 'gregoria30@gmail.com', 'trầnthànhmai@gmail.com', '23000020@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000021', NULL, 'Phan Hữu Hùng', NULL, '2007-01-29', 0, 315625960, '2024-05-09', 'Công an Long An', '0370436666', 'Vĩnh Long', '485 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Hữu Khang', 'Mẹ', 779106851, NULL, 'isom.bartoletti@mitchell.biz', 'phanhữuhùng@gmail.com', '23000021@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000022', NULL, 'Đặng Minh Giang', NULL, '2003-08-06', 1, 611151177, '2024-10-17', 'Công an Cần Thơ', '0325114720', 'Vĩnh Long', '556 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Văn An', 'Anh', 706761372, NULL, 'maida.hegmann@russel.com', 'dặngminhgiang@gmail.com', '23000022@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000023', NULL, 'Bùi Đức Mai', NULL, '2005-01-11', 1, 238128340, '2023-06-18', 'Công an Vĩnh Long', '0795312964', 'An Giang', '582 Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Quang Linh', 'Em', 828114347, NULL, 'jeanie.doyle@hotmail.com', 'bùidứcmai@gmail.com', '23000023@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000024', NULL, 'Đặng Đức Nam', NULL, '2003-05-23', 0, 627996487, '2024-04-06', 'Công an Vĩnh Long', '0331566911', 'Hậu Giang', '904 Mậu Thân, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Minh Dũng', 'Cha', 795962272, NULL, 'edmond84@luettgen.com', 'dặngdứcnam@gmail.com', '23000024@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000025', NULL, 'Võ Hữu Nam', NULL, '2004-09-09', 0, 314880664, '2023-09-10', 'Công an An Giang', '0337461276', 'Đồng Tháp', '867 30/4, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Công Em', 'Anh', 386478829, NULL, 'tblanda@hotmail.com', 'võhữunam@gmail.com', '23000025@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000026', NULL, 'Phan Hữu Dũng', NULL, '2006-12-05', 1, 334917817, '2024-03-26', 'Công an Vĩnh Long', '0816445344', 'Sóc Trăng', '380 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Minh Phúc', 'Chị', 862980452, NULL, 'addie70@hotmail.com', 'phanhữudũng@gmail.com', '23000026@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000027', NULL, 'Huỳnh Văn Phúc', NULL, '2006-08-07', 1, 122416011, '2025-04-20', 'Công an Tiền Giang', '0393291190', 'Long An', '76 Nguyễn Văn Cừ, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Quang An', 'Cha', 860208830, NULL, 'broderick.johnston@hotmail.com', 'huỳnhvănphúc@gmail.com', '23000027@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000028', NULL, 'Đỗ Thị Nam', NULL, '2005-02-24', 0, 229420630, '2025-04-01', 'Công an Sóc Trăng', '0358356921', 'Kiên Giang', '37 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Văn Nam', 'Cha', 362668106, NULL, 'uhoeger@nolan.com', 'dỗthịnam@gmail.com', '23000028@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000029', NULL, 'Đỗ Công Khang', NULL, '2005-07-26', 0, 629933286, '2025-01-21', 'Công an Sóc Trăng', '0342250181', 'Vĩnh Long', '344 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Hoàng Hùng', 'Anh', 965122310, NULL, 'tremblay.kirk@terry.com', 'dỗcôngkhang@gmail.com', '23000029@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000030', NULL, 'Trần Văn Nam', NULL, '2006-12-10', 0, 221735165, '2023-05-23', 'Công an Đồng Tháp', '0862981501', 'Kiên Giang', '297 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Minh Bình', 'Mẹ', 373410491, NULL, 'sterling.dickens@schmitt.info', 'trầnvănnam@gmail.com', '23000030@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000031', NULL, 'Đỗ Công Linh', NULL, '2005-09-18', 0, 612856019, '2024-04-22', 'Công an An Giang', '0880490891', 'Bạc Liêu', '62 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Nguyễn Thành Phúc', 'Chị', 868938133, NULL, 'slegros@yahoo.com', 'dỗcônglinh@gmail.com', '23000031@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000032', NULL, 'Lê Công Linh', NULL, '2005-08-02', 1, 336375902, '2023-08-31', 'Công an Bạc Liêu', '0884832090', 'Bạc Liêu', '536 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Bình', 'Em', 818247185, NULL, 'rashawn48@schimmel.com', 'lêcônglinh@gmail.com', '23000032@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000033', NULL, 'Trần Quang Cường', NULL, '2003-07-13', 0, 213303281, '2025-05-11', 'Công an Cà Mau', '0937281055', 'Tiền Giang', '723 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Em', 'Mẹ', 347403800, NULL, 'benton.walter@hotmail.com', 'trầnquangcường@gmail.com', '23000033@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000034', NULL, 'Nguyễn Thành Linh', NULL, '2005-11-05', 1, 215790808, '2023-07-17', 'Công an Kiên Giang', '0369710389', 'Đồng Tháp', '528 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Đức Hùng', 'Em', 381356201, NULL, 'jones.darlene@stoltenberg.com', 'nguyễnthànhlinh@gmail.com', '23000034@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000035', NULL, 'Vũ Công Hùng', NULL, '2005-04-29', 1, 130301974, '2023-08-15', 'Công an Hậu Giang', '0837022546', 'Trà Vinh', '368 Nguyễn Văn Cừ, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Hữu Cường', 'Em', 770271581, NULL, 'alejandrin.jast@yahoo.com', 'vũcônghùng@gmail.com', '23000035@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000036', NULL, 'Trần Hoàng Khang', NULL, '2006-03-17', 1, 221502787, '2024-11-24', 'Công an Kiên Giang', '0896079962', 'Sóc Trăng', '635 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Hoàng Phúc', 'Cha', 996104338, NULL, 'nola30@bradtke.org', 'trầnhoàngkhang@gmail.com', '23000036@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000037', NULL, 'Vũ Thành Cường', NULL, '2003-06-13', 1, 339145156, '2023-10-18', 'Công an Cần Thơ', '0773029160', 'Kiên Giang', '83 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Thị Bình', 'Chị', 764682357, NULL, 'ayla69@morar.com', 'vũthànhcường@gmail.com', '23000037@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000038', NULL, 'Vũ Hoàng Hùng', NULL, '2004-02-12', 1, 129453558, '2024-11-24', 'Công an Bạc Liêu', '0771852519', 'An Giang', '773 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Thành Mai', 'Mẹ', 784351467, NULL, 'electa42@hotmail.com', 'vũhoànghùng@gmail.com', '23000038@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000039', NULL, 'Võ Minh Mai', NULL, '2005-08-08', 1, 129487516, '2024-08-03', 'Công an Hậu Giang', '0332489345', 'Tiền Giang', '136 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Hoàng Bình', 'Anh', 346774698, NULL, 'julius.gislason@schmitt.net', 'võminhmai@gmail.com', '23000039@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000040', NULL, 'Phan Hữu Phúc', NULL, '2007-01-21', 1, 518827139, '2025-04-24', 'Công an Cần Thơ', '0840027653', 'Sóc Trăng', '770 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thị Khang', 'Cha', 383512078, NULL, 'streich.waino@raynor.com', 'phanhữuphúc@gmail.com', '23000040@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000041', NULL, 'Đỗ Đức Linh', NULL, '2004-12-28', 0, 415723949, '2025-03-10', 'Công an Bạc Liêu', '0901534038', 'Hậu Giang', '485 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Minh Linh', 'Mẹ', 357814492, NULL, 'goreilly@gmail.com', 'dỗdứclinh@gmail.com', '23000041@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000042', NULL, 'Nguyễn Hữu Bình', NULL, '2004-04-03', 0, 533609104, '2025-04-20', 'Công an Bạc Liêu', '0355102213', 'Cần Thơ', '487 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Văn Giang', 'Cha', 865189101, NULL, 'ferne.haag@hilpert.com', 'nguyễnhữubình@gmail.com', '23000042@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000043', NULL, 'Phạm Hữu Khang', NULL, '2003-12-06', 0, 139377617, '2024-01-27', 'Công an Hậu Giang', '0843539393', 'Vĩnh Long', '148 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Văn Phúc', 'Anh', 901700850, NULL, 'garnet07@hotmail.com', 'phạmhữukhang@gmail.com', '23000043@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000044', NULL, 'Phan Văn Mai', NULL, '2003-09-29', 1, 333628781, '2023-09-25', 'Công an Sóc Trăng', '0700470647', 'Bạc Liêu', '321 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Công Em', 'Em', 811746137, NULL, 'liza.huels@sipes.com', 'phanvănmai@gmail.com', '23000044@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000045', NULL, 'Lê Văn Bình', NULL, '2004-02-18', 1, 228945302, '2024-03-16', 'Công an Hậu Giang', '0814008345', 'Tiền Giang', '496 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Thành Nam', 'Chị', 998880801, NULL, 'tconn@yahoo.com', 'lêvănbình@gmail.com', '23000045@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000046', NULL, 'Lê Thành Hùng', NULL, '2004-10-12', 1, 424361386, '2024-04-25', 'Công an Sóc Trăng', '0333752925', 'Bạc Liêu', '918 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Văn Mai', 'Em', 964384784, NULL, 'rosella.hauck@collins.com', 'lêthànhhùng@gmail.com', '23000046@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000047', NULL, 'Phạm Văn An', NULL, '2004-05-05', 1, 631214545, '2023-05-27', 'Công an Vĩnh Long', '0821675324', 'Cà Mau', '291 Mậu Thân, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Minh Linh', 'Chị', 359788377, NULL, 'pedro14@hotmail.com', 'phạmvănan@gmail.com', '23000047@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000048', NULL, 'Bùi Minh Linh', NULL, '2006-11-20', 0, 138441154, '2024-02-08', 'Công an Hậu Giang', '0838612275', 'Sóc Trăng', '163 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Đức An', 'Cha', 817297042, NULL, 'koepp.oma@gmail.com', 'bùiminhlinh@gmail.com', '23000048@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000049', NULL, 'Hoàng Hoàng Linh', NULL, '2003-10-18', 0, 338370166, '2024-06-17', 'Công an Hậu Giang', '0373069092', 'Vĩnh Long', '608 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Thị Phúc', 'Cha', 785797144, NULL, 'jast.hershel@gmail.com', 'hoànghoànglinh@gmail.com', '23000049@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000050', NULL, 'Vũ Minh Cường', NULL, '2006-09-08', 0, 117147994, '2024-06-01', 'Công an Cần Thơ', '0391778133', 'An Giang', '84 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Quang Nam', 'Chị', 781584475, NULL, 'harvey.darby@larson.com', 'vũminhcường@gmail.com', '23000050@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000051', NULL, 'Hoàng Hoàng An', NULL, '2003-06-14', 0, 613913392, '2024-07-21', 'Công an Trà Vinh', '0984041095', 'Bạc Liêu', '653 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Văn Cường', 'Chị', 901495468, NULL, 'ressie34@kilback.com', 'hoànghoàngan@gmail.com', '23000051@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000052', NULL, 'Huỳnh Minh Phúc', NULL, '2006-03-01', 1, 519810376, '2025-03-08', 'Công an Kiên Giang', '0354131244', 'Hậu Giang', '259 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Văn Linh', 'Mẹ', 989738792, NULL, 'cummerata.brooks@medhurst.com', 'huỳnhminhphúc@gmail.com', '23000052@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000053', NULL, 'Nguyễn Hữu Mai', NULL, '2004-06-25', 1, 210645569, '2024-12-20', 'Công an Bạc Liêu', '0898545357', 'An Giang', '920 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Công Bình', 'Cha', 382763987, NULL, 'terry.forrest@powlowski.com', 'nguyễnhữumai@gmail.com', '23000053@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000054', NULL, 'Huỳnh Đức Linh', NULL, '2003-12-29', 0, 637280264, '2025-03-11', 'Công an Cà Mau', '0838363698', 'Trà Vinh', '367 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Đức Nam', 'Chị', 397565383, NULL, 'cristopher.donnelly@ratke.com', 'huỳnhdứclinh@gmail.com', '23000054@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000055', NULL, 'Phạm Công Mai', NULL, '2004-03-28', 0, 624824391, '2025-03-21', 'Công an Bạc Liêu', '0885807146', 'Cà Mau', '860 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Quang An', 'Em', 937037237, NULL, 'ldeckow@wolf.com', 'phạmcôngmai@gmail.com', '23000055@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000056', NULL, 'Hoàng Đức Em', NULL, '2004-10-18', 0, 410890725, '2023-05-24', 'Công an Kiên Giang', '0882721526', 'Trà Vinh', '491 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Hoàng An', 'Cha', 844199100, NULL, 'anibal.gottlieb@yahoo.com', 'hoàngdứcem@gmail.com', '23000056@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000057', NULL, 'Huỳnh Thị Linh', NULL, '2004-11-17', 1, 139125985, '2024-07-31', 'Công an Đồng Tháp', '0903539508', 'Kiên Giang', '646 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Thị Dũng', 'Anh', 775022692, NULL, 'stark.cara@hotmail.com', 'huỳnhthịlinh@gmail.com', '23000057@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000058', NULL, 'Huỳnh Quang Phúc', NULL, '2003-09-15', 0, 418518946, '2024-03-14', 'Công an Kiên Giang', '0363313073', 'An Giang', '893 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thị Bình', 'Em', 899078860, NULL, 'brain51@yahoo.com', 'huỳnhquangphúc@gmail.com', '23000058@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000059', NULL, 'Võ Thị Nam', NULL, '2004-12-19', 0, 113493714, '2024-07-24', 'Công an Vĩnh Long', '0899583529', 'An Giang', '343 Nguyễn Văn Linh, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Đức Giang', 'Em', 846560644, NULL, 'filiberto90@hotmail.com', 'võthịnam@gmail.com', '23000059@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000060', NULL, 'Bùi Công Khang', NULL, '2006-01-18', 1, 117925110, '2024-01-27', 'Công an Kiên Giang', '0972654688', 'Cần Thơ', '33 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Công Giang', 'Chị', 975686836, NULL, 'magnus41@hotmail.com', 'bùicôngkhang@gmail.com', '23000060@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000061', NULL, 'Huỳnh Hữu Hùng', NULL, '2003-09-15', 0, 217960251, '2025-04-30', 'Công an Hậu Giang', '0834761254', 'Cà Mau', '793 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Minh Khang', 'Anh', 975025518, NULL, 'mckayla.moen@hotmail.com', 'huỳnhhữuhùng@gmail.com', '23000061@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000062', NULL, 'Đỗ Văn Mai', NULL, '2003-12-30', 0, 532179938, '2024-04-02', 'Công an Vĩnh Long', '0795505503', 'Bến Tre', '812 Nguyễn Văn Cừ, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Công Dũng', 'Anh', 338123968, NULL, 'dooley.ozella@hotmail.com', 'dỗvănmai@gmail.com', '23000062@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000063', NULL, 'Hoàng Đức Phúc', NULL, '2006-06-27', 1, 225326480, '2025-05-06', 'Công an Vĩnh Long', '0848828056', 'Cà Mau', '439 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Đức Giang', 'Mẹ', 792471159, NULL, 'keeling.alexandro@hotmail.com', 'hoàngdứcphúc@gmail.com', '23000063@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000064', NULL, 'Bùi Hữu Khang', NULL, '2007-01-09', 0, 621802533, '2024-11-11', 'Công an Cần Thơ', '0356610877', 'Cần Thơ', '767 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Minh Phúc', 'Cha', 344231009, NULL, 'mrodriguez@gleason.com', 'bùihữukhang@gmail.com', '23000064@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000065', NULL, 'Đỗ Văn Hùng', NULL, '2007-03-22', 1, 630610723, '2023-09-14', 'Công an Kiên Giang', '0855909387', 'An Giang', '453 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Công Phúc', 'Mẹ', 982156889, NULL, 'korbin.mayert@gmail.com', 'dỗvănhùng@gmail.com', '23000065@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000066', NULL, 'Lê Hoàng Giang', NULL, '2005-04-26', 1, 634254463, '2023-07-23', 'Công an Trà Vinh', '0839659754', 'Trà Vinh', '243 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Công An', 'Mẹ', 793747464, NULL, 'britney.rice@hotmail.com', 'lêhoànggiang@gmail.com', '23000066@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000067', NULL, 'Hoàng Hoàng Em', NULL, '2004-01-03', 0, 420467573, '2023-07-31', 'Công an An Giang', '0841618932', 'Hậu Giang', '720 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Dũng', 'Mẹ', 889939914, NULL, 'leslie68@gmail.com', 'hoànghoàngem@gmail.com', '23000067@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000068', NULL, 'Huỳnh Hữu Hùng', NULL, '2007-03-29', 1, 512765926, '2024-11-22', 'Công an Trà Vinh', '0975521711', 'Sóc Trăng', '664 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Quang Phúc', 'Chị', 368077982, NULL, 'ivah.cremin@yahoo.com', 'huỳnhhữuhùng@gmail.com', '23000068@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000069', NULL, 'Lê Hữu Phúc', NULL, '2006-04-17', 1, 231550325, '2025-01-04', 'Công an An Giang', '0838583683', 'Hậu Giang', '827 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Văn An', 'Em', 817781923, NULL, 'stephen74@hotmail.com', 'lêhữuphúc@gmail.com', '23000069@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000070', NULL, 'Lê Đức Cường', NULL, '2005-07-24', 0, 635617180, '2024-02-03', 'Công an Bạc Liêu', '0322054803', 'Bến Tre', '709 Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Thành Em', 'Em', 814549358, NULL, 'muriel.murphy@boyer.com', 'lêdứccường@gmail.com', '23000070@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000071', NULL, 'Lê Đức Khang', NULL, '2003-09-28', 0, 337569956, '2024-02-24', 'Công an Hậu Giang', '0328269953', 'Sóc Trăng', '176 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Hữu Mai', 'Mẹ', 838480576, NULL, 'jrippin@halvorson.com', 'lêdứckhang@gmail.com', '23000071@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000072', NULL, 'Đỗ Văn Khang', NULL, '2004-08-01', 1, 136518232, '2023-06-03', 'Công an Vĩnh Long', '0866806545', 'Trà Vinh', '912 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đặng Quang Mai', 'Mẹ', 865286041, NULL, 'bailee.sipes@fadel.com', 'dỗvănkhang@gmail.com', '23000072@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000073', NULL, 'Nguyễn Đức Linh', NULL, '2007-01-18', 1, 217465832, '2025-01-07', 'Công an Sóc Trăng', '0891662809', 'Tiền Giang', '264 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Quang Em', 'Mẹ', 790073502, NULL, 'olen.stanton@gmail.com', 'nguyễndứclinh@gmail.com', '23000073@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000074', NULL, 'Nguyễn Văn Em', NULL, '2005-11-30', 0, 222319580, '2023-08-26', 'Công an Sóc Trăng', '0345023556', 'Bến Tre', '755 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Hoàng Giang', 'Em', 882320593, NULL, 'pollich.susanna@yahoo.com', 'nguyễnvănem@gmail.com', '23000074@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000075', NULL, 'Trần Công Cường', NULL, '2006-07-28', 1, 633479443, '2024-05-09', 'Công an Kiên Giang', '0868004431', 'Kiên Giang', '221 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Thành Mai', 'Chị', 898817611, NULL, 'jesus.feil@yahoo.com', 'trầncôngcường@gmail.com', '23000075@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000076', NULL, 'Phạm Hoàng Mai', NULL, '2004-09-23', 1, 124237399, '2024-05-07', 'Công an Bạc Liêu', '0848639924', 'An Giang', '233 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Hoàng Giang', 'Anh', 843470222, NULL, 'murphy.edythe@walsh.com', 'phạmhoàngmai@gmail.com', '23000076@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000077', NULL, 'Hoàng Quang Dũng', NULL, '2004-11-25', 0, 323009306, '2024-06-01', 'Công an Đồng Tháp', '0936232959', 'Cần Thơ', '532 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Hoàng An', 'Mẹ', 889981948, NULL, 'pmayert@hotmail.com', 'hoàngquangdũng@gmail.com', '23000077@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000078', NULL, 'Lê Đức Bình', NULL, '2006-08-13', 1, 229579046, '2025-04-23', 'Công an Bến Tre', '0320571416', 'Trà Vinh', '644 3/2, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Thành Khang', 'Cha', 340150752, NULL, 'tromp.darrin@gmail.com', 'lêdứcbình@gmail.com', '23000078@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000079', NULL, 'Phan Công Dũng', NULL, '2006-10-11', 1, 628346257, '2023-07-11', 'Công an An Giang', '0352200861', 'Bạc Liêu', '289 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Công Mai', 'Em', 880598764, NULL, 'mitchell.lexie@dach.org', 'phancôngdũng@gmail.com', '23000079@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000080', NULL, 'Nguyễn Thành Hùng', NULL, '2006-02-24', 1, 336635923, '2024-06-03', 'Công an Vĩnh Long', '0794848934', 'Cà Mau', '446 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Công Giang', 'Mẹ', 356207086, NULL, 'lorena.rolfson@parisian.com', 'nguyễnthànhhùng@gmail.com', '23000080@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000081', NULL, 'Bùi Quang Giang', NULL, '2004-04-19', 0, 419416609, '2024-12-13', 'Công an An Giang', '0374463797', 'Tiền Giang', '155 Nguyễn Văn Cừ, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Thị Giang', 'Em', 860210552, NULL, 'lockman.fredrick@gmail.com', 'bùiquanggiang@gmail.com', '23000081@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000082', NULL, 'Huỳnh Hữu An', NULL, '2006-05-13', 1, 130639051, '2024-12-11', 'Công an Cà Mau', '0391795573', 'Cà Mau', '396 3/2, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Vũ Đức Dũng', 'Anh', 702514622, NULL, 'rylan.kautzer@zboncak.com', 'huỳnhhữuan@gmail.com', '23000082@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000083', NULL, 'Phạm Thành Hùng', NULL, '2003-07-27', 1, 525665616, '2024-08-06', 'Công an Vĩnh Long', '0931119943', 'Tiền Giang', '638 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Hoàng Khang', 'Cha', 782383434, NULL, 'elena.gorczany@hotmail.com', 'phạmthànhhùng@gmail.com', '23000083@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000084', NULL, 'Đặng Thành Mai', NULL, '2006-03-10', 1, 212418191, '2024-08-14', 'Công an Sóc Trăng', '0764426300', 'Cà Mau', '646 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Quang Linh', 'Cha', 324627684, NULL, 'misael36@schmidt.biz', 'dặngthànhmai@gmail.com', '23000084@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000085', NULL, 'Đỗ Hoàng Bình', NULL, '2005-04-09', 1, 432849557, '2024-03-23', 'Công an Trà Vinh', '0367417520', 'Vĩnh Long', '953 3/2, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Văn Dũng', 'Cha', 931343135, NULL, 'anais.cummings@yahoo.com', 'dỗhoàngbình@gmail.com', '23000085@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000086', NULL, 'Phạm Công An', NULL, '2006-07-04', 0, 315941084, '2024-03-05', 'Công an Tiền Giang', '0772490922', 'Tiền Giang', '171 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Hữu Giang', 'Mẹ', 883317726, NULL, 'mark66@yahoo.com', 'phạmcôngan@gmail.com', '23000086@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000087', NULL, 'Phạm Hoàng Linh', NULL, '2005-03-31', 1, 532764901, '2023-06-06', 'Công an Kiên Giang', '0968509543', 'Trà Vinh', '899 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Bùi Hoàng Em', 'Cha', 868032557, NULL, 'upton.arely@green.biz', 'phạmhoànglinh@gmail.com', '23000087@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000088', NULL, 'Trần Công Cường', NULL, '2007-02-27', 1, 236032348, '2023-06-25', 'Công an Sóc Trăng', '0371594250', 'Cần Thơ', '381 Mậu Thân, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Công An', 'Mẹ', 869866587, NULL, 'zena87@gmail.com', 'trầncôngcường@gmail.com', '23000088@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000089', NULL, 'Phan Đức Linh', NULL, '2004-07-18', 1, 531602009, '2024-05-28', 'Công an Cần Thơ', '0771107439', 'An Giang', '417 3/2, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Thị Cường', 'Em', 368327156, NULL, 'cierra78@krajcik.net', 'phandứclinh@gmail.com', '23000089@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000090', NULL, 'Phạm Thành Dũng', NULL, '2005-03-21', 0, 327999049, '2024-06-14', 'Công an Sóc Trăng', '0885597664', 'Bạc Liêu', '810 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phan Đức Em', 'Anh', 973002244, NULL, 'raegan70@osinski.com', 'phạmthànhdũng@gmail.com', '23000090@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000091', NULL, 'Võ Hữu Mai', NULL, '2005-05-07', 0, 533907493, '2024-04-06', 'Công an Vĩnh Long', '0810857494', 'Bến Tre', '389 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Thị Em', 'Anh', 932188375, NULL, 'coleman20@stokes.com', 'võhữumai@gmail.com', '23000091@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000092', NULL, 'Vũ Đức An', NULL, '2006-06-12', 1, 316644441, '2025-04-18', 'Công an Vĩnh Long', '0789039355', 'An Giang', '790 3/2, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Thành Dũng', 'Anh', 792109706, NULL, 'lockman.leonard@gleichner.com', 'vũdứcan@gmail.com', '23000092@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000093', NULL, 'Nguyễn Thành Cường', NULL, '2004-10-28', 1, 210521149, '2024-11-20', 'Công an Trà Vinh', '0780079367', 'Kiên Giang', '705 30/4, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Huỳnh Văn An', 'Mẹ', 867561655, NULL, 'kris63@nitzsche.com', 'nguyễnthànhcường@gmail.com', '23000093@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000094', NULL, 'Phan Minh Khang', NULL, '2004-04-04', 1, 114053507, '2024-01-08', 'Công an Vĩnh Long', '0704721922', 'Vĩnh Long', '602 Nguyễn Văn Linh, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Võ Quang Hùng', 'Anh', 844001099, NULL, 'isenger@hotmail.com', 'phanminhkhang@gmail.com', '23000094@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000095', NULL, 'Vũ Văn An', NULL, '2005-01-27', 0, 514276556, '2024-06-19', 'Công an Tiền Giang', '0828370685', 'Vĩnh Long', '526 Mậu Thân, Cái Răng, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Trần Hoàng Phúc', 'Mẹ', 369739964, NULL, 'buford98@hotmail.com', 'vũvănan@gmail.com', '23000095@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000096', NULL, 'Đỗ Hữu An', NULL, '2003-09-11', 1, 523619722, '2023-05-26', 'Công an Long An', '0972035892', 'Kiên Giang', '720 Nguyễn Văn Linh, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Lê Minh Cường', 'Chị', 780548664, NULL, 'boyd93@hotmail.com', 'dỗhữuan@gmail.com', '23000096@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000097', NULL, 'Hoàng Thành Bình', NULL, '2005-09-17', 0, 229959399, '2025-05-07', 'Công an Bạc Liêu', '0827266190', 'Cà Mau', '368 30/4, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Đức An', 'Cha', 969182505, NULL, 'cremin.myra@gmail.com', 'hoàngthànhbình@gmail.com', '23000097@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000098', NULL, 'Bùi Đức Khang', NULL, '2005-07-11', 0, 239642676, '2024-04-15', 'Công an Vĩnh Long', '0895108298', 'Trà Vinh', '414 Nguyễn Văn Cừ, Bình Thủy, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Đỗ Văn Mai', 'Em', 821110077, NULL, 'arvid12@mann.info', 'bùidứckhang@gmail.com', '23000098@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000099', NULL, 'Vũ Công Linh', NULL, '2007-04-06', 0, 437802714, '2024-08-23', 'Công an Bến Tre', '0766180647', 'Bến Tre', '541 Mậu Thân, Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Phạm Thành An', 'Chị', 783225818, NULL, 'milan03@gmail.com', 'vũcônglinh@gmail.com', '23000099@student.cusc.vn', NULL, NULL, NULL, NULL),
('23000100', NULL, 'Bùi Thị Hùng', NULL, '2004-11-17', 0, 323372439, '2025-03-01', 'Công an Vĩnh Long', '0788330434', 'Đồng Tháp', '885 30/4, Ô Môn, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-20', 'Hoàng Quang Nam', 'Cha', 335441984, NULL, 'odeckow@witting.com', 'bùithịhùng@gmail.com', '23000100@student.cusc.vn', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tainguyen_hoctap`
--

CREATE TABLE `tainguyen_hoctap` (
  `MaTaiNguyen` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenTaiNguyen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `LoaiTaiNguyen` enum('Sach','TaiLieu','PhanMem','ThietBiThucHanh') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `TrangThai` enum('KhaDung','KhongKhaDung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KhaDung',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `thietbi`
--

CREATE TABLE `thietbi` (
  `MaThietBi` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `TenThietBi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `MoTa` text COLLATE utf8mb4_unicode_ci,
  `TinhTrang` enum('TotNhat','Tot','TrungBinh','CanSuaChua','HongHoan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tot',
  `NgayNhap` date DEFAULT NULL,
  `HanBaoHanh` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thongtintuyensinh`
--

CREATE TABLE `thongtintuyensinh` (
  `MaTS` varchar(12) NOT NULL,
  `NamTS` int DEFAULT NULL,
  `DotTS` int DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  `ChiTieuTS` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thongtintuyensinh`
--

INSERT INTO `thongtintuyensinh` (`MaTS`, `NamTS`, `DotTS`, `NgayBatDau`, `NgayKetThuc`, `ChiTieuTS`, `created_at`, `updated_at`) VALUES
('TS20251', 2025, 1, '2025-05-18', '2025-06-18', 100, '2025-05-18 15:02:22', '2025-05-18 15:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `tkb`
--

CREATE TABLE `tkb` (
  `TenTKB` varchar(255) NOT NULL,
  `MaLop` varchar(12) DEFAULT NULL,
  `MaHK` varchar(50) DEFAULT NULL,
  `NgayHoc` date DEFAULT NULL,
  `NgayPhienBan` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tkb`
--

INSERT INTO `tkb` (`TenTKB`, `MaLop`, `MaHK`, `NgayHoc`, `NgayPhienBan`) VALUES
('THỜI KHÓA BIỂU LỚP CP2396G11 - HỌC KỲ IV (OV-7023)', 'CP2396G11', 'OV-7023-HK IV', '2025-06-22', NULL),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 'CP2396M02', 'OV-7023-HK II', '2025-05-09', '1.0'),
('THỜI KHÓA BIỂU LỚP CP2396M02 - HỌC KỲ III (OV-7023)', 'CP2396M02', 'OV-7023-HK III', '2025-06-22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trangthaimh`
--

CREATE TABLE `trangthaimh` (
  `MaTTMH` varchar(12) NOT NULL,
  `TrangThai` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tuvan_tuyensinh`
--

CREATE TABLE `tuvan_tuyensinh` (
  `MaTuVan` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `HoTen` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SoDienThoai` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NoiDungTuVan` text COLLATE utf8mb4_unicode_ci,
  `NgayTuVan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TrangThai` enum('ChuaLienHe','DaLienHe','DaHoanThanh') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ChuaLienHe',
  `NhanVienTuVan` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bangcapcanbo`
--
ALTER TABLE `bangcapcanbo`
  ADD PRIMARY KEY (`MaBang`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
  ADD PRIMARY KEY (`MaChuongTrinh`,`TenMH`),
  ADD KEY `TenMH` (`TenMH`);

--
-- Indexes for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  ADD KEY `TenKhungGio` (`TenKhungGio`),
  ADD KEY `MaHK` (`MaHK`),
  ADD KEY `TenMH` (`TenMH`);

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
  ADD PRIMARY KEY (`MaSV`,`TenMH`,`LanThi`),
  ADD KEY `TenMH` (`TenMH`),
  ADD KEY `MaLop` (`MaLop`);

--
-- Indexes for table `donvi`
--
ALTER TABLE `donvi`
  ADD PRIMARY KEY (`MaDV`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`MaFeedback`),
  ADD KEY `feedback_masv_foreign` (`MaSV`),
  ADD KEY `feedback_magv_foreign` (`MaGV`);

--
-- Indexes for table `giangday`
--
ALTER TABLE `giangday`
  ADD PRIMARY KEY (`MaGV`,`MaLop`),
  ADD KEY `MaLop` (`MaLop`);

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
-- Indexes for table `hosotuyensinh`
--
ALTER TABLE `hosotuyensinh`
  ADD PRIMARY KEY (`MaHoSo`),
  ADD KEY `MaSV` (`MaSV`),
  ADD KEY `fk_MaTS` (`MaTS`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD PRIMARY KEY (`MaLichThi`),
  ADD KEY `MaLop` (`MaLop`),
  ADD KEY `TenMH` (`TenMH`),
  ADD KEY `PhongThi` (`PhongThi`);

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
  ADD PRIMARY KEY (`TenMH`);

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
-- Indexes for table `phieulido`
--
ALTER TABLE `phieulido`
  ADD PRIMARY KEY (`MaPhieuLiDo`);

--
-- Indexes for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  ADD PRIMARY KEY (`MaPhanCong`),
  ADD KEY `MaLichThi` (`MaLichThi`),
  ADD KEY `MaCB` (`MaCB`);

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
-- Indexes for table `tainguyen_hoctap`
--
ALTER TABLE `tainguyen_hoctap`
  ADD PRIMARY KEY (`MaTaiNguyen`);

--
-- Indexes for table `taphuan`
--
ALTER TABLE `taphuan`
  ADD PRIMARY KEY (`MaTapHuan`);

--
-- Indexes for table `thietbi`
--
ALTER TABLE `thietbi`
  ADD PRIMARY KEY (`MaThietBi`);

--
-- Indexes for table `thongtintuyensinh`
--
ALTER TABLE `thongtintuyensinh`
  ADD PRIMARY KEY (`MaTS`);

--
-- Indexes for table `tkb`
--
ALTER TABLE `tkb`
  ADD PRIMARY KEY (`TenTKB`),
  ADD KEY `MaHK` (`MaHK`),
  ADD KEY `MaLop` (`MaLop`);

--
-- Indexes for table `trangthaimh`
--
ALTER TABLE `trangthaimh`
  ADD PRIMARY KEY (`MaTTMH`);

--
-- Indexes for table `tuvan_tuyensinh`
--
ALTER TABLE `tuvan_tuyensinh`
  ADD PRIMARY KEY (`MaTuVan`),
  ADD KEY `tuvan_tuyensinh_nhanvientuvan_foreign` (`NhanVienTuVan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lichthi`
--
ALTER TABLE `lichthi`
  MODIFY `MaLichThi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ngaynghi`
--
ALTER TABLE `ngaynghi`
  MODIFY `MaNgayNghi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ngaytuhoc`
--
ALTER TABLE `ngaytuhoc`
  MODIFY `MaNgayTuHoc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `phieulido`
--
ALTER TABLE `phieulido`
  MODIFY `MaPhieuLiDo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phieuphancongthi`
--
ALTER TABLE `phieuphancongthi`
  MODIFY `MaPhanCong` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `chuongtrinh_monhoc_ibfk_2` FOREIGN KEY (`TenMH`) REFERENCES `monhoc` (`TenMH`);

--
-- Constraints for table `danhsachmh`
--
ALTER TABLE `danhsachmh`
  ADD CONSTRAINT `danhsachmh_ibfk_1` FOREIGN KEY (`TenKhungGio`) REFERENCES `khunggio` (`TenKhungGio`),
  ADD CONSTRAINT `danhsachmh_ibfk_2` FOREIGN KEY (`MaHK`) REFERENCES `hocki` (`MaHK`),
  ADD CONSTRAINT `danhsachmh_ibfk_3` FOREIGN KEY (`TenMH`) REFERENCES `monhoc` (`TenMH`);

--
-- Constraints for table `danhsachngaynghi`
--
ALTER TABLE `danhsachngaynghi`
  ADD CONSTRAINT `danhsachngaynghi_ibfk_1` FOREIGN KEY (`TenTKB`) REFERENCES `tkb` (`TenTKB`),
  ADD CONSTRAINT `danhsachngaynghi_ibfk_2` FOREIGN KEY (`MaNgayNghi`) REFERENCES `ngaynghi` (`MaNgayNghi`);

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
  ADD CONSTRAINT `diemthi_ibfk_2` FOREIGN KEY (`TenMH`) REFERENCES `monhoc` (`TenMH`),
  ADD CONSTRAINT `diemthi_ibfk_3` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_magv_foreign` FOREIGN KEY (`MaGV`) REFERENCES `giaovien` (`MaGV`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `feedback_masv_foreign` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `giangday`
--
ALTER TABLE `giangday`
  ADD CONSTRAINT `giangday_ibfk_1` FOREIGN KEY (`MaGV`) REFERENCES `giaovien` (`MaGV`),
  ADD CONSTRAINT `giangday_ibfk_2` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`);

--
-- Constraints for table `giaovien`
--
ALTER TABLE `giaovien`
  ADD CONSTRAINT `giaovien_mabang_foreign` FOREIGN KEY (`MaBang`) REFERENCES `bangcapcanbo` (`MaBang`) ON DELETE SET NULL,
  ADD CONSTRAINT `giaovien_madv_foreign` FOREIGN KEY (`MaDV`) REFERENCES `donvi` (`MaDV`) ON DELETE SET NULL,
  ADD CONSTRAINT `giaovien_mahv_foreign` FOREIGN KEY (`MaHV`) REFERENCES `hocvi` (`MaHV`) ON DELETE SET NULL,
  ADD CONSTRAINT `giaovien_tenchucvu_foreign` FOREIGN KEY (`TenChucVu`) REFERENCES `chucvu` (`TenChucVu`) ON DELETE SET NULL;

--
-- Constraints for table `hocki`
--
ALTER TABLE `hocki`
  ADD CONSTRAINT `hocki_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);

--
-- Constraints for table `hosotuyensinh`
--
ALTER TABLE `hosotuyensinh`
  ADD CONSTRAINT `fk_MaTS` FOREIGN KEY (`MaTS`) REFERENCES `thongtintuyensinh` (`MaTS`),
  ADD CONSTRAINT `hosotuyensinh_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`);

--
-- Constraints for table `lichthi`
--
ALTER TABLE `lichthi`
  ADD CONSTRAINT `lichthi_ibfk_1` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`),
  ADD CONSTRAINT `lichthi_ibfk_2` FOREIGN KEY (`TenMH`) REFERENCES `monhoc` (`TenMH`),
  ADD CONSTRAINT `lichthi_ibfk_3` FOREIGN KEY (`PhongThi`) REFERENCES `phonghoc` (`TenPhong`);

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
  ADD CONSTRAINT `phieuphancongthi_ibfk_1` FOREIGN KEY (`MaLichThi`) REFERENCES `lichthi` (`MaLichThi`),
  ADD CONSTRAINT `phieuphancongthi_ibfk_2` FOREIGN KEY (`MaCB`) REFERENCES `canbo` (`MaCB`);

--
-- Constraints for table `tkb`
--
ALTER TABLE `tkb`
  ADD CONSTRAINT `tkb_ibfk_1` FOREIGN KEY (`MaHK`) REFERENCES `hocki` (`MaHK`),
  ADD CONSTRAINT `tkb_ibfk_2` FOREIGN KEY (`MaLop`) REFERENCES `lophoc` (`MaLop`);

--
-- Constraints for table `tuvan_tuyensinh`
--
ALTER TABLE `tuvan_tuyensinh`
  ADD CONSTRAINT `tuvan_tuyensinh_nhanvientuvan_foreign` FOREIGN KEY (`NhanVienTuVan`) REFERENCES `canbo` (`MaCB`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
