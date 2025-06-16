-- Script Hợp Nhất An Toàn Hệ Thống Quản Lý Đào Tạo
-- Phiên bản nâng cao với kiểm tra xung đột

-- Bước 1: Tạo bảng tạm để lưu trữ dữ liệu gốc
DROP PROCEDURE IF EXISTS MergeDatabase;
DELIMITER //

CREATE PROCEDURE MergeDatabase()
BEGIN
    -- Bảng tạm để lưu trữ dữ liệu gốc
    CREATE TEMPORARY TABLE temp_bangcapcanbo AS 
    SELECT * FROM `qldaotao_vi`.`bangcapcanbo`;

    CREATE TEMPORARY TABLE temp_canbo AS 
    SELECT * FROM `qldaotao_vi`.`canbo`;

    CREATE TEMPORARY TABLE temp_chucvu AS 
    SELECT * FROM `qldaotao_vi`.`chucvu`;

    CREATE TEMPORARY TABLE temp_chuongtrinh AS 
    SELECT * FROM `qldaotao_vi`.`chuongtrinh`;

    CREATE TEMPORARY TABLE temp_chuongtrinh_monhoc AS 
    SELECT * FROM `qldaotao_vi`.`chuongtrinh_monhoc`;

    -- Bước 2: Thêm các cột bổ sung
    ALTER TABLE `chuongtrinh` 
    ADD COLUMN IF NOT EXISTS `HinhThucDanhGia` varchar(255) DEFAULT NULL;

    ALTER TABLE `chuongtrinh_monhoc` 
    ADD COLUMN IF NOT EXISTS `TenMH` varchar(255) DEFAULT NULL;

    -- Bước 3: Kiểm tra và xử lý xung đột
    -- Ví dụ với bảng bangcapcanbo
    INSERT INTO `bangcapcanbo` 
    SELECT * FROM temp_bangcapcanbo t
    ON DUPLICATE KEY UPDATE 
        `TenBang` = COALESCE(t.`TenBang`, `TenBang`),
        `ThoiGianCap` = COALESCE(t.`ThoiGianCap`, `ThoiGianCap`),
        `DonViCap` = COALESCE(t.`DonViCap`, `DonViCap`);

    -- Tương tự cho các bảng khác với logic xử lý riêng
    INSERT INTO `canbo` 
    SELECT * FROM temp_canbo t
    ON DUPLICATE KEY UPDATE 
        `HoTenCB` = COALESCE(t.`HoTenCB`, `HoTenCB`),
        `Email` = COALESCE(t.`Email`, `Email`),
        `Sdt` = COALESCE(t.`Sdt`, `Sdt`);

    INSERT INTO `chucvu` 
    SELECT * FROM temp_chucvu t
    ON DUPLICATE KEY UPDATE 
        `ThoiGianBatDauCV` = COALESCE(t.`ThoiGianBatDauCV`, `ThoiGianBatDauCV`),
        `ThoiGianKTCV` = COALESCE(t.`ThoiGianKTCV`, `ThoiGianKTCV`);

    -- Xử lý riêng cho bảng chuongtrinh với logic ưu tiên phiên bản mới nhất
    INSERT INTO `chuongtrinh` 
    SELECT * FROM temp_chuongtrinh t
    ON DUPLICATE KEY UPDATE 
        `PhienBan` = CASE 
            WHEN t.`PhienBan` > `PhienBan` THEN t.`PhienBan`
            ELSE `PhienBan`
        END,
        `NgayTrienKhaiPB` = CASE 
            WHEN t.`NgayTrienKhaiPB` > `NgayTrienKhaiPB` THEN t.`NgayTrienKhaiPB`
            ELSE `NgayTrienKhaiPB`
        END,
        `HinhThucDanhGia` = COALESCE(t.`HinhThucDanhGia`, `HinhThucDanhGia`);

    -- Xử lý bảng chuongtrinh_monhoc
    INSERT INTO `chuongtrinh_monhoc` 
    SELECT * FROM temp_chuongtrinh_monhoc t
    ON DUPLICATE KEY UPDATE 
        `TenMH` = COALESCE(t.`TenMH`, `TenMH`);

    -- Bước 4: Dọn dẹp bảng tạm
    DROP TEMPORARY TABLE temp_bangcapcanbo;
    DROP TEMPORARY TABLE temp_canbo;
    DROP TEMPORARY TABLE temp_chucvu;
    DROP TEMPORARY TABLE temp_chuongtrinh;
    DROP TEMPORARY TABLE temp_chuongtrinh_monhoc;

    -- Thông báo hoàn thành
    SELECT 'Hợp nhất dữ liệu thành công!' AS Result;
END //

DELIMITER ;

-- Gọi Procedure để thực thi
CALL MergeDatabase(); 