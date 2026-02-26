<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard tetap dipisah
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::prefix('pembina')->middleware('auth:pembina')->group(function () {
    Route::get('dashboard', function () {
        return view('pembina.dashboard');
    })->name('pembina.dashboard');
});

Route::prefix('siswa')->middleware('auth:siswa')->group(function () {
    Route::get('dashboard', function () {
        return "Dashboard Siswa";
    })->name('siswa.dashboard');
});