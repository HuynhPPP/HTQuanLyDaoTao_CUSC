<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SinhVienController;
use App\Http\Middleware\RoleMiddleware;

// Trang chủ, giới thiệu, login, logout, captcha: ai cũng truy cập được
Route::get('/', [PagesController::class, 'about'])->name('about');
Route::get('/home', [PagesController::class, 'index'])->name('home');
Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');
Route::post('ldap', [LDAPConnection::class, 'index'])->name('ldap');
Route::get('error_alert', function () {
    return view('error_alert'); });

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

