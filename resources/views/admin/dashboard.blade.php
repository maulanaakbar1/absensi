@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<h2 class="text-2xl font-bold mb-6">Dashboard Overview</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-gray-500 text-sm">Total Siswa</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">120</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-gray-500 text-sm">Total Pembina</h3>
        <p class="text-3xl font-bold text-emerald-600 mt-2">15</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-gray-500 text-sm">Absensi Hari Ini</h3>
        <p class="text-3xl font-bold text-purple-600 mt-2">98</p>
    </div>

</div>

@endsection