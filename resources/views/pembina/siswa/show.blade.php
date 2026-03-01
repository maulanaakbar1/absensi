@extends('layouts.pembina')

@section('title', 'Detail Siswa')

@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-black text-slate-800 mb-2">
        Biodata Siswa
    </h2>
    <p class="text-slate-500 text-sm">
        Informasi lengkap siswa.
    </p>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">

    {{-- DATA SISWA --}}
    <div class="grid grid-cols-2 gap-6 mb-10">
        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">NIS</p>
            <p class="font-semibold text-slate-700">{{ $siswa->nis ?? '-' }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">Kelas</p>
            <p class="font-semibold text-slate-700">{{ $siswa->kelas ?? '-' }}</p>
        </div>

        <div class="col-span-2 md:col-span-1">
            <p class="text-xs text-slate-400 uppercase font-bold">Nama Lengkap</p>
            <p class="font-semibold text-slate-800 text-lg">{{ $siswa->name }}</p>
        </div>

        <div class="col-span-2 md:col-span-1">
            <p class="text-xs text-slate-400 uppercase font-bold">Email</p>
            <p class="font-semibold text-slate-700">{{ $siswa->email }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">No Telp Siswa</p>
            <p class="font-semibold text-slate-700">{{ $siswa->no_telp ?? '-' }}</p>
        </div>

        {{-- BAGIAN ALAMAT (DIBUAT FULL WIDTH) --}}
        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">Alamat Lengkap</p>
            <p class="font-semibold text-slate-700 leading-relaxed">{{ $siswa->alamat ?? '-' }}</p>
        </div>
    </div>

    <hr class="my-8 border-slate-100">

    {{-- DATA ORANG TUA --}}
    <div class="flex items-center gap-2 mb-6">
        <div class="w-1 h-6 bg-blue-600 rounded-full"></div>
        <h3 class="text-lg font-bold text-slate-800">
            Data Orang Tua
        </h3>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">Nama Ayah</p>
            <p class="font-semibold text-slate-700">{{ $siswa->nama_ayah ?? '-' }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">No Telp Ayah</p>
            <p class="font-semibold text-slate-700">{{ $siswa->no_telp_ayah ?? '-' }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">Nama Ibu</p>
            <p class="font-semibold text-slate-700">{{ $siswa->nama_ibu ?? '-' }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">No Telp Ibu</p>
            <p class="font-semibold text-slate-700">{{ $siswa->no_telp_ibu ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-12 flex items-center justify-between">
        <a href="{{ route('siswa.index') }}"
            class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-8 py-3 rounded-2xl font-bold transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        
        <p class="text-slate-400 text-xs italic">Terdaftar pada: {{ $siswa->created_at->format('d M Y') }}</p>
    </div>

</div>

@endsection