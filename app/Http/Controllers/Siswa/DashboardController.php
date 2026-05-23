<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $siswa = Siswa::where('user_id', $user->id)->first();

        // CEK JIKA SISWA BELUM TERDAFTAR
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // =========================
        // DATA STATISTIK
        // =========================
        $totalHadir = Absensi::where('siswa_id', $siswa->id)
            ->where('status', 'hadir')
            ->count();

        $namaEkskul = $siswa->ekstrakurikuler->nama ?? 'Belum Terdaftar';

        // =========================
        // RIWAYAT ABSENSI
        // =========================
        $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // =========================
        // FILTER JADWAL BERDASARKAN
        // HARI & JAM SEKARANG
        // =========================
        $ekskulId = $siswa->ekstrakurikuler_id;

        // Hari sekarang format Indonesia
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $hariInggris = Carbon::now()->format('l');
        $hariIni = $hariMap[$hariInggris];

        // Jam sekarang
        $jamSekarang = Carbon::now()->format('H:i:s');

        // Jadwal yang sedang berlangsung
        $jadwalEkskul = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariIni)
            ->where('jam_mulai', '<=', $jamSekarang)
            ->where('jam_selesai', '>=', $jamSekarang)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Data Pembina
        $pembina = Pembina::where('ekstrakurikuler_id', $ekskulId)->first();

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