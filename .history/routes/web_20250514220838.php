<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\CanBoController;
use App\Http\Controllers\Staff\BangCapCanBoController;
use App\Http\Middleware\RoleMiddleware;

// Trang chủ, giới thiệu, login, logout, captcha: ai cũng truy cập được
Route::get('/', [PagesController::class, 'about'])->name('about');
Route::get('/home', [PagesController::class, 'index'])->name('home');
Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');
Route::post('ldap', [LDAPConnection::class, 'index'])->name('ldap');
Route::get('error_alert', function () {
    return view('error_alert');
});

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

    Route::get('/student/list', [SinhVienController::class, 'index'])->name('student.list');
    Route::get('/student/show/{maSV}', [SinhVienController::class, 'show'])->name('student.show');
    Route::get('/student/edit/{maSV}', [SinhVienController::class, 'edit'])->name('student.edit');
    Route::post('/student/update/{maSV}', [SinhVienController::class, 'update'])->name('student.update');
    Route::delete('/student/destroy/{maSV}', [SinhVienController::class, 'destroy'])->name('student.destroy');
    Route::get('/student/create', [SinhVienController::class, 'create'])->name('student.create');
    Route::post('/student/import', [SinhVienController::class, 'import'])->name('student.import');
    Route::post('/student/store', [SinhVienController::class, 'store'])->name('student.store');
    Route::get('sinhvien/{maSV}/hoso', [SinhVienController::class, 'showHoSo'])->name('sinhvien.hoso');
    Route::get('sinhvien/{maSV}/tinhtrang', [SinhVienController::class, 'showTinhTrang'])->name('sinhvien.tinhtrang');

    Route::prefix('staff')->group(function () {
        Route::get('/list', [CanBoController::class, 'index'])->name('staff.index');
        Route::get('/create', [CanBoController::class, 'create'])->name('staff.create');
        Route::post('/store', [CanBoController::class, 'store'])->name('staff.store');
        Route::get('/{maCB}', [CanBoController::class, 'show'])->name('staff.show');
        Route::get('/edit/{maCB}', [CanBoController::class, 'edit'])->name('staff.edit');
        Route::post('/update/{maCB}', [CanBoController::class, 'update'])->name('staff.update');
        Route::delete('/destroy/{maCB}', [CanBoController::class, 'destroy'])->name('staff.destroy');
        // Route import nếu cần
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
        Route::get('/list', [\App\Http\Controllers\PhongHocController::class, 'index'])->name('phonghoc.index');
        Route::get('/create', [\App\Http\Controllers\PhongHocController::class, 'create'])->name('phonghoc.create');
        Route::post('/store', [\App\Http\Controllers\PhongHocController::class, 'store'])->name('phonghoc.store');
        Route::get('/{tenPhong}', [\App\Http\Controllers\PhongHocController::class, 'show'])->name('phonghoc.show');
        Route::get('/edit/{tenPhong}', [\App\Http\Controllers\PhongHocController::class, 'edit'])->name('phonghoc.edit');
        Route::post('/update/{tenPhong}', [\App\Http\Controllers\PhongHocController::class, 'update'])->name('phonghoc.update');
        Route::delete('/destroy/{tenPhong}', [\App\Http\Controllers\PhongHocController::class, 'destroy'])->name('phonghoc.destroy');
    });
    Route::prefix('lophoc')->group(function () {
        Route::get('/list', [\App\Http\Controllers\LopHocController::class, 'index'])->name('lophoc.index');
        Route::get('/create', [\App\Http\Controllers\LopHocController::class, 'create'])->name('lophoc.create');
        Route::post('/store', [\App\Http\Controllers\LopHocController::class, 'store'])->name('lophoc.store');
        Route::get('/{maLop}', [\App\Http\Controllers\LopHocController::class, 'show'])->name('lophoc.show');
        Route::get('/edit/{maLop}', [\App\Http\Controllers\LopHocController::class, 'edit'])->name('lophoc.edit');
        Route::post('/update/{maLop}', [\App\Http\Controllers\LopHocController::class, 'update'])->name('lophoc.update');
        Route::delete('/destroy/{maLop}', [\App\Http\Controllers\LopHocController::class, 'destroy'])->name('lophoc.destroy');
    });
    Route::prefix('danhsachphong')->group(function () {
        Route::get('/list', [\App\Http\Controllers\DanhSachPhongController::class, 'index'])->name('danhsachphong.index');
        Route::get('/create', [\App\Http\Controllers\DanhSachPhongController::class, 'create'])->name('danhsachphong.create');
        Route::post('/store', [\App\Http\Controllers\DanhSachPhongController::class, 'store'])->name('danhsachphong.store');
        Route::get('/{maLop}', [\App\Http\Controllers\DanhSachPhongController::class, 'show'])->name('danhsachphong.show');
        Route::get('/edit/{maLop}', [\App\Http\Controllers\DanhSachPhongController::class, 'edit'])->name('danhsachphong.edit');
        Route::post('/update/{maLop}', [\App\Http\Controllers\DanhSachPhongController::class, 'update'])->name('danhsachphong.update');
        Route::delete('/destroy/{maLop}', [\App\Http\Controllers\DanhSachPhongController::class, 'destroy'])->name('danhsachphong.destroy');
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

