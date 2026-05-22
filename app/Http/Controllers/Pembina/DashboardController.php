<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pembina;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\Absensi;
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
            return view('pembina.dashboard', [
                'pembina' => $pembina,
                'jumlahSiswa' => 0,
                'jadwalTerdekat' => null,
                'absensiHariIni' => 0
            ]);
        }

        $ekskulId = $pembina->ekstrakurikuler_id;

        // 2. Hitung jumlah siswa
        $jumlahSiswa = Siswa::where('ekstrakurikuler_id', $ekskulId)->count();

        // =========================
        // 3. LOGIC JADWAL FIX FINAL (STABIL + BENAR + URUT)
        // =========================

        $daftarHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

        $now = Carbon::now();
        $hariSekarangIndex = date('N');
        $hariSekarang = $daftarHari[$hariSekarangIndex - 1];
        $jamSekarang = $now->format('H:i:s');

        // =========================
        // 1. CEK APAKAH MASIH ADA JADWAL AKTIF HARI INI
        // =========================
        $jadwalAktifHariIni = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariSekarang)
            ->where('jam_selesai', '>', $jamSekarang)
            ->orderBy('jam_mulai', 'asc')
            ->first();

        // =========================
        // 2. JIKA MASIH ADA → PAKAI ITU
        // =========================
        if ($jadwalAktifHariIni) {
            $jadwalTerdekat = $jadwalAktifHariIni;
        } 
        // =========================
        // 3. JIKA SUDAH HABIS → CARI HARI BERIKUTNYA
        // =========================
        else {

            $urutanHari = [
                'Senin' => 1,
                'Selasa' => 2,
                'Rabu' => 3,
                'Kamis' => 4,
                'Jumat' => 5,
                'Sabtu' => 6,
                'Minggu' => 7,
            ];

            $jadwalTerdekat = Jadwal::where('ekstrakurikuler_id', $ekskulId)
                ->get()
                ->sortBy(function ($item) use ($urutanHari, $hariSekarangIndex, $jamSekarang) {

                    $hariIndex = $urutanHari[$item->hari];

                    $diff = $hariIndex - $hariSekarangIndex;

                    // kalau sudah lewat → masuk minggu depan
                    if ($diff < 0) {
                        $diff += 7;
                    }

                    // bonus: kalau hari sama tapi jam sudah lewat → dorong ke belakang
                    if ($diff == 0 && $item->jam_mulai <= $jamSekarang) {
                        $diff = 7;
                    }

                    return $diff;
                })
                ->sortBy('jam_mulai')
                ->first();
        }

        // 4. Absensi hari ini
        $absensiHariIni = Absensi::whereHas('siswa', function ($q) use ($ekskulId) {
                $q->where('ekstrakurikuler_id', $ekskulId);
            })
            ->whereDate('tanggal', Carbon::today())
            ->count();

        return view('pembina.dashboard', compact(
            'pembina',
            'jumlahSiswa',
            'jadwalTerdekat',
            'absensiHariIni'
        ));
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

        $daftarHari = [
            'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'
        ];

        $urutanHari = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        $now = Carbon::now();
        $hariSekarangIndex = $now->dayOfWeekIso; // 1-7

        $jadwal = Jadwal::where('ekstrakurikuler_id', $pembina->ekstrakurikuler_id)
            ->get()
            ->map(function ($item) use ($urutanHari, $hariSekarangIndex, $now) {

                $hariIndex = $urutanHari[$item->hari];

                $diff = $hariIndex - $hariSekarangIndex;

                if ($diff < 0) {
                    $diff += 7;
                }

                // ⛔ kalau hari sama tapi jam sudah lewat → dorong ke minggu depan
                if ($diff == 0 && $item->jam_mulai <= $now->format('H:i:s')) {
                    $diff = 7;
                }

                $item->ranking = $diff;

                return $item;
            })
            ->sortBy('ranking')
            ->first();

        // =========================
        // KIRIM WA
        // =========================
        foreach ($siswaList as $siswa) {

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

            // kirim ke siswa
            if ($siswa->no_telp_siswa) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
                ])->post(env('WA_API_URL'), [
                    'phone' => $this->formatNomor($siswa->no_telp_siswa),
                    'message' => $pesan,
                ]);

                sleep(2);
            }

            // kirim ke ayah
            if ($siswa->no_telp_ayah) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
                ])->post(env('WA_API_URL'), [
                    'phone' => $this->formatNomor($siswa->no_telp_ayah),
                    'message' => $pesan,
                ]);

                sleep(2);
            }

            // kirim ke ibu
            if ($siswa->no_telp_ibu) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
                ])->post(env('WA_API_URL'), [
                    'phone' => $this->formatNomor($siswa->no_telp_ibu),
                    'message' => $pesan,
                ]);

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