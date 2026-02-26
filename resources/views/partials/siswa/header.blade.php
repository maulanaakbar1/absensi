<nav class="glass mx-4 mt-4 rounded-2xl px-6 py-4 flex justify-between items-center shadow-lg">
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
            <span class="text-purple-600 font-bold text-sm">S</span>
        </div>
        <h1 class="text-lg font-bold tracking-tight">Dashboard Siswa</h1>
    </div>

    <div class="flex items-center gap-4">
        <span class="hidden md:inline text-sm font-medium">👋 {{ auth('siswa')->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-white/20 hover:bg-white/40 p-2 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</nav>