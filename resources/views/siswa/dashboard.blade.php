@extends('layouts.siswa')

@section('title', 'Presensi')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl text-center text-gray-800 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-100 rounded-full opacity-50"></div>
        
        <div class="relative z-10">
            <h2 class="text-xl font-semibold text-gray-500">Selamat Pagi,</h2>
            <h3 class="text-3xl font-bold text-gray-800 mb-6">{{ auth('siswa')->user()->name }} 👋</h3>

            <div class="bg-gray-50 rounded-3xl py-6 mb-8 border border-gray-100">
                <p class="text-sm text-gray-400 font-bold uppercase tracking-widest mb-1">Waktu Saat Ini</p>
                <p id="clock" class="text-4xl font-black text-purple-600 tracking-tighter">00:00:00</p>
                <p class="text-xs text-gray-400 mt-1">{{ date('l, d F Y') }}</p>
            </div>

            <p class="text-gray-500 text-sm mb-6 px-4">
                Silakan tekan tombol di bawah untuk mencatat kehadiran Anda hari ini.
            </p>

            <button class="group w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white py-5 rounded-2xl font-bold text-lg shadow-xl shadow-purple-200 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                ABSEN SEKARANG
            </button>

            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="bg-indigo-50 p-4 rounded-2xl text-left">
                    <p class="text-[10px] font-bold text-indigo-400 uppercase leading-tight">Masuk Kelas</p>
                    <p class="text-sm font-bold text-indigo-700">07:30 WIB</p>
                </div>
                <div class="bg-pink-50 p-4 rounded-2xl text-left">
                    <p class="text-[10px] font-bold text-pink-400 uppercase leading-tight">Status Anda</p>
                    <p class="text-sm font-bold text-pink-700">Belum Absen</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-center gap-6">
        <a href="#" class="text-white/80 text-sm font-semibold hover:text-white transition-colors">Lihat Riwayat</a>
        <span class="text-white/30">|</span>
        <a href="#" class="text-white/80 text-sm font-semibold hover:text-white transition-colors">Profil Saya</a>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection