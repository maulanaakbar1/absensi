<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Ekstrakurikuler;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // =========================
        // SKIP JIKA EMAIL SUDAH ADA
        // =========================
        if (
            isset($row['email']) &&
            User::where('email', $row['email'])->exists()
        ) {
            return null;
        }

        // =========================
        // SKIP JIKA NIS SUDAH ADA
        // =========================
        if (
            isset($row['nis']) &&
            Siswa::where('nis', $row['nis'])->exists()
        ) {
            return null;
        }

        // =========================
        // CARI EKSKUL
        // =========================
        $ekskul = Ekstrakurikuler::where(
            'nama',
            $row['ekstrakurikuler'] ?? ''
        )->first();

        // =========================
        // AMBIL TINGKAT AWAL
        // =========================
        $tingkatAwal = (int) ($row['tingkat_awal'] ?? 10);

        // =========================
        // AMBIL JURUSAN
        // =========================
        $jurusan = $row['jurusan'] ?? '';

        // =========================
        // GENERATE KELAS OTOMATIS
        // =========================
        $kelas = $this->generateKelas(
            $tingkatAwal,
            $jurusan
        );

        // =========================
        // CREATE USER
        // =========================
        $user = User::create([
            'name' => $row['nama'] ?? '-',
            'email' => $row['email'],
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);

        // =========================
        // CREATE SISWA
        // =========================
        return new Siswa([
            'user_id'             => $user->id,
            'ekstrakurikuler_id'  => $ekskul?->id,

            'nis'                 => $row['nis'] ?? null,
            'nisn'                => $row['nisn'] ?? null,

            // AUTO GENERATE
            'kelas'               => $kelas,

            'jurusan'             => $jurusan,
            'tingkat_awal'        => $tingkatAwal,
            'tahun_masuk'         => $row['tahun_masuk'] ?? null,

            'jenis_kelamin'       => $row['jenis_kelamin'] ?? 'L',

            'alamat'              => $row['alamat'] ?? null,
            'tempat_lahir'        => $row['tempat_lahir'] ?? null,
            'tanggal_lahir'       => $row['tanggal_lahir'] ?? null,

            'nama_ayah'           => $row['nama_ayah'] ?? null,
            'nama_ibu'            => $row['nama_ibu'] ?? null,

            'no_telp_ayah'        => $row['no_telp_ayah'] ?? null,
            'no_telp_ibu'         => $row['no_telp_ibu'] ?? null,
            'no_telp_siswa'       => $row['no_telp_siswa'] ?? null,
        ]);
    }

    // =========================
    // GENERATE KELAS
    // =========================
    private function generateKelas($tingkat, $jurusan)
    {
        $label = match ((int) $tingkat) {
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
            default => '',
        };

        // HAPUS PREFIX X/XI/XII JIKA ADA
        $jurusan = preg_replace(
            '/^(X|XI|XII)\s+/i',
            '',
            $jurusan
        );

        return trim($label . ' ' . $jurusan);
    }
}