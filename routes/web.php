<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostsController;



Route::get('/', [PagesController::class, 'index'])->name('home');
Route::get('/posts', [PostsController::class, 'index']);



Route::post('ldap', [
    LDAPConnection::class,
    'index'
])->name('ldap');

Route::get('error_alert', function () {
    return view('error_alert');
});
