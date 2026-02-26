<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex">

    @include('partials.admin.sidebar')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        @include('partials.admin.header')

        <main class="p-8 flex-1 overflow-y-auto">
            @yield('content')
        </main>

        @include('partials.admin.footer')
    </div>

</body>
</html>