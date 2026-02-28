@extends('layouts.pembina')

@section('title', 'Data Siswa')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-black text-slate-800 italic uppercase tracking-wider">Database Siswa</h2>
        <p class="text-slate-500 text-sm">Kelola data siswa yang terdaftar di bimbingan Anda.</p>
    </div>
    <button onclick="toggleModal('modal-add-siswa')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-emerald-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Siswa
    </button>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 font-bold text-sm">
        ✨ {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-sm border border-emerald-50 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-emerald-50/50 text-emerald-700 text-xs uppercase font-black tracking-widest">
            <tr>
                <th class="px-8 py-5">NIS / Nama</th>
                <th class="px-8 py-5">Kelas</th>
                <th class="px-8 py-5">Email</th>
                <th class="px-8 py-5 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-emerald-50">
            @forelse($siswa as $s)
            <tr class="hover:bg-emerald-50/30 transition-colors group">
                <td class="px-8 py-5">
                    <p class="text-[10px] font-bold text-emerald-500 uppercase">{{ $s->nis ?? 'N/A' }}</p>
                    <p class="font-bold text-slate-700">{{ $s->name }}</p>
                </td>
                <td class="px-8 py-5 text-slate-500 font-medium">{{ $s->kelas ?? '-' }}</td>
                <td class="px-8 py-5 text-slate-400 text-sm">{{ $s->email }}</td>
                <td class="px-8 py-5">
                    <div class="flex justify-center gap-2">
                        <button onclick="editSiswa({{ $s }})" class="p-2 text-emerald-600 hover:bg-emerald-100 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-10 text-center text-slate-400 italic">Belum ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modal-add-siswa" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-emerald-950/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-2xl font-black text-slate-800 mb-6 italic uppercase">Tambah Siswa</h3>
        <form action="{{ route('siswa.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIS</label>
                    <input type="text" name="nis" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Kelas</label>
                    <input type="text" name="kelas" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">No. Telp Siswa</label>
                    <input type="text" name="no_telp" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Telp Ayah</label>
                    <input type="text" name="no_telp_ayah" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-pink-600 uppercase mb-1">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-pink-600 uppercase mb-1">Telp Ibu</label>
                    <input type="text" name="no_telp_ibu" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="toggleModal('modal-add-siswa')" class="px-6 py-3 text-slate-400 font-bold uppercase text-xs">Batal</button>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 uppercase text-xs tracking-widest transition-all">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-siswa" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-emerald-950/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-2xl font-black text-slate-800 mb-6 italic uppercase">Edit Siswa</h3>
        <form id="form-edit-siswa" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIS</label>
                    <input type="text" name="nis" id="edit-nis" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Kelas</label>
                    <input type="text" name="kelas" id="edit-kelas" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="edit-name" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">No. Telp Siswa</label>
                    <input type="text" name="no_telp" id="edit-no-telp" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-emerald-50 pt-4">
                <div>
                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Nama Ayah</label>
                    <input type="text" name="nama_ayah" id="edit-nama-ayah" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Telp Ayah</label>
                    <input type="text" name="no_telp_ayah" id="edit-no-telp-ayah" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-pink-600 uppercase mb-1">Nama Ibu</label>
                    <input type="text" name="nama_ibu" id="edit-nama-ibu" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-pink-600 uppercase mb-1">Telp Ibu</label>
                    <input type="text" name="no_telp_ibu" id="edit-no-telp-ibu" class="w-full px-4 py-2.5 rounded-xl border border-emerald-50 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-emerald-50 pt-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Email</label>
                    <input type="email" name="email" id="edit-email" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Password (Kosongkan jika tetap)</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-emerald-100 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="toggleModal('modal-edit-siswa')" class="px-6 py-3 text-slate-400 font-bold uppercase text-xs">Batal</button>
                <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 uppercase text-xs tracking-widest transition-all">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function editSiswa(data) {
        // Set Action URL
        document.getElementById('form-edit-siswa').action = `/pembina/siswa/${data.id}`;
        
        // Isi Data ke Input dengan ID yang sesuai
        document.getElementById('edit-nis').value = data.nis || '';
        document.getElementById('edit-kelas').value = data.kelas || '';
        document.getElementById('edit-name').value = data.name || '';
        document.getElementById('edit-email').value = data.email || '';
        document.getElementById('edit-no-telp').value = data.no_telp || '';
        document.getElementById('edit-nama-ayah').value = data.nama_ayah || '';
        document.getElementById('edit-no-telp_ayah').value = data.no_telp_ayah || ''; // sesuaikan key database
        document.getElementById('edit-nama-ibu').value = data.nama_ibu || '';
        document.getElementById('edit-no-telp_ibu').value = data.no_telp_ibu || ''; // sesuaikan key database
        
        toggleModal('modal-edit-siswa');
    }
</script>
@endsection