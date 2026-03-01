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

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">Nama Lengkap</p>
            <p class="font-semibold text-slate-700">{{ $siswa->name }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">Email</p>
            <p class="font-semibold text-slate-700">{{ $siswa->email }}</p>
        </div>

        <div>
            <p class="text-xs text-slate-400 uppercase font-bold">No Telp Siswa</p>
            <p class="font-semibold text-slate-700">{{ $siswa->no_telp ?? '-' }}</p>
        </div>
    </div>

    <hr class="my-6">

    {{-- DATA ORANG TUA --}}
    <h3 class="text-lg font-bold text-slate-700 mb-4">
        Data Orang Tua
    </h3>

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

    <div class="mt-10">
        <a href="{{ route('siswa.index') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold">
            Kembali
        </a>
    </div>

</div>

@endsection