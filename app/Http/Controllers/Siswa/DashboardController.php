<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pembina;
use Illuminate\Http\Request;
use App\Models\Ekstrakurikuler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekskulId = $user->ekskul_aktif;
        // dd($user->ekskul_aktif);

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

        $namaEkskul = Ekstrakurikuler::where('id', $user->ekskul_aktif)->first()->nama ?? 'Belum Terdaftar';

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
        // $ekskulId = $siswa->ekstrakurikuler_id;

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

        // =========================
        // CEK JADWAL BERLANGSUNG
        // =========================
        $jadwalEkskul = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariIni)
            ->where('jam_mulai', '<=', $jamSekarang)
            ->where('jam_selesai', '>=', $jamSekarang)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // =========================
        // JIKA TIDAK ADA JADWAL HARI INI
        // MAKA TAMPILKAN JADWAL BERIKUTNYA
        // =========================
        $statusJadwal = 'Sedang Berlangsung';

        if ($jadwalEkskul->isEmpty()) {

            // Urutan hari
            $urutanHari = [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu',
                'Minggu'
            ];

            $indexHariIni = array_search($hariIni, $urutanHari);

            // Cari jadwal terdekat
            for ($i = 1; $i <= 7; $i++) {

                $nextIndex = ($indexHariIni + $i) % 7;
                $hariBerikutnya = $urutanHari[$nextIndex];

                $jadwalBesok = Jadwal::where('ekstrakurikuler_id', $ekskulId)
                    ->where('hari', $hariBerikutnya)
                    ->orderBy('jam_mulai', 'asc')
                    ->get();

                if ($jadwalBesok->isNotEmpty()) {

                    $jadwalEkskul = $jadwalBesok;

                    if ($i == 1) {
                        $statusJadwal = 'Segera Hadir';
                    } else {
                        $statusJadwal = 'Akan Datang';
                    }

                    break;
                }
            }
        }

        // Data Pembina
        $pembinas = Pembina::where('ekstrakurikuler_id', $ekskulId)->get();

        return view('siswa.dashboard', compact(
            'user',
            'totalHadir',
            'namaEkskul',
            'riwayatAbsensi',
            'jadwalEkskul',
            'pembinas',
            'statusJadwal'
        ));
    }
}
