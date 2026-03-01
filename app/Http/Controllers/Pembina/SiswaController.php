<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::latest()->get();
        return view('pembina.siswa.index', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:siswa,email',
            'password'      => 'required|min:6',
            'nis'           => 'nullable|string',
            'kelas'         => 'nullable|string',
            'alamat'        => 'nullable|string',
            'no_telp'       => 'nullable|string',
            'nama_ayah'     => 'nullable|string',
            'nama_ibu'      => 'nullable|string',
            'no_telp_ayah'  => 'nullable|string',
            'no_telp_ibu'   => 'nullable|string',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        Siswa::create($data);

        return back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:siswa,email,' . $id,
            'nis'           => 'nullable|string',
            'kelas'         => 'nullable|string',
            'alamat'        => 'nullable|string',
            'no_telp'       => 'nullable|string',
            'nama_ayah'     => 'nullable|string',
            'nama_ibu'      => 'nullable|string',
            'no_telp_ayah'  => 'nullable|string',
            'no_telp_ibu'   => 'nullable|string',
        ]);

        $data = $request->all();

        // Password hanya di-hash dan di-update jika diisi oleh user
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']); // Hapus dari array agar tidak menimpa password lama dengan null
        }

        $siswa->update($data);

        return back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return back()->with('success', 'Siswa berhasil dihapus!');
    }

    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('pembina.siswa.show', compact('siswa'));
    }
}