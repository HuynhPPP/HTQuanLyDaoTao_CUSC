-- Script hợp nhất cơ sở dữ liệu Quản Lý Đào Tạo
-- Tạo các cột bổ sung nếu chưa tồn tại

-- Thêm cột HinhThucDanhGia vào bảng chuongtrinh nếu chưa có
ALTER TABLE `chuongtrinh` 
ADD COLUMN `HinhThucDanhGia` varchar(255) DEFAULT NULL;

-- Thêm cột TenMH vào bảng chuongtrinh_monhoc nếu chưa có
ALTER TABLE `chuongtrinh_monhoc` 
ADD COLUMN `TenMH` varchar(255) DEFAULT NULL;

-- Thêm cột id vào bảng danhsachmh nếu chưa có
ALTER TABLE `danhsachmh` 
ADD COLUMN `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- Merge dữ liệu từ các bảng
-- Lưu ý: Sử dụng INSERT IGNORE để tránh trùng lặp

-- Merge bảng bangcapcanbo
INSERT IGNORE INTO `bangcapcanbo` 
SELECT * FROM `qldaotao_vi`.`bangcapcanbo`;

-- Merge bảng canbo
INSERT IGNORE INTO `canbo` 
SELECT * FROM `qldaotao_vi`.`canbo`;

-- Merge bảng chucvu
INSERT IGNORE INTO `chucvu` 
SELECT * FROM `qldaotao_vi`.`chucvu`;

-- Merge bảng chuongtrinh (cập nhật HinhThucDanhGia)
INSERT INTO `chuongtrinh` 
    (`MaChuongTrinh`, `TenChuongTrinh`, `PhienBan`, `NgayTrienKhaiPB`, 
     `TenKhoaDaoTao`, `HinhThucDanhGia`, `created_at`, `updated_at`)
SELECT 
    `MaChuongTrinh`, `TenChuongTrinh`, `PhienBan`, `NgayTrienKhaiPB`, 
    `TenKhoaDaoTao`, `HinhThucDanhGia`, `created_at`, `updated_at`
FROM `qldaotao_vi`.`chuongtrinh`
ON DUPLICATE KEY UPDATE 
    `TenChuongTrinh` = VALUES(`TenChuongTrinh`),
    `PhienBan` = VALUES(`PhienBan`),
    `NgayTrienKhaiPB` = VALUES(`NgayTrienKhaiPB`),
    `TenKhoaDaoTao` = VALUES(`TenKhoaDaoTao`),
    `HinhThucDanhGia` = VALUES(`HinhThucDanhGia`);

-- Merge bảng chuongtrinh_monhoc (cập nhật TenMH)
INSERT INTO `chuongtrinh_monhoc` 
    (`MaChuongTrinh`, `MaMH`, `TenMH`, `created_at`, `updated_at`)
SELECT 
    `MaChuongTrinh`, `MaMH`, `TenMH`, `created_at`, `updated_at`
FROM `qldaotao_vi`.`chuongtrinh_monhoc`
ON DUPLICATE KEY UPDATE 
    `TenMH` = VALUES(`TenMH`);

-- Merge các bảng còn lại
-- Thêm các bảng khác tương tự ở đây

-- Lưu ý: Kiểm tra và điều chỉnh các ràng buộc, khóa ngoại nếu cần 