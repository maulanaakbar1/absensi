@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    {{-- Header Admin --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Panel Utama 🚀</h3>
            <p class="text-slate-500 text-sm mt-1">Ringkasan aktivitas sistem AbsensiPro hari ini.</p>
        </div>

        <div class="bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xs font-bold text-slate-400 uppercase">Hari Ini</span>
                <span class="text-sm font-bold text-slate-700">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Card Total Siswa --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Siswa</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalSiswa }}</h3>
        </div>

        {{-- Card Total Ekskul --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-running text-xl"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Ekstrakurikuler</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalEkskul }}</h3>
        </div>

        {{-- Card Total Pembina --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Pembina</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPembina }}</h3>
        </div>

        {{-- Card Kehadiran --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="h-12 w-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Kehadiran Hari Ini</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $persentaseHadir }}%</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Diagram Grafik --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-bold text-slate-800">Tren Kehadiran (7 Hari Terakhir)</h4>
            </div>
            <div class="h-[300px]">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        {{-- Banner / Info Cepat --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-8 text-white relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold italic mb-4">AbsensiPro v2.0</h2>
                <p class="text-blue-100 text-sm leading-relaxed">
                    Sistem otomatisasi kehadiran ekstrakurikuler SMKN 1 Talaga. Pantau data kapan saja secara akurat.
                </p>
            </div>
            <button class="relative z-10 mt-6 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white py-3 rounded-2xl font-bold transition-all text-sm border border-white/30">
                Download Laporan PDF
            </button>
            <div class="absolute -right-8 -bottom-8 opacity-10">
                <i class="fas fa-bolt text-[12rem]"></i>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(94, 114, 228, 0.2)'); 
    gradient.addColorStop(1, 'rgba(94, 114, 228, 0)');  

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Siswa Hadir',
                data: {!! json_encode($values) !!},
                borderColor: '#5e72e4', 
                borderWidth: 3,
                fill: true,
                backgroundColor: gradient,
                tension: 0.4,           
                pointRadius: 0,         
                pointHoverRadius: 5,    
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } 
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,   
                        display: true,
                        color: '#e9ecef',     
                        borderDash: [5, 5]  
                    },
                    ticks: {
                        stepSize: 1 
                    }
                },
                x: {
                    grid: {
                        display: false,       
                        drawBorder: false
                    },
                    ticks: {
                        display: true,
                        color: '#adb5bd',
                        padding: 10,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>
@endpush