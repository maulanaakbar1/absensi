<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $tahunAjaran = $this->getCurrentTahunAjaran();
        $tahunAjaranStart = (int) explode('/', $tahunAjaran)[0];

        $kelasDisplay = $this->getKelasDisplay(
            $siswa,
            $tahunAjaranStart
        );

        $isProfileComplete =
            !empty($siswa->no_telp_siswa) &&
            !empty($siswa->tempat_lahir) &&
            !empty($siswa->tanggal_lahir) &&
            !empty($siswa->alamat) &&
            !empty($siswa->nama_ayah) &&
            !empty($siswa->no_telp_ayah) &&
            !empty($siswa->nama_ibu) &&
            !empty($siswa->no_telp_ibu);

        return view('siswa.profile', compact(
            'user',
            'siswa',
            'tahunAjaran',
            'kelasDisplay',
            'isProfileComplete'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nis' => 'required|string|unique:siswas,nis,' . $siswa->id,
            'jenis_kelamin' => 'required|in:L,P',
            'nisn' => 'required|string|unique:siswas,nisn,' . $siswa->id,
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'no_telp_ayah' => 'nullable|string|max:15',
            'no_telp_ibu' => 'nullable|string|max:15',
            'no_telp_siswa' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $siswa->update([
            'nis' => $request->nis, 
            'jenis_kelamin' => $request->jenis_kelamin, 
            'nisn' => $request->nisn,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'no_telp_ayah' => $request->no_telp_ayah,
            'no_telp_ibu' => $request->no_telp_ibu,
            'no_telp_siswa' => $request->no_telp_siswa,
        ]);

        return redirect()
            ->route('siswa.profile')
            ->with('success', 'Profil dan data personal berhasil diperbarui!');
    }

    private function getCurrentTahunAjaran(): string
    {
        $year = now()->month >= 7
            ? now()->year
            : now()->year - 1;

        return $year . '/' . ($year + 1);
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
            return '-';
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