<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Pembina Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            scroll-behavior: smooth; 
        }
        /* Mencegah scroll body saat modal buka */
        .modal-open { overflow: hidden; }
        
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-fade-in-down { animation: fadeInDown 0.3s ease-out; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex">

    @include('partials.pembina.sidebar')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        @include('partials.pembina.header')

        <main class="p-8 flex-1 overflow-y-auto relative contents-wrapper">
            @yield('content')
        </main>

        @include('partials.pembina.footer')
    </div>

</body>
</html>