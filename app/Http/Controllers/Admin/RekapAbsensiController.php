<?php

namespace App\Http\Controllers\Admin;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsensiExport;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use App\Models\HariLibur;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));

        $ekskul = $request->get('ekskul', 'all');

        // =========================
        // FILTER TAHUN AJARAN
        // =========================
        $selectedTahun = $request->get(
            'tahun_ajaran',
            $this->getCurrentTahunAjaran()
        );

        $selectedKelas = $request->get('kelas');

        // =========================
        // TAHUN AWAL TA
        // =========================
        $selectedTahunStart = $selectedTahun !== 'semua'
            ? $this->parseTahunAjaranStart($selectedTahun)
            : now()->year;

        // =========================
        // TENTUKAN TAHUN
        // =========================
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
        ]);

        // =========================
        // FILTER EKSKUL
        // =========================
        if ($ekskul != 'all') {
            $query->where('ekstrakurikuler_id', $ekskul);
        }

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

        $listEkskul = Ekstrakurikuler::all();

        $hariLibur = HariLibur::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $jadwals = Jadwal::all();

        // =========================
        // LIST TAHUN AJARAN
        // =========================
        $tahunAjaranList = $this->getTahunAjaranList();

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

        return view('admin.rekap_absensi', compact(
            'siswas',
            'bulan',
            'tahun',
            'jumlahHari',
            'namaBulan',
            'listEkskul',
            'ekskul',
            'hariLibur',
            'jadwals',
            'tahunAjaranList',
            'selectedTahun',
            'selectedKelas'
        ));
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

    private function getTahunAjaranList(): array
    {
        $range = Siswa::whereNotNull('tahun_masuk')
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

    public function downloadPdf(Request $request)
    {
        $data = $this->getRekapData($request);

        $pdf = Pdf::loadView(
            'exports.rekap_absensi_pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'rekap-absensi-' . now()->format('YmdHis') . '.pdf'
        );
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->getRekapData($request);

        return Excel::download(
            new RekapAbsensiExport($data),
            'rekap-absensi-' . now()->format('YmdHis') . '.xlsx'
        );
    }

    private function getRekapData(Request $request): array
    {
        // Mengamankan fallback input jika kosong
        $bulan = $request->get('bulan', date('m'));
        $selectedKelas = $request->get('kelas');
        
        // Deteksi otomatis role berdasarkan URL prefix
        $isAdmin = $request->is('admin/*') || auth()->user()->role === 'admin';
        
        if ($isAdmin) {
            $ekskul = $request->get('ekskul', 'all');
        } else {
            // Jika pembina, batasi hanya untuk ekskul yang dipegangnya agar tidak merujuk ke 'all' yang memicu loop/error data
            $ekskul = auth()->user()->pembina->ekstrakurikuler_id ?? $request->get('ekskul');
        }

        $selectedTahun = $request->get('tahun_ajaran');
        if (!$selectedTahun || $selectedTahun === 'semua') {
            $selectedTahun = $this->getCurrentTahunAjaran();
        }

        $selectedTahunStart = $this->parseTahunAjaranStart($selectedTahun);

        // Tentukan tahun kalender berdasarkan bulan akademik
        $tahun = ((int) $bulan >= 7) ? $selectedTahunStart : $selectedTahunStart + 1;

        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        // Build Query Siswa
        $query = Siswa::with([
            'user',
            'absensis' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
            }
        ]);

        // Filter Ekskul
        if ($ekskul && $ekskul !== 'all') {
            $query->where('ekstrakurikuler_id', $ekskul);
        }

        // Filter Tahun Ajaran / Tahun Masuk Sekolah
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

        // Transformasi Tampilan Kelas & Tingkat
        $siswas->transform(function ($siswa) use ($selectedTahunStart) {
            $siswa->kelas_display = $this->getKelasDisplay($siswa, $selectedTahunStart);
            $siswa->tingkat_display = $this->getTingkat($siswa, $selectedTahunStart);
            return $siswa;
        });

        // Filter Tingkat Kelas (X/XI/XII) jika dipilih
        if ($selectedKelas) {
            $siswas = $siswas->filter(function ($siswa) use ($selectedKelas) {
                return $siswa->tingkat_display == $selectedKelas;
            })->values();
        }

        // Mengambil Nama Ekskul untuk Header PDF
        $namaEkskul = 'Semua Ekskul';
        if ($ekskul && $ekskul !== 'all') {
            $ekskulModel = Ekstrakurikuler::find($ekskul);
            $namaEkskul = $ekskulModel ? $ekskulModel->nama : 'Semua Ekskul';
        }

        $namaBulan = [
            '01'=>'Januari', '02'=>'Februari', '03'=>'Maret',
            '04'=>'April',   '05'=>'Mei',      '06'=>'Juni',
            '07'=>'Juli',    '08'=>'Agustus',  '09'=>'September',
            '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
        ];

        return compact(
            'siswas', 'bulan', 'tahun', 'jumlahHari',
            'namaBulan', 'selectedTahun', 'selectedTahunStart',
            'selectedKelas', 'namaEkskul', 'isAdmin'
        );
    }
}