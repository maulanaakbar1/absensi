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

        // Ambil semua siswa sesuai ekskul pembina
        $siswaList = Siswa::with('user')
            ->where('ekstrakurikuler_id', $pembina->ekstrakurikuler_id)
            ->get();

        // Ambil jadwal ekskul
        $jadwal = Jadwal::where('ekstrakurikuler_id', $pembina->ekstrakurikuler_id)
            ->first();

        foreach ($siswaList as $siswa) {

            // =========================
            // FORMAT PESAN
            // =========================

            $pesan = "📢 *INFORMASI EKSKUL*\n\n";

            $pesan .= "🏫 Ekskul: " . $pembina->ekstrakurikuler->nama . "\n";
            $pesan .= "👤 Siswa: " . $siswa->user->name . "\n\n";

            if ($jadwal) {

                $pesan .= "📅 Jadwal Kegiatan\n";
                $pesan .= "Hari : {$jadwal->hari}\n";

                $pesan .= "Jam : "
                    . date('H:i', strtotime($jadwal->jam_mulai))
                    . " - "
                    . date('H:i', strtotime($jadwal->jam_selesai))
                    . " WIB\n";

                $pesan .= "📍 Lokasi : {$jadwal->lokasi}\n";

                if ($jadwal->keterangan) {
                    $pesan .= "📝 Keterangan : {$jadwal->keterangan}\n";
                }

                $pesan .= "\n";
            }

            $pesan .= "Jangan lupa mengikuti kegiatan ekskul sesuai jadwal.\n\n";
            $pesan .= "Terima kasih.";

            // =========================
            // KIRIM KE SISWA
            // =========================

            if ($siswa->no_telp_siswa) {

                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
                ])->post(env('WA_API_URL'), [
                    'phone' => $this->formatNomor($siswa->no_telp_siswa),
                    'message' => $pesan,
                ]);

                // Delay 2 detik
                sleep(2);
            }

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

                // Delay 2 detik
                sleep(2);
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

                // Delay 2 detik
                sleep(2);
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