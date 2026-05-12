@extends('layouts.app')
@section('title', 'Data Anggota')

@section('content')
<div x-data="{ openModal: false, editMode: false, currentData: {} }" class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Data Anggota Siswa</h3>
            <p class="text-slate-500 text-sm">
                Kelola daftar siswa ekstrakurikuler Anda.
            </p>
        </div>

        <button 
            @click="openModal = true; editMode = false; currentData = {}"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-2xl font-bold transition shadow-lg shadow-emerald-100 flex items-center gap-2 w-fit"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>

            Tambah Siswa
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER AUTO --}}
    <div class="bg-white border border-slate-200 rounded-[2rem] p-6 shadow-sm">

        <form 
            action="{{ route('pembina.anggota.index') }}"
            method="GET"
            id="filterForm"
            class="grid grid-cols-1 md:grid-cols-4 gap-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">
                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                    Cari Nama
                </label>

                <input 
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Masukkan nama siswa..."
                    onkeyup="clearTimeout(this.delay); this.delay = setTimeout(() => document.getElementById('filterForm').submit(), 500)"
                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                >
            </div>

            {{-- Tahun Ajaran --}}
            <div>
                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                    Tahun Ajaran
                </label>

                <select 
                    name="tahun_ajaran"
                    onchange="document.getElementById('filterForm').submit()"
                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                >
                    <option value="">Semua Tahun</option>

                    @foreach($listTahun as $tahun)
                        <option 
                            value="{{ $tahun }}"
                            {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}
                        >
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kelas --}}
            <div>
                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                    Kelas
                </label>

                <select 
                    name="kelas"
                    onchange="document.getElementById('filterForm').submit()"
                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0 transition"
                >
                    <option value="">Semua Kelas</option>

                    @foreach($listKelas as $kelas)
                        <option 
                            value="{{ $kelas }}"
                            {{ request('kelas') == $kelas ? 'selected' : '' }}
                        >
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- RESET --}}
            <div class="md:col-span-4 flex justify-end">
                <a 
                    href="{{ route('pembina.anggota.index') }}"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition"
                >
                    Reset
                </a>
            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">
                            Siswa
                        </th>

                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">
                            NISN
                        </th>

                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">
                            Kelas
                        </th>

                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">
                            Tahun Ajaran
                        </th>

                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400">
                            Jenis Kelamin
                        </th>

                        <th class="px-6 py-4 text-xs font-bold uppercase text-slate-400 text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($anggota as $item)

                    @php
                        $siswa = $item->siswa;
                    @endphp

                    <tr class="hover:bg-slate-50/60 transition">

                        {{-- Nama --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="h-11 w-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($siswa->user->name, 0, 1)) }}
                                </div>

                                <div>
                                    <h4 class="font-bold text-slate-700">
                                        {{ $siswa->user->name }}
                                    </h4>

                                    <p class="text-xs text-slate-400">
                                        {{ $siswa->nis }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        {{-- NISN --}}
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                            {{ $siswa->nisn }}
                        </td>

                        {{-- Kelas --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold">
                                {{ $item->kelas }}
                            </span>
                        </td>

                        {{-- Tahun --}}
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                            {{ $item->tahun_ajaran }}
                        </td>

                        {{-- JK --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold">
                                {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">

                                {{-- EDIT --}}
                                <button 
                                    @click="
                                        openModal = true;
                                        editMode = true;

                                        currentData = {
                                            id: '{{ $siswa->id }}',
                                            name: '{{ $siswa->user->name }}',
                                            email: '{{ $siswa->user->email }}',
                                            nis: '{{ $siswa->nis }}',
                                            nisn: '{{ $siswa->nisn }}',
                                            kelas: '{{ $item->kelas }}',
                                            tahun_ajaran: '{{ $item->tahun_ajaran }}',
                                            jk: '{{ $siswa->jenis_kelamin }}'
                                        }
                                    "
                                    class="p-2 rounded-lg text-amber-500 hover:bg-amber-50 transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- DELETE --}}
                                <form 
                                    action="{{ route('pembina.anggota.destroy', $siswa->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus siswa ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                            Belum ada anggota di ekstrakurikuler Anda.
                        </td>
                    </tr>

                    @endforelse

                    </tbody>

            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div 
        x-show="openModal"
        class="fixed inset-0 z-[999] overflow-y-auto"
        x-cloak
    >
        <div class="flex items-center justify-center min-h-screen px-4 py-10">

            {{-- BACKDROP --}}
            <div 
                @click="openModal = false"
                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
            ></div>

            {{-- CARD --}}
            <div 
                class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl p-8"
                @click.away="openModal = false"
            >

                <h3 
                    class="text-2xl font-bold text-slate-800 mb-6"
                    x-text="editMode ? 'Edit Data Siswa' : 'Tambah Siswa Baru'"
                ></h3>

                <form 
                    :action="editMode 
                        ? `/pembina/anggota/${currentData.id}` 
                        : '{{ route('pembina.anggota.store') }}'"
                    method="POST"
                >
                    @csrf

                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-5">

                        <div>
                            <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                Nama Lengkap
                            </label>

                            <input 
                                type="text"
                                name="name"
                                x-model="currentData.name"
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                required
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                    Email
                                </label>

                                <input 
                                    type="email"
                                    name="email"
                                    x-model="currentData.email"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                    Password
                                </label>

                                <input 
                                    type="password"
                                    name="password"
                                    :placeholder="editMode ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter'"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                    :required="!editMode"
                                >
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                    NIS
                                </label>

                                <input 
                                    type="text"
                                    name="nis"
                                    x-model="currentData.nis"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                    NISN
                                </label>

                                <input 
                                    type="text"
                                    name="nisn"
                                    x-model="currentData.nisn"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                    required
                                >
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                    Tahun Ajaran
                                </label>

                                <input 
                                    type="text"
                                    name="tahun_ajaran"
                                    x-model="currentData.tahun_ajaran"
                                    placeholder="2025/2026"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                    required
                                >
                            </div>

                            <div>
                                <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                    Kelas
                                </label>

                                <input 
                                    type="text"
                                    name="kelas"
                                    x-model="currentData.kelas"
                                    placeholder="XI RPL 1"
                                    class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
                                    required
                                >
                            </div>

                        </div>

                        <div>
                            <label class="text-xs uppercase font-bold text-slate-400 ml-1">
                                Jenis Kelamin
                            </label>

                            <select 
                                name="jenis_kelamin"
                                x-model="currentData.jk"
                                class="mt-1 w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-0"
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
                            class="flex-1 px-5 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit"
                            class="flex-1 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition"
                        >
                            Simpan
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection