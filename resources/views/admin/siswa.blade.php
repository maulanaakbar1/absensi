@extends('layouts.app')
@section('title', 'Manajemen Siswa')

@section('content')
<div x-data="{ openModal: false, editMode: false, currentData: {} }" class="space-y-6">
    
    <div class="mb-6">
    <div class="mb-4">
        <h3 class="text-2xl font-bold text-slate-800">Data Siswa Seluruh Ekskul</h3>
        <p class="text-slate-500 text-sm">Kelola seluruh data siswa SMKN 1 Talaga.</p>
    </div>

    <div class="flex justify-end">
        <button @click="openModal = true; editMode = false; currentData = { jk: 'L', ekskul: '{{ $ekskul->first()->id ?? '' }}' }" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-md shadow-blue-100 flex items-center gap-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Siswa
        </button>
    </div>
</div>

    @if(session('success'))
        {{-- Warna alert diubah ke Blue --}}
        <div class="bg-blue-50 border border-blue-200 text-blue-600 px-6 py-4 rounded-2xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">NIS</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">Kelas</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">Ekstrakurikuler</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($anggota as $s)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($s->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-700 leading-none">{{ $s->user->name }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $s->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-600">
                        {{ $s->nis }}
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-600">
                        <span class="text-blue-500 uppercase">{{ $s->kelas }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold">
                            {{ $s->ekstrakurikuler->nama ?? 'Belum Pilih' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button @click="openModal = true; editMode = true; currentData = {
                                id: '{{ $s->id }}',
                                name: '{{ $s->user->name }}',
                                email: '{{ $s->user->email }}',
                                nis: '{{ $s->nis }}',
                                kelas: '{{ $s->kelas }}',
                                jk: '{{ $s->jenis_kelamin }}',
                                ekskul: '{{ $s->ekstrakurikuler_id }}'
                            }" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini? User login juga akan terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Form --}}
    <div x-show="openModal" class="fixed inset-0 z-[99] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div @click="openModal = false" class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"></div>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">
                <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="editMode ? 'Edit Data Siswa' : 'Tambah Siswa Baru'"></h3>
                
                <form :action="editMode ? `/admin/siswa/${currentData.id}` : '{{ route('admin.siswa.store') }}'" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase ml-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="currentData.name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Email</label>
                                <input type="email" name="email" x-model="currentData.email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Password</label>
                                <input type="password" name="password" :placeholder="editMode ? 'Kosongkan jika tidak ubah' : 'Minimal 6 karakter'" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" :required="!editMode">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">NIS</label>
                                <input type="text" name="nis" x-model="currentData.nis" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Kelas</label>
                                <input type="text" name="kelas" x-model="currentData.kelas" placeholder="Contoh: XII RPL 1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" x-model="currentData.jk" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" required>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Ekstrakurikuler</label>
                                <select name="ekstrakurikuler_id" x-model="currentData.ekskul" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 transition" required>
                                    @foreach($ekskul as $e)
                                        <option value="{{ $e->id }}">{{ $e->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 px-4 py-4 rounded-2xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-4 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection