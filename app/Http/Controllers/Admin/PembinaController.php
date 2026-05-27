<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pembina;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PembinaController extends Controller {
    
    public function index() {
        $pembinas = Pembina::with(['user', 'ekstrakurikuler'])->latest()->get();
        $ekskuls = Ekstrakurikuler::all();

        return view('admin.pembina', compact('pembinas', 'ekskuls'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id', 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembina',
        ]);

        Pembina::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'no_telp' => $request->no_telp,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id, 
        ]);

        return back()->with('success', 'Pembina berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pembina = Pembina::findOrFail($id);
        $user = $pembina->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // update password jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $pembina->update([
            'nip' => $request->nip,
            'no_telp' => $request->no_telp,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id
        ]);

        return back()->with('success', 'Data pembina berhasil diperbarui');
    }

    public function destroy($id) {
        $pembina = Pembina::findOrFail($id);
        $pembina->user->delete(); 
        return back()->with('success', 'Pembina dihapus');
    }
}