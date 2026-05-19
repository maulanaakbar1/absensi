@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="p-8 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">
                    Detail Siswa
                </h1>

                <p class="text-slate-500 text-sm mt-1">
                    Informasi lengkap anggota ekstrakurikuler
                </p>
            </div>

            <a href="{{ route('pembina.anggota.index') }}"
                class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-bold text-sm">

                Kembali
            </a>
        </div>

        <div class="p-8 space-y-10">

            {{-- Profil --}}
            <div class="flex items-center gap-5">
                <div class="h-20 w-20 rounded-3xl bg-blue-100 text-blue-600 flex items-center justify-center text-3xl font-bold">
                    {{ strtoupper(substr($siswa->user->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-slate-800">
                        {{ $siswa->user->name }}
                    </h2>

                    <p class="text-slate-500">
                        {{ $siswa->user->email }}
                    </p>

                    <div class="mt-2 inline-flex px-3 py-1 rounded-xl bg-blue-50 text-blue-600 text-sm font-bold">
                        {{ $kelasDisplay }}
                    </div>
                </div>
            </div>

            {{-- Data Akademik --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 mb-5">
                    Data Akademik
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            NIS
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->nis }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            NISN
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->nisn }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            No Telp Siswa
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->no_telp_siswa ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            Tahun Masuk
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->tahun_masuk }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            Tahun Ajaran
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $tahunAjaran }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            Jenis Kelamin
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Data Personal --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 mb-5">
                    Data Personal
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            Tempat Lahir
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->tempat_lahir ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            Tanggal Lahir
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold">
                            {{ $siswa->tanggal_lahir
                                ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y')
                                : '-' }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-xs text-slate-400 font-bold uppercase">
                            Alamat
                        </p>

                        <p class="mt-1 text-slate-700 font-semibold leading-relaxed">
                            {{ $siswa->alamat ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- Orang Tua --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 mb-5">
                    Data Orang Tua
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-700 mb-4">
                            Data Ayah
                        </h4>

                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">
                                    Nama Ayah
                                </p>

                                <p class="text-slate-700 font-semibold">
                                    {{ $siswa->nama_ayah ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">
                                    No Telp Ayah
                                </p>

                                <p class="text-slate-700 font-semibold">
                                    {{ $siswa->no_telp_ayah ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-700 mb-4">
                            Data Ibu
                        </h4>

                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">
                                    Nama Ibu
                                </p>

                                <p class="text-slate-700 font-semibold">
                                    {{ $siswa->nama_ibu ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 uppercase font-bold">
                                    No Telp Ibu
                                </p>

                                <p class="text-slate-700 font-semibold">
                                    {{ $siswa->no_telp_ibu ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection