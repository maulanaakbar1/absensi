<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    @include('partials.admin.sidebar')

    <div class="flex-1 flex flex-col">
        @include('partials.admin.header')

        <main class="p-8 flex-1">
            @yield('content')
        </main>

        @include('partials.admin.footer')
    </div>

</body>
</html>