<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Default tahun ajaran sekarang
        $selectedTahun = $request->get(
            'tahun_ajaran',
            $this->getCurrentTahunAjaran()
        );

        // Filter kelas
        $selectedKelas = $request->get('kelas');

        // FILTER JURUSAN
        $selectedJurusan = $request->get('jurusan');

        $selectedTahunStart = $selectedTahun !== 'semua'
            ? $this->parseTahunAjaranStart($selectedTahun)
            : $this->parseTahunAjaranStart($this->getCurrentTahunAjaran());

        $query = Siswa::with(['user', 'ekstrakurikuler']);

        // =========================
        // FILTER TAHUN AJARAN
        // =========================
        if ($selectedTahun !== 'semua') {
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

        // =========================
        // FILTER JURUSAN
        // =========================
        if ($selectedJurusan) {
            $query->where('jurusan', $selectedJurusan);
        }

        // =========================
        // PERBAIKAN FILTER KELAS: Langsung di Query Database
        // =========================
        if ($selectedKelas) {
            $query->where(function ($q) use ($selectedTahunStart, $selectedKelas) {
                $q->whereRaw('(? - tahun_masuk) + tingkat_awal = ?', [$selectedTahunStart, $selectedKelas]);
            });
        }

        // Ambil Data dengan Paginate & Pertahankan Filter di URL
        $anggota = $query
            ->join('users', 'siswas.user_id', '=', 'users.id')
            ->orderBy('siswas.jurusan', 'asc')
            ->orderBy('siswas.tingkat_awal', 'asc')
            ->orderBy('users.name', 'asc')
            ->select('siswas.*')
            ->paginate(10)
            ->withQueryString();

        // Transform data display teks kelas untuk setiap item halaman
        $anggota->getCollection()->transform(function ($siswa) use ($selectedTahunStart) {
            $tingkat = $this->getTingkat($siswa, $selectedTahunStart);
            $siswa->kelas_display = $this->getKelasDisplay($siswa, $selectedTahunStart);
            $siswa->tingkat_display = $tingkat;

            return $siswa;
        });

        // Dropdown tahun ajaran
        $tahunAjaranList = $this->getTahunAjaranList();

        // Pastikan selected tetap muncul
        if (
            $selectedTahun !== 'semua'
            && !in_array($selectedTahun, $tahunAjaranList)
        ) {
            $tahunAjaranList[] = $selectedTahun;
        }

        $ekskul = Ekstrakurikuler::all();

        return view('admin.siswa', compact(
            'anggota',
            'ekskul',
            'tahunAjaranList',
            'selectedTahun',
            'selectedTahunStart',
            'selectedKelas',
            'selectedJurusan' 
        ));
    }

    public function show($id)
    {
        $siswa = Siswa::with(['user', 'ekstrakurikuler'])
            ->findOrFail($id);

        $tahunAjaran = $this->getCurrentTahunAjaran();

        $tahunStart = $this->parseTahunAjaranStart($tahunAjaran);

        $kelasDisplay = $this->getKelasDisplay(
            $siswa,
            $tahunStart
        );

        return view('admin.siswa-show', compact(
            'siswa',
            'tahunAjaran',
            'kelasDisplay'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nis' => 'required|unique:siswas,nis',
            'nisn' => 'required|unique:siswas,nisn',
            'tahun_masuk' => 'required|integer|min:2000|max:2100',
            'tingkat_awal' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'no_telp_siswa' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:100',
            'no_telp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'nullable|string|max:100',
            'no_telp_ibu' => 'nullable|string|max:15',
            'tingkatan' => 'required|in:balonpas,instruktur',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'tahun_masuk' => $request->tahun_masuk,
                'tingkat_awal' => $request->tingkat_awal,
                'jurusan' => $request->jurusan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telp_siswa' => $request->no_telp_siswa,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'no_telp_ayah' => $request->no_telp_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_telp_ibu' => $request->no_telp_ibu,
                'tingkatan' => $request->tingkatan,
            ]);
        });

        return back()->with(
            'success',
            'Siswa baru berhasil ditambahkan.'
        );
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nisn' => 'required|unique:siswas,nisn,' . $siswa->id,
            'tahun_masuk' => 'required|integer|min:2000|max:2100',
            'tingkat_awal' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'no_telp_siswa' => 'nullable|string|max:15',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:100',
            'no_telp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'nullable|string|max:100',
            'no_telp_ibu' => 'nullable|string|max:15',
            'tingkatan' => 'required|in:balonpas,instruktur',
        ]);

        DB::transaction(function () use (
            $request,
            $user,
            $siswa
        ) {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {

                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $siswa->update([
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'tahun_masuk' => $request->tahun_masuk,
                'tingkat_awal' => $request->tingkat_awal,
                'jurusan' => $request->jurusan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
                'no_telp_siswa' => $request->no_telp_siswa,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'no_telp_ayah' => $request->no_telp_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_telp_ibu' => $request->no_telp_ibu,
                'tingkatan' => $request->tingkatan,
            ]);
        });

        return back()->with(
            'success',
            'Data siswa berhasil diperbarui.'
        );
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        $user = $siswa->user;

        $siswa->delete();

        if ($user) {
            $user->delete();
        }

        return back()->with(
            'success',
            'Data siswa berhasil dihapus.'
        );
    }

    public function export()
    {
        return Excel::download(
            new SiswaExport,
            'data-siswa.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new SiswaImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Import data siswa berhasil.'
        );
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
}