@extends('layouts.app')
@section('title', 'Dashboard Pembina')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Sisi Kiri: Ucapan Selamat Datang --}}
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Halo, {{ Auth::user()->name }}! 👋</h3>
            <p class="text-slate-500 text-sm mt-1">Selamat datang kembali di panel pembina ekskul.</p>
        </div>

        {{-- Sisi Kanan: Status & Tanggal --}}
        <div class="flex flex-col md:flex-row items-end md:items-center gap-3">
            {{-- Kartu Tanggal --}}
            <div class="bg-white px-5 py-2.5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Hari Ini</span>
                    <span class="text-sm font-bold text-slate-700 leading-none">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </span>
                </div>
            </div>

            {{-- Badge Status --}}
            <div class="bg-white px-4 py-2.5 rounded-2xl border border-slate-200 flex items-center gap-3 shadow-sm">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Status: Pembina Aktif</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Ekskul Card --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="h-32 w-32 rounded-[2rem] bg-blue-600 flex items-center justify-center text-white text-4xl font-black shadow-2xl shadow-blue-200 overflow-hidden">
                    @if($pembina && $pembina->ekstrakurikuler && $pembina->ekstrakurikuler->foto)
                        <img src="{{ asset('storage/' . $pembina->ekstrakurikuler->foto) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($pembina->ekstrakurikuler->nama ?? '?', 0, 1) }}
                    @endif
                </div>
                
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-[0.2em]">Ekskul yang Dibina</span>
                    <h2 class="text-3xl font-black text-slate-800 mt-1 mb-2">
                        {{ $pembina->ekstrakurikuler->nama ?? 'Belum Ditugaskan' }}
                    </h2>
                    <p class="text-slate-500 leading-relaxed max-w-md">
                        {{ $pembina->ekstrakurikuler->deskripsi ?? 'Silahkan hubungi admin untuk menetapkan tugas pembina.' }}
                    </p>
                </div>
            </div>
            <div class="absolute -right-10 -top-10 h-40 w-40 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        {{-- Total Anggota Widget --}}
        <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Anggota</p>
                <h4 class="text-5xl font-black italic">{{ $jumlahSiswa }} <span class="text-xl font-normal not-italic text-slate-500">Siswa</span></h4>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-800 relative z-10">
                {{-- Link ke daftar siswa (sesuaikan route-nya) --}}
                <a href="{{ route('pembina.anggota.index') }}" class="flex items-center justify-between group">
                    <span class="font-bold text-sm">Lihat Daftar Anggota</span>
                    <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center group-hover:bg-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>
            </div>
            {{-- Decorative Icon --}}
            <svg class="absolute right-[-20%] bottom-[-10%] h-48 w-48 text-slate-800 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
            </svg>
        </div>
    </div>

    {{-- Bottom Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Absensi Hari Ini --}}
        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-3xl flex items-start gap-4">
            <div class="h-12 w-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h5 class="font-bold text-slate-800">Kehadiran Hari Ini</h5>
                <p class="text-2xl font-black text-emerald-700">{{ $absensiHariIni }} <span class="text-sm font-medium text-emerald-600/70">Siswa Hadir</span></p>
            </div>
        </div>

        {{-- Jadwal Terdekat --}}
        <div class="bg-blue-50 border border-blue-100 p-6 rounded-3xl flex items-start gap-4">
            <div class="h-12 w-12 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h5 class="font-bold text-slate-800">Jadwal Latihan</h5>
                @if($jadwalTerdekat)
                    <p class="text-sm text-blue-700 mt-1 font-semibold">
                        {{ $jadwalTerdekat->hari }}, 
                        {{ date('H:i', strtotime($jadwalTerdekat->jam_mulai)) }} - {{ date('H:i', strtotime($jadwalTerdekat->jam_selesai)) }} WIB
                    </p>
                    <div class="flex flex-col mt-1">
                        <span class="text-xs text-blue-600 opacity-75 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $jadwalTerdekat->lokasi }}
                        </span>
                        
                        {{-- Tambahan Keterangan --}}
                        @if($jadwalTerdekat->keterangan)
                            <span class="text-xs text-amber-600 font-medium mt-1 flex items-start gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="italic">"{{ $jadwalTerdekat->keterangan }}"</span>
                            </span>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-slate-600 mt-1 italic">Tidak ada jadwal untuk hari ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection