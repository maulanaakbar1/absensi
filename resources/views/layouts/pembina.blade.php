<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-50 min-h-screen flex">

    @include('partials.pembina.sidebar')

    <div class="flex-1 flex flex-col">
        @include('partials.pembina.header')

        <main class="p-8 flex-1">
            @yield('content')
        </main>

        @include('partials.pembina.footer')
    </div>

</body>
</html>