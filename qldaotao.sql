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
 	`ThoiGianBatDauCV` VARCHAR(50),
 	`ThoiGianKTCV` VARCHAR(50)
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
	`MieuTaChiTiet` VARCHAR(255)
);



CREATE TABLE `DonVi`(
	`MaDV` VARCHAR(12) PRIMARY KEY,
	`TenDVHienTai` VARCHAR(255),
	`TenDVTungCongTac` VARCHAR(255)
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

CREATE TABLE `KhoaDaoTao`(
	`TenKhoaDaoTao` VARCHAR(20) PRIMARY KEY,
	`ThoiGianDaoTao` VARCHAR(10)
);
CREATE TABLE `ChuongTrinh` (
  `MaChuongTrinh` VARCHAR(12) PRIMARY KEY,
  `TenChuongTrinh` VARCHAR(30),
  `PhienBan` VARCHAR(12),
  `NgayTrienKhaiPB` DATE,
  `TenKhoaDaoTao` VARCHAR(20),
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
	`MonSau` VARCHAR(255),
	`LiDo` VARCHAR(255),
	`NgayDuyetDon` DATE,
	`NguoiDuyetDon` DATE
);


CREATE TABLE `LopHoc` (
  `MaLop` VARCHAR(12) PRIMARY KEY,
  `TenLop` VARCHAR(100),
  `NgayBatDau` DATE,
  `MaChuongTrinh` VARCHAR(12),
  FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`)
);


CREATE TABLE `DanhSachSV`(
	`MaLop` VARCHAR(12),
	`MaSV` VARCHAR(12),
	FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`),
	PRIMARY KEY (`MaLop`,`MaSV`)
);

CREATE TABLE `HinhThucDanhGia`(
	`MaHTDanhGia` VARCHAR(12) PRIMARY KEY,
	`HinhThuc` VARCHAR(255)
);
 CREATE TABLE `HocKi`(
 	`MaHK` VARCHAR(50) PRIMARY KEY ,
 	`TenHK` VARCHAR(30),
 	`TongGioGoc` INT,
 	`TongGioTrienKhai` INT,
 	`MaChuongTrinh` VARCHAR(12),
   FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`)
 );


CREATE TABLE `MonHoc` (
  `TenMH` VARCHAR(255) PRIMARY KEY,
  `MaMH` VARCHAR(12),
  `GioGoc` INT,
  `GioTrienKhai` INT,
  `TietLT`  boolean,
  `TietTH` boolean,
  `TietLTvaTH` boolean,
  `MaHTDanhGia` VARCHAR(12),
  FOREIGN KEY (`MaHTDanhGia`) REFERENCES `HinhThucDanhGia`(`MaHTDanhGia`)
);


CREATE TABLE `TrangThaiMH`(
	`MaTTMH` VARCHAR(12) PRIMARY KEY,
	`TrangThai` BOOLEAN
);


CREATE TABLE `PhongHoc` (
  `TenPhong` VARCHAR(20) PRIMARY KEY ,
  `LoaiPhong` VARCHAR(255),
  `SucChua` INT
);


CREATE TABLE `KhungGio`(
	`TenKhungGio` VARCHAR(100) PRIMARY KEY ,
	`ThoiGian` INT
);


CREATE TABLE `DanhSachPhong`(
	`MaLop` VARCHAR(12) ,
	`TenPhong` VARCHAR(20),
	FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
	FOREIGN KEY (`TenPhong`) REFERENCES `PhongHoc`(`TenPhong`),
	PRIMARY KEY (`MaLop`,`TenPhong`)
);


CREATE TABLE `NgayNghi`(
	`MaNgayNghi` INT auto_increment PRIMARY KEY,
	`TenNgayNghi` VARCHAR(50),
	`NgayBDNghi` DATE,
	`NgayKT` DATE
);

CREATE TABLE `TKB` (
  `TenTKB` VARCHAR(255) PRIMARY KEY,
  `MaLop` VARCHAR(12),
  `MaHK` VARCHAR(50),
  `NgayHoc` DATE,
  `NgayPhienBan` VARCHAR(12),
  FOREIGN KEY (`MaHK`) REFERENCES `HocKi`(`MaHK`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`)
);



CREATE TABLE `NgayTuHoc`(
	`MaNgayTuHoc` INT AUTO_INCREMENT primary KEY,
	`TenNgayTuHoc` VARCHAR(50),
	`NgayBDTuHoc` DATE,
	`NgayKTTuHoc` DATE,
	`TenTKB` VARCHAR (255),
	FOREIGN KEY  (`TenTKB`) REFERENCES `TKB`(`TenTKB`)
);


CREATE TABLE `DanhSachNgayNghi`(
	`TenTKB` VARCHAR(255),
	`MaNgayNghi` int,
	FOREIGN KEY (`TenTKB`) REFERENCES `TKB`(`TenTKB`),
	FOREIGN KEY (`MaNgayNghi`) REFERENCES `NgayNghi`(`MaNgayNghi`),
	PRIMARY KEY (`MaNgayNghi`)
);

CREATE TABLE `DanhSachMH`(
	`MaHK` VARCHAR(50),
	`TenKhungGio` VARCHAR(100),
	`SttMH` INT,
	`TenMH` VARCHAR(255),
	FOREIGN KEY (`TenKhungGio`) REFERENCES `KhungGio`(`TenKhungGio`),
	FOREIGN KEY (`MaHK`) REFERENCES `HocKi`(`MaHK`),
	FOREIGN KEY (`TenMH`) REFERENCES `MonHoc`(`TenMH`)
);


/*CREATE TABLE `TheoDoiMHSapBatDau`(
	`MaTheoDoiMH` VARCHAR(12) PRIMARY KEY,
  TenMH VARCHAR(30) ,
  `MaLop` VARCHAR(12),
  TenPhong VARCHAR(20) ,
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
  `TenMH` VARCHAR(255),
  `GhiChu` VARCHAR(255),
  `MaCB` VARCHAR(12),
  `TenTKB` VARCHAR(30),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`),
  FOREIGN KEY (`TenTKB`) REFERENCES `TKB`(`TenTKB`)

);*/
INSERT INTO khoadaotao(TenKhoaDaoTao,ThoiGianDaoTao) VALUES ('Dài hạn', '2 năm'), ('Ngắn hạn','1 Học Kỳ');

INSERT INTO chuongtrinh(MaChuongTrinh,TenChuongTrinh, PhienBan,TenKhoaDaoTao)
VALUES
('OV-7023','Lap trinh vien quoc te','1.0','Dài hạn'),
('OV9001','lap trinh vien arena','2.0','Dài hạn'),
('AT7096','My thuat da phuong tien','3.0','Ngắn hạn');

INSERT INTO LopHoc (MaLop,TenLop,MaChuongTrinh)
VALUES
('CP2396G11','Lập trình viên','OV-7023'),
('CP2396M02','Quản trị mạng','OV-7023'),
('CP2296H07','Truyền thông đa phương tiện','OV-7023');

INSERT INTO hocki(MaHK,TenHK,TongGioGoc, TongGioTrienKhai, MaChuongTrinh)
 VALUES
 ('OV-7023-HK I','HỌC KỲ I','168','172','OV-7023'),
 ('OV-7023-HK II','HỌC KỲ II','218','200','OV-7023'),
 ('OV-7023-HK III','HỌC KỲ III','170','168','OV-7023'),
 ('OV-7023-HK IV','HỌC KỲ IV','194','208','OV-7023'),
 ('OV-7023-HK V','HỌC KỲ V','168','172','OV-7023');

INSERT INTO MonHoc(TenMH, GioGoc, GioTrienKhai, TietLT, TietTH)
VALUES
('Computer fundamentals' , '0', '8','0','1'),
('Logic Building and Elementary Programing' , '40', '42','0','1'),
('HTML5,CSS and Javascript' , '40', '44','0','1'),
('AngularJS' , '16', '16','0','1'),
('eProject-Website Development' , '2', '8','0','1'),
('Database Design and Development(core)' , '24', '16','0','1'),
('Data Management with SQL server' , '40', '40','0','1'),
('Markup Language & JSON ','16','16','0','1') ,
('Java Programming - I', '36', '40','0','1'),
('Java Programming -II', '40', '42','0','1'),
('Information Systems Analysis(Core)', '24', '12','1','0'),
('Project-Java Application Development', '2', '12','1','0'),
('Application Programming with C#', '36', '38','0','1'),
('PHP Web Development with Laravel Framework', '40', '40','0','1');

INSERT INTO phonghoc(TenPhong, LoaiPhong)
VALUES
('Class1','Class'),
('Class2','Class'),
('Lab1','Lab'),
('Lab2','Lab');

INSERT INTO khunggio(TenKhungGio, ThoiGian)
VALUES
('7:00-9:00','2'),
('9:00-11:00','2'),
('13:00-15:00','2'),
('15:00-17:00','2'),
('17:30-19:30','2'),
('19:30-21:30','2');

INSERT INTO danhsachphong(MaLop, TenPhong)
VALUES
('CP2296H07', 'Class1'),
('CP2296H07', 'Lab1'),
('CP2396M02','Class2'),
('CP2396M02', 'Lab2');

INSERT INTO `ngaynghi` (`TenNgayNghi`, `NgayBDNghi`, `NgayKT`) VALUES
	('Nghỉ Tết DL', '2024-01-01', '2024-01-01'),
	('Nghỉ Tết Nguyên Đán 2024', '2024-02-05', '2024-02-16'),
	('Nghỉ - Giỗ Tổ Hùng Vương', '2024-04-18', '2024-04-18'),
	('Nghỉ 30/04-01/05', '2024-04-29', '2024-05-01'),
	('Nghỉ hè', '2024-07-15', '2024-07-26');

INSERT INTO tkb(TenTKB,MaLop,MaHK,NgayHoc, NgayPhienBan)
VALUES
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)','CP2396M02','OV-7023-HK II','2023-12-26','1.0');

INSERT INTO ngaytuhoc(TenNgayTuHoc, NgayBDTuHoc, NgayKTTuHoc,TenTKB)
VALUES
('self study', '2023-12-28', '2023-12-28','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('self study', '2024-01-26', '2024-01-26','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),

('self study', '2024-02-29', '2024-02-29','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('self study', '2024-03-06', '2024-03-06','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('self study', '2024-03-13', '2024-03-13','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('self study', '2024-03-20', '2024-03-20','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('self study', '2024-03-27', '2024-03-27','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('self study', '2024-04-03', '2024-04-03','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-04-24', '2024-04-24','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-04-26', '2024-04-26','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-03', '2024-05-03','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-06', '2024-05-08','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-10', '2024-05-10','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-13', '2024-05-15','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-17', '2024-05-17','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-20', '2024-05-22','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-24', '2024-05-24','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-27', '2024-05-29','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-05-31', '2024-05-31','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Team works', '2024-06-03', '2024-06-06','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)'),
('Báo cáo đồ án', '2024-06-07', '2024-06-07','THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)');

INSERT INTO danhsachngaynghi(TenTKB, MaNgayNghi)
VALUES
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', '1'),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', '2'),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', '3'),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', '4'),
('THỜI KHÓA BIỂU LỚP CP2396M02 - Học Kỳ II (OV-7023)', '5');

INSERT INTO danhsachmh(MaHK, SttMH, TenMH)
VALUES
('OV-7023-HK I','1','Computer fundamentals' ),
('OV-7023-HK I','2','Logic Building and Elementary Programing' ),
('OV-7023-HK I','3','HTML5,CSS and Javascript' ),
('OV-7023-HK I','4','AngularJS'),
('OV-7023-HK I','5', 'eProject-Website Development' ),
('OV-7023-HK I','6','Database Design and Development(core)' ),
('OV-7023-HK I','7','Data Management with SQL server'),
('OV-7023-HK II','8','Markup Language & JSON '),
('OV-7023-HK II','9','Java Programming - I'),
('OV-7023-HK II','10','Java Programming -II'),
('OV-7023-HK II','11','Information Systems Analysis(Core)'),
('OV-7023-HK II','12','Project-Java Application Development'),
('OV-7023-HK II','13','Application Programming with C#'),
('OV-7023-HK II','14','PHP Web Development with Laravel Framework');
