<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Portal Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 min-h-screen text-white">

    <div class="flex flex-col md:flex-row min-h-screen">
        <div class="hidden md:block">
            @include('partials.siswa.sidebar')
        </div>

        <div class="flex-1 flex flex-col">
            @include('partials.siswa.header')

            <main class="flex-1 flex items-center justify-center p-6">
                @yield('content')
            </main>

            @include('partials.siswa.footer')
        </div>
    </div>

</body>
</html>