@extends('layouts.admin')

@section('title', 'Data Pembina')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Pembina</h2>
        <p class="text-slate-500 text-sm">Daftar akun pembina organisasi yang terdaftar.</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pembina
    </button>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl flex items-center gap-3">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
    <span class="font-medium text-sm">{{ session('success') }}</span>
</div>
@endif

<div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs uppercase tracking-widest font-bold">
            <tr>
                <th class="px-8 py-5">Nama</th>
                <th class="px-8 py-5">Email</th>
                <th>Nomor Telepon</th>
                <th class="px-8 py-5">Organisasi</th>
                <th class="px-8 py-5 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($pembina as $item)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-8 py-5 font-bold text-slate-700">{{ $item->name }}</td>
                <td class="px-8 py-5 text-slate-500 text-sm">{{ $item->email }}</td>
                <td>{{ $item->no_telepon ?? '-' }}</td>
                <td class="px-8 py-5 italic text-blue-600 text-sm font-medium">{{ $item->organisasi->nama_organisasi ?? '-' }}</td>
                <td class="px-8 py-5">
                    <div class="flex justify-center gap-2">
                        {{-- Tombol Edit (Icon Pensil) --}}
                        <button onclick="editPembina({{ $item }})" title="Edit"
                            class="bg-amber-50 text-amber-600 hover:bg-amber-100 p-3 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        {{-- Tombol Hapus (Icon Sampah) --}}
                        <form action="{{ route('pembina.destroy', $item->id) }}" 
                            method="POST" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembina {{ $item->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus"
                                class="bg-red-50 text-red-600 hover:bg-red-100 p-3 rounded-xl transition-colors">
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
                <td colspan="4" class="px-8 py-10 text-center text-slate-400 italic">Belum ada data pembina.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="modal-add" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800">Tambah Pembina Baru</h3>
            <button onclick="toggleModal('modal-add')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('pembina.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="budi@email.com" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon</label>
                <input type="text" name="no_telepon"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Organisasi</label>
                <select name="organisasi_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    <option value="">Pilih Organisasi</option>
                    @foreach($organisasi as $org)
                        <option value="{{ $org->id }}">{{ $org->nama_organisasi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition-all mt-4">Simpan Data</button>
        </form>
    </div>
</div>

<div id="modal-edit" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800">Edit Data Pembina</h3>
            <button onclick="toggleModal('modal-edit')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="edit-form" method="POST" class="p-8 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" id="edit-email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nomor Telepon</label>
                <input type="text" name="no_telepon" id="edit-phone"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Organisasi</label>
                <select name="organisasi_id" id="edit-org" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    @foreach($organisasi as $org)
                        <option value="{{ $org->id }}">{{ $org->nama_organisasi }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Password Baru (Kosongkan jika tidak ganti)</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-amber-500 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-amber-600 transition-all mt-4">Perbarui Data</button>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function editPembina(data) {
        document.getElementById('edit-form').action = `/admin/pembina/${data.id}`;
        document.getElementById('edit-name').value = data.name;
        document.getElementById('edit-email').value = data.email;
        document.getElementById('edit-phone').value = data.no_telepon;
        document.getElementById('edit-org').value = data.organisasi_id;
        toggleModal('modal-edit');
    }
</script>
@endsection