<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed md:static inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 transition-transform duration-300 md:translate-x-0 flex flex-col">
    
    <div class="p-8">

        {{-- HEADER SIDEBAR MOBILE --}}
        <div class="flex items-center justify-between">
            
            @php
                $siswaLogin = \App\Models\Siswa::with('ekstrakurikuler')
                    ->where('user_id', Auth::id())
                    ->first();

                $ekskul = $siswaLogin?->ekstrakurikuler;

                $logo = $ekskul?->foto
                    ? asset('storage/' . $ekskul->foto)
                    : asset('images/braga1.jpeg');

                $namaSatuan = $ekskul?->nama_satuan
                    ?? 'BRAGA';

                // WARNA DINAMIS
                $warna = match(strtolower($namaSatuan)) {

                    'pramuka' => [
                        'text' => 'text-amber-700',
                        'shadow' => 'shadow-amber-200',
                    ],

                    'pmr' => [
                        'text' => 'text-red-700',
                        'shadow' => 'shadow-red-200',
                    ],

                    'paskibra' => [
                        'text' => 'text-slate-800',
                        'shadow' => 'shadow-slate-300',
                    ],

                    default => [
                        'text' => 'text-blue-700',
                        'shadow' => 'shadow-blue-200',
                    ],
                };
            @endphp

            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                
                <div class="h-12 w-12 rounded-xl overflow-hidden shadow-lg {{ $warna['shadow'] }} bg-white border border-slate-100">

                    <img 
                        src="{{ $logo }}"
                        alt="{{ $namaSatuan }}"
                        class="h-full w-full object-cover">

                </div>

                <div class="leading-tight">

                    <h1 class="text-xl font-extrabold tracking-tight {{ $warna['text'] }}">
                        {{ $namaSatuan }}
                    </h1>

                    <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold mt-1">
                        Siswa Panel
                    </p>

                </div>

            </div>

            {{-- TOMBOL CLOSE --}}
            <button 
                @click="sidebarOpen = false"
                class="md:hidden h-10 w-10 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 transition">
                
                <svg xmlns="http://www.w3.org/2000/svg" 
                    class="h-6 w-6" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor">
                    
                    <path stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="2" 
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

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