@extends('layouts.app')
@section('title', 'Manajemen Siswa')

@section('content')
{{-- Tambahkan variabel search ke dalam x-data --}}
<div x-data="{ 
    openModal: false, 
    editMode: false, 
    search: '',
    currentData: { id: '', name: '', email: '', nis: '', kelas: '', jk: 'L', ekskul: '' } 
}" class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col gap-4 mb-8">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Data Siswa Seluruh Ekskul</h3>
            <p class="text-slate-500 text-sm">Kelola seluruh data siswa SMKN 1 Talaga.</p>
        </div>

        {{-- Tombol Tambah (Disamakan dengan Pembina) --}}
        <div class="flex justify-start md:justify-end">
            <button @click="openModal = true; editMode = false; currentData = { jk: 'L', ekskul: '{{ $ekskul->first()->id ?? '' }}' }" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-100 hover:bg-blue-700 transition flex items-center gap-2 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    {{-- Filter Pencarian (Sama dengan Pembina) --}}
    <div class="mb-6 flex justify-start">
        <div class="relative w-full md:w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" 
                    x-model="search" 
                    placeholder="Cari nama siswa..." 
                    class="pl-10 pr-4 py-2.5 w-full rounded-xl border border-slate-200 focus:ring-0 focus:border-blue-500 outline-none transition text-sm bg-white shadow-sm">
        </div>
    </div>

    @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 text-blue-600 px-6 py-4 rounded-2xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table dengan Logic Search --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Ekstrakurikuler</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($anggota as $s)
                    <tr x-show="'{{ strtolower($s->user->name) }}'.includes(search.toLowerCase())" 
                        x-transition.opacity
                        class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">
                                    {{ strtoupper(substr($s->user->name, 0, 1)) }}
                                </div>
                                <div class="whitespace-nowrap">
                                    <p class="font-bold text-slate-700 text-sm md:text-base">{{ $s->user->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $s->user->email }}</p>
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
                                }" class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini? User login juga akan terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="font-medium text-lg text-slate-500">Belum ada data siswa</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form (Dipercantik agar sama dengan Pembina) --}}
    <div x-show="openModal" 
         class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak>
        
        <div @click.away="openModal = false" class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800" x-text="editMode ? 'Edit Data Siswa' : 'Tambah Siswa Baru'"></h3>
                <p class="text-slate-500 text-xs mt-1">Lengkapi informasi biodata dan akun siswa.</p>
            </div>
            
            <form :action="editMode ? `/admin/siswa/${currentData.id}` : '{{ route('admin.siswa.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-4">
                    {{-- Nama --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="currentData.name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                    </div>

                    {{-- Email & Password --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Email</label>
                            <input type="email" name="email" x-model="currentData.email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Password</label>
                            <input type="password" name="password" :placeholder="editMode ? 'Kosongkan jika tidak ubah' : 'Minimal 6 karakter'" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" :required="!editMode">
                        </div>
                    </div>

                    {{-- NIS & Kelas --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">NIS</label>
                            <input type="text" name="nis" x-model="currentData.nis" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Kelas</label>
                            <input type="text" name="kelas" x-model="currentData.kelas" placeholder="Contoh: XII RPL 1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                        </div>
                    </div>

                    {{-- JK & Ekskul --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" x-model="currentData.jk" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Ekstrakurikuler</label>
                            <select name="ekstrakurikuler_id" x-model="currentData.ekskul" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition" required>
                                @foreach($ekskul as $e)
                                    <option value="{{ $e->id }}">{{ $e->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="openModal = false" class="flex-1 px-4 py-4 rounded-2xl border border-slate-100 font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" 
                            :class="editMode ? 'bg-amber-500 shadow-amber-100 hover:bg-amber-600' : 'bg-blue-600 shadow-blue-100 hover:bg-blue-700'"
                            class="flex-[2] px-4 py-4 rounded-2xl text-white font-bold shadow-lg transition">
                        <span x-text="editMode ? 'Update Data' : 'Simpan Data'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection