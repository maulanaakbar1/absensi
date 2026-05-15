@extends('layouts.app')
@section('title', 'Data Anggota')

@section('content')
<div x-data="{ openModal: false, editMode: false, currentData: {} }" class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Data Anggota Siswa</h3>
            <p class="text-slate-500 text-sm">Kelola daftar siswa ekstrakurikuler Anda.</p>
        </div>

        <div class="flex justify-start md:justify-end">
            <button 
                @click="openModal = true; editMode = false; currentData = {}" 
                class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md shadow-blue-100 hover:bg-blue-700 transition flex items-center gap-2 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Siswa
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
        <form action="{{ route('pembina.anggota.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            
            <div class="flex-1 w-full">
                <label class="text-xs font-bold text-slate-400 uppercase ml-1">Cari Nama</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Masukkan nama siswa..." 
                    class="w-full mt-1 px-4 py-2.5 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition text-sm"
                >
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-slate-900 transition text-sm flex-1">
                    Filter
                </button>

                @if(request('search'))
                    <a href="{{ route('pembina.anggota.index') }}" class="bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition text-sm flex-1 text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">NISN</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">Kelas</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase">Jenis Kelamin</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-50">
                @forelse($anggota as $s)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($s->user?->name ?? 'S', 0, 1)) }}
                            </div>

                            <span class="font-bold text-slate-700">
                                {{ $s->user?->name ?? '-' }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm font-bold text-slate-600">
                        {{ $s->nisn }}
                    </td>

                    <td class="px-6 py-4 text-sm font-bold text-slate-600">
                        <span class="text-emerald-500 uppercase">
                            {{ $s->kelas }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold">
                            {{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">

                            {{-- Edit --}}
                            <button 
                                @click="
                                    openModal = true;
                                    editMode = true;
                                    currentData = {
                                        id: '{{ $s->id }}',
                                        name: '{{ $s->user?->name ?? '' }}',
                                        email: '{{ $s->user?->email ?? '' }}',
                                        nis: '{{ $s->nis }}',
                                        nisn: '{{ $s->nisn }}',
                                        kelas: '{{ $s->kelas }}',
                                        jk: '{{ $s->jenis_kelamin }}'
                                    }
                                "
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Delete --}}
                            <form action="{{ route('pembina.anggota.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
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
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">
                        Belum ada anggota di ekstrakurikuler Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div x-show="openModal" class="fixed inset-0 z-[99] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div 
                @click="openModal = false" 
                class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm"
            ></div>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8">

                <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="editMode ? 'Edit Data Siswa' : 'Tambah Siswa Baru'"></h3>

                <form :action="editMode ? `/pembina/anggota/${currentData.id}` : '{{ route('pembina.anggota.store') }}'" method="POST">
                    @csrf

                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-4">

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                Nama Lengkap
                            </label>

                            <input 
                                type="text"
                                name="name"
                                x-model="currentData.name"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                                required
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                    Email
                                </label>

                                <input 
                                    type="email"
                                    name="email"
                                    x-model="currentData.email"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                    Password
                                </label>

                                <input 
                                    type="password"
                                    name="password"
                                    :placeholder="editMode ? 'Kosongkan jika tidak ubah' : 'Minimal 6 karakter'"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                                    :required="!editMode"
                                >
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                    NIS
                                </label>

                                <input 
                                    type="text"
                                    name="nis"
                                    x-model="currentData.nis"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                    NISN
                                </label>

                                <input 
                                    type="text"
                                    name="nisn"
                                    x-model="currentData.nisn"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                                    required
                                >
                            </div>

                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                Kelas
                            </label>

                            <input 
                                type="text"
                                name="kelas"
                                x-model="currentData.kelas"
                                placeholder="Contoh: XII RPL 1"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase ml-1">
                                Jenis Kelamin
                            </label>

                            <select 
                                name="jenis_kelamin"
                                x-model="currentData.jk"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                                required
                            >
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-8 flex gap-3">

                        <button 
                            type="button"
                            @click="openModal = false"
                            class="flex-1 px-4 py-4 rounded-2xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit"
                            class="flex-1 px-4 py-4 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition"
                        >
                            Simpan Data
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection