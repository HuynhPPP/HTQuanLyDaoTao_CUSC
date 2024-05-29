CREATE DATABASE QLDAOTAO;

USE qldaotao;

CREATE TABLE `BangCapCanBo`(
	`MaBang` VARCHAR(12) PRIMARY KEY,
	`TenBang` VARCHAR(50),
	`ChuyenMon` VARCHAR(30),
	`ThoiGianCap` DATE,
	`DonViCap` VARCHAR(30),
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
	`SoChucVu` INT, /*so luong chuc vu dam nhan*/
 	`KinhNghiemLamViec` VARCHAR(255),
 	`ThoiGianBDCongTac` VARCHAR(50),/*Thoi gian bat dau cong tac*/
 	`ThoiGianKTCongTac` VARCHAR(50),/*Thoi gian ket thuc cong tac*/
 	`DangCongTac` BOOLEAN /*hien tai con cong tac o vi tri nay hay da ngung*/
);
 
CREATE TABLE `HocVi` (
 	`TenHocVi` VARCHAR(50) PRIMARY KEY,
	`SoLuongHocVi` INT, /* co can thiet field nay khong nếu có bằng thạc sĩ ngành mạng, thì cán bộ có thể sở hữu thêm bằng thạc sĩ của ngành khác */
 	`ThoiDiemNhanChungNhan` DATE,
 	`TenCoQuanCap` VARCHAR(100),
 	`DiaDiem` VARCHAR(30)
);

CREATE TABLE `PhuTrach`(
	`LinhVucPhuTrach` VARCHAR(255) PRIMARY KEY,
	`MieuTaChiTiet` VARCHAR(255), /*giai thich cong viec can bo phu trach*/
	`SoLuongCVPhuTrach` INT
);

CREATE TABLE `CongTac`(
	`MaCT` VARCHAR(12) PRIMARY KEY,
	`NamBatDauCongTac` YEAR,
	`NamBatDauCTCUSC` YEAR,
	`NamKTCTCUSC` YEAR /*co can luu khong? Doi voi can bo da nghi viec Nam nao can bo ket thuc cong tac tai CUSC*/
);

CREATE TABLE `BoMon`(
	`MaBM` VARCHAR(12) PRIMARY KEY,
	`TenBMHienTai` VARCHAR(255),
	`TenBMTungCongTac` VARCHAR(255)/*co the chuyen sang ban khac  hay k can bo co the cong tac tai nhieu bo mon khac nhau*/ 
);
 
CREATE TABLE `CanBo` (
  `MaCB` VARCHAR(12) PRIMARY KEY,
  `HoTenCB` VARCHAR(30),
  `GioiTinh` BOOLEAN,
  `Email` VARCHAR(40),
  `Sdt` INT,
  `TenHocVi` VARCHAR(50),
  `TenChucVu` VARCHAR(30),
  `LinhVucPhuTrach` VARCHAR(255),
  `MaCT` VARCHAR(12),
  `MaBM` VARCHAR(12),
  `MaBang` VARCHAR(12),
  `MaTapHuan` VARCHAR(12),
  FOREIGN KEY (`MaBM`) REFERENCES `BoMon`(`MaBM`) ,
  FOREIGN KEY (`MaCT`) REFERENCES `CongTac`(`MaCT`),
  FOREIGN KEY (`LinhVucPhuTrach`) REFERENCES `PhuTrach`(`LinhVucPhuTrach`),
  FOREIGN KEY (`TenHocVi`) REFERENCES `HocVi`(`TenHocVi`),
  FOREIGN KEY (`TenChucVu`) REFERENCES `ChucVu`(`TenChucVu`),
  FOREIGN KEY (`MaBang`) REFERENCES `BangCapCanBo`(`MaBang`),
  FOREIGN KEY (`MaTapHuan`) REFERENCES `TapHuan`(`MaTapHuan`)
);
 
CREATE TABLE `LoaiDaoTao`(
	`TenKhoaDaoTao` VARCHAR(20) PRIMARY KEY,
	`ThoiGianDaoTao` VARCHAR(10),
	`ThoiGianBDDaoTao` DATE,
	`ThoiGianKTDaoTao` DATE /*dao tao ngan han co thoi gian bd kt cu the hay khong */
);

CREATE TABLE `SinhVien` (
  `MaSV` VARCHAR(12) PRIMARY KEY,
  `MaEnroll` VARCHAR(6),
  `MaEnrollOld` VARCHAR(6),
  `ChuongTrinhHoc` VARCHAR(80),
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
	`MaTTHocTap` VARCHAR(12),
	`DangHoc` BOOLEAN,
	`BaoLuu` BOOLEAN,
	`TotNghiep` BOOLEAN,
	`MaSV` VARCHAR(12),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `ChiTietTinhTrang` (
	`MaGhiChu` VARCHAR(12) PRIMARY KEY,
	`LiDoNghiHoc` VARCHAR(255),
	`ThoiGianNghiHoc` DATE,
	`MaSV` VARCHAR(12),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `PhieuChuyenLop`(
	`MaPhieuChuyenLop` INT AUTO_INCREMENT PRIMARY KEY,
	`LiDoChuyenLop` VARCHAR(255),
	`MaSV` VARCHAR(12),
	`ThoiGianBDChuyenLop` DATE,
	`ThoiGianKTChuyenLop` DATE,
	`MonBDChuyen` VARCHAR(255),
	`MonSauChuyen` VARCHAR(255), /*chuyen tu mon nay sang mon khac lop khac*/
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `PhieuBaoLuu`(
	`MaPhieuBL` INT AUTO_INCREMENT PRIMARY KEY,
	`MaSV` VARCHAR(12),
	`ThoiGianBDBaoLuu` DATE,
	`ThoiGianKTBaoLuu` DATE,/**/
	`MonBDBaoLuu` VARCHAR(255),
	`MonKTBaoLuu` VARCHAR(255),/*sinh vien xin bao luu tu mon nao den mon nao*/
	`LiDoBaoLuu` VARCHAR(255),
	FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `ChuongTrinh` (
  `MaChuongTrinh` VARCHAR(12) PRIMARY KEY,
  `TenChuongTrinh` VARCHAR(30),
  `PhienBan` VARCHAR(12),
  `NgayTrienKhaiPB` DATE
);

CREATE TABLE `LopHoc` (
  `MaLop` VARCHAR(12) PRIMARY KEY,
  `TenLop` VARCHAR(20),
  `NgayBatDau` DATE,
  `SiSoBanDau` INT,
  `SiSoHienTai` INT,  
  `MaChuongTrinh` VARCHAR(12),
  `MaGhiChu` VARCHAR(12),
  
  FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`),
  FOREIGN KEY (`MaGhiChu`) REFERENCES `ChiTietTinhTrang`(`MaGhiChu`)
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
	`MaTTMH` VARCHAR(12),
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
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
  FOREIGN KEY (`TenMH`) REFERENCES `MonHoc`(`TenMH`),
  FOREIGN KEY (`TenPhong`) REFERENCES `PhongHoc`(`TenPhong`)
);

CREATE TABLE `PhanCongGiangDay` (
  `TenMH` VARCHAR(255),
  `MaCB` VARCHAR(12),
  `MaLop` VARCHAR(12),
  `HocKy` VARCHAR(50),
  `NgayBatDau` DATE,
  `GioHoc` VARCHAR(30),
  `MaTTMH` VARCHAR(12),
  `MaTheoDoiMH` VARCHAR(12),
  FOREIGN KEY (`MaTheoDoiMH`) REFERENCES `TheoDoiMHSapBatDau`(`MaTheoDoiMH`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
  FOREIGN KEY (`TenMH`) REFERENCES `MonHoc`(`TenMH`),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`)
);

CREATE TABLE `TKB` (
	`TenTKB` VARCHAR(30) PRIMARY KEY,
  `MaLop` VARCHAR(12),
  `MaChuongTrinh` VARCHAR(12),
  `TuanHoc` INT,
  `TenPhong` VARCHAR(20),
  `BuoiLyThuyet` INT,
  `BuoiThucHanh` INT,
  `MaTheoDoiMH` VARCHAR(12),
  FOREIGN KEY (`MaTheoDoiMH`) REFERENCES `TheoDoiMHSapBatDau`(`MaTheoDoiMH`),
  FOREIGN KEY (`TenPhong`) REFERENCES `PhongHoc`(`TenPhong`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`)
);



CREATE TABLE `LichThi` (
  `MaLichThi` VARCHAR(12) PRIMARY KEY,
  `NgayThi` DATE,
  `TenPhong` VARCHAR(20),
  `MaChuongTrinh` VARCHAR(12),
  `MaLop` VARCHAR(12),
  `MaHTDanhGia` VARCHAR(12),
  `LanThi` INT,
  `GhiChu` VARCHAR(255),
  `MaTheoDoiMH` VARCHAR(12),
  FOREIGN KEY (`MaChuongTrinh`) REFERENCES `ChuongTrinh`(`MaChuongTrinh`),
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

