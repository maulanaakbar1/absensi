<aside class="w-72 bg-slate-900 text-slate-300 min-h-screen sticky top-0 shadow-xl flex flex-col">
    <div class="p-8 flex-1">
        {{-- Branding Samain Persis --}}
        <div class="flex items-center gap-3 mb-10">
            <div class="bg-blue-500 p-2 rounded-lg text-white font-bold text-sm">AB</div>
            <h2 class="text-xl font-extrabold tracking-wider text-white">ABSENSI<span class="text-blue-500">PRO</span></h2>
        </div>

        <nav class="space-y-2">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Menu Utama</p>
            
            {{-- Dashboard --}}
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-900/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- Absensi Saya --}}
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="font-medium">Absensi Saya</span>
            </a>

            {{-- Riwayat --}}
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 hover:text-white transition-all group">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Riwayat</span>
            </a>
        </nav>
    </div>

    {{-- Info Box ala Admin --}}
    <div class="p-6 mt-auto">
        <div class="bg-slate-800/50 rounded-2xl p-4 border border-slate-700/50">
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mb-2">Bantuan IT</p>
            <p class="text-xs text-slate-400 leading-relaxed">Hubungi admin sekolah jika ada kendala absen.</p>
        </div>
    </div>
</aside>