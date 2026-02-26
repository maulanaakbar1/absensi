@extends('layouts.admin')

@section('title', 'Data Organisasi')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Daftar Organisasi</h2>
        <p class="text-slate-500 text-sm">Kelola kategori organisasi/ekskul di sistem.</p>
    </div>
    <button onclick="toggleModal('modal-add-org')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Organisasi
    </button>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 italic text-sm font-medium">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 italic text-sm font-medium">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-widest font-bold">
            <tr>
                <th class="px-8 py-5">Nama Organisasi</th>
                <th class="px-8 py-5 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($organisasi as $org)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-8 py-5 font-bold text-slate-700">{{ $org->nama_organisasi }}</td>
                <td class="px-8 py-5 flex justify-center gap-2">
                    <button onclick="editOrg({{ $org }})" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('organisasi.destroy', $org->id) }}" method="POST" onsubmit="return confirm('Hapus organisasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="2" class="p-10 text-center text-slate-400 italic">Data kosong.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modal-add-org" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl transition-all">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Tambah Organisasi</h3>
        <form action="{{ route('organisasi.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Organisasi</label>
                <input type="text" name="nama_organisasi" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Eks: OSIS, Pramuka..." required>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="toggleModal('modal-add-org')" class="px-6 py-2 text-slate-500 font-bold hover:text-slate-700">Batal</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-org" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl transition-all">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Edit Organisasi</h3>
        <form id="form-edit-org" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Organisasi</label>
                <input type="text" name="nama_organisasi" id="edit-nama-org" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-amber-500" required>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="toggleModal('modal-edit-org')" class="px-6 py-2 text-slate-500 font-bold hover:text-slate-700">Batal</button>
                <button type="submit" class="bg-amber-500 text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:bg-amber-600">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function editOrg(data) {
        document.getElementById('form-edit-org').action = `/admin/organisasi/${data.id}`;
        document.getElementById('edit-nama-org').value = data.nama_organisasi;
        toggleModal('modal-edit-org');
    }
</script>
@endsection