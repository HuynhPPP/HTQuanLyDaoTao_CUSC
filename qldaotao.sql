CREATE DATABASE QLDAOTAO;

CREATE TABLE `CanBo` (
  `MaCB` VARCHAR(255) PRIMARY KEY,
  `HoTenCB` VARCHAR(255),
  `GioiTinh` BOOLEAN,
  `HocVi` VARCHAR(255),
  `ChucVu` VARCHAR(255),
  `PhuTrach` VARCHAR(255),
  `NamBatDauDay` YEAR(4),
  `NamBatDauDayCUSC` YEAR(4),
  `ChungChiSuPham` VARCHAR(255),
  `ChungChiAptech` VARCHAR(255),
  `ChungChiKhac` VARCHAR(255),
  `CacKhoaDaoTaoDaThamGia` VARCHAR(255),
  `BoMon` VARCHAR(255),
  `Email` VARCHAR(255),
  `Sdt` INT
);

CREATE TABLE `SinhVien` (
  `MaSV` VARCHAR(255) PRIMARY KEY,
  `HoTen` VARCHAR(255),
  `NgaySinh` DATE,
  `GioiTinh` BOOLEAN,
  `DiaChi` VARCHAR(255),
  `Email` VARCHAR(255),
  `Sdt` INT
);

CREATE TABLE `LopHoc` (
  `MaLop` VARCHAR(255) PRIMARY KEY,
  `TenLop` VARCHAR(255),
  `MaCB` VARCHAR(255),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`)
);

CREATE TABLE `Khoa` (
  `MaKhoa` VARCHAR(255) PRIMARY KEY,
  `TenKhoa` VARCHAR(255),
  `NgayThanhLap` DATE
);


CREATE TABLE `MonHoc` (
  `MaMH` VARCHAR(255) PRIMARY KEY,
  `TenMH` VARCHAR(255),
  `MaKhoa` VARCHAR(255),
  `GioGoc` VARCHAR(255),
  `GioTrienKhai` VARCHAR(255),
  `SoTietLT`  SMALLINT,
  `SoTietOnl` SMALLINT,
  `ThiLT` TINYINT,
  `ThiTH` TINYINT,
  `Project` TINYINT,
  FOREIGN KEY (`MaKhoa`) REFERENCES `Khoa`(`MaKhoa`)
);

CREATE TABLE `PhanMon` (
  `MaMH` VARCHAR(255),
  `MaCB` VARCHAR(255),
  `HocKy` INT,
  `NamHoc` INT,
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`)
);

CREATE TABLE `TKB` (
  `MaMH` VARCHAR(255),
  `MaLop` VARCHAR(255),
  `NgayHoc` DATE,
  `TuanHoc` TINYINT,
  `GioHoc` VARCHAR(255),
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`)
);

CREATE TABLE `PhongHoc` (
  `MaPhong` VARCHAR(255) PRIMARY KEY,
  `TenPhong` VARCHAR(255),
  `SucChua` INT
);

CREATE TABLE `LichThi` (
  `MaMH` VARCHAR(255),
  `MaLop` VARCHAR(255),
  `NgayThi` DATE,
  `MaPhong` VARCHAR(255),
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`),
  FOREIGN KEY (`MaLop`) REFERENCES `LopHoc`(`MaLop`),
  FOREIGN KEY (`MaPhong`) REFERENCES `PhongHoc`(`MaPhong`)
);

CREATE TABLE `DoAn` (
  `MaDA` VARCHAR(255) PRIMARY KEY,
  `TenDA` VARCHAR(255),
  `MoTa` VARCHAR(255),
  `MaCb` VARCHAR(255),
  `MaSVThucHien` VARCHAR(255),
  `NgayBaoCao` DATE,
  FOREIGN KEY (`MaCb`) REFERENCES `CanBo`(`MaCB`),
  FOREIGN KEY (`MaSVThucHien`) REFERENCES `SinhVien`(`MaSV`)
);

CREATE TABLE `Diem` (
  `MaSV` VARCHAR(255),
  `MaMH` VARCHAR(255),
  `DiemSo` FLOAT,
  `LanThi` INT,
  FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`),
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`)
);

CREATE TABLE `DanhSachMonTienQuyet` (
  `MaMH` VARCHAR(255),
  `MaMHTienQuyet` VARCHAR(255),
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`),
  FOREIGN KEY (`MaMHTienQuyet`) REFERENCES `MonHoc`(`MaMH`)
);

CREATE TABLE `BaoCaoKetQuaHocTap` (
  `MaSV` VARCHAR(255),
  `HocKy` INT,
  `NamHoc` INT,
  `DiemTrungBinh` FLOAT,
  FOREIGN KEY (`MaSV`) REFERENCES `SinhVien`(`MaSV`)
);
CREATE TABLE `TheoDoiPhongHoc`(
  `MaPhong` VARCHAR(255) , 
  `MaMH` VARCHAR(255) ,
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`),
  FOREIGN KEY (`MaPhong`) REFERENCES `PhongHoc`(`MaPhong`),
  PRIMARY KEY(MaPhong,MaMH)
);

CREATE TABLE `TheoDoiMHSapBatDau`(
  MaMH VARCHAR(255) ,
  MaPhong VARCHAR(255) ,
  MaCB VARCHAR(255) ,
  NgayBatDau DATE,
  TrangThai BOOLEAN,
  FOREIGN KEY (`MaMH`) REFERENCES `MonHoc`(`MaMH`),
  FOREIGN KEY (`MaPhong`) REFERENCES `PhongHoc`(`MaPhong`),
  FOREIGN KEY (`MaCB`) REFERENCES `CanBo`(`MaCB`),
  PRIMARY KEY(MaMH,MaCB,MaPhong)
);
