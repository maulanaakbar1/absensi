@extends('layouts.app')
@section('title', 'Data Ekstrakurikuler')

@section('content')
<div x-data="{ openAdd: false, openEdit: false, editData: { id: '', nama: '', deskripsi: '' } }">
    
    <div class="mb-8">
        <div class="mb-4">
            <h3 class="text-2xl font-bold text-slate-800">Daftar Ekstrakurikuler</h3>
            <p class="text-slate-500 text-sm">Kelola semua cabang kegiatan ekskul SMKN 1 Talaga.</p>
        </div>

        <div class="flex justify-end">
            <button @click="openAdd = true" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-md shadow-blue-100 flex items-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Ekskul
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ekskuls as $e)
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center overflow-hidden border border-blue-100">
                    @if($e->foto)
                        <img src="{{ asset('storage/'.$e->foto) }}" class="object-cover h-full w-full">
                    @else
                        <span class="text-blue-600 font-bold text-xl">{{ substr($e->nama, 0, 1) }}</span>
                    @endif
                </div>
                <div class="flex gap-1">
                    <button @click="editData = { id: '{{ $e->id }}', nama: '{{ $e->nama }}', deskripsi: '{{ $e->deskripsi }}' }; openEdit = true" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <form action="{{ route('admin.ekskul.destroy', $e->id) }}" method="POST" onsubmit="return confirm('Hapus ekskul ini?')">
                        @csrf @method('DELETE')
                        <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                    </form>
                </div>
            </div>
            <h4 class="text-lg font-bold text-slate-800">{{ $e->nama }}</h4>
            <p class="text-slate-500 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $e->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
        </div>
        @empty
        <div class="col-span-full py-20 text-center opacity-40">
            <p class="text-xl font-medium">Belum ada data Ekstrakurikuler</p>
        </div>
        @endforelse
    </div>

    <div x-show="openAdd" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="openAdd = false" class="bg-white rounded-[2rem] w-full max-w-md p-8 shadow-2xl">
            <h4 class="text-xl font-bold mb-6 text-slate-800">Tambah Ekskul Baru</h4>
            <form action="{{ route('admin.ekskul.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="text" name="nama" placeholder="Nama Ekskul (Contoh: Paskibra)" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-blue-500" required>
                <textarea name="deskripsi" placeholder="Deskripsi Singkat" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:border-blue-500" rows="3"></textarea>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Logo Ekskul</label>
                    <input type="file" name="foto" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openAdd = false" class="flex-1 py-3 font-bold text-slate-400">Batal</button>
                    <button class="flex-[2] bg-blue-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection