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
 	`ThoiGianBatDauCV` VARCHAR(50),/*Thoi gian bat dau chuc vu*/
 	`ThoiGianKTCV` VARCHAR(50)/*Thoi gian ket thuc chuc vu*/
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
INSERT INTO khoadaotao(TenKhoaDaoTao,ThoiGianDaoTao) VALUES ('Dài hạn', '2 năm'), ('Ngắn hạn','1 học kì');
INSERT INTO chuongtrinh(MaChuongTrinh,TenChuongTrinh,TenKhoaDaoTao) 
VALUES 
('OV-7023','Lap trinh vien quoc te','Dài hạn'),
('OV9001','lap trinh vien arena','Dài hạn'),
('AT7096','My thuat da phuong tien','Ngắn hạn');

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
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)/*them phieulido*/
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
INSERT INTO LopHoc (MaLop,MaChuongTrinh) VALUES ('CP2396G11','OV-7023'), ('CP2396M02','OV-7023'), ('CP2296H07','OV-7023');

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
 	`MaHK` VARCHAR(12) PRIMARY KEY ,
 	`TenHK` VARCHAR(30),
 	`GioGoc` INT,
 	`GioTrienKhai` INT,
 	`MaChuongTrinh` VARCHAR(12),
   FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`)
 );
 INSERT INTO hocki(MaHK,TenHK,MaChuongTrinh)
 VALUES 
 ('OV-7023-hk1','Học kì 1','OV-7023'),
 ('OV-7023-hk2','Học kì 2','OV-7023'),
 ('OV-7023-hk3','Học kì 3','OV-7023'),
 ('OV-7023-hk4','Học kì 4','OV-7023'),
 ('OV-7023-hk5','Học kì 5','OV-7023');

CREATE TABLE `MonHoc` (
  `TenMH` VARCHAR(255) PRIMARY KEY,
  `MaMH` VARCHAR(12),
  `MaHK` VARCHAR(12),
  `GioGoc` INT,
  `GioTrienKhai` INT,
  `SoTietLT`  INT,
  `SoTietTH` INT,
  `SoTietLTvaTH` INT,
  `MaHTDanhGia` VARCHAR(12),
  FOREIGN KEY (`MaHTDanhGia`) REFERENCES `HinhThucDanhGia`(`MaHTDanhGia`),
  FOREIGN KEY (`MaHK`) REFERENCES `HocKi`(`MaHK`)
);
INSERT INTO MonHoc(TenMH, MaHK, GioGoc, GioTrienKhai)
VALUES 
('Computer fundamentals' , 'OV-7023-hk1', '0', '8'),
('Logic Building and Elementary Programing' , 'OV-7023-hk1', '40', '42'),
('AngularJS' , 'OV-7023-hk2', '16', '16'),
('HTML5,CSS and Javascript' , 'OV-7023-hk3', '40', '44'),
('eProject-Website Development' , 'OV-7023-hk4', '2', '8'),
('Database Design and Development(core)' , 'OV-7023-hk5', '24', '16'),
('Data Management with SQL server' , 'OV-7023-hk2', '40', '40');

CREATE TABLE `TrangThaiMH`(
	`MaTTMH` VARCHAR(12) PRIMARY KEY,
	`TrangThai` BOOLEAN
);


CREATE TABLE `PhongHoc` (
  `TenPhong` VARCHAR(20) PRIMARY KEY ,
  `LoaiPhong` VARCHAR(255),
  `SucChua` INT
);
INSERT INTO phonghoc(TenPhong, LoaiPhong) 
VALUES 
('LT1','LT'),
('LT2','LT'),
('TH1','TH'),
('TH2','TH');

CREATE TABLE `KhungGio`(
	`TenKhungGio` VARCHAR(100) PRIMARY KEY ,
	`ThoiGian` INT
);
INSERT INTO khunggio(TenKhungGio, ThoiGian)
VALUES 
('7h-9h','2'),
('9h-11h','2'),
('13h-15h','2'),
('15h-17h','2'),
('17h30-19h30','2'),
('19h30-21h30','2'),
('13h30-16h30','3'),
('14h-17h','3'),
('18h-21h 2,4,6','3'),
('18h-21h 3,5,7','3'),
('8h-11h t7,cn','3');

CREATE TABLE `DanhSachPhong`(
	`MaLop` VARCHAR(12) ,
	`TenPhong` VARCHAR(20),
	FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
	FOREIGN KEY (`TenPhong`) REFERENCES `PhongHoc`(`TenPhong`),
	PRIMARY KEY (`MaLop`,`TenPhong`)
);

CREATE TABLE `NgayNghi`(
	`TenNgayNghi` VARCHAR(50) PRIMARY KEY,
	`NgayBDNghi` DATE,
	`NgayKT` DATE /*vi du le 30/04-01/05 thi nghi 3 ngay */
);
INSERT INTO ngaynghi(TenNgayNghi) 
VALUES
('Lễ Quốc Khánh'),
('Nghỉ 30/04-01/05'),
('Nghỉ hè'),
('self study'),
('team works');



CREATE TABLE `TKB` (
  `TenTKB` VARCHAR(255) PRIMARY KEY,
  `MaLop` VARCHAR(12),
  `MaHK` VARCHAR(12),
  `TuanHoc` INT,
  `NgayHoc` DATE,
  FOREIGN KEY (`MaHK`) REFERENCES `HocKi`(`MaHK`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`)
);

CREATE TABLE `DanhSachNgayNghi`(
	`TenNgayNghi` VARCHAR(50),
	`TenTKB` VARCHAR(12),
	FOREIGN KEY (`TenNgayNghi`) REFERENCES `NgayNghi`(`TenNgayNghi`),
	FOREIGN KEY (`TenTKB`) REFERENCES `TKB`(`TenTKB`),
	PRIMARY KEY (`TenNgayNghi`,`TenTKB`)

);

CREATE TABLE `DanhSachMH`(
	`TenKhungGio` VARCHAR(100),
	`TenMH` VARCHAR(255),
	`TenTKB` VARCHAR(255),
	FOREIGN KEY (`TenTKB`) REFERENCES `TKB`(`TenTKB`),
	FOREIGN KEY (`TenKhungGio`) REFERENCES `KhungGio`(`TenKhungGio`),
	FOREIGN KEY (`TenMH`) REFERENCES `MonHoc`(`TenMH`),
	PRIMARY KEY (`TenMH`,`TenKhungGio`,`TenTKB`)
	
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

