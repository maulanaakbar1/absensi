@extends('layouts.pembina')

@section('title', 'Dashboard Pembina')

@section('content')
<div class="flex justify-between items-end mb-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 italic">Ringkasan Kelas</h2>
        <p class="text-slate-500 mt-1">Pantau perkembangan kehadiran siswa bimbingan Anda.</p>
    </div>
    <div class="text-right">
        <p class="text-sm font-bold text-emerald-600">{{ date('d M Y') }}</p>
        <p class="text-xs text-slate-400">Update terakhir: 08:00 WIB</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-emerald-50 group hover:border-emerald-200 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">Aktif</span>
        </div>
        <h3 class="text-slate-500 font-medium italic">Jumlah Siswa</h3>
        <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight">32</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-emerald-50 group hover:border-blue-200 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">87%</span>
        </div>
        <h3 class="text-slate-500 font-medium italic">Hadir Hari Ini</h3>
        <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight">28</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-emerald-50 group hover:border-red-200 transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-50 rounded-2xl text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-lg">Perlu Cek</span>
        </div>
        <h3 class="text-slate-500 font-medium italic">Tidak Hadir</h3>
        <p class="text-4xl font-black text-slate-800 mt-2 tracking-tight">4</p>
    </div>
</div>

<div class="mt-10 bg-white rounded-3xl border border-emerald-50 p-8">
    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
        <span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
        Siswa Belum Absen
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-slate-400 text-sm border-b">
                    <th class="pb-4 font-medium">Nama Siswa</th>
                    <th class="pb-4 font-medium">Status</th>
                    <th class="pb-4 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-600">
                <tr class="border-b last:border-0">
                    <td class="py-4 font-semibold">Andi Hermawan</td>
                    <td class="py-4"><span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold uppercase">Alfa</span></td>
                    <td class="py-4"><button class="text-emerald-600 font-bold text-sm hover:underline">Hubungi</button></td>
                </tr>
                </tbody>
        </table>
    </div>
</div>
@endsection