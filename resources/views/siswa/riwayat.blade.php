@extends('layouts.app')

@section('title_page', 'Riwayat Absensi')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Semua Riwayat Absensi</h3>
                <p class="text-xs text-slate-500">Daftar kehadiran Anda selama ini</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b text-center w-16">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b">Foto</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b">Hari & Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b">Jam Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b">Keterangan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase border-b text-center">Lokasi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($semuaRiwayat as $index => $row)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-sm text-slate-400 text-center font-medium">
                            {{ $semuaRiwayat->firstItem() + $index }}
                        </td>
                        
                        {{-- FOTO --}}
                        <td class="px-6 py-4">
                            @if($row->foto)
                                <button type="button" 
                                    onclick="openImage('{{ asset('storage/' . $row->foto) }}')">
                                    <img src="{{ asset('storage/' . $row->foto) }}" 
                                        class="w-16 h-12 object-cover rounded-lg shadow-sm border hover:scale-110 transition cursor-zoom-in">
                                </button>
                            @else
                                <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400">
                                    TIDAK ADA
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm font-bold text-slate-700">
                            {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $row->jam_masuk }} WIB
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusColor = [
                                    'hadir' => 'bg-emerald-50 text-emerald-600',
                                    'sakit' => 'bg-amber-50 text-amber-600',
                                    'izin'  => 'bg-blue-50 text-blue-600',
                                    'alpa'  => 'bg-red-50 text-red-600',
                                ][$row->status] ?? 'bg-slate-50 text-slate-600';
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase {{ $statusColor }}">
                                {{ $row->status }}
                            </span>
                        </td>

                        {{-- KETERANGAN --}}
                        <td class="px-6 py-4 text-sm">
                            @if($row->keterangan)
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-xs">
                                    {{ $row->keterangan }}
                                </span>
                            @else
                                <span class="text-slate-400 italic text-xs">
                                    Tidak ada
                                </span>
                            @endif
                        </td>

                        {{-- LOKASI --}}
                        <td class="px-6 py-4 text-center">
                            @if($row->lokasi)
                                <a href="https://www.google.com/maps?q={{ $row->lokasi }}" target="_blank" 
                                    class="text-xs font-bold text-blue-600 hover:text-white hover:bg-blue-600 bg-blue-50 px-3 py-1.5 rounded-full">
                                    📍 Maps
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Lokasi Gagal</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            Belum ada data absensi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 bg-slate-50 border-t">
            {{ $semuaRiwayat->links() }}
        </div>
    </div>
</div>

<!-- MODAL -->
<div id="imageModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
    <div class="relative max-w-3xl w-full px-4">
        <button onclick="closeImage()" 
            class="absolute -top-10 right-0 text-white text-2xl">✕</button>

        <img id="modalImage" 
            class="w-full max-h-[80vh] object-contain rounded-xl shadow-lg">
    </div>
</div>

@endsection

<script>
function openImage(src) {
    document.getElementById('modalImage').src = src;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImage() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('click', function(e) {
    if (e.target.id === 'imageModal') {
        closeImage();
    }
});
</script>