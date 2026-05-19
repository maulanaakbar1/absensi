<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $pembina = Pembina::where('user_id', Auth::id())->firstOrFail();
        $ekskulId = $pembina->ekstrakurikuler_id;

        // Default: tampilkan tahun ajaran sekarang
        $selectedTahun = $request->get(
            'tahun_ajaran',
            $this->getCurrentTahunAjaran()
        );

        // Filter kelas
        $selectedKelas = $request->get('kelas');

        $selectedTahunStart = $selectedTahun !== 'semua'
            ? $this->parseTahunAjaranStart($selectedTahun)
            : null;

        $query = Siswa::with(['user', 'ekstrakurikuler'])
            ->where('ekstrakurikuler_id', $ekskulId);

        // Filter berdasarkan tahun ajaran
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

        // Search nama siswa
        if ($request->filled('search')) {

            $query->whereHas('user', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                );

            });

        }

        $anggota = $query->latest()->get();

        // Transform data kelas otomatis
        $anggota->transform(function ($siswa) use ($selectedTahunStart) {

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

        // FILTER KELAS
        if ($selectedKelas) {

            $anggota = $anggota->filter(function ($siswa) use ($selectedKelas) {

                return $siswa->tingkat_display == $selectedKelas;

            })->values();

        }

        // Dropdown tahun ajaran
        $tahunAjaranList = $this->getTahunAjaranList($ekskulId);

        // Pastikan selected tetap ada di dropdown
        if (
            $selectedTahun !== 'semua'
            && !in_array($selectedTahun, $tahunAjaranList)
        ) {

            $tahunAjaranList[] = $selectedTahun;

        }

        return view('pembina.anggota', compact(
            'anggota',
            'tahunAjaranList',
            'selectedTahun',
            'selectedTahunStart',
            'selectedKelas'
        ));
    }

    public function store(Request $request)
    {
        $pembina = Pembina::where('user_id', Auth::id())->firstOrFail();
        $ekskulId = $pembina->ekstrakurikuler_id;

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'nis'           => 'required|unique:siswas,nis',
            'nisn'          => 'required|unique:siswas,nisn',
            'tahun_masuk'   => 'required|integer|min:2000|max:2100',
            'tingkat_awal' => 'required|in:10,11,12',
            'jurusan'       => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        Siswa::create([
            'user_id'           => $user->id,
            'ekstrakurikuler_id' => $ekskulId,
            'tahun_masuk'       => $request->tahun_masuk,
            'tingkat_awal' => $request->tingkat_awal,
            'jurusan'           => $request->jurusan,
            'nis'               => $request->nis,
            'nisn'              => $request->nisn,
            'jenis_kelamin'     => $request->jenis_kelamin,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan ke ekstrakurikuler Anda!');
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $user  = $siswa->user;

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'nis'           => 'required|unique:siswas,nis,' . $siswa->id,
            'nisn'          => 'required|unique:siswas,nisn,' . $siswa->id,
            'tahun_masuk'   => 'required|integer|min:2000|max:2100',
            'tingkat_awal' => 'required|in:10,11,12',
            'jurusan'       => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $siswa->update([
            'tahun_masuk'   => $request->tahun_masuk,
            'tingkat_awal' => $request->tingkat_awal,
            'jurusan'       => $request->jurusan,
            'nis'           => $request->nis,
            'nisn'          => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return back()->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function show($id)
    {
        $pembina = Pembina::where('user_id', Auth::id())->firstOrFail();

        $siswa = Siswa::with(['user', 'ekstrakurikuler'])
            ->where('ekstrakurikuler_id', $pembina->ekstrakurikuler_id)
            ->findOrFail($id);

        $tahunAjaran = $this->getCurrentTahunAjaran();

        $tahunStart = $this->parseTahunAjaranStart($tahunAjaran);

        $kelasDisplay = $this->getKelasDisplay(
            $siswa,
            $tahunStart
        );

        return view('pembina.anggota-show', compact(
            'siswa',
            'tahunAjaran',
            'kelasDisplay'
        ));
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->user->delete();

        return back()->with('success', 'Anggota berhasil dihapus!');
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