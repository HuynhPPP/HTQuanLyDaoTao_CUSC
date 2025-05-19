-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2025 at 03:54 PM
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
('OV-6060', 'Trí tuệ nhân tạo và máy học – ACN Pro', '1.0', '2025-06-30', 'Dài hạn', '2025-05-19 14:38:55', '2025-05-19 14:38:55'),
('OV-7023', 'Lập trình viên Quốc tế – Aptech', '1.0', '2025-06-24', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:30:03'),
('OV9001', 'Mỹ thuật Đa phương tiện – Arena', '2.0', '2025-06-16', 'Dài hạn', '2025-05-17 13:48:56', '2025-05-19 14:31:16');

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
('GV002', 'Trần Thị Bình', 0, 'tranthib@gmail.com', '0923456789', 'HV002', 'Trưởng khoa', 'DV002', 'BC002', 'MoiGiang', 'Kinh tế học', 'Giảng viên thỉnh giảng', '2018-05-15', NULL, '2025-05-16 07:53:41', '2025-05-16 07:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `hinhthucdanhgia`
--

CREATE TABLE `hinhthucdanhgia` (
  `MaHTDanhGia` varchar(12) NOT NULL,
  `HinhThuc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hocki`
--

CREATE TABLE `hocki` (
  `MaHK` varchar(50) NOT NULL,
  `TenHK` varchar(30) DEFAULT NULL,
  `TongGioGoc` int DEFAULT NULL,
  `TongGioTrienKhai` int DEFAULT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hocki`
--

INSERT INTO `hocki` (`MaHK`, `TenHK`, `TongGioGoc`, `TongGioTrienKhai`, `MaChuongTrinh`) VALUES
('OV-7023-HK I', 'HỌC KỲ I', 168, 172, 'OV-7023'),
('OV-7023-HK II', 'HỌC KỲ II', 218, 200, 'OV-7023'),
('OV-7023-HK III', 'HỌC KỲ III', 170, 168, 'OV-7023'),
('OV-7023-HK IV', 'HỌC KỲ IV', 194, 208, 'OV-7023'),
('OV-7023-HK V', 'HỌC KỲ V', 168, 172, 'OV-7023');

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
-- Table structure for table `lophoc`
--

CREATE TABLE `lophoc` (
  `MaLop` varchar(12) NOT NULL,
  `TenLop` varchar(100) DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `MaChuongTrinh` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lophoc`
--

INSERT INTO `lophoc` (`MaLop`, `TenLop`, `NgayBatDau`, `MaChuongTrinh`) VALUES
('CP2296H07', 'Truyền thông đa phương tiện', NULL, 'OV-7023'),
('CP2396G11', 'Lập trình viên', NULL, 'OV-7023'),
('CP2396M02', 'Quản trị mạng', NULL, 'OV-7023');

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
  `MaMH` varchar(12) DEFAULT NULL,
  `GioGoc` int DEFAULT NULL,
  `GioTrienKhai` int DEFAULT NULL,
  `TietLT` tinyint(1) DEFAULT NULL,
  `TietTH` tinyint(1) DEFAULT NULL,
  `TietLTvaTH` tinyint(1) DEFAULT NULL,
  `MaHTDanhGia` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monhoc`
--

INSERT INTO `monhoc` (`TenMH`, `MaMH`, `GioGoc`, `GioTrienKhai`, `TietLT`, `TietTH`, `TietLTvaTH`, `MaHTDanhGia`) VALUES
('AngularJS', NULL, 16, 16, 0, 1, NULL, NULL),
('Application Programming with C#', NULL, 36, 38, 0, 1, NULL, NULL),
('Computer fundamentals', NULL, 0, 8, 0, 1, NULL, NULL),
('Data Management with SQL server', NULL, 40, 40, 0, 1, NULL, NULL),
('Database Design and Development(core)', NULL, 24, 16, 0, 1, NULL, NULL),
('eProject-Website Development', NULL, 2, 8, 0, 1, NULL, NULL),
('HTML5,CSS and Javascript', NULL, 40, 44, 0, 1, NULL, NULL),
('Information Systems Analysis(Core)', NULL, 24, 12, 1, 0, NULL, NULL),
('Java Programming - I', NULL, 36, 40, 0, 1, NULL, NULL),
('Java Programming -II', NULL, 40, 42, 0, 1, NULL, NULL),
('Logic Building and Elementary Programing', NULL, 40, 42, 0, 1, NULL, NULL),
('Markup Language & JSON ', NULL, 16, 16, 0, 1, NULL, NULL),
('PHP Web Development with Laravel Framework', NULL, 40, 40, 0, 1, NULL, NULL),
('Project-Java Application Development', NULL, 2, 12, 1, 0, NULL, NULL);

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
-- Table structure for table `phonghoc`
--

CREATE TABLE `phonghoc` (
  `TenPhong` varchar(20) NOT NULL,
  `LoaiPhong` varchar(255) DEFAULT NULL,
  `SucChua` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phonghoc`
--

INSERT INTO `phonghoc` (`TenPhong`, `LoaiPhong`, `SucChua`) VALUES
('Class1', 'Class', NULL),
('Class2', 'Class', NULL),
('Lab1', 'Lab', NULL),
('Lab2', 'Lab', NULL);

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
('IHwNy68eujNX5FITKQ4osinTHLLct1xXCDUI0Ui4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoia1JYNGpwY0FxSEhqbVVxN3drcVFjM2FMd2VxaHQ4Ujgza2lyUEg0TSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbWluaXN0cnkvc2NoZWR1bGVzL3NjaGVkdWxlL1RIJUUxJUJCJTlDSSUyMEtIJUMzJTkzQSUyMEJJJUUxJUJCJTgyVSUyMEwlRTElQkIlOUFQJTIwQ1AyMzk2TTAyJTIwLSUyMEglRTElQkIlOERjJTIwSyVFMSVCQiVCMyUyMElJJTIwJTI4T1YtNzAyMyUyOSI7fXM6MTQ6ImNhcHRjaGFfcGhyYXNlIjtzOjU6InhMNlJlIjtzOjQ6InVzZXIiO3M6MTI6ImFkbWluLmtodW9uZyI7czoxMToiZGlzcGxheW5hbWUiO3M6MTA6IlRhbiBLaHVvbmciO3M6NDoicm9sZSI7czo1OiJhZG1pbiI7fQ==', 1747670014);

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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`MaSV`, `MaEnroll`, `HoTen`, `InDebt`, `NgaySinh`, `GioiTinh`, `SoCCCD`, `NgayCap`, `NoiCap`, `Sdt`, `NoiSinh`, `DiaChi`, `Zalo`, `Receipt`, `Invoice`, `Billing`, `Coll`, `Billing(VND)`, `Coll(VND)`, `Discount`, `LiDo`, `NgayDangKi`, `HoTenNguoiThan`, `MoiQuanHe`, `SdtNguoiThan`, `ZaloNguoiThan`, `EmailNguoiThan`, `Email`, `EmailCUSC`, `Size`, `created_at`, `updated_at`) VALUES
('21010001', NULL, 'Nguyễn Văn A', NULL, '2003-06-12', 1, 12345678, NULL, NULL, '0944902423', NULL, 'Ninh Kiều', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nva1@gmail.com', NULL, NULL, '2025-05-11 06:31:03', '2025-05-12 14:07:51'),
('21010002', NULL, 'Trần Thị B', NULL, '1970-01-01', 0, 12345679, NULL, NULL, '0912345679', NULL, 'Bình Thủy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ttb2@gmail.com', NULL, NULL, '2025-05-11 06:31:03', '2025-05-12 14:07:57'),
('21010003', NULL, 'Lê Văn C', NULL, '1970-01-01', 1, 12345680, NULL, NULL, '0912345680', NULL, 'Cái Răng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'lvc3@gmail.com', NULL, NULL, '2025-05-11 06:31:03', '2025-05-12 14:08:03');

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
-- Table structure for table `tinhtranghoctap`
--

CREATE TABLE `tinhtranghoctap` (
  `MaTTHocTap` varchar(12) NOT NULL,
  `TinhTrang` varchar(255) DEFAULT NULL,
  `MaSV` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', 'CP2396M02', 'OV-7023-HK II', '2025-05-09', '1.0');

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
-- Indexes for table `hinhthucdanhgia`
--
ALTER TABLE `hinhthucdanhgia`
  ADD PRIMARY KEY (`MaHTDanhGia`);

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
-- Indexes for table `lophoc`
--
ALTER TABLE `lophoc`
  ADD PRIMARY KEY (`MaLop`),
  ADD KEY `MaChuongTrinh` (`MaChuongTrinh`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`TenMH`),
  ADD KEY `MaHTDanhGia` (`MaHTDanhGia`);

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
-- Indexes for table `tinhtranghoctap`
--
ALTER TABLE `tinhtranghoctap`
  ADD PRIMARY KEY (`MaTTHocTap`),
  ADD KEY `MaSV` (`MaSV`);

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
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_magv_foreign` FOREIGN KEY (`MaGV`) REFERENCES `giaovien` (`MaGV`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `feedback_masv_foreign` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Constraints for table `lophoc`
--
ALTER TABLE `lophoc`
  ADD CONSTRAINT `lophoc_ibfk_1` FOREIGN KEY (`MaChuongTrinh`) REFERENCES `chuongtrinh` (`MaChuongTrinh`);

--
-- Constraints for table `monhoc`
--
ALTER TABLE `monhoc`
  ADD CONSTRAINT `monhoc_ibfk_1` FOREIGN KEY (`MaHTDanhGia`) REFERENCES `hinhthucdanhgia` (`MaHTDanhGia`);

--
-- Constraints for table `ngaytuhoc`
--
ALTER TABLE `ngaytuhoc`
  ADD CONSTRAINT `ngaytuhoc_ibfk_1` FOREIGN KEY (`TenTKB`) REFERENCES `tkb` (`TenTKB`);

--
-- Constraints for table `tinhtranghoctap`
--
ALTER TABLE `tinhtranghoctap`
  ADD CONSTRAINT `tinhtranghoctap_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `sinhvien` (`MaSV`);

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
