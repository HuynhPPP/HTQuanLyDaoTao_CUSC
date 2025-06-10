<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NhanSu\SinhVienController;
use App\Http\Controllers\NhanSu\CanBoController;
use App\Http\Controllers\NhanSu\GiaoVienController;
use App\Http\Controllers\CanBo\BangCapCanBoController;
use App\Http\Controllers\CanBo\HocViController;
use App\Http\Controllers\CanBo\ChucVuController;
use App\Http\Controllers\CanBo\PhuTrachController;
use App\Http\Controllers\CanBo\TapHuanController;
use App\Http\Controllers\CanBo\DonViController;
use App\Http\Controllers\DaoTao\ChuongTrinhDaoTaoController;
use App\Http\Controllers\DaoTao\MonHocController;
use App\Http\Controllers\DaoTao\KhoaDaoTaoController;
use App\Http\Controllers\DaoTao\HocKiController;
use App\Http\Controllers\TuyenSinh\TuyenSinhController;
use App\Http\Controllers\GiaoVien\LichCoiThiController;
use App\Http\Controllers\GiaoVien\NhapDiemController;
use App\Http\Controllers\Facilities\DanhSachPhongController;
use App\Http\Controllers\Facilities\LopHocController;
use App\Http\Controllers\Facilities\PhongHocController;
use App\Http\Controllers\ToChucThi\LichThiController;
use App\Http\Controllers\ToChucThi\PhanCongThiController;
use App\Http\Controllers\ToChucThi\BangDiemController;
use App\Http\Controllers\ToChucThi\SinhVienDuThiController;
use App\Http\Middleware\RoleMiddleware;

// Trang chủ, giới thiệu, login, logout, captcha: ai cũng truy cập được
Route::get('/', [PagesController::class, 'about'])->name('about');
Route::get('/home', [PagesController::class, 'index'])->name('home');
Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');
Route::post('ldap', [LDAPConnection::class, 'index'])->name('ldap');

// Các route ministry: chỉ cho admin (quản lý dữ liệu hệ thống)
Route::middleware([RoleMiddleware::class . ':admin,staff'])->group(function () {
    Route::get('/ministry', [PagesController::class, 'ministry'])->name('ministry');
    Route::get('/ministry/schedules', [PagesController::class, 'schedules'])->name('schedules');
    Route::post('/ministry/schedules/save', [PagesController::class, 'saveSchedule'])->name('saveSchedule');
    Route::get('/ministry/schedules/schedule/{TenTKB}', [PagesController::class, 'schedule'])->name('schedule');
    Route::delete('/ministry/schedules/schedule/{TenTKB}', [PagesController::class, 'deleteSchedule'])->name('deleteSchedule');
    Route::get('/ministry/schedules/export-excel/{TenTKB}', [PagesController::class, 'exportExcel'])->name('exportExcel');
    Route::post('/saveholiday/{TenTKB}', [PagesController::class, 'saveholiday'])->name('saveholiday');
    Route::get('/ministry/monitorClassroom', [PagesController::class, 'monitorClassroom'])->name('monitorClassroom');
    Route::get('/ministry/monitorSubject', [PagesController::class, 'monitorSubject'])->name('monitorSubject');
    Route::get('/ministry/rollCall', [PagesController::class, 'rollCall'])->name('rollCall');
    Route::get('/get-subjects', [PagesController::class, 'getSubjects'])->name('getSubjects');
    Route::post('/ministry/schedules/update-subjects/{TenTKB}', [PagesController::class, 'updateScheduleSubjects'])->name('updateScheduleSubjects');
    Route::post('/saveSelfStudy/{TenTKB}', [PagesController::class, 'saveSelfStudy'])->name('saveSelfStudy');

    Route::prefix('student')->group(function () {
        Route::get('/list', [SinhVienController::class, 'index'])->name('student.list');
        Route::get('/create', [SinhVienController::class, 'create'])->name('student.create');
        Route::post('/store', [SinhVienController::class, 'store'])->name('student.store');
        Route::post('/import', [SinhVienController::class, 'import'])->name('student.import');

        Route::get('/show/{maSV}', [SinhVienController::class, 'show'])->name('student.show');
        Route::get('/edit/{maSV}', [SinhVienController::class, 'edit'])->name('student.edit');
        Route::post('/update/{maSV}', [SinhVienController::class, 'update'])->name('student.update');
        Route::get('/edit/all/{maSV}', [SinhVienController::class, 'edit_all'])->name('student.edit_all');
        Route::post('/update/all/{maSV}', [SinhVienController::class, 'update_all'])->name('student.update_all');
        Route::delete('/destroy/{maSV}', [SinhVienController::class, 'destroy'])->name('student.destroy');

        // Các route đặc biệt của sinh viên (không chuẩn REST nhưng vẫn nên nhóm tại đây)
        Route::get('/{maSV}/hoso', [SinhVienController::class, 'showHoSo'])->name('student.hoso');
        Route::get('/{maSV}/tinhtrang', [SinhVienController::class, 'showTinhTrang'])->name('student.tinhtrang');


        Route::get('/dong-bo-tai-khoan-ldap', [SinhVienController::class, 'dongBoTaiKhoanLDAP'])
            ->name('dongbo.taikhoan.ldap');

        Route::get('/kiem-tra-dong-bo-ldap', [SinhVienController::class, 'kiemTraDongBoLDAP'])
            ->name('ldap.kiem-tra-dong-bo');

        Route::get('/ldap/danh-sach-tai-khoan', [SinhVienController::class, 'xuatDanhSachTaiKhoanMoi'])
            ->name('ldap.account.list');

        Route::post('/ldap/gui-thong-tin/{id}', [SinhVienController::class, 'guiThongTinTaiKhoan'])
            ->name('ldap.account.send');
    });
    Route::prefix('staff')->group(function () {
        Route::get('/list', [CanBoController::class, 'index'])->name('staff.index');
        Route::get('/create', [CanBoController::class, 'create'])->name('staff.create');
        Route::post('/store', [CanBoController::class, 'store'])->name('staff.store');

        // Route đặc biệt phải đặt trước route {maGV}
        Route::get('/dong-bo-tai-khoan-canbo-ldap', [CanBoController::class, 'dongBoTaiKhoanLDAP'])
            ->name('staff.dongbo.taikhoan.ldap');

        Route::get('/kiem-tra-dong-bo-canbo-ldap', [CanBoController::class, 'kiemTraDongBoLDAP'])
            ->name('staff.ldap.kiem-tra-dong-bo');

        Route::get('/ldap/danh-sach-tai-khoan-canbo', [CanBoController::class, 'xuatDanhSachTaiKhoanMoi'])
            ->name('staff.ldap.account.list');

        Route::post('/ldap/gui-thong-tin-gv/{id}', [CanBoController::class, 'guiThongTinTaiKhoan'])
            ->name('staff.ldap.account.send');

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
        Route::post('/import', [GiaoVienController::class, 'import'])->name('giaovien.import');

        // Route đặc biệt phải đặt trước route {maGV}
        Route::get('/dong-bo-tai-khoan-gv-ldap', [GiaoVienController::class, 'dongBoTaiKhoanGVLDAP'])
            ->name('giaovien.dongbo.taikhoan.ldap');

        Route::get('/kiem-tra-dong-bo-gv-ldap', [GiaoVienController::class, 'kiemTraDongBoGVLDAP'])
            ->name('giaovien.ldap.kiem-tra-dong-bo');

        Route::get('/ldap/danh-sach-tai-khoan-gv', [GiaoVienController::class, 'xuatDanhSachTaiKhoanMoi'])
            ->name('giaovien.ldap.account.list');

        Route::post('/ldap/gui-thong-tin-gv/{id}', [GiaoVienController::class, 'guiThongTinTaiKhoan'])
            ->name('giaovien.ldap.account.send');

        // Cuối cùng mới đến các route động
        Route::get('/{maGV}', [GiaoVienController::class, 'show'])->name('giaovien.show');
        Route::get('/edit/{maGV}', [GiaoVienController::class, 'edit'])->name('giaovien.edit');
        Route::post('/update/{maGV}', [GiaoVienController::class, 'update'])->name('giaovien.update');
        Route::delete('/destroy/{maGV}', [GiaoVienController::class, 'destroy'])->name('giaovien.destroy');
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
        Route::put('/update/{tenPhong}', [PhongHocController::class, 'update'])->name('phonghoc.update');
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
        Route::get('/{maLop}/add-student', [LopHocController::class, 'addStudentForm'])->name('lophoc.add-student');
        Route::post('/{maLop}/add-student', [LopHocController::class, 'addStudent'])->name('lophoc.store-student');
        Route::delete('/lophoc/{malop}/sinhvien/{masv}', [LopHocController::class, 'removeSinhVien'])->name('lophoc.remove-student');
        Route::get('/{maLop}/add-teacher', [LopHocController::class, 'addTeacherForm'])->name('lophoc.add-teacher');
        Route::post('/{maLop}/add-teacher', [LopHocController::class, 'addTeacher'])->name('lophoc.store-teacher');
        Route::delete('/lophoc/{malop}/giaovien/{magv}', [LopHocController::class, 'removeTeacher'])->name('lophoc.remove-teacher');
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
        Route::get('/monhoc/{maChuongTrinh}', [ChuongTrinhDaoTaoController::class, 'showMonHoc'])->name('chuongtrinh.monhoc');
        Route::post('/monhoc/{maChuongTrinh}/store', [ChuongTrinhDaoTaoController::class, 'storeMonHoc'])->name('chuongtrinh.monhoc.store');
        Route::delete('/monhoc/{maChuongTrinh}/{tenMH}', [ChuongTrinhDaoTaoController::class, 'destroyMonHoc'])->name('chuongtrinh.monhoc.destroy');
    });
    Route::prefix('monhoc')->group(function () {
        Route::get('/list', [MonHocController::class, 'index'])->name('monhoc.index');
        Route::get('/create', [MonHocController::class, 'create'])->name('monhoc.create');
        Route::post('/store', [MonHocController::class, 'store'])->name('monhoc.store');
        Route::get('/edit/{tenMH}', [MonHocController::class, 'edit'])->name('monhoc.edit');
        Route::post('/update/{tenMH}', [MonHocController::class, 'update'])->name('monhoc.update');
        Route::delete('/destroy/{tenMH}', [MonHocController::class, 'destroy'])->name('monhoc.destroy');
        Route::get('/mon-hoc/phan-cong-giang-vien/{maMH}', [MonHocController::class, 'addTeacherForm'])
            ->name('monhoc.add-teacher');

        Route::post('/mon-hoc/phan-cong-giang-vien/{maMH}', [MonHocController::class, 'storeTeacher'])->name('monhoc.store-teacher');
        // Xoá giảng viên khỏi môn học
        Route::delete('/mon-hoc/{MaMH}/remove-teacher/{maGV}', [MonHocController::class, 'removeTeacher'])
            ->name('monhoc.remove-teacher');

        // Chỉnh sửa phân công giảng viên
        Route::get('/mon-hoc/{MaMH}/edit-teacher/{maGV}', [MonHocController::class, 'editTeacherAssignment'])
            ->name('monhoc.edit-teacher');
        Route::put('/mon-hoc/{MaMH}/update-teacher/{maGV}', [MonHocController::class, 'updateTeacherAssignment'])
            ->name('monhoc.update-teacher');
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
    Route::prefix('hocki')->group(function () {
        Route::get('/list', [HocKiController::class, 'index'])->name('hocki.index');
        Route::get('/create', [HocKiController::class, 'create'])->name('hocki.create');
        Route::post('/store', [HocKiController::class, 'store'])->name('hocki.store');
        Route::get('/edit/{maHK}', [HocKiController::class, 'edit'])->name('hocki.edit');
        Route::post('/update/{maHK}', [HocKiController::class, 'update'])->name('hocki.update');
        Route::delete('/destroy/{maHK}', [HocKiController::class, 'destroy'])->name('hocki.destroy');
    });
});

// Các route lấy dữ liệu chương trình, lớp, học kỳ, điểm thi: cho admin, staff
Route::middleware([RoleMiddleware::class . ':admin,staff'])->group(function () {

    Route::get('/getChuongTrinh/{TenKhoaDaoTao}', [PagesController::class, 'getChuongTrinh']);
    Route::get('/getLop/{MaChuongTrinh}', [PagesController::class, 'getLop']);
    Route::get('/getHK/{MaChuongTrinh}', [PagesController::class, 'getHK']);
    Route::post('/saveTimeSlot/{TenTKB}', [PagesController::class, 'saveTimeSlot'])->name('saveTimeSlot');
    Route::post('/EditTKB/{TenTKB}', [PagesController::class, 'EditTKB'])->name('EditTKB');

    Route::prefix('tochucthi')->group(function () {
        Route::get('/lichthi', [LichThiController::class, 'index'])->name('lichthi.index');
        Route::get('/lichthi/create', [LichThiController::class, 'create'])->name('lichthi.create');
        Route::post('/lichthi/store', [LichThiController::class, 'store'])->name('lichthi.store');
        Route::get('/lichthi/{maLichThi}', [LichThiController::class, 'show'])->name('lichthi.show');
        Route::get('/lichthi/{maLichThi}/edit', [LichThiController::class, 'edit'])->name('lichthi.edit');
        Route::post('/lichthi/{maLichThi}', [LichThiController::class, 'update'])->name('lichthi.update');
        Route::delete('/lichthi/{maLichThi}', [LichThiController::class, 'destroy'])->name('lichthi.destroy');


        // Danh sách sinh viên dự thi theo lịch thi
        Route::get('/lich-thi/{maLichThi}/danh-sach-du-thi', [SinhVienDuThiController::class, 'danhSachSinhVienDuThi'])
            ->name('sinhvien.duthi.danh-sach');

        // Lưu danh sách sinh viên dự thi
        Route::post('/lich-thi/luu-danh-sach-du-thi', [SinhVienDuThiController::class, 'luuDanhSachDuThi'])
            ->name('sinhvien.duthi.luu');

        // Xuất Excel
        Route::get('/lich-thi/{maLichThi}/xuat-excel', [SinhVienDuThiController::class, 'xuatExcel'])
            ->name('sinhvien.duthi.xuat-excel');

        Route::get('/phancong', [PhanCongThiController::class, 'index'])->name('phancong.index');
        Route::get('/phancong/{maLichThi}', [PhanCongThiController::class, 'create'])->name('phancong.create');
        Route::post('/phancong/{maLichThi}', [PhanCongThiController::class, 'store'])->name('phancong.store');
        Route::delete('/phancong/{maLichThi}/{maPhanCong}', [PhanCongThiController::class, 'destroy'])->name('phancong.destroy');

        Route::get('/bangdiem/chon', [BangDiemController::class, 'chonLopVaMon'])->name('bangdiem.chon');
        Route::get('/bangdiem/xem', [BangDiemController::class, 'xemBangDiem'])->name('bangdiem.xem');

        Route::get('/bangdiem/{maLop}/{tenMH}', [BangDiemController::class, 'show'])->name('bangdiem.show');
        Route::post('/bangdiem/import', [BangDiemController::class, 'import'])->name('bangdiem.import');
        Route::get('/bangdiem/export/{maLop}/{tenMH}', [BangDiemController::class, 'export'])->name('bangdiem.export');
    });
});

Route::middleware([RoleMiddleware::class . ':teacher'])->group(function () {
    Route::get('/giao-vien/ho-so', [GiaoVienController::class, 'profile'])->name('giaovien.profile');
    Route::post('/giao-vien/ho-so/cap-nhat', [GiaoVienController::class, 'updateProfile'])
        ->name('giaovien.profile.update');
    Route::post('/giao-vien/doi-mat-khau', [GiaoVienController::class, 'changePassword'])
        ->name('giaovien.change.password');

    // Lịch coi thi
    Route::prefix('giao-vien/lich-thi')->group(function () {
        Route::get('/', [LichCoiThiController::class, 'index'])
            ->name('giaovien.lichthi.index');
        Route::get('/{maLichThi}', [LichCoiThiController::class, 'chiTietLichThi'])
            ->name('giaovien.lichthi.chi-tiet');
    });

    // Nhập điểm thi
    Route::prefix('giao-vien/nhap-diem-thi')->group(function () {
        Route::get('/', [NhapDiemController::class, 'danhSachLopDay'])
            ->name('giaovien.nhapdiemthi.danh-sach-mon');
        Route::post('/luu-diem/{MaLopHoc}/{MaMH}', [NhapDiemController::class, 'luuDiem'])
            ->name('giaovien.nhapdiemthi.luu-diem');
        Route::get('/{MaLop}/{MaMH}', [NhapDiemController::class, 'nhapDiem'])
            ->name('giaovien.nhapdiemthi.nhap-diem');
    });
});

Route::middleware([RoleMiddleware::class . ':student'])->group(function () {
    Route::get('/sinh-vien/ho-so', [SinhVienController::class, 'profile'])->name('student.profile');
    Route::post('/sinh-vien/ho-so/cap-nhat', [SinhVienController::class, 'updateProfile'])
        ->name('student.profile.update');
    Route::post('/sinh-vien/doi-mat-khau', [SinhVienController::class, 'changePassword'])
        ->name('student.change.password');

    Route::prefix('calendar')->group(function () {
        Route::get('/student/list', [SinhVienController::class, 'StudentCalendar'])->name('student.calendar.list');
        // routes/web.php
        Route::get('/get-calendar-events', [CalendarController::class, 'getEvents']);
    });
});

