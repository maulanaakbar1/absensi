@extends('layouts.app')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Kehadiran</h1>
    </div>

    {{-- LOGIKA PENGECEKAN JADWAL --}}
    @php
        $hariMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariMap[date('l', strtotime($tanggal))];
        $isJadwal = \App\Models\Jadwal::where('ekstrakurikuler_id', auth()->user()->pembina->ekstrakurikuler_id)
                    ->where('hari', $hariIni)->exists();
    @endphp

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('pembina.absensi.manage') }}" method="GET" class="flex items-end gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" 
                    class="rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-lg font-medium hover:bg-slate-700 flex items-center gap-2">
                <span>🔍</span> Filter
            </button>
        </form>
    </div>

    {{-- ALERT JIKA BUKAN HARI JADWAL --}}
    @if(!$isJadwal)
    <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-700">
        <div class="flex items-center">
            <span class="mr-2 text-lg">⚠️</span>
            <p class="text-sm font-medium">
                Hari <strong>{{ $hariIni }}</strong> bukan jadwal rutin ekskul kamu. 
                Tombol absen dinonaktifkan secara otomatis untuk mencegah manipulasi data.
            </p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-bold text-slate-700 text-center w-16">No</th>
                    <th class="px-6 py-4 font-bold text-slate-700">NISN</th>
                    <th class="px-6 py-4 font-bold text-slate-700">Nama Siswa</th>
                    <th class="px-6 py-4 font-bold text-slate-700">Kelas</th>
                    <th class="px-12 py-4 font-bold text-slate-700">Status Kehadiran</th>
                    <th class="px-6 py-4 font-bold text-slate-700">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($siswas as $index => $siswa)
                    @php
                        $absen = $siswa->absensis->first();
                        $status = $absen ? $absen->status : 'belum ada';
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $siswa->nisn }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800 uppercase">{{ $siswa->user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $siswa->kelas }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('pembina.absensi.update') }}" method="POST" class="flex items-center">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                    
                                    {{-- SELECT DINONAKTIFKAN JIKA SUDAH HADIR ATAU BUKAN HARI JADWAL --}}
                                    <select name="status" onchange="this.form.submit()"
                                        @if($status == 'hadir' || !$isJadwal) disabled @endif
                                        class="text-center rounded-lg py-1.5 w-40
                                        {{ ($status == 'hadir' || !$isJadwal) ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'border-slate-200 focus:border-blue-500 focus:ring-blue-500' }}">
                                        <option value="" {{ $status == 'belum ada' ? 'selected' : '' }} disabled>-- Pilih Status --</option>
                                        <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="alpa" {{ $status == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                    </select>
                                </form>
                        </td>
                        <td class="px-6 py-4">
                            @if($status == 'hadir')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md font-bold text-[11px] uppercase">Hadir</span>
                            @elseif($status == 'sakit')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-md font-bold text-[11px] uppercase">Sakit</span>
                            @elseif($status == 'izin')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md font-bold text-[11px] uppercase">Izin</span>
                            @elseif($status == 'alpa')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md font-bold text-[11px] uppercase">Alpa</span>
                            @else
                                <span class="text-slate-400 italic">Belum Ada</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection