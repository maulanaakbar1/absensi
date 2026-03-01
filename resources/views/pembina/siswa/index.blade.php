@extends('layouts.pembina')

@section('title', 'Data Siswa')

@section('content')

<style>
    .input-style {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        outline: none;
        transition: all 0.2s;
    }
    .input-style:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>

<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-black text-slate-800">Database Siswa</h2>
        <p class="text-slate-500 text-sm">Kelola data siswa yang terdaftar di bimbingan Anda.</p>
    </div>

    <button onclick="toggleModal('modal-add-siswa')" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Tambah Siswa
    </button>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-200 font-bold text-sm flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-8 py-5">NIS</th>
                    <th class="px-8 py-5">Nama</th>
                    <th class="px-8 py-5">Kelas</th>
                    <th class="px-8 py-5">Email</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($siswa as $s)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5 font-semibold text-slate-700">{{ $s->nis ?? '-' }}</td>
                    <td class="px-8 py-5 font-bold text-slate-800">{{ $s->name }}</td>
                    <td class="px-8 py-5 text-slate-600">{{ $s->kelas ?? '-' }}</td>
                    <td class="px-8 py-5 text-sm text-slate-500">{{ $s->email }}</td>
                    <td class="px-8 py-5">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('siswa.show', $s->id) }}" title="Detail"
                                class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-3 rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <button onclick='editSiswa(@json($s))' title="Edit"
                                class="bg-amber-50 text-amber-600 hover:bg-amber-100 p-3 rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data {{ $s->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus" class="bg-red-50 text-red-600 hover:bg-red-100 p-3 rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-slate-500 italic">Belum ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL ADD --}}
<div id="modal-add-siswa" class="fixed inset-0 hidden z-[9999] items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl p-8 shadow-2xl animate-fade-in-down my-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-slate-800">Tambah Siswa</h3>
            <button onclick="toggleModal('modal-add-siswa')" class="text-slate-400 hover:text-red-500 transition-colors text-2xl">&times;</button>
        </div>
        <form action="{{ route('siswa.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nis" placeholder="NIS" class="input-style" required>
                <input type="text" name="kelas" placeholder="Kelas" class="input-style" required>
            </div>
            <textarea name="alamat" placeholder="Alamat Lengkap" class="input-style h-24 resize-none"></textarea>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Nama Lengkap" class="input-style" required>
                <input type="email" name="email" placeholder="Email" class="input-style" required>
            </div>
            <input type="text" name="no_telp" placeholder="No Telp Siswa" class="input-style">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                <input type="text" name="nama_ayah" placeholder="Nama Ayah" class="input-style">
                <input type="text" name="no_telp_ayah" placeholder="Telp Ayah" class="input-style">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nama_ibu" placeholder="Nama Ibu" class="input-style">
                <input type="text" name="no_telp_ibu" placeholder="Telp Ibu" class="input-style">
            </div>
            <div class="flex justify-end gap-3 mt-8 border-t pt-6">
                <button type="button" onclick="toggleModal('modal-add-siswa')" class="px-6 py-2 rounded-xl text-slate-500 font-bold hover:bg-slate-100">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-xl font-bold shadow-lg shadow-blue-200">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit-siswa" class="fixed inset-0 hidden z-[9999] items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl p-8 shadow-2xl animate-fade-in-down my-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-slate-800">Edit Siswa</h3>
            <button onclick="toggleModal('modal-edit-siswa')" class="text-slate-400 hover:text-red-500 transition-colors text-2xl">&times;</button>
        </div>
        <form id="form-edit-siswa" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" id="edit-nis" name="nis" placeholder="NIS" class="input-style" required>
                <input type="text" id="edit-kelas" name="kelas" placeholder="Kelas" class="input-style" required>
            </div>
            <textarea id="edit-alamat" name="alamat" placeholder="Alamat Lengkap" class="input-style h-24 resize-none"></textarea>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" id="edit-name" name="name" placeholder="Nama Lengkap" class="input-style" required>
                <input type="email" id="edit-email" name="email" placeholder="Email" class="input-style" required>
            </div>
            <input type="text" id="edit-no-telp" name="no_telp" placeholder="No Telp Siswa" class="input-style">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                <input type="text" id="edit-nama-ayah" name="nama_ayah" placeholder="Nama Ayah" class="input-style">
                <input type="text" id="edit-no-telp_ayah" name="no_telp_ayah" placeholder="Telp Ayah" class="input-style">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" id="edit-nama-ibu" name="nama_ibu" placeholder="Nama Ibu" class="input-style">
                <input type="text" id="edit-no-telp_ibu" name="no_telp_ibu" placeholder="Telp Ibu" class="input-style">
            </div>
            <div class="flex justify-end gap-3 mt-8 border-t pt-6">
                <button type="button" onclick="toggleModal('modal-edit-siswa')" class="px-6 py-2 rounded-xl text-slate-500 font-bold hover:bg-slate-100">Batal</button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-2 rounded-xl font-bold shadow-lg shadow-amber-200">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id){
        const modal = document.getElementById(id);
        if(modal.classList.contains('hidden')){
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('modal-open');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('modal-open');
        }
    }

    function editSiswa(data){
        document.getElementById('form-edit-siswa').action = `/pembina/siswa/${data.id}`;
        document.getElementById('edit-nis').value = data.nis || '';
        document.getElementById('edit-kelas').value = data.kelas || '';
        document.getElementById('edit-name').value = data.name || '';
        document.getElementById('edit-alamat').value = data.alamat || '';
        document.getElementById('edit-email').value = data.email || '';
        document.getElementById('edit-no-telp').value = data.no_telp || '';
        document.getElementById('edit-nama-ayah').value = data.nama_ayah || '';
        document.getElementById('edit-no-telp_ayah').value = data.no_telp_ayah || '';
        document.getElementById('edit-nama-ibu').value = data.nama_ibu || '';
        document.getElementById('edit-no-telp_ibu').value = data.no_telp_ibu || '';
        toggleModal('modal-edit-siswa');
    }

    // Menutup modal jika klik di area luar (overlay)
    window.onclick = function(event) {
        if (event.target.id.includes('modal-')) {
            toggleModal(event.target.id);
        }
    }
</script>
@endsection