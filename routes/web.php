<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LDAPConnection;

Route::post('ldap', [
    LDAPConnection::class,
    'index'
]);

Route::get('error_alert', function () {
    return view('error_alert');
});

Route::get('/', function () {
    return view('home');
});
