@extends('layouts.pembina')

@section('title', 'Data Siswa')

@section('content')

<style>
.input-style {
    width:100%;
    padding:10px;
    border:1px solid #e2e8f0;
    border-radius:12px;
    outline:none;
}
.input-style:focus {
    border-color:#3b82f6;
}
</style>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-black text-slate-800">Database Siswa</h2>
        <p class="text-slate-500 text-sm">Kelola data siswa yang terdaftar di bimbingan Anda.</p>
    </div>

    <button onclick="toggleModal('modal-add-siswa')" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-200">
        Tambah Siswa
    </button>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-200 font-bold text-sm">
    ✨ {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
<table class="w-full text-left">
<thead class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs uppercase font-bold tracking-widest">
<tr>
    <th class="px-8 py-5">NIS</th>
    <th class="px-8 py-5">Nama</th>
    <th class="px-8 py-5">Kelas</th>
    <th class="px-8 py-5">Email</th>
    <th class="px-8 py-5 text-center">Aksi</th>
</tr>
</thead>

<tbody class="divide-y divide-slate-50">
@forelse($siswa as $s)
<tr class="hover:bg-slate-50/50 transition-colors">

<td class="px-8 py-5 font-bold">{{ $s->nis ?? '-' }}</td>
<td class="px-8 py-5 font-bold">{{ $s->name }}</td>
<td class="px-8 py-5">{{ $s->kelas ?? '-' }}</td>
<td class="px-8 py-5 text-sm text-slate-400">{{ $s->email }}</td>

<td class="px-8 py-5">
<div class="flex justify-center gap-3">

<a href="{{ route('siswa.show', $s->id) }}"
    class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
    Detail
</a>

<button onclick='editSiswa(@json($s))' 
    class="text-amber-500 hover:text-amber-600 font-semibold text-sm">
    Edit
</button>

<form action="{{ route('siswa.destroy', $s->id) }}" 
    method="POST" 
    onsubmit="return confirm('Hapus siswa ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" 
        class="text-red-500 hover:text-red-600 font-semibold text-sm">
        Hapus
    </button>
</form>

</div>
</td>

</tr>
@empty
<tr>
<td colspan="5" class="p-10 text-center text-slate-400 italic">
    Belum ada data siswa.
</td>
</tr>
@endforelse
</tbody>
</table>
</div>

{{-- ================= MODAL ADD ================= --}}
<div id="modal-add-siswa" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-slate-900/50 p-4">
<div class="bg-white rounded-[2rem] w-full max-w-2xl p-8 shadow-2xl">

<h3 class="text-2xl font-black mb-6 uppercase">Tambah Siswa</h3>

<form action="{{ route('siswa.store') }}" method="POST" class="space-y-4">
@csrf

<div class="grid grid-cols-2 gap-4">
<input type="text" name="nis" placeholder="NIS" class="input-style" required>
<input type="text" name="kelas" placeholder="Kelas" class="input-style" required>
</div>

<div class="grid grid-cols-2 gap-4">
<input type="text" name="name" placeholder="Nama Lengkap" class="input-style" required>
<input type="email" name="email" placeholder="Email" class="input-style" required>
</div>

<input type="text" name="no_telp" placeholder="No Telp Siswa" class="input-style">

<div class="grid grid-cols-2 gap-4 border-t pt-4">
<input type="text" name="nama_ayah" placeholder="Nama Ayah" class="input-style">
<input type="text" name="no_telp_ayah" placeholder="Telp Ayah" class="input-style">
</div>

<div class="grid grid-cols-2 gap-4">
<input type="text" name="nama_ibu" placeholder="Nama Ibu" class="input-style">
<input type="text" name="no_telp_ibu" placeholder="Telp Ibu" class="input-style">
</div>

<div class="flex justify-end gap-3 mt-6">
<button type="button" onclick="toggleModal('modal-add-siswa')" class="text-slate-400 font-bold">Batal</button>
<button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold">Simpan</button>
</div>

</form>
</div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modal-edit-siswa" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-slate-900/50 p-4">
<div class="bg-white rounded-[2rem] w-full max-w-2xl p-8 shadow-2xl">

<h3 class="text-2xl font-black mb-6 uppercase">Edit Siswa</h3>

<form id="form-edit-siswa" method="POST" class="space-y-4">
@csrf
@method('PUT')

<div class="grid grid-cols-2 gap-4">
<input type="text" id="edit-nis" name="nis" class="input-style">
<input type="text" id="edit-kelas" name="kelas" class="input-style">
</div>

<div class="grid grid-cols-2 gap-4">
<input type="text" id="edit-name" name="name" class="input-style">
<input type="email" id="edit-email" name="email" class="input-style">
</div>

<input type="text" id="edit-no-telp" name="no_telp" class="input-style">

<div class="grid grid-cols-2 gap-4 border-t pt-4">
<input type="text" id="edit-nama-ayah" name="nama_ayah" class="input-style">
<input type="text" id="edit-no-telp_ayah" name="no_telp_ayah" class="input-style">
</div>

<div class="grid grid-cols-2 gap-4">
<input type="text" id="edit-nama-ibu" name="nama_ibu" class="input-style">
<input type="text" id="edit-no-telp_ibu" name="no_telp_ibu" class="input-style">
</div>

<div class="flex justify-end gap-3 mt-6">
<button type="button" onclick="toggleModal('modal-edit-siswa')" class="text-slate-400 font-bold">Batal</button>
<button type="submit" class="bg-amber-500 text-white px-6 py-2 rounded-xl font-bold">Update</button>
</div>

</form>
</div>
</div>

<script>
function toggleModal(id){
    document.getElementById(id).classList.toggle('hidden');
}

function editSiswa(data){
    document.getElementById('form-edit-siswa').action = `/pembina/siswa/${data.id}`;

    document.getElementById('edit-nis').value = data.nis || '';
    document.getElementById('edit-kelas').value = data.kelas || '';
    document.getElementById('edit-name').value = data.name || '';
    document.getElementById('edit-email').value = data.email || '';
    document.getElementById('edit-no-telp').value = data.no_telp || '';
    document.getElementById('edit-nama-ayah').value = data.nama_ayah || '';
    document.getElementById('edit-no-telp_ayah').value = data.no_telp_ayah || '';
    document.getElementById('edit-nama-ibu').value = data.nama_ibu || '';
    document.getElementById('edit-no-telp_ibu').value = data.no_telp_ibu || '';

    toggleModal('modal-edit-siswa');
}
</script>

@endsection