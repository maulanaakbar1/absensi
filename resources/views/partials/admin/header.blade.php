<nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>

    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">
            👋 {{ auth('admin')->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                Logout
            </button>
        </form>
    </div>
</nav>