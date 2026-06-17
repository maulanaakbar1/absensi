@extends('layouts.app')

@section('title', 'Jurnal Pembina')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Jurnal Pembina
            </h1>

            <p class="text-slate-500">
                Jurnal otomatis dari jadwal latihan dan absensi
            </p>

        </div>

        <form method="GET" id="filterForm" class="flex flex-wrap gap-2">

            <select
                name="bulan"
                onchange="document.getElementById('filterForm').submit()"
                class="border border-slate-200 rounded-xl px-4 py-2 bg-white">

                @foreach(range(1,12) as $b)

                    <option
                        value="{{ $b }}"
                        {{ $bulan == $b ? 'selected' : '' }}>

                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}

                    </option>

                @endforeach

            </select>

            <select
                name="tahun"
                onchange="document.getElementById('filterForm').submit()"
                class="border border-slate-200 rounded-xl px-4 py-2 bg-white">

                @foreach(range(now()->year-3, now()->year+1) as $t)

                    <option
                        value="{{ $t }}"
                        {{ $tahun == $t ? 'selected' : '' }}>

                        {{ $t }}

                    </option>

                @endforeach

            </select>

            <a
                href="{{ route('pembina.jurnal.index') }}"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition">

                Reset

            </a>

        </form>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-50">

                <tr>

                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Hari / Tanggal</th>
                    <th class="p-4 text-left">Jam</th>
                    <th class="p-4 text-left">Lokasi</th>
                    <th class="p-4 text-left">Keterangan</th>
                    <th class="p-4 text-center">Kehadiran</th>

                </tr>

            </thead>

            <tbody>

                @forelse($events as $index => $event)

                    <tr class="border-t">

                        <td class="p-4">
                            {{ $index + 1 }}
                        </td>

                        <td class="p-4">

                            {{ $event['tanggal']->translatedFormat('l, d F Y') }}

                        </td>

                        <td class="p-4">

                            {{ $event['jam'] }}

                        </td>

                        <td class="p-4">

                            {{ $event['lokasi'] }}

                        </td>

                        <td class="p-4">

                            {{ $event['keterangan'] ?: '-' }}

                        </td>

                        <td class="p-4 text-center">

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">

                                {{ $event['hadir'] }}/{{ $event['total'] }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="p-10 text-center text-slate-400">

                            Belum ada jurnal

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection