<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\CaptchaController;


Route::get('/', [PagesController::class, 'index'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/ministry', [PagesController::class, 'ministry'])->name('ministry');
Route::get('/ministry/schedules', [PagesController::class, 'schedules'])->name('schedules');
Route::post('/ministry/schedules/save', [PagesController::class, 'saveSchedule'])->name('saveSchedule');
Route::get('/ministry/monitorClassroom', [PagesController::class, 'monitorClassroom'])->name('monitorClassroom');
Route::get('/ministry/monitorSubject', [PagesController::class, 'monitorSubject'])->name('monitorSubject');
Route::get('/ministry/rollCall', [PagesController::class, 'rollCall'])->name('rollCall');
Route::get('/login', [PagesController::class, 'login'])->name('login');
Route::get('/logout', [LDAPConnection::class, 'logout'])->name('logout');
Route::get('/captcha', [CaptchaController::class, 'generateCaptcha'])->name('captcha');



Route::post('ldap', [
    LDAPConnection::class,
    'index'
])->name('ldap');

Route::get('error_alert', function () {
    return view('error_alert');
});
