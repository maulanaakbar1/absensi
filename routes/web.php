<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PembinaController;
use App\Http\Controllers\Admin\EkstrakurikulerController;
use App\Http\Controllers\Pembina\DashboardController as PembinaDashboard;
use App\Http\Controllers\Pembina\ProfileController as PembinaProfile;
use App\Http\Controllers\Pembina\AnggotaController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Admin\SiswaController as AdminSiswa;
use App\Http\Controllers\Pembina\JadwalController;
use App\Http\Controllers\Admin\RekapAbsensiController as AdminRekap;
use App\Http\Controllers\Pembina\RekapAbsensiController as PembinaRekap;

Route::get('/', function () { 
    return redirect()->route('login'); 
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // --- KHUSUS ADMIN --
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        
        Route::get('/pembina', [PembinaController::class, 'index'])->name('admin.pembina.index');
        Route::post('/pembina', [PembinaController::class, 'store'])->name('admin.pembina.store');
        Route::put('/pembina/{id}', [PembinaController::class, 'update'])->name('admin.pembina.update');
        Route::delete('/pembina/{id}', [PembinaController::class, 'destroy'])->name('admin.pembina.destroy');

        Route::get('/siswa', [AdminSiswa::class, 'index'])->name('admin.siswa.index');
        Route::post('/siswa', [AdminSiswa::class, 'store'])->name('admin.siswa.store');
        Route::put('/siswa/{id}', [AdminSiswa::class, 'update'])->name('admin.siswa.update');
        Route::delete('/siswa/{id}', [AdminSiswa::class, 'destroy'])->name('admin.siswa.destroy');

        Route::get('/ekskul', [EkstrakurikulerController::class, 'index'])->name('admin.ekskul.index');
        Route::post('/ekskul', [EkstrakurikulerController::class, 'store'])->name('admin.ekskul.store');
        Route::put('/ekskul/{id}', [EkstrakurikulerController::class, 'update'])->name('admin.ekskul.update');
        Route::delete('/ekskul/{id}', [EkstrakurikulerController::class, 'destroy'])->name('admin.ekskul.destroy');

        Route::get('/rekap-absensi', [AdminRekap::class, 'index'])->name('admin.rekap.index');

    });

    // --- KHUSUS PEMBINA ---
    Route::middleware(['role:pembina'])->prefix('pembina')->group(function () {
        Route::get('/dashboard', [PembinaDashboard::class, 'index'])->name('pembina.dashboard');

        Route::get('/profile', [PembinaProfile::class, 'index'])->name('pembina.profile');
        Route::put('/profile', [PembinaProfile::class, 'update'])->name('pembina.profile.update');

        Route::get('/anggota', [AnggotaController::class, 'index'])->name('pembina.anggota.index');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('pembina.anggota.store');
        Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('pembina.anggota.update');
        Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('pembina.anggota.destroy');

        Route::get('/absensi/manage', [PembinaRekap::class, 'manage'])->name('pembina.absensi.manage');

        Route::post('/absensi/update', [PembinaRekap::class, 'updateStatus'])->name('pembina.absensi.update');

        Route::get('/rekap-absensi', [PembinaRekap::class, 'index'])->name('pembina.rekap.index');

        Route::get('/jadwal', [JadwalController::class, 'index'])->name('pembina.jadwal.index');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('pembina.jadwal.store');
        Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('pembina.jadwal.update');
        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('pembina.jadwal.destroy');


        Route::get('/hari-libur', [JadwalController::class, 'liburIndex'])->name('pembina.libur.index');
        Route::post('/hari-libur', [JadwalController::class, 'liburStore'])->name('pembina.libur.store');
        Route::put('/hari-libur/{id}', [JadwalController::class, 'liburUpdate'])->name('pembina.libur.update');
        Route::delete('/hari-libur/{id}', [JadwalController::class, 'liburDestroy'])->name('pembina.libur.destroy');

        Route::get('/riwayat-absensi', [PembinaRekap::class, 'riwayat'])->name('pembina.riwayat.index');

    });

    // --- KHUSUS SISWA ---
    Route::middleware(['role:siswa'])->prefix('siswa')->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('siswa.dashboard');

        Route::get('/profile', [App\Http\Controllers\Siswa\ProfileController::class, 'index'])->name('siswa.profile');
        Route::put('/profile', [App\Http\Controllers\Siswa\ProfileController::class, 'update'])->name('siswa.profile.update');
        
        Route::get('/absen', [App\Http\Controllers\Siswa\AbsenController::class, 'index'])->name('siswa.absen');
        Route::post('/absen', [App\Http\Controllers\Siswa\AbsenController::class, 'store'])->name('siswa.absen.store');
        Route::get('/absen/riwayat', [App\Http\Controllers\Siswa\AbsenController::class, 'riwayat'])->name('siswa.absen.riwayat');

    });

});