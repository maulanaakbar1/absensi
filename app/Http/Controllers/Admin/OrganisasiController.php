<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;

class OrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::latest()->get();
        return view('admin.organisasi.index', compact('organisasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255|unique:organisasi,nama_organisasi',
            'keterangan' => 'nullable|string',
        ]);

        Organisasi::create($request->only('nama_organisasi','keterangan'));

        return back()->with('success', 'Organisasi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255|unique:organisasi,nama_organisasi,' . $id,
            'keterangan' => 'nullable|string',
        ]);

        $org = Organisasi::findOrFail($id);
        $org->update($request->only('nama_organisasi','keterangan'));

        return back()->with('success', 'Organisasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $org = Organisasi::findOrFail($id);
        
        // Cek apakah ada pembina di organisasi ini sebelum hapus (opsional)
        if($org->pembina()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Masih ada pembina di organisasi ini.');
        }

        $org->delete();
        return back()->with('success', 'Organisasi berhasil dihapus!');
    }
}