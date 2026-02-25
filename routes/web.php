<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\PembinaAuthController;
use App\Http\Controllers\Auth\SiswaAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('dashboard', function () {
        return "Dashboard Admin";
    })->middleware('auth:admin')->name('admin.dashboard');
});

Route::prefix('pembina')->group(function () {
    Route::get('login', [PembinaAuthController::class, 'showLogin'])->name('pembina.login');
    Route::post('login', [PembinaAuthController::class, 'login'])->name('pembina.login.submit');
    Route::post('logout', [PembinaAuthController::class, 'logout'])->name('pembina.logout');

    Route::get('dashboard', function () {
        return "Dashboard Pembina";
    })->middleware('auth:pembina')->name('pembina.dashboard');
});

Route::prefix('siswa')->group(function () {
    Route::get('login', [SiswaAuthController::class, 'showLogin'])->name('siswa.login');
    Route::post('login', [SiswaAuthController::class, 'login'])->name('siswa.login.submit');
    Route::post('logout', [SiswaAuthController::class, 'logout'])->name('siswa.logout');

    Route::get('dashboard', function () {
        return "Dashboard Siswa";
    })->middleware('auth:siswa')->name('siswa.dashboard');
});