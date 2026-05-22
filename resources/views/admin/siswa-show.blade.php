@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

<div class="p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Siswa</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap siswa</p>
        </div>

        <a href="{{ route('admin.siswa.index') }}"
           class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-bold">
            Kembali
        </a>
    </div>

    {{-- CARD --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">

        <div class="flex items-center gap-4 mb-6">

            <div class="h-14 w-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xl">
                {{ strtoupper(substr($siswa->user?->name ?? 'S', 0, 1)) }}
            </div>

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ $siswa->user?->name }}
                </h2>
                <p class="text-slate-500 text-sm">
                    {{ $siswa->user?->email }}
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-slate-400">NIS</p>
                <p class="font-bold">{{ $siswa->nis }}</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-slate-400">NISN</p>
                <p class="font-bold">{{ $siswa->nisn }}</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-slate-400">Kelas</p>
                <p class="font-bold">{{ $kelasDisplay }}</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-slate-400">Tahun Ajaran Masuk</p>
                <p class="font-bold">
                    {{ $siswa->tahun_masuk }}/{{ $siswa->tahun_masuk + 1 }}
                </p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-slate-400">Jurusan</p>
                <p class="font-bold">{{ $siswa->jurusan }}</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl">
                <p class="text-slate-400">Jenis Kelamin</p>
                <p class="font-bold">
                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl md:col-span-2">
                <p class="text-slate-400">Ekstrakurikuler</p>
                <p class="font-bold text-indigo-600">
                    {{ $siswa->ekstrakurikuler->nama ?? '-' }}
                </p>
            </div>

        </div>
    </div>

</div>

@endsection