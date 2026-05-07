@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    {{-- Sisi Kiri: Ucapan Selamat Datang --}}
    <div>
        <h3 class="text-2xl font-bold text-slate-800">Halo, {{ Auth::user()->name }}! 👋</h3>
        <p class="text-slate-500 text-sm">Selamat datang di panel siswa AbsensiPro.</p>
    </div>

    {{-- Sisi Kanan: Hari & Tanggal --}}
    <div class="bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">Hari Ini</span>
            <span class="text-sm font-bold text-slate-700 leading-none">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kehadiran</p>
                <h4 class="text-2xl font-bold text-slate-800">0</h4>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="h-12 w-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ekstrakurikuler</p>
                <h4 class="text-2xl font-bold text-slate-800">Siswa</h4>
            </div>
        </div>
    </div>
</div>

<div class="bg-blue-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-blue-200">
    <div class="relative z-10">
        <h4 class="text-xl font-bold mb-2">Informasi Penting</h4>
        <p class="text-blue-100 text-sm max-w-md">Jangan lupa untuk melakukan absensi setiap kali mengikuti kegiatan ekstrakurikuler di SMKN 1 Talaga.</p>
    </div>
    <div class="absolute top-0 right-0 -mr-16 -mt-16 h-64 w-64 bg-blue-500 rounded-full opacity-50"></div>
</div>
@endsection