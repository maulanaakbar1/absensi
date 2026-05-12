@extends('layouts.app')

@section('title_page', 'Manajemen Hari Libur')

@section('content')
<div class="p-6" x-data="{ openModal: false, editMode: false, currentLibur: {} }">
    {{-- HEADER --}}
    <div class="mb-6 flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Hari Libur Latihan</h1>
                <p class="text-slate-500 text-sm">Daftar tanggal dimana latihan ditiadakan</p>
            </div>
        </div>

        <div class="flex justify-start md:justify-end">
            <button @click="openModal = true; editMode = false; currentLibur = {}" 
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-100 hover:bg-blue-700 transition flex items-center gap-2 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Libur
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-xl border border-emerald-200 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE CONTAINER: Biar bisa digeser (Horizontal Scroll) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[11px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Hari & Tanggal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hariLibur as $libur)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700">
                                {{ \Carbon\Carbon::parse($libur->tanggal)->translatedFormat('l') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($libur->tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-sm font-medium">
                                {{ $libur->keterangan }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button @click="openModal = true; editMode = true; currentLibur = {{ $libur }}" 
                                    class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('pembina.libur.destroy', $libur->id) }}" method="POST" onsubmit="return confirm('Hapus hari libur ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="italic text-sm">Belum ada hari libur yang diatur.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="openModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4" x-cloak x-transition>
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" @click.away="openModal = false">
            <div class="p-8">
                <h3 class="text-xl font-bold text-slate-800 mb-6" x-text="editMode ? 'Edit Hari Libur' : 'Tambah Hari Libur Baru'"></h3>
                
                <form :action="editMode ? `/pembina/hari-libur/${currentLibur.id}` : '{{ route('pembina.libur.store') }}'" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggal Libur</label>
                            <input type="date" name="tanggal" :value="currentLibur.tanggal" 
                                class="w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition py-3" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Keterangan</label>
                            <textarea name="keterangan" x-model="currentLibur.keterangan" rows="3" 
                                class="w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition" 
                                placeholder="Misal: Libur Nasional / Ujian" required></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <button type="button" @click="openModal = false" 
                            class="py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition text-sm">
                            Batal
                        </button>
                        <button type="submit" 
                            class="py-3 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition text-sm">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection