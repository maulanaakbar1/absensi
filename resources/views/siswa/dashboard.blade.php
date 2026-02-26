@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="bg-white p-8 rounded-3xl shadow-2xl text-center max-w-md w-full">
    <h2 class="text-2xl font-bold text-gray-800 mb-3">
        Halo, {{ auth('siswa')->user()->name }} 👋
    </h2>

    <p class="text-gray-600 mb-6">
        Jangan lupa lakukan absensi hari ini sebelum kelas dimulai.
    </p>

    <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg">
        Absen Sekarang
    </button>
</div>

@endsection