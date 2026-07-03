<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    protected $ekskulId;

    public function __construct($ekskulId = null)
    {
        $this->ekskulId = $ekskulId;
    }

    public function collection()
    {
        $query = Siswa::with(['user']);

        // FILTER KHUSUS PEMBINA
        if ($this->ekskulId) {
            $query->whereJsonContains('ekstrakurikuler_id', $this->ekskulId);
        }

        $ekskuls = \App\Models\Ekstrakurikuler::all()->keyBy('id');

        return $query->get()
            ->map(function ($siswa) use ($ekskuls) {
                $siswaEkskulIds = is_array($siswa->ekstrakurikuler_id)
                    ? $siswa->ekstrakurikuler_id
                    : json_decode($siswa->ekstrakurikuler_id, true) ?? [];

                $ekskulNames = collect($siswaEkskulIds)
                    ->map(fn($id) => $ekskuls->get($id)->nama ?? null)
                    ->filter()
                    ->implode(', ');

                return [
                    'nama'                => $siswa->user->name ?? '-',
                    'email'               => $siswa->user->email ?? '-',
                    'nis'                 => $siswa->nis,
                    'nisn'                => $siswa->nisn,

                    'kelas'               => $this->generateKelas($siswa),

                    'jurusan'             => $siswa->jurusan,
                    'tingkat_awal'        => $siswa->tingkat_awal,
                    'tahun_masuk'         => $siswa->tahun_masuk,
                    'jenis_kelamin'       => $siswa->jenis_kelamin,
                    'alamat'              => $siswa->alamat,
                    'tempat_lahir'        => $siswa->tempat_lahir,
                    'tanggal_lahir'       => $siswa->tanggal_lahir,
                    'nama_ayah'           => $siswa->nama_ayah,
                    'nama_ibu'            => $siswa->nama_ibu,
                    'no_telp_ayah'        => $siswa->no_telp_ayah,
                    'no_telp_ibu'         => $siswa->no_telp_ibu,
                    'no_telp_siswa'       => $siswa->no_telp_siswa,
                    'ekstrakurikuler'     => $ekskulNames ?: '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'NIS',
            'NISN',
            'Kelas',
            'Jurusan',
            'Tingkat Awal',
            'Tahun Masuk',
            'Jenis Kelamin',
            'Alamat',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Nama Ayah',
            'Nama Ibu',
            'No Telp Ayah',
            'No Telp Ibu',
            'No Telp Siswa',
            'Ekstrakurikuler',
        ];
    }

    private function generateKelas($siswa)
    {
        if (!$siswa->tahun_masuk || !$siswa->tingkat_awal) {
            return '-';
        }

        $tahunSekarang = now()->month >= 7
            ? now()->year
            : now()->year - 1;

        $tingkat = ($tahunSekarang - $siswa->tahun_masuk)
            + $siswa->tingkat_awal;

        $label = match ($tingkat) {
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
            default => '?',
        };

        return $label . ' ' . $siswa->jurusan;
    }
}