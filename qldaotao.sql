CREATE DATABASE QLDAOTAO;

USE qldaotao;

CREATE TABLE `BangCapCanBo`(
	`MaBang` VARCHAR(12) PRIMARY KEY,
	`TenBang` VARCHAR(50),
	`ThoiGianCap` DATE,
	`DonViCap` VARCHAR(255),
	`SoHieu` VARCHAR(30),
	`SoVaoSo` VARCHAR(30)
);

CREATE TABLE `TapHuan`(
	`MaTapHuan` VARCHAR(12) PRIMARY KEY,
	`TenKhoaTapHuan` VARCHAR(30) ,
	`ThoiGianBatDau` DATE,
	`ThoiGianKetThuc` DATE,
	`DiaDiem` VARCHAR(20)
);

CREATE TABLE `ChucVu`(
	`TenChucVu` VARCHAR(30) PRIMARY KEY,
 	`ThoiGianDamNhanCV` VARCHAR(50),/*Thoi gian bat dau cong viec*/
 	`ThoiGianKTCV` VARCHAR(50)/*Thoi gian ket thuc cong viec*/
);
 
CREATE TABLE `HocVi` (
	`MaHV` VARCHAR(12) PRIMARY KEY,
 	`TenHocVi` VARCHAR(50) ,
 	`NganhHoc` VARCHAR(255),
 	`ChuyenNganh` VARCHAR(255),
 	`CoSoDaoTao` VARCHAR(255),
 	`NamCap` DATE,
 	`HinhThucDaoTao` VARCHAR(255)
);

CREATE TABLE `PhuTrach`(
	`CongViecPhuTrach` VARCHAR(255) PRIMARY KEY,
	`MieuTaChiTiet` VARCHAR(255) /*giai thich cong viec can bo phu trach*/
);



CREATE TABLE `DonVi`(
	`MaDV` VARCHAR(12) PRIMARY KEY,
	`TenDVHienTai` VARCHAR(255),
	`TenDVTungCongTac` VARCHAR(255)/*co the chuyen sang ban khac  hay k can bo co the cong tac tai nhieu bo mon khac nhau*/ 
);
 
CREATE TABLE `CanBo` (
  `MaCB` VARCHAR(12) PRIMARY KEY,
  `HoTenCB` VARCHAR(30),
  `GioiTinh` BOOLEAN,
  `Email` VARCHAR(40),
  `Sdt` INT,
  `MaHV` VARCHAR(12),
  `TenChucVu` VARCHAR(30),
  `CongViecPhuTrach` VARCHAR(255),
  `MaDV` VARCHAR(12),
  `MaBang` VARCHAR(12),
  `MaTapHuan` VARCHAR(12),
  `ThoiGianBDCongTacCUSC` DATE,
  `ThoiGianKTCongTacCUSC` DATE,
  FOREIGN KEY (`MaDV`) REFERENCES `DonVi`(`MaDV`) ,
  FOREIGN KEY (`CongViecPhuTrach`) REFERENCES `PhuTrach`(`CongViecPhuTrach`),
  FOREIGN KEY (`MaHV`) REFERENCES `HocVi`(`MaHV`),
  FOREIGN KEY (`TenChucVu`) REFERENCES `ChucVu`(`TenChucVu`),
  FOREIGN KEY (`MaBang`) REFERENCES `BangCapCanBo`(`MaBang`),
  FOREIGN KEY (`MaTapHuan`) REFERENCES `TapHuan`(`MaTapHuan`)
);

CREATE TABLE `KhoaDaoTao`(/*doi thanh khoadaotao*/
	`TenKhoaDaoTao` VARCHAR(20) PRIMARY KEY,/*daihan,nganhan,theo yeu cau*/
	`ThoiGianDaoTao` VARCHAR(10)
);
CREATE TABLE `ChuongTrinh` (
  `MaChuongTrinh` VARCHAR(12) PRIMARY KEY,
  `TenChuongTrinh` VARCHAR(30),
  `PhienBan` VARCHAR(12),
  `NgayTrienKhaiPB` DATE,
  `TenKhoaDaoTao` VARCHAR(20),/*môn học trong chương trình*/
  FOREIGN KEY (`TenKhoaDaoTao`) REFERENCES `KhoaDaoTao`(`TenKhoaDaoTao`)
);
 


CREATE TABLE `SinhVien` (
  `MaSV` VARCHAR(12) PRIMARY KEY,
  `MaEnroll` VARCHAR(6),
  `HoTen` VARCHAR(30),
  `InDebt` VARCHAR(255),
  `NgaySinh` DATE,
  `GioiTinh` BOOLEAN,
  `SoCCCD` INT,
  `NgayCap` DATE,
  `NoiCap` VARCHAR(80),
  `Sdt` INT,
  `NoiSinh` VARCHAR(50),
  `DiaChi` VARCHAR(255),
  `Zalo` INT,
  `Receipt` INT,
  `Invoice` INT,
  `Billing` FLOAT(10,2),
  `Coll` FLOAT(10,2),
  `Billing(VND)` INT,
  `Coll(VND)` INT,
  `Discount` DECIMAL(3,2),
  `LiDo` VARCHAR(255),
  `NgayDangKi` DATE,
  `HoTenNguoiThan` VARCHAR(30),
  `MoiQuanHe` VARCHAR(15),
  `SdtNguoiThan` INT,
  `ZaloNguoiThan` INT,
  `EmailNguoiThan` VARCHAR(40),
  `Email` VARCHAR(40),
  `EmailCUSC` VARCHAR(40),
  `Size` VARCHAR(12)
);

CREATE TABLE `HoSo` (
	`Hinh3X4` BOOLEAN,
	`HinhCCCD` BOOLEAN,
	`ToDangKi` BOOLEAN,
	`MaSV` VARCHAR(12),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `TinhTrangHocTap`(
	`MaTTHocTap` VARCHAR(12) PRIMARY KEY,
	`TinhTrang` VARCHAR(255),
	`MaSV` VARCHAR(12),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);


CREATE TABLE `PhieuLiDo`(
	`MaPhieuLiDo` INT AUTO_INCREMENT PRIMARY KEY,
	`ThoiGianBD` DATE,
	`ThoiGianKT` DATE,
	`MonBD` VARCHAR(255),
	`MonSau` VARCHAR(255), /*chuyen tu mon nay sang mon khac lop khac*/
	`LiDo` VARCHAR(255),
	`NgayDuyetDon` DATE,
	`NguoiDuyetDon` DATE
);


CREATE TABLE `LopHoc` (
  `MaLop` VARCHAR(12) PRIMARY KEY,
  `TenLop` VARCHAR(20),
  `NgayBatDau` DATE,
  `MaChuongTrinh` VARCHAR(12),
  FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`)
);

CREATE TABLE `DanhSachSV`(
	`MaLop` VARCHAR(12),
	`MaSV` VARCHAR(12),
	FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `HinhThucDanhGia`(
	`MaHTDanhGia` VARCHAR(12) PRIMARY KEY,
	`LT` BOOLEAN,
	`TH` BOOLEAN,
	`Assignment` BOOLEAN,
	`BCProject` BOOLEAN,
	`BaiTapLon` BOOLEAN
);

CREATE TABLE `MonHoc` (
  `TenMH` VARCHAR(255) PRIMARY KEY,
  `MaMH` VARCHAR(12),
  `MaChuongTrinh` VARCHAR(12),
  `GioGoc` INT,
  `GioTrienKhai` INT,
  `SoTietLT`  INT,
  `SoTietTH` INT,
  `SoTietLTvaTH` INT,
  `MaHTDanhGia` VARCHAR(12),
  FOREIGN KEY (`MaHTDanhGia`) REFERENCES `HinhThucDanhGia`(`MaHTDanhGia`),
  FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`)
);

CREATE TABLE `TrangThaiMH`(
	`MaTTMH` VARCHAR(12) PRIMARY KEY,
	`TrangThai` BOOLEAN
);


CREATE TABLE `PhongHoc` (
  `TenPhong` VARCHAR(20) PRIMARY KEY,
  `LoaiPhong` VARCHAR(255),
  `SucChua` INT
);

CREATE TABLE `TheoDoiMHSapBatDau`(
	`MaTheoDoiMH` VARCHAR(12) PRIMARY KEY,
  TenMH VARCHAR(30) ,
  `MaLop` VARCHAR(12),
  TenPhong VARCHAR(12) ,
  NgayBatDau DATE,
  `GioHoc` VARCHAR(30),
  `HocKy` VARCHAR(50), 
  `MaTTMH` VARCHAR(12),
  FOREIGN KEY (`MaTTMH`) REFERENCES `TrangThaiMH`(`MaTTMH`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
  FOREIGN KEY (`TenMH`) REFERENCES `MonHoc`(`TenMH`),
  FOREIGN KEY (`TenPhong`) REFERENCES `PhongHoc`(`TenPhong`)
);

CREATE TABLE `PhanCongGiangDay` (
  `MaCB` VARCHAR(12),
  `MaLop` VARCHAR(12),
  `HocKy` VARCHAR(50),
  `NgayBatDau` DATE,
  `GioHoc` VARCHAR(30),
  `MaTheoDoiMH` VARCHAR(12),
  FOREIGN KEY (`MaTheoDoiMH`) REFERENCES `TheoDoiMHSapBatDau`(`MaTheoDoiMH`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`)
);

CREATE TABLE `TKB` (
	`TenTKB` VARCHAR(30) PRIMARY KEY,
  `MaLop` VARCHAR(12),
  `TuanHoc` INT,
  `TenPhongLT` VARCHAR(20),
  `TenPhongTH` VARCHAR(20),
  `BuoiLyThuyet` INT,
  `BuoiThucHanh` INT,
  `MaTheoDoiMH` VARCHAR(12),
  FOREIGN KEY (`MaTheoDoiMH`) REFERENCES `TheoDoiMHSapBatDau`(`MaTheoDoiMH`),
  FOREIGN KEY (`TenPhongLT`) REFERENCES `PhongHoc`(`TenPhong`),
  FOREIGN KEY (`TenPhongTH`) REFERENCES `PhongHoc`(`TenPhong`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`)
);



CREATE TABLE `LichThi` (
  `MaLichThi` VARCHAR(12) PRIMARY KEY,
  `NgayThi` DATE,
  `TenPhong` VARCHAR(20),
  `MaLop` VARCHAR(12),
  `MaHTDanhGia` VARCHAR(12),
  `LanThi` INT,
  `GhiChu` VARCHAR(255),
  `MaTheoDoiMH` VARCHAR(12),
  FOREIGN KEY (`MaTheoDoiMH`) REFERENCES `TheoDoiMHSapBatDau`(`MaTheoDoiMH`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
  FOREIGN KEY (`TenPhong`) REFERENCES `PhongHoc`(`TenPhong`),
  FOREIGN KEY (`MaHTDanhGia`) REFERENCES `HinhThucDanhGia`(`MaHTDanhGia`)
);

CREATE TABLE `Diem` (
  `MaDiem` VARCHAR(12) PRIMARY KEY,
  `MaSV` VARCHAR(12),
  `TenMH` VARCHAR(255),
  `DiemSo` FLOAT,
  `LanThi` INT,
  `KetQuaThi` BOOLEAN,
  `MaLichThi` VARCHAR(12),
  FOREIGN KEY (`MaLichThi`) REFERENCES `LichThi`(`MaLichThi`),
  FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`),
  FOREIGN KEY (`TenMH`) REFERENCES `MonHoc`(`TenMH`)
);


CREATE TABLE `BaoCaoKetQuaHocTap` (
  `MaLichThi` VARCHAR(12),
  `ThoiGianHoc` VARCHAR(20),
  `SoBuoiVang` INT,
  `NamHoc` INT,
  `MaTheoDoiMH` VARCHAR(12),
  `MaDiem` VARCHAR(12),
  FOREIGN KEY (`MaDiem`) REFERENCES `Diem`(`MaDiem`),
  FOREIGN KEY (`MaLichThi`) REFERENCES `LichThi`(`MaLichThi`),
  FOREIGN KEY (`MaTheoDoiMH`) REFERENCES `TheoDoiMHSapBatDau`(`MaTheoDoiMH`)
);
CREATE TABLE `TheoDoiPhongHoc`(
  `GhiChu` VARCHAR(255),
  `MaCB` VARCHAR(12),
  `TenTKB` VARCHAR(30),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`),
  FOREIGN KEY (`TenTKB`) REFERENCES `TKB`(`TenTKB`)
 
);

INSERT INTO TapHuan (MaTapHuan, TenKhoaTapHuan, ThoiGianBatDau, ThoiGianKetThuc, DiaDiem)
VALUES
('MH001', 'Khoa học dữ liệu', '2024-05-31', '2024-06-02', 'TP. Hồ Chí Minh'),
('MH002', 'Phân tích dữ liệu lớn', '2024-06-05', '2024-06-09', 'Hà Nội'),
('MH003', 'Lập trình ứng dụng web', '2024-06-12', '2024-06-16', 'Đà Nẵng'),
('MH004', 'Hệ thống nhúng', '2024-06-19', '2024-06-23', 'Cần Thơ'),
('MH005', 'Mạng máy tính và truyền thông', '2024-06-26', '2024-06-30', 'Hải Phòng'),
('MH006', 'An ninh mạng', '2024-07-03', '2024-07-07', 'Huế'),
('MH007', 'Trí tuệ nhân tạo', '2024-07-10', '2024-07-14', 'Quảng Ninh'),
('MH008', 'Xử lý ngôn ngữ tự nhiên', '2024-07-17', '2024-07-21', 'Bình Dương'),
('MH009', 'Học máy', '2024-07-24', '2024-07-28', 'Đồng Nai'),
('MH010', 'Khai phá dữ liệu', '2024-07-31', '2024-08-04', 'Vũng Tàu'),
('MH011', 'Công nghệ thông tin di động', '2024-08-07', '2024-08-11', 'Cần Thơ'),
('MH012', 'Bảo mật thông tin', '2024-08-14', '2024-08-18', 'TP. Hồ Chí Minh');
/*du lieu cua bang bang cap can bo*/
INSERT INTO BangCapCanBo (MaBang, TenBang, ChuyenMon, ThoiGianCap, DonViCap, SoHieu, SoVaoSo)
VALUES
('BC001', 'Cử nhân Công nghệ Thông tin', 'Công nghệ phần mềm', '2020-07-01', ' Trường Đại học Cần Thơ', 'A123456', 'GV123456'),
('BC002', 'Thạc sĩ An toàn thông tin', 'An ninh mạng', '2022-06-30', 'Đại học Bách khoa TP. Hồ Chí Minh', 'B789012', 'CB789012'),
('BC003', 'Tiến sĩ Khoa học Máy tính', 'Trí tuệ nhân tạo', '2023-12-25', 'Viện Hàn lâm Khoa học và Công nghệ Việt Nam', 'C345678', 'CN345678'),
('BC004', 'Cao đẳng Quản trị kinh doanh', 'Marketing', '2019-05-10', 'Cao đẳng Kinh tế Tây Nam', 'D901234', 'NV901234'),
('BC005', 'Trung cấp Kế toán doanh nghiệp', 'Kế toán tổng hợp', '2021-09-17', 'Trung cấp Thương mại và Du lịch Cần Thơ', 'E567890', 'KT567890'),
('BC006', 'Cử nhân Ngôn ngữ Anh', 'Tiếng Anh thương mại', '2018-02-22', 'Đại học Ngoại ngữ - Đại học Đà Nẵng', 'F123456', 'QT123456'),
('BC007', 'Thạc sĩ Quản lý giáo dục', 'Quản lý đào tạo', '2024-01-15', 'Đại học Sư phạm TP. Hồ Chí Minh', 'G789012', 'QL789012'),
('BC008', 'Cao đẳng Điều dưỡng', 'Điều dưỡng đa khoa', '2022-03-08', 'Cao đẳng Y tế Cần Thơ', 'H345678', 'DY345678'),
('BC009', 'Trung cấp Điện tử', 'Sửa chữa điện tử dân dụng', '2020-11-21', 'Trung cấp Công nghiệp và Nghề Hà Nội', 'I901234', 'KT901234'),
('BC010', 'Cử nhân Luật', 'Luật thương mại', '2023-08-10', 'Đại học Luật TP. Hồ Chí Minh', 'J567890', 'PH567890'); 
/* dữ liệu bảng chức vụ*/
INSERT INTO ChucVu (TenChucVu, SoChucVu, KinhNghiemLamViec, ThoiGianBDCongTac, ThoiGianKTCongTac, DangCongTac)
VALUES
('Giám đốc', 1, 'Trên 15 năm kinh nghiệm quản lý doanh nghiệp', '2010-01-01', 'NULL', 'Đang công tác'),
('Phó giám đốc', 2, 'Trên 10 năm kinh nghiệm quản lý cấp phòng', '2015-03-01', 'NULL', 'Đang công tác'),
('Trưởng phòng', 5, 'Trên 5 năm kinh nghiệm quản lý bộ phận', '2018-07-01', 'NULL', 'Đã ngưng công tác'),
('Chuyên viên cao cấp', 8, 'Trên 3 năm kinh nghiệm chuyên môn', '2020-11-01', 'NULL', 'Đang công tác'),
('Chuyên viên', 10, 'Trên 1 năm kinh nghiệm chuyên môn', '2022-05-01', 'NULL', 'Đang công tác'),
('Kỹ thuật viên', 15, 'Có kinh nghiệm kỹ thuật', '2023-09-01', 'NULL', 'Đang công tác'),
('Nhân viên văn phòng', 20, 'Có kỹ năng văn phòng', '2024-01-01', 'NULL', 'Đang công tác'),
('Công nhân kỹ thuật', 12, 'Có tay nghề kỹ thuật', '2019-02-01', 'NULL', 'Đang tạm nghỉ'),
('Bảo vệ', 15, 'Có sức khỏe tốt', '2021-06-01', 'NULL', 'Đang công tác'),
('Lễ tân', 10, 'Có ngoại hình ưa nhìn', '2020-10-01', 'NULL', 'Đang công tác'),
('Kế toán', 8, 'Có trình độ kế toán', '2018-04-01', 'NULL', 'Đang công tác'),
('Nhân viên bán hàng', 12, 'Có kỹ năng bán hàng', '2022-08-01', 'NULL', 'Đang tạm nghỉ');
/*dữ liệu bảng học vị*/
INSERT INTO HocVi (TenHocVi, SoLuongHocVi, ThoiDiemNhanChungNhan, TenCoQuanCap, DiaDiem)
VALUES
  ('Cử nhân Công nghệ Thông tin', 1, '2015-06-20', 'Đại học Quốc gia Hà Nội', 'Hà Nội'),
  ('Thạc sĩ Quản trị Kinh doanh', 1, '2018-09-15', 'Đại học Kinh tế Quốc dân', 'Hà Nội'),
  ('Cử nhân Luật', 1, '2020-12-30', 'Đại học Luật Hà Nội', 'Hà Nội'),
  ('Kỹ sư Điện tử Viễn thông', 1, '2022-03-25', 'Đại học Bách khoa Hà Nội', 'Hà Nội'),
  ('Cử nhân Ngôn ngữ Anh', 1, '2016-07-10', 'Đại học Ngoại thương', 'Hà Nội'),
  ('Thạc sĩ Khoa học Máy tính', 1, '2019-11-01', 'Đại học Khoa học và Công nghệ Hà Nội', 'Hà Nội'),
  ('Cử nhân Tài chính - Ngân hàng', 1, '2021-02-20', 'Đại học Kinh tế TP. Hồ Chí Minh', 'TP. Hồ Chí Minh'),
  ('Kỹ sư Cơ khí', 1, '2023-05-31', 'Đại học Bách khoa TP. Hồ Chí Minh', 'TP. Hồ Chí Minh'),
  ('Cử nhân Báo chí', 1, '2017-08-15', 'Đại học Khoa học Xã hội và Nhân văn TP. Hồ Chí Minh', 'TP. Hồ Chí Minh'),
  ('Thạc sĩ Quản trị Công', 1, '2020-12-24', 'Đại học Quốc gia TP. Hồ Chí Minh', 'TP. Hồ Chí Minh');
/*du lieu phu trach*/
INSERT INTO PhuTrach (CongViecPhuTrach, MieuTaChiTiet, SoLuongCVPhuTrach)
VALUES ('Quản lý nhân sự', 'Tuyển dụng, đào tạo, đánh giá cán bộ', 5),
       ('Quản lý tài chính', 'Lập kế hoạch thu chi, quản lý ngân sách', 3),
       ('Phát triển sản phẩm', 'Nghiên cứu thị trường, thiết kế sản phẩm, marketing', 7),
       ('Quản lý dự án', 'Lập kế hoạch dự án, theo dõi tiến độ, kiểm soát chất lượng', 4),
       ('Công nghệ thông tin', 'Bảo trì hệ thống, hỗ trợ người dùng, phát triển phần mềm', 6),
       ('Hành chính văn phòng', 'Soạn thảo văn bản, quản lý tài liệu, tổ chức hội họp', 4),
       ('Chăm sóc khách hàng ', ' chăm sóc khách hàng, giải quyết khiếu nại', 2),
       ('Tiếp thị', 'Xây dựng chiến lược marketing, thực hiện các chiến dịch marketing', 3),
       ('Bán hàng', 'Tìm kiếm khách hàng, tư vấn sản phẩm, chốt hợp đồng', 3),
       ('Kiểm soát chất lượng', 'Thực hiện kiểm tra chất lượng sản phẩm, dịch vụ', 2);
/*du lieu bang cong tac*/
INSERT INTO CongTac (MaCT, NamBatDauCongTac, NamBatDauCTCUSC, NamKTCTCUSC)
VALUES ('NV000001', 2015, 2018, NULL),
       ('NV000002', 2017, 2019, NULL),
       ('NV000003', 2020, 2022, NULL),
       ('NV000004', 2018, 2021, 2023),  
       ('NV000005', 2022, 2023, 2023),
       ('NV000006', 2019, 2020, NULL),
       ('NV000007', 2021, 2023, NULL),
       ('NV000008', 2016, 2018, 2020),
       ('NV000009', 2023, 2023, NULL),
       ('NV000010', 2020, 2022, NULL);
/*du lieu bang bo mon*/
INSERT INTO DonVi (MaDV, TenBMHienTai, TenBMTungCongTac)
VALUES ('BM000001', 'Khoa học máy tính', 'Khoa học máy tính'),
       ('BM000002', 'Công nghệ phần mềm', 'Công nghệ phần mềm'),
       ('BM000003', 'Hệ thống thông tin', 'Hệ thống thông tin'),
       ('BM000004', 'Mạng máy tính', 'Mạng máy tính'),
       ('BM000005', 'An ninh mạng', 'An ninh mạng'),
       ('BM000006', 'Trí tuệ nhân tạo', 'Trí tuệ nhân tạo'),
       ('BM000007', 'Kỹ thuật phần mềm', 'Kỹ thuật phần mềm'),
       ('BM000008', 'Truyền thông đa phương tiện', 'Truyền thông đa phương tiện'),
       ('BM000009', 'Quản trị mạng', 'Quản trị mạng'),
       ('BM000010', 'Kinh doanh điện tử', 'Kinh doanh điện tử'),
       ('BM000011', 'Khoa học dữ liệu', 'Khoa học dữ liệu'),
       ('BM000012', 'Điện toán đám mây', 'Điện toán đám mây'),
       ('BM000013', 'Bảo mật thông tin di động', 'Bảo mật thông tin di động');
/*du lieu can bo*/
INSERT INTO CanBo (MaCB, HoTenCB, GioiTinh, Email, Sdt, TenHocVi, TenChucVu, CongViecPhuTrach, MaCT, MaDV, MaBang, MaTapHuan)
VALUES
('CB001', 'Nguyễn Văn A', 'Nam', 'nv_a@gmail.com', '0123456789', 'Cử nhân Công nghệ Thông tin', 'Chuyên viên cao cấp', 'Công nghệ thông tin', 'NV000001', 'BM000001', 'BC001', 'MH001');
/*('CB002', 'Trần Thị B', 'Nữ', 'tran_b@yahoo.com', '0987654321', 'Cử nhân', 'Trợ giảng', 'Quản trị kinh doanh', 'CT001', 'BM002', 'BC002', 'TH002'),
('CB003', 'Lê Văn C', 'Nam', 'le_c@hotmail.com', '0345678901', 'Tiến sĩ', 'Phó khoa', 'Kỹ thuật cơ khí', 'CT002', 'BM003', 'BC003', 'TH003');*/
INSERT INTO tkb(TuanHoc) VALUES ('8');
INSERT INTO theodoimhsapbatdau(MaTheoDoiMH,NgayBatDau) VALUES('afwafagj','2024-03-25');