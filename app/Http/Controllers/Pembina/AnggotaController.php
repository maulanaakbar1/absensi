<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\User;
use App\Models\Pembina; 
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $pembina = Pembina::where('user_id', Auth::id())->firstOrFail();

        $ekskulId = $pembina->ekstrakurikuler_id;

        // Ambil list tahun ajaran
        $listTahun = SiswaKelas::where('ekstrakurikuler_id', $ekskulId)
            ->select('tahun_ajaran')
            ->distinct()
            ->orderBy('tahun_ajaran', 'desc')
            ->pluck('tahun_ajaran');

        // Ambil list kelas
        $listKelas = SiswaKelas::where('ekstrakurikuler_id', $ekskulId)
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // Query anggota
        $query = SiswaKelas::with(['siswa.user'])
            ->where('ekstrakurikuler_id', $ekskulId)

            // Filter nama
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('siswa.user', function ($user) use ($request) {
                    $user->where('name', 'like', '%' . $request->search . '%');
                });
            })

            // Filter tahun ajaran
            ->when($request->tahun_ajaran, function ($q) use ($request) {
                $q->where('tahun_ajaran', $request->tahun_ajaran);
            })

            // Filter kelas
            ->when($request->kelas, function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });

        // Ambil data
        $anggota = $query
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('kelas', 'asc')
            ->get();

        return view('pembina.anggota', compact(
            'anggota',
            'listTahun',
            'listKelas'
        ));
    }

    public function store(Request $request)
    {
        $pembina = Pembina::where('user_id', Auth::id())->firstOrFail();

        $ekskulId = $pembina->ekstrakurikuler_id;

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nis' => 'required|unique:siswas,nis',
            'nisn' => 'required|unique:siswas,nisn',
            'kelas' => 'required',
            'tahun_ajaran' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa'
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin
        ]);

        SiswaKelas::create([
            'siswa_id' => $siswa->id,
            'ekstrakurikuler_id' => $ekskulId,
            'tahun_ajaran' => $request->tahun_ajaran,
            'kelas' => $request->kelas,
        ]);

        return back()->with(
            'success',
            'Anggota berhasil ditambahkan ke ekstrakurikuler Anda!'
        );
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $user = $siswa->user;

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nisn' => 'required|unique:siswas,nisn,' . $siswa->id,
            'kelas' => 'required',
            'tahun_ajaran' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $siswa->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        // Update data kelas aktif
        $riwayat = SiswaKelas::where('siswa_id', $siswa->id)
            ->where('status', 'aktif')
            ->first();

        if ($riwayat) {
            $riwayat->update([
                'kelas' => $request->kelas,
                'tahun_ajaran' => $request->tahun_ajaran,
            ]);
        }

        return back()->with(
            'success',
            'Data anggota berhasil diperbarui!'
        );
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        // otomatis hapus relasi jika pakai cascade
        $siswa->user->delete();

        return back()->with(
            'success',
            'Anggota berhasil dihapus!'
        );
    }
}