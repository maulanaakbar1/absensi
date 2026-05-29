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
                'absensiHariIni' => 0,
                'labelJadwal' => null,
            ]);
        }

        $ekskulId = $pembina->ekstrakurikuler_id;

        // 2. Hitung jumlah siswa
        $jumlahSiswa = Siswa::where('ekstrakurikuler_id', $ekskulId)->count();

        // =========================
        // 3. LOGIC JADWAL FIX FINAL
        // =========================

        $daftarHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

        $now = Carbon::now();
        $hariSekarangIndex = date('N');
        $hariSekarang = $daftarHari[$hariSekarangIndex - 1];
        $jamSekarang = $now->format('H:i:s');

        // 1. CEK JADWAL AKTIF HARI INI
        $jadwalAktifHariIni = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariSekarang)
            ->where('jam_selesai', '>', $jamSekarang)
            ->orderBy('jam_mulai', 'asc')
            ->first();

        if ($jadwalAktifHariIni) {
            $jadwalTerdekat = $jadwalAktifHariIni;
        } else {

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

                    if ($diff < 0) {
                        $diff += 7;
                    }

                    if ($diff == 0 && $item->jam_mulai <= $jamSekarang) {
                        $diff = 7;
                    }

                    return $diff;
                })
                ->sortBy('jam_mulai')
                ->first();
        }

        // =========================
        // LABEL JADWAL (INI YANG KAMU TAMBAH)
        // =========================
        $labelJadwal = null;

        if ($jadwalTerdekat) {

            if ($jadwalTerdekat->hari == $hariSekarang) {
                $labelJadwal = 'Jadwal Latihan Hari Ini';
            } else {

                // hitung selisih hari (0-6)
                $urutanHari = [
                    'Senin' => 1,
                    'Selasa' => 2,
                    'Rabu' => 3,
                    'Kamis' => 4,
                    'Jumat' => 5,
                    'Sabtu' => 6,
                    'Minggu' => 7,
                ];

                $selisih = $urutanHari[$jadwalTerdekat->hari] - $hariSekarangIndex;

                if ($selisih == 1 || $selisih == -6) {
                    $labelJadwal = 'Jadwal Latihan Besok';
                } else {
                    $labelJadwal = 'Jadwal Latihan Hari ' . $jadwalTerdekat->hari;
                }
            }
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
            'absensiHariIni',
            'labelJadwal'
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

            // siswa
            if ($siswa->no_telp_siswa) {

                \App\Jobs\KirimWaJob::dispatch(
                    $this->formatNomor($siswa->no_telp_siswa),
                    $pesan
                );

            }

            // ayah
            if ($siswa->no_telp_ayah) {

                \App\Jobs\KirimWaJob::dispatch(
                    $this->formatNomor($siswa->no_telp_ayah),
                    $pesan
                );

            }

            // ibu
            if ($siswa->no_telp_ibu) {

                \App\Jobs\KirimWaJob::dispatch(
                    $this->formatNomor($siswa->no_telp_ibu),
                    $pesan
                );

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