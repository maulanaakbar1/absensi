<nav class="bg-emerald-600 text-white px-6 py-4 flex justify-between items-center shadow">
    <h1 class="text-lg font-semibold">Portal Pembina</h1>

    <div class="flex items-center gap-4">
        <span class="text-sm">
            👋 {{ auth('pembina')->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-white text-emerald-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100">
                Logout
            </button>
        </form>
    </div>
</nav>