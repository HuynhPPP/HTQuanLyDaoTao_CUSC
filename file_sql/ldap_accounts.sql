-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2025 at 09:08 AM
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
-- Table structure for table `ldap_accounts`
--

CREATE TABLE `ldap_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `MaTaiKhoan` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('student','teacher','staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'student',
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ldap_accounts`
--

INSERT INTO `ldap_accounts` (`id`, `MaTaiKhoan`, `username`, `email`, `full_name`, `initial_password`, `role`, `is_sent`, `is_active`, `created_at`, `updated_at`) VALUES
(13, '21010003', '21010003levanc', '21010003levanc@cusc.ctu.vn', 'Lê Văn C', '$2y$12$MfIGAS.bEtBUYiEl.VEI1u.wYdnrLHWcmnyrjYOWkZrL0ptFEroTK', 'student', 1, 1, '2025-06-02 23:55:48', '2025-06-03 20:03:15'),
(14, '23000001', '23000001ovanan', '23000001ovanan@cusc.ctu.vn', 'Đỗ Văn An', '7@lwrYtMzb4@', 'student', 0, 1, '2025-06-02 23:55:48', '2025-06-02 23:55:48'),
(15, '23000002', '23000002ohuuhung', '23000002ohuuhung@cusc.ctu.vn', 'Đỗ Hữu Hùng', 'PkP++4z;lh(Z', 'student', 0, 1, '2025-06-02 23:55:48', '2025-06-02 23:55:48'),
(32, 'GV001', 'gv001nguyenvanan', 'gv001nguyenvanan@cusc.ctu.vn', 'Nguyễn Văn An', '$2y$12$k99GuVyQPJ5khWkfUWLE8.SIyeddGhB4qqnSaYrXaH0UgeStCQLG6', 'teacher', 1, 1, '2025-06-03 06:13:21', '2025-06-03 19:32:06'),
(33, 'GV002', 'gv002tranthibinh', 'gv002tranthibinh@cusc.ctu.vn', 'Trần Thị Bình', '6#u&ev4.Yxt#', 'teacher', 0, 1, '2025-06-03 06:13:21', '2025-06-03 06:13:21'),
(34, 'GV003', 'gv003phamduclinh', 'gv003phamduclinh@cusc.ctu.vn', 'Phạm Đức Linh', '7S>1#:Y7E4En', 'teacher', 0, 1, '2025-06-03 06:13:21', '2025-06-03 06:13:21'),
(35, 'GV004', 'gv004hoangquanggiang', 'gv004hoangquanggiang@cusc.ctu.vn', 'Hoàng Quang Giang', '|L}D@4oq4}wZ', 'teacher', 0, 1, '2025-06-03 06:13:21', '2025-06-03 06:13:21'),
(36, 'GV005', 'gv005hoangcongnam', 'gv005hoangcongnam@cusc.ctu.vn', 'Hoàng Công Nam', '20qr(=f,&6Lo', 'teacher', 0, 1, '2025-06-03 06:13:22', '2025-06-03 06:13:22'),
(37, 'GV006', 'gv006phanhuugiang', 'gv006phanhuugiang@cusc.ctu.vn', 'Phan Hữu Giang', 'Pa)T2^bTR{E?', 'teacher', 0, 1, '2025-06-03 06:13:22', '2025-06-03 06:13:22'),
(38, 'GV007', 'gv007phanducphuc', 'gv007phanducphuc@cusc.ctu.vn', 'Phan Đức Phúc', '^%Ag80SPSQFf', 'teacher', 0, 1, '2025-06-03 06:13:22', '2025-06-03 06:13:22'),
(39, 'GV008', 'gv008danghoanggiang', 'gv008danghoanggiang@cusc.ctu.vn', 'Đặng Hoàng Giang', ';6+399Oxc@4i', 'teacher', 0, 1, '2025-06-03 06:13:22', '2025-06-03 06:13:22');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ldap_accounts`
--
ALTER TABLE `ldap_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
