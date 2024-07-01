<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;

Route::get('/', [PagesController::class, 'index'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');

Route::prefix('ministry')->name('ministry.')->group(function () {
    Route::get('/', [PagesController::class, 'ministry'])->name('index');
    Route::get('/schedules', [PagesController::class, 'schedules'])->name('schedules');
    Route::post('/schedules/save', [PagesController::class, 'saveSchedule'])->name('saveSchedule');
    
    Route::prefix('schedules/schedule/{TenTKB}')->group(function () {
        Route::get('/', [PagesController::class, 'schedule'])->name('schedule');
        Route::delete('/', [PagesController::class, 'deleteSchedule'])->name('deleteSchedule');
        Route::get('/export-excel', [PagesController::class, 'exportExcel'])->name('exportExcel');
    });

    Route::get('/monitorClassroom', [PagesController::class, 'monitorClassroom'])->name('monitorClassroom');
    Route::get('/monitorSubject', [PagesController::class, 'monitorSubject'])->name('monitorSubject');
    Route::get('/rollCall', [PagesController::class, 'rollCall'])->name('rollCall');
});

Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');

Route::prefix('get')->group(function () {
    Route::get('/ChuongTrinh/{TenKhoaDaoTao}', [PagesController::class, 'getChuongTrinh']);
    Route::get('/Lop/{MaChuongTrinh}', [PagesController::class, 'getLop']);
    Route::get('/HK/{MaChuongTrinh}', [PagesController::class, 'getHK']);
});

Route::prefix('save')->group(function () {
    Route::post('/TimeSlot/{TenTKB}', [PagesController::class, 'saveTimeSlot'])->name('saveTimeSlot');
    Route::post('/holiday/{TenTKB}', [PagesController::class, 'saveholiday'])->name('saveholiday');
    Route::post('/SelfStudy/{TenTKB}', [PagesController::class, 'saveSelfStudy'])->name('saveSelfStudy');
});

Route::post('/EditTKB/{TenTKB}', [PagesController::class, 'EditTKB'])->name('EditTKB');
Route::post('ldap', [LDAPConnection::class, 'index'])->name('ldap');

Route::get('error_alert', function () {
    return view('error_alert');
});