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

        $query = Siswa::with(['user', 'ekstrakurikuler'])
                    ->where('ekstrakurikuler_id', $ekskulId);

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $anggota = $query->latest()->get();

        return view('pembina.anggota', compact('anggota'));
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
            'jenis_kelamin' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa'
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'ekstrakurikuler_id' => $ekskulId,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan ke ekstrakurikuler Anda!');
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
            'jenis_kelamin' => 'required',
        ]);

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
            'kelas' => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return back()->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->user->delete();

        return back()->with('success', 'Anggota berhasil dihapus!');
    }
}