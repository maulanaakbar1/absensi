<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed md:static inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 transition-transform duration-300 md:translate-x-0 flex flex-col">
    
    <div class="p-8">
        {{-- TAMBAHKAN TOMBOL SILANG DI SINI --}}
        <div class="flex items-center justify-between md:hidden mb-6">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Menu Navigasi</span>
            <button @click="sidebarOpen = false" class="text-slate-400 hover:text-red-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <span class="text-xl font-bold text-slate-800 tracking-tight">EskulMate</span>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
        
        <a href="{{ route('siswa.dashboard') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('siswa.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-bold text-sm">Dashboard</span>
        </a>

        <a href="{{ route('siswa.absen') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('siswa.absen') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 002-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="font-bold text-sm">Absensi</span>
        </a>

        <a href="{{ route('siswa.absen.riwayat') }}" 
        class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('siswa.absen.riwayat') ? 'bg-blue-50 text-blue-600' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold text-sm">Riwayat Absen</span>
        </a>
        
    </nav>
</aside>