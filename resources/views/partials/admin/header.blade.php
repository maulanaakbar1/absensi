<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-slate-100 px-8 py-4 flex justify-between items-center">
    <div>
        <h1 class="text-sm font-medium text-slate-400">Selamat datang kembali,</h1>
        <p class="text-lg font-bold text-slate-800 italic">Administrator</p>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-3 border-r pr-6">
            <div class="text-right">
                <p class="text-sm font-bold text-slate-700">{{ auth('admin')->user()->name }}</p>
                <p class="text-[10px] text-emerald-500 font-bold uppercase">Online</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm flex items-center justify-center font-bold text-slate-500">
                {{ substr(auth('admin')->user()->name, 0, 1) }}
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="group flex items-center gap-2 text-slate-500 hover:text-red-600 font-medium transition-colors">
                <span class="text-sm">Keluar</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</nav>