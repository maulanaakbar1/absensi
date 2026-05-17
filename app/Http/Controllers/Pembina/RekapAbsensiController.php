<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));

        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        // =========================
        // FILTER TAHUN AJARAN
        // =========================
        $selectedTahun = $request->get(
            'tahun_ajaran',
            $this->getCurrentTahunAjaran()
        );

        $selectedKelas = $request->get('kelas');

        // =========================
        // AMBIL TAHUN AWAL TA
        // contoh:
        // 2025/2026 => 2025
        // =========================
        $selectedTahunStart = $selectedTahun !== 'semua'
            ? $this->parseTahunAjaranStart($selectedTahun)
            : now()->year;

        /**
         * =========================
         * PENENTUAN TAHUN OTOMATIS
         * =========================
         *
         * Juli - Desember => tahun awal TA
         * Januari - Juni  => tahun akhir TA
         */

        if ((int) $bulan >= 7) {

            $tahun = $selectedTahunStart;

        } else {

            $tahun = $selectedTahunStart + 1;

        }

        $jumlahHari = Carbon::createFromDate(
            $tahun,
            $bulan,
            1
        )->daysInMonth;

        $query = Siswa::with([
            'user',
            'absensis' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
            }
        ])->where('ekstrakurikuler_id', $ekskulId);

        // =========================
        // FILTER TAHUN AJARAN
        // =========================
        if ($selectedTahunStart) {

            $query->where(function ($q) use ($selectedTahunStart) {

                $q->whereNull('tahun_masuk')

                ->orWhere(function ($q2) use ($selectedTahunStart) {

                    $q2->whereRaw(
                        '? BETWEEN tahun_masuk AND (tahun_masuk + (12 - tingkat_awal))',
                        [$selectedTahunStart]
                    );

                });

            });

        }

        $siswas = $query->get();

        // =========================
        // TRANSFORM KELAS DISPLAY
        // =========================
        $siswas->transform(function ($siswa) use ($selectedTahunStart) {

            $tahunDisplay = $selectedTahunStart
                ?? $this->parseTahunAjaranStart(
                    $this->getCurrentTahunAjaran()
                );

            $tingkat = $this->getTingkat(
                $siswa,
                $tahunDisplay
            );

            $kelasDisplay = $this->getKelasDisplay(
                $siswa,
                $tahunDisplay
            );

            $siswa->kelas_display = $kelasDisplay;
            $siswa->tingkat_display = $tingkat;

            return $siswa;
        });

        // =========================
        // FILTER KELAS
        // =========================
        if ($selectedKelas) {

            $siswas = $siswas->filter(function ($siswa) use ($selectedKelas) {

                return $siswa->tingkat_display == $selectedKelas;

            })->values();

        }

        // =========================
        // LIST TAHUN AJARAN
        // =========================
        $tahunAjaranList = $this->getTahunAjaranList($ekskulId);

        if (
            $selectedTahun !== 'semua'
            && !in_array($selectedTahun, $tahunAjaranList)
        ) {

            $tahunAjaranList[] = $selectedTahun;

        }

        $namaBulan = [
            '01'=>'Januari',
            '02'=>'Februari',
            '03'=>'Maret',
            '04'=>'April',
            '05'=>'Mei',
            '06'=>'Juni',
            '07'=>'Juli',
            '08'=>'Agustus',
            '09'=>'September',
            '10'=>'Oktober',
            '11'=>'November',
            '12'=>'Desember'
        ];

        return view('pembina.rekap_absensi', compact(
            'siswas',
            'bulan',
            'tahun',
            'jumlahHari',
            'namaBulan',
            'tahunAjaranList',
            'selectedTahun',
            'selectedTahunStart',
            'selectedKelas'
        ));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha'
        ]);

        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        $hariInput = Carbon::parse($request->tanggal)->translatedFormat('l'); 
        $hariMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariEnglish = Carbon::parse($request->tanggal)->format('l');
        $hariIndo = $hariMap[$hariEnglish];

        // 2. Cek apakah ada jadwal ekskul di hari tersebut
        $cekJadwal = \App\Models\Jadwal::where('ekstrakurikuler_id', $ekskulId)
                    ->where('hari', $hariIndo)
                    ->exists();

        if (!$cekJadwal) {
            return back()->with('error', "Tidak ada jadwal latihan pada hari $hariIndo (" . date('d/m/Y', strtotime($request->tanggal)) . ")");
        }

        // 3. Cek apakah tanggal tersebut terdaftar sebagai Hari Libur Ekskul
        $cekLibur = \App\Models\HariLibur::where('ekstrakurikuler_id', $ekskulId)
                    ->whereDate('tanggal', $request->tanggal)
                    ->exists();
        
        if ($cekLibur) {
            return back()->with('error', "Gagal absen! Tanggal tersebut ditandai sebagai hari libur ekskul.");
        }

        // --- Logika update/create absensi tetap sama ---
        $absensi = Absensi::where('siswa_id', $request->siswa_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($absensi && $absensi->status === 'hadir') {
            return back()->with('error', 'Status hadir tidak bisa diubah!');
        }

        if ($absensi) {
            $absensi->update(['status' => $request->status]);
        } else {
            Absensi::create([
                'siswa_id' => $request->siswa_id,
                'tanggal'  => $request->tanggal,
                'status'   => $request->status,
            ]);
        }
        return redirect()->back()->with('success', 'Status absensi berhasil diperbarui');
    }

    public function manage(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        $siswas = Siswa::with(['user', 'absensis' => function($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        }])
        ->where('ekstrakurikuler_id', $ekskulId)
        ->get();

        return view('pembina.absensi_manage', compact('siswas', 'tanggal'));
    }

    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        $riwayat = Absensi::whereHas('siswa', function($query) use ($ekskulId) {
            $query->where('ekstrakurikuler_id', $ekskulId);
        })
        ->with('siswa.user') 
        ->latest('tanggal')
        ->latest('jam_masuk')
        ->paginate(15); 

        return view('pembina.riwayat_absensi', compact('riwayat'));
    }

    private function parseTahunAjaranStart(string $tahunAjaran): int
    {
        return (int) explode('/', $tahunAjaran)[0];
    }

    private function getCurrentTahunAjaran(): string
    {
        $year = now()->month >= 7
            ? now()->year
            : now()->year - 1;

        return $year . '/' . ($year + 1);
    }

    private function getTahunAjaranList(int $ekskulId): array
    {
        $range = Siswa::where('ekstrakurikuler_id', $ekskulId)
            ->whereNotNull('tahun_masuk')
            ->selectRaw('MIN(tahun_masuk) as min_tahun, MAX(tahun_masuk) as max_tahun')
            ->first();

        if (!$range || !$range->min_tahun) {
            return [];
        }

        $currentYear = now()->month >= 7
            ? now()->year
            : now()->year - 1;

        $maxLimit = max($currentYear, $range->max_tahun);

        $list = [];

        for ($y = $range->min_tahun; $y <= $maxLimit; $y++) {
            $list[] = $y . '/' . ($y + 1);
        }

        return array_reverse($list);
    }

    private function getTingkat($siswa, int $tahunAjaranStart): ?int
    {
        if (!$siswa->tahun_masuk) {
            return null;
        }

        $tingkat = ($tahunAjaranStart - $siswa->tahun_masuk)
            + $siswa->tingkat_awal;

        return ($tingkat >= 10 && $tingkat <= 12)
            ? $tingkat
            : null;
    }

    private function getKelasDisplay($siswa, int $tahunAjaranStart): string
    {
        $tingkat = $this->getTingkat($siswa, $tahunAjaranStart);

        if (!$tingkat) {
            return $siswa->kelas ?? '-';
        }

        $label = match ($tingkat) {
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
            default => '?',
        };

        $jurusan = preg_replace(
            '/^(X|XI|XII)\s+/i',
            '',
            $siswa->jurusan ?? ''
        );

        return trim($label . ' ' . $jurusan);
    }
    
}