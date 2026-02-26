<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex">

    @include('partials.siswa.sidebar')

    <div class="flex-1 flex flex-col">

        @include('partials.siswa.header')

        <main class="flex-1 flex items-center justify-center px-4">
            @yield('content')
        </main>

        @include('partials.siswa.footer')

    </div>

</body>
</html>