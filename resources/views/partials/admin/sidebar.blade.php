<aside class="w-72 bg-slate-900 text-slate-300 min-h-screen sticky top-0 shadow-xl">
    <div class="p-8">
        <div class="flex items-center gap-3 mb-10">
            <div class="bg-blue-500 p-2 rounded-lg text-white font-bold">AB</div>
            <h2 class="text-xl font-extrabold tracking-wider text-white">ABSENSI<span class="text-blue-500">PRO</span></h2>
        </div>

        <nav class="space-y-2">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Menu Utama</p>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-900/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('organisasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="font-medium">Data Organisasi</span>
            </a>

            <a href="{{ route('pembina.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">Data Pembina</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Laporan Absensi</span>
            </a>
        </nav>
    </div>
</aside>