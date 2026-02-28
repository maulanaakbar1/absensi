<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembina;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PembinaController extends Controller
{
    public function index()
    {
        $pembina = Pembina::with('organisasi')->latest()->get();
        $organisasi = Organisasi::all();
        return view('admin.pembina.index', compact('pembina', 'organisasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pembina,email',
            'no_telepon' => 'nullable|string|max:20',
            'password' => 'required|min:6',
            'organisasi_id' => 'required|exists:organisasi,id',
        ]);

        Pembina::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon, 
            'password' => Hash::make($request->password),
            'organisasi_id' => $request->organisasi_id,
        ]);

        return back()->with('success', 'Data Pembina berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pembina = Pembina::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pembina,email,' . $id,
            'no_telepon' => 'nullable|string|max:20',
            'organisasi_id' => 'required|exists:organisasi,id',
        ]);

        $pembina->name = $request->name;
        $pembina->email = $request->email;
        $pembina->no_telepon = $request->no_telepon; 
        $pembina->organisasi_id = $request->organisasi_id;

        if ($request->filled('password')) {
            $pembina->password = Hash::make($request->password);
        }

        $pembina->save();

        return back()->with('success', 'Data Pembina berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Pembina::findOrFail($id)->delete();
        return back()->with('success', 'Data Pembina berhasil dihapus!');
    }
}