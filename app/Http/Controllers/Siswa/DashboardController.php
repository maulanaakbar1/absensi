<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = \App\Models\Siswa::where('user_id', $user->id)->first();

        // Data Statistik
        $totalHadir = Absensi::where('siswa_id', $siswa->id)
                                ->where('status', 'hadir')
                                ->count();

        $namaEkskul = $siswa->ekstrakurikuler->nama ?? 'Belum Terdaftar';

        // Riwayat 5 Terakhir
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
                                    ->orderBy('tanggal', 'desc')
                                    ->take(5)
                                    ->get();

        // AMBIL DATA TAMBAHAN: Jadwal & Pembina
        $ekskulId = $siswa->ekstrakurikuler_id;
        $jadwalEkskul = \App\Models\Jadwal::where('ekstrakurikuler_id', $ekskulId)->get();
        $pembina = \App\Models\Pembina::where('ekstrakurikuler_id', $ekskulId)->first();

        return view('siswa.dashboard', compact(
            'user', 
            'totalHadir', 
            'namaEkskul', 
            'riwayatAbsensi', 
            'jadwalEkskul', 
            'pembina'
        ));
    }
}