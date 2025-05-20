<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\CanBo\CanBoController;
use App\Http\Controllers\CanBo\BangCapCanBoController;
use App\Http\Controllers\CanBo\HocViController;
use App\Http\Controllers\CanBo\ChucVuController;
use App\Http\Controllers\CanBo\PhuTrachController;
use App\Http\Controllers\CanBo\TapHuanController;
use App\Http\Controllers\CanBo\DonViController;
use App\Http\Controllers\CanBo\GiaoVienController;
use App\Http\Controllers\DaoTao\ChuongTrinhDaoTaoController;
use App\Http\Controllers\DaoTao\MonHocController;
use App\Http\Controllers\DaoTao\KhoaDaoTaoController;
use App\Http\Controllers\TuyenSinh\TuyenSinhController;
use App\Http\Controllers\Facilities\DanhSachPhongController;
use App\Http\Controllers\Facilities\LopHocController;
use App\Http\Controllers\Facilities\PhongHocController;
use App\Http\Middleware\RoleMiddleware;

// Trang chủ, giới thiệu, login, logout, captcha: ai cũng truy cập được
Route::get('/', [PagesController::class, 'about'])->name('about');
Route::get('/home', [PagesController::class, 'index'])->name('home');
Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');
Route::post('ldap', [LDAPConnection::class, 'index'])->name('ldap');

// Các route ministry: chỉ cho admin và staff
Route::middleware([RoleMiddleware::class . ':admin,staff'])->group(function () {
    Route::get('/ministry', [PagesController::class, 'ministry'])->name('ministry');
    Route::get('/ministry/schedules', [PagesController::class, 'schedules'])->name('schedules');
    Route::post('/ministry/schedules/save', [PagesController::class, 'saveSchedule'])->name('saveSchedule');
    Route::get('/ministry/schedules/schedule/{TenTKB}', [PagesController::class, 'schedule'])->name('schedule');
    Route::delete('/ministry/schedules/schedule/{TenTKB}', [PagesController::class, 'deleteSchedule'])->name('deleteSchedule');
    Route::get('/ministry/schedules/export-excel/{TenTKB}', [PagesController::class, 'exportExcel'])->name('exportExcel');
    Route::get('/ministry/monitorClassroom', [PagesController::class, 'monitorClassroom'])->name('monitorClassroom');
    Route::get('/ministry/monitorSubject', [PagesController::class, 'monitorSubject'])->name('monitorSubject');
    Route::get('/ministry/rollCall', [PagesController::class, 'rollCall'])->name('rollCall');

    Route::prefix('student')->group(function () {
        Route::get('/list', [SinhVienController::class, 'index'])->name('student.list');
        Route::get('/create', [SinhVienController::class, 'create'])->name('student.create');
        Route::post('/store', [SinhVienController::class, 'store'])->name('student.store');
        Route::post('/import', [SinhVienController::class, 'import'])->name('student.import');

        Route::get('/show/{maSV}', [SinhVienController::class, 'show'])->name('student.show');
        Route::get('/edit/{maSV}', [SinhVienController::class, 'edit'])->name('student.edit');
        Route::post('/update/{maSV}', [SinhVienController::class, 'update'])->name('student.update');
        Route::get('/edit/all/{maSV}', [SinhVienController::class, 'edit'])->name('student.edit_all');
        Route::post('/update/all/{maSV}', [SinhVienController::class, 'update'])->name('student.updat_all');
        Route::delete('/destroy/{maSV}', [SinhVienController::class, 'destroy'])->name('student.destroy');

        // Các route đặc biệt của sinh viên (không chuẩn REST nhưng vẫn nên nhóm tại đây)
        Route::get('/{maSV}/hoso', [SinhVienController::class, 'showHoSo'])->name('student.hoso');
        Route::get('/{maSV}/tinhtrang', [SinhVienController::class, 'showTinhTrang'])->name('student.tinhtrang');
    });
    Route::prefix('staff')->group(function () {
        Route::get('/list', [CanBoController::class, 'index'])->name('staff.index');
        Route::get('/create', [CanBoController::class, 'create'])->name('staff.create');
        Route::post('/store', [CanBoController::class, 'store'])->name('staff.store');
        Route::get('/{maCB}', [CanBoController::class, 'show'])->name('staff.show');
        Route::get('/edit/{maCB}', [CanBoController::class, 'edit'])->name('staff.edit');
        Route::post('/update/{maCB}', [CanBoController::class, 'update'])->name('staff.update');
        Route::delete('/destroy/{maCB}', [CanBoController::class, 'destroy'])->name('staff.destroy');
        Route::post('/import', [CanBoController::class, 'import'])->name('staff.import');
    });
    Route::prefix('giaovien')->group(function () {
        Route::get('/list', [GiaoVienController::class, 'index'])->name('giaovien.index');
        Route::get('/create', [GiaoVienController::class, 'create'])->name('giaovien.create');
        Route::post('/store', [GiaoVienController::class, 'store'])->name('giaovien.store');
        Route::get('/{maGV}', [GiaoVienController::class, 'show'])->name('giaovien.show');
        Route::get('/edit/{maGV}', [GiaoVienController::class, 'edit'])->name('giaovien.edit');
        Route::post('/update/{maGV}', [GiaoVienController::class, 'update'])->name('giaovien.update');
        Route::delete('/destroy/{maGV}', [GiaoVienController::class, 'destroy'])->name('giaovien.destroy');
        Route::post('/import', [GiaoVienController::class, 'import'])->name('giaovien.import');
    });
    Route::prefix('bangcapcanbo')->group(function () {
        Route::get('/list', [BangCapCanBoController::class, 'index'])->name('bangcapcanbo.index');
        Route::get('/create', [BangCapCanBoController::class, 'create'])->name('bangcapcanbo.create');
        Route::post('/store', [BangCapCanBoController::class, 'store'])->name('bangcapcanbo.store');
        Route::get('/{maBang}', [BangCapCanBoController::class, 'show'])->name('bangcapcanbo.show');
        Route::get('/edit/{maBang}', [BangCapCanBoController::class, 'edit'])->name('bangcapcanbo.edit');
        Route::post('/update/{maBang}', [BangCapCanBoController::class, 'update'])->name('bangcapcanbo.update');
        Route::delete('/destroy/{maBang}', [BangCapCanBoController::class, 'destroy'])->name('bangcapcanbo.destroy');
    });
    Route::prefix('phonghoc')->group(function () {
        Route::get('/list', [PhongHocController::class, 'index'])->name('phonghoc.index');
        Route::get('/create', [PhongHocController::class, 'create'])->name('phonghoc.create');
        Route::post('/store', [PhongHocController::class, 'store'])->name('phonghoc.store');
        Route::get('/{tenPhong}', [PhongHocController::class, 'show'])->name('phonghoc.show');
        Route::get('/edit/{tenPhong}', [PhongHocController::class, 'edit'])->name('phonghoc.edit');
        Route::post('/update/{tenPhong}', [PhongHocController::class, 'update'])->name('phonghoc.update');
        Route::delete('/destroy/{tenPhong}', [PhongHocController::class, 'destroy'])->name('phonghoc.destroy');
    });
    Route::prefix('lophoc')->group(function () {
        Route::get('/list', [LopHocController::class, 'index'])->name('lophoc.index');
        Route::get('/create', [LopHocController::class, 'create'])->name('lophoc.create');
        Route::post('/store', [LopHocController::class, 'store'])->name('lophoc.store');
        Route::get('/{maLop}', [LopHocController::class, 'show'])->name('lophoc.show');
        Route::get('/edit/{maLop}', [LopHocController::class, 'edit'])->name('lophoc.edit');
        Route::post('/update/{maLop}', [LopHocController::class, 'update'])->name('lophoc.update');
        Route::delete('/destroy/{maLop}', [LopHocController::class, 'destroy'])->name('lophoc.destroy');
    });
    Route::prefix('danhsachphong')->group(function () {
        Route::get('/list', [DanhSachPhongController::class, 'index'])->name('danhsachphong.index');
        Route::get('/create', [DanhSachPhongController::class, 'create'])->name('danhsachphong.create');
        Route::post('/store', [DanhSachPhongController::class, 'store'])->name('danhsachphong.store');
        Route::get('/{maLop}', [DanhSachPhongController::class, 'show'])->name('danhsachphong.show');
        Route::get('/edit/{maLop}', [DanhSachPhongController::class, 'edit'])->name('danhsachphong.edit');
        Route::post('/update/{maLop}', [DanhSachPhongController::class, 'update'])->name('danhsachphong.update');
        Route::delete('/destroy/{maLop}', [DanhSachPhongController::class, 'destroy'])->name('danhsachphong.destroy');
    });
    Route::prefix('chucvu')->group(function () {
        Route::get('/list', [ChucVuController::class, 'index'])->name('chucvu.index');
        Route::get('/create', [ChucVuController::class, 'create'])->name('chucvu.create');
        Route::post('/store', [ChucVuController::class, 'store'])->name('chucvu.store');
        Route::get('/edit/{tenChucVu}', [ChucVuController::class, 'edit'])->name('chucvu.edit');
        Route::post('/update/{tenChucVu}', [ChucVuController::class, 'update'])->name('chucvu.update');
        Route::delete('/destroy/{tenChucVu}', [ChucVuController::class, 'destroy'])->name('chucvu.destroy');
    });
    Route::prefix('donvi')->group(function () {
        Route::get('/list', [DonViController::class, 'index'])->name('donvi.index');
        Route::get('/create', [DonViController::class, 'create'])->name('donvi.create');
        Route::post('/store', [DonViController::class, 'store'])->name('donvi.store');
        Route::get('/edit/{maDV}', [DonViController::class, 'edit'])->name('donvi.edit');
        Route::post('/update/{maDV}', [DonViController::class, 'update'])->name('donvi.update');
        Route::delete('/destroy/{maDV}', [DonViController::class, 'destroy'])->name('donvi.destroy');
    });
    Route::prefix('hocvi')->group(function () {
        Route::get('/list', [HocViController::class, 'index'])->name('hocvi.index');
        Route::get('/create', [HocViController::class, 'create'])->name('hocvi.create');
        Route::post('/store', [HocViController::class, 'store'])->name('hocvi.store');
        Route::get('/edit/{maHV}', [HocViController::class, 'edit'])->name('hocvi.edit');
        Route::post('/update/{maHV}', [HocViController::class, 'update'])->name('hocvi.update');
        Route::delete('/destroy/{maHV}', [HocViController::class, 'destroy'])->name('hocvi.destroy');
    });
    Route::prefix('phutrach')->group(function () {
        Route::get('/list', [PhuTrachController::class, 'index'])->name('phutrach.index');
        Route::get('/create', [PhuTrachController::class, 'create'])->name('phutrach.create');
        Route::post('/store', [PhuTrachController::class, 'store'])->name('phutrach.store');
        Route::get('/edit/{congViecPhuTrach}', [PhuTrachController::class, 'edit'])->name('phutrach.edit');
        Route::post('/update/{congViecPhuTrach}', [PhuTrachController::class, 'update'])->name('phutrach.update');
        Route::delete('/destroy/{congViecPhuTrach}', [PhuTrachController::class, 'destroy'])->name('phutrach.destroy');
    });
    Route::prefix('taphuan')->group(function () {
        Route::get('/list', [TapHuanController::class, 'index'])->name('taphuan.index');
        Route::get('/create', [TapHuanController::class, 'create'])->name('taphuan.create');
        Route::post('/store', [TapHuanController::class, 'store'])->name('taphuan.store');
        Route::get('/edit/{maTapHuan}', [TapHuanController::class, 'edit'])->name('taphuan.edit');
        Route::post('/update/{maTapHuan}', [TapHuanController::class, 'update'])->name('taphuan.update');
        Route::delete('/destroy/{maTapHuan}', [TapHuanController::class, 'destroy'])->name('taphuan.destroy');
    });
    Route::prefix('chuongtrinh')->group(function () {
        Route::get('/list', [ChuongTrinhDaoTaoController::class, 'index'])->name('chuongtrinh.index');
        Route::get('/create', [ChuongTrinhDaoTaoController::class, 'create'])->name('chuongtrinh.create');
        Route::post('/store', [ChuongTrinhDaoTaoController::class, 'store'])->name('chuongtrinh.store');
        Route::get('/edit/{maChuongTrinh}', [ChuongTrinhDaoTaoController::class, 'edit'])->name('chuongtrinh.edit');
        Route::post('/update/{maChuongTrinh}', [ChuongTrinhDaoTaoController::class, 'update'])->name('chuongtrinh.update');
        Route::delete('/destroy/{maChuongTrinh}', [ChuongTrinhDaoTaoController::class, 'destroy'])->name('chuongtrinh.destroy');
    });
    Route::prefix('monhoc')->group(function () {
        Route::get('/list', [MonHocController::class, 'index'])->name('monhoc.index');
        Route::get('/create', [MonHocController::class, 'create'])->name('monhoc.create');
        Route::post('/store', [MonHocController::class, 'store'])->name('monhoc.store');
        Route::get('/edit/{tenMH}', [MonHocController::class, 'edit'])->name('monhoc.edit');
        Route::post('/update/{tenMH}', [MonHocController::class, 'update'])->name('monhoc.update');
        Route::delete('/destroy/{tenMH}', [MonHocController::class, 'destroy'])->name('monhoc.destroy');
    });
    Route::prefix('tuyensinh')->group(function () {
        Route::get('/', [TuyenSinhController::class, 'index'])->name('tuyensinh.index');
        Route::post('/store', [TuyenSinhController::class, 'store'])->name('tuyensinh.store');
        Route::delete('/{maTS}', [TuyenSinhController::class, 'destroy'])->name('tuyensinh.destroy');
        Route::get('/dot/{maTS}', [TuyenSinhController::class, 'danhSachHoSo'])->name('tuyensinh.danhsach_hoso');
        Route::post('/hoso', [TuyenSinhController::class, 'taoHoSo'])->name('tuyensinh.tao_hoso');
        Route::post('/hoso/{maHoSo}', [TuyenSinhController::class, 'capNhatTrangThai'])->name('tuyensinh.capnhat_trangthai');
    });
    Route::prefix('khoadaotao')->group(function () {
        Route::get('/list', [KhoaDaoTaoController::class, 'index'])->name('khoadaotao.index');
        Route::get('/create', [KhoaDaoTaoController::class, 'create'])->name('khoadaotao.create');
        Route::post('/store', [KhoaDaoTaoController::class, 'store'])->name('khoadaotao.store');
        Route::get('/edit/{tenKhoaDaoTao}', [KhoaDaoTaoController::class, 'edit'])->name('khoadaotao.edit');
        Route::post('/update/{tenKhoaDaoTao}', [KhoaDaoTaoController::class, 'update'])->name('khoadaotao.update');
        Route::delete('/destroy/{tenKhoaDaoTao}', [KhoaDaoTaoController::class, 'destroy'])->name('khoadaotao.destroy');
    });
});

// Các route lấy dữ liệu chương trình, lớp, học kỳ: cho admin, staff, teacher
Route::middleware([RoleMiddleware::class . ':admin,staff,teacher'])->group(function () {
    Route::get('/getChuongTrinh/{TenKhoaDaoTao}', [PagesController::class, 'getChuongTrinh']);
    Route::get('/getLop/{MaChuongTrinh}', [PagesController::class, 'getLop']);
    Route::get('/getHK/{MaChuongTrinh}', [PagesController::class, 'getHK']);
    Route::post('/saveTimeSlot/{TenTKB}', [PagesController::class, 'saveTimeSlot'])->name('saveTimeSlot');
    Route::post('/saveholiday/{TenTKB}', [PagesController::class, 'saveholiday'])->name('saveholiday');
    Route::post('/saveSelfStudy/{TenTKB}', [PagesController::class, 'saveSelfStudy'])->name('saveSelfStudy');
    Route::post('/EditTKB/{TenTKB}', [PagesController::class, 'EditTKB'])->name('EditTKB');


});

