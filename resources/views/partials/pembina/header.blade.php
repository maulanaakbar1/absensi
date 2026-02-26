<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-emerald-100 px-8 py-4 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <div class="h-8 w-1 bg-emerald-500 rounded-full"></div>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Portal Pembina</h1>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-3 bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100">
            <div class="text-right">
                <p class="text-xs font-semibold text-emerald-800">{{ auth('pembina')->user()->name }}</p>
                <p class="text-[10px] text-emerald-600 font-medium">Pembina Aktif</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-200">
                {{ substr(auth('pembina')->user()->name, 0, 1) }}
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</nav>