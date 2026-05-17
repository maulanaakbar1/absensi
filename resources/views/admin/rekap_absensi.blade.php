@extends('layouts.app') 

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        {{-- Header & Filter --}}
        <div class="p-6 border-b border-slate-100">

            @php
                $isFiltered =
                    request('ekskul') ||
                    request('tahun_ajaran') ||
                    request('kelas') ||
                    request('bulan');
            @endphp

            <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-wrap items-end gap-4">

                {{-- Ekskul --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-600 mb-2">
                        Pilih Ekskul
                    </label>

                    <select
                        name="ekskul"
                        onchange="this.form.submit()"
                        class="w-full border-slate-200 rounded-xl focus:ring-emerald-500">
                        <option value="all">Semua Ekskul</option>

                        @foreach($listEkskul as $item)
                            <option
                                value="{{ $item->id }}"
                                {{ $ekskul == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun Ajaran --}}
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-sm font-semibold text-slate-600 mb-2">
                        Tahun Ajaran
                    </label>

                    <select
                        name="tahun_ajaran"
                        onchange="this.form.submit()"
                        class="w-full border-slate-200 rounded-xl focus:ring-cyan-500">
                        <option value="semua">
                            Semua Tahun Ajaran
                        </option>

                        @foreach($tahunAjaranList as $tahunAjaran)
                            <option
                                value="{{ $tahunAjaran }}"
                                {{ $selectedTahun == $tahunAjaran ? 'selected' : '' }}>
                                {{ $tahunAjaran }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-semibold text-slate-600 mb-2">
                        Kelas
                    </label>

                    <select
                        name="kelas"
                        onchange="this.form.submit()"
                        class="w-full border-slate-200 rounded-xl focus:ring-cyan-500">
                        <option value="">Semua Kelas</option>

                        <option value="10" {{ $selectedKelas == '10' ? 'selected' : '' }}>
                            X
                        </option>

                        <option value="11" {{ $selectedKelas == '11' ? 'selected' : '' }}>
                            XI
                        </option>

                        <option value="12" {{ $selectedKelas == '12' ? 'selected' : '' }}>
                            XII
                        </option>
                    </select>
                </div>

                {{-- Bulan --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-slate-600 mb-2">
                        Pilih Bulan
                    </label>

                    <select
                        name="bulan"
                        onchange="this.form.submit()"
                        class="w-full border-slate-200 rounded-xl focus:ring-emerald-500">
                        @foreach($namaBulan as $key => $name)
                            <option
                                value="{{ $key }}"
                                {{ $bulan == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset --}}
                @if($isFiltered)
                    <a href="{{ route('admin.rekap.index') }}"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-xl font-bold">
                        Reset
                    </a>
                @endif

            </form>
        </div>

        {{-- Legend --}}
        <div class="px-6 py-5 flex flex-wrap gap-8 bg-slate-50/80 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 bg-emerald-100 border border-emerald-200 rounded-md"></div> 
                <span class="text-sm font-bold text-slate-700">Hadir</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 bg-amber-100 border border-amber-200 rounded-md"></div> 
                <span class="text-sm font-bold text-slate-700">Sakit</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 bg-blue-100 border border-blue-200 rounded-md"></div> 
                <span class="text-sm font-bold text-slate-700">Izin</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 bg-slate-400 border border-slate-500 rounded-md"></div> 
                <span class="text-sm font-bold text-slate-700">Alpa</span>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-6 h-6 bg-red-500 border border-red-600 rounded-md"></div> 
                <span class="text-sm font-bold text-slate-700">Libur</span>
            </div>
        </div>

        <div class="p-6">
            <h2 class="text-center text-xl font-bold text-slate-700 mb-6">
                Rekap Absensi Bulan {{ $namaBulan[$bulan] }} {{ $tahun }}
            </h2>
            
            <div class="overflow-x-auto border rounded-xl">
                <table class="min-w-max table-fixed text-sm border-collapse">
                    
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th rowspan="2" class="w-12 border px-2 text-center">No</th>
                            <th rowspan="2" class="w-32 border px-2">NISN</th>
                            <th rowspan="2" class="w-64 border px-2">Nama Siswa</th>
                            <th rowspan="2" class="w-32 border px-2">Kelas</th>
                            <th rowspan="2" class="w-24 border px-2 text-center">Angkatan</th>
                            <th colspan="{{ $jumlahHari }}" class="border text-center text-xs">Tanggal</th>
                            <th colspan="4" class="border text-center text-xs">Total</th>
                        </tr>
                        <tr>
                            @for($i = 1; $i <= $jumlahHari; $i++)
                                <th class="w-10 border text-center text-xs">{{ $i }}</th>
                            @endfor
                            <th class="w-10 border text-center">H</th>
                            <th class="w-10 border text-center">S</th>
                            <th class="w-10 border text-center">I</th>
                            <th class="w-10 border text-center">A</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($siswas as $index => $siswa)
                        @php
                            $totalHadir = 0;
                            $totalSakit = 0;
                            $totalIzin = 0;
                            $totalAlpa = 0;
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="border text-center">{{ $index + 1 }}</td>
                            <td class="border px-2">{{ $siswa->nisn }}</td>
                            <td class="border px-2">{{ $siswa->user->name ?? '-' }}</td>
                            <td class="border px-2 text-center">
                                {{ $siswa->kelas_display }}
                            </td>

                            <td class="border px-2 text-center">
                                {{ $siswa->tahun_masuk ?? '-' }}
                            </td>

                            @for($i = 1; $i <= $jumlahHari; $i++)
                                @php
                                    $tgl = sprintf('%02d', $i);
                                    $fullDate = "$tahun-$bulan-$tgl";

                                    $tanggalCarbon = \Carbon\Carbon::parse($fullDate);
                                    $hari = $tanggalCarbon->translatedFormat('l');

                                    $isLibur = $hariLibur
                                        ->where('ekstrakurikuler_id', $siswa->ekstrakurikuler_id)
                                        ->where('tanggal', $fullDate)
                                        ->isNotEmpty();

                                    $adaJadwal = $jadwals
                                        ->where('ekstrakurikuler_id', $siswa->ekstrakurikuler_id)
                                        ->where('hari', $hari)
                                        ->isNotEmpty();

                                    $absen = $siswa->absensis->firstWhere('tanggal', $fullDate);

                                    $statusColor = '';

                                    if ($isLibur) {
                                        $statusColor = 'bg-red-500 border-red-600';
                                    } elseif (!$adaJadwal) {
                                        $statusColor = 'bg-slate-100';
                                    } elseif ($absen) {
                                        if ($absen->status == 'hadir') {
                                            $statusColor = 'bg-emerald-100 border-emerald-200';
                                            $totalHadir++;
                                        } elseif ($absen->status == 'sakit') {
                                            $statusColor = 'bg-amber-100 border-amber-200';
                                            $totalSakit++;
                                        } elseif ($absen->status == 'izin') {
                                            $statusColor = 'bg-blue-100 border-blue-200';
                                            $totalIzin++;
                                        } elseif ($absen->status == 'alpa') {
                                            $statusColor = 'bg-slate-400 border-slate-500';
                                            $totalAlpa++;
                                        }
                                    }
                                @endphp

                                <td class="border p-0 w-10 h-10">
                                    <div class="w-full h-full flex items-center justify-center {{ $statusColor }}"></div>
                                </td>
                            @endfor

                            <td class="border text-center font-bold">{{ $totalHadir }}</td>
                            <td class="border text-center font-bold">{{ $totalSakit }}</td>
                            <td class="border text-center font-bold">{{ $totalIzin }}</td>
                            <td class="border text-center font-bold">{{ $totalAlpa }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection