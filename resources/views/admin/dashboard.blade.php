@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Ringkasan Sistem</h2>
    <p class="text-slate-500 text-sm">Pantau statistik absensi secara real-time.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
        <div class="relative z-10">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Siswa</h3>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold text-slate-800 mt-1">120</p>
                <span class="text-xs text-emerald-500 font-bold">+2 baru</span>
            </div>
        </div>
        <div class="absolute -right-4 -bottom-4 text-slate-50 opacity-10 group-hover:scale-110 transition-transform duration-500">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
        <div class="relative z-10">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Pembina</h3>
            <p class="text-3xl font-bold text-slate-800 mt-1">15</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
        <div class="relative z-10">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Kehadiran Hari Ini</h3>
            <div class="flex items-center gap-2">
                <p class="text-3xl font-bold text-slate-800 mt-1">82%</p>
                <div class="flex-1 h-2 bg-slate-100 rounded-full mt-2 overflow-hidden">
                    <div class="h-full bg-purple-500 rounded-full" style="width: 82%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection