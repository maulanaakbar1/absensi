<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\PembinaController; 
use App\Http\Controllers\Admin\OrganisasiController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('login', [LoginController::class, 'showLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// AREA ADMIN
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // organisasi
    Route::resource('organisasi', OrganisasiController::class)->except(['create', 'show', 'edit']);

    // pembina
    Route::resource('pembina', PembinaController::class)->except(['create', 'show', 'edit']);
});

// AREA PEMBINA
Route::prefix('pembina')->middleware('auth:pembina')->group(function () {
    Route::get('dashboard', function () {
        return view('pembina.dashboard');
    })->name('pembina.dashboard');
});

// AREA SISWA
Route::prefix('siswa')->middleware('auth:siswa')->group(function () {
    Route::get('dashboard', function () {
        return view('siswa.dashboard'); 
    })->name('siswa.dashboard');
});