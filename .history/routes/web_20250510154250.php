<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\LDAPTest;


Route::get('/home', [PagesController::class, 'index'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');


Route::get('/ministry', [PagesController::class, 'ministry'])->name('ministry');
Route::get('/ministry/schedules', [PagesController::class, 'schedules'])->name('schedules');
Route::post('/ministry/schedules/save', [PagesController::class, 'saveSchedule'])->name('saveSchedule');

Route::get('/ministry/schedules/schedule/{TenTKB}', [PagesController::class, 'schedule'])->name('schedule');
Route::delete('/ministry/schedules/schedule/{TenTKB}', [PagesController::class, 'deleteSchedule'])->name('deleteSchedule');
Route::get('/ministry/schedules/export-excel/{TenTKB}', [PagesController::class, 'exportExcel'])->name('exportExcel');

Route::get('/ministry/monitorClassroom', [PagesController::class, 'monitorClassroom'])->name('monitorClassroom');
Route::get('/ministry/monitorSubject', [PagesController::class, 'monitorSubject'])->name('monitorSubject');
Route::get('/ministry/rollCall', [PagesController::class, 'rollCall'])->name('rollCall');


Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');

Route::get('/getChuongTrinh/{TenKhoaDaoTao}', [PagesController::class, 'getChuongTrinh']);
Route::get('/getLop/{MaChuongTrinh}', [PagesController::class, 'getLop']);
Route::get('/getHK/{MaChuongTrinh}', [PagesController::class, 'getHK']);

Route::post('/saveTimeSlot/{TenTKB}', [PagesController::class, 'saveTimeSlot'])->name('saveTimeSlot');
Route::post('/saveholiday/{TenTKB}', [PagesController::class, 'saveholiday'])->name('saveholiday');
Route::post('/saveSelfStudy/{TenTKB}',[PagesController::class,'saveSelfStudy'])->name('saveSelfStudy');
Route::post('/EditTKB/{TenTKB}',[PagesController::class,'EditTKB'])->name('EditTKB');
Route::post('ldap', [
    LDAPConnection::class,
    'index'
])->name('ldap');

Route::get('error_alert', function () {
    return view('error_alert');
});

Route::get('/ldap-test', [LDAPTest::class, 'testConnection']);