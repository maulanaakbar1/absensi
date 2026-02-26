<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembina</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-50 min-h-screen">

    <!-- Navbar -->
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

    <!-- Content -->
    <div class="p-8">

        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Ringkasan Kelas
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-gray-500 text-sm">Jumlah Siswa</h3>
                <p class="text-3xl font-bold text-emerald-600 mt-2">32</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-gray-500 text-sm">Hadir Hari Ini</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">28</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-gray-500 text-sm">Tidak Hadir</h3>
                <p class="text-3xl font-bold text-red-500 mt-2">4</p>
            </div>

        </div>

    </div>

</body>
</html>