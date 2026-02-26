<nav class="bg-white/20 backdrop-blur-md text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-lg font-semibold">Dashboard Siswa</h1>

    <div class="flex items-center gap-4">
        <span class="text-sm">
            👋 {{ auth('siswa')->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100">
                Logout
            </button>
        </form>
    </div>
</nav>