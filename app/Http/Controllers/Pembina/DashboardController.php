<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pembina;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data pembina dan ekskulnya
        $pembina = Pembina::where('user_id', Auth::id())
            ->with('ekstrakurikuler')
            ->first();

        if (!$pembina || !$pembina->ekstrakurikuler) {
            return view('pembina.dashboard', ['pembina' => $pembina, 'jumlahSiswa' => 0]);
        }

        $ekskulId = $pembina->ekstrakurikuler_id;

        // 2. Hitung jumlah siswa di ekskul tersebut
        $jumlahSiswa = Siswa::where('ekstrakurikuler_id', $ekskulId)->count();

        // 3. Ambil jadwal terdekat (berdasarkan hari ini)
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $hariIni = $daftarHari[date('N') - 1];
        
        $jadwalTerdekat = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariIni)
            ->first();

        // 4. Ambil ringkasan absensi hari ini (opsional untuk tambahan info)
        $absensiHariIni = Absensi::whereHas('siswa', function($q) use ($ekskulId) {
                $q->where('ekstrakurikuler_id', $ekskulId);
            })
            ->whereDate('tanggal', Carbon::today())
            ->count();

        return view('pembina.dashboard', compact('pembina', 'jumlahSiswa', 'jadwalTerdekat', 'absensiHariIni'));
    }

    public function kirimWa()
    {
        $pembina = Pembina::where('user_id', Auth::id())
            ->with('ekstrakurikuler')
            ->first();

        if (!$pembina || !$pembina->ekstrakurikuler) {
            return back()->with('error', 'Ekskul tidak ditemukan.');
        }

        $siswaList = Siswa::with('user')
            ->where('ekstrakurikuler_id', $pembina->ekstrakurikuler_id)
            ->get();

        foreach ($siswaList as $siswa) {

            $pesan = "📢 INFORMASI EKSKUL\n\n"
                . "Ekskul: " . $pembina->ekstrakurikuler->nama . "\n"
                . "Siswa: " . $siswa->user->name . "\n\n"
                . "Jangan lupa mengikuti kegiatan ekskul sesuai jadwal.\n\n"
                . "Terima kasih.";

            // =========================
            // KIRIM KE AYAH
            // =========================
            if ($siswa->no_telp_ayah) {

                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
                ])->post(env('WA_API_URL'), [
                    'phone' => $this->formatNomor($siswa->no_telp_ayah),
                    'message' => $pesan,
                ]);
            }

            // =========================
            // KIRIM KE IBU
            // =========================
            if ($siswa->no_telp_ibu) {

                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
                ])->post(env('WA_API_URL'), [
                    'phone' => $this->formatNomor($siswa->no_telp_ibu),
                    'message' => $pesan,
                ]);
            }
        }

        return back()->with('success', 'Pesan WA berhasil dikirim.');
    }

    private function formatNomor($nomor)
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}