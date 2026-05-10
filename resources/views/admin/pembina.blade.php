@extends('layouts.app')
@section('title', 'Data Pembina')

@section('content')
{{-- Inisialisasi Alpine.js --}}
<div x-data="{ openAdd: false, openEdit: false, editData: { id: '', name: '', nip: '', no_telp: '', ekskul_id: '' } }">
    
    <div class="mb-8">
        <div class="mb-4">
            <h3 class="text-2xl font-bold text-slate-800">Manajemen Pembina</h3>
            <p class="text-slate-500 text-sm">Kelola data pembina ekstrakurikuler sekolah.</p>
        </div>

        <div class="flex justify-end">
            <button @click="openAdd = true" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-md shadow-blue-100 flex items-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Pembina
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs uppercase tracking-wider font-bold text-slate-500">Info Pembina</th>
                        <th class="px-6 py-4 text-xs uppercase tracking-wider font-bold text-slate-500">NIP</th>
                        <th class="px-6 py-4 text-xs uppercase tracking-wider font-bold text-slate-500">Kontak</th>
                        <th class="px-6 py-4 text-xs uppercase tracking-wider font-bold text-slate-500">Ekstrakurikuler</th>
                        <th class="px-6 py-4 text-xs uppercase tracking-wider font-bold text-slate-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembinas as $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 line-clamp-1">{{ $p->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $p->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ $p->nip ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                {{ $p->no_telp ?? 'No Contact' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($p->ekstrakurikuler)
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center overflow-hidden">
                                        @if($p->ekstrakurikuler->foto)
                                            <img src="{{ asset('storage/' . $p->ekstrakurikuler->foto) }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $p->ekstrakurikuler->nama }}</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Edit: Mengisi data ke editData --}}
                                <button @click="editData = { id: '{{ $p->id }}', name: '{{ $p->user->name }}', nip: '{{ $p->nip }}', no_telp: '{{ $p->no_telp }}', ekskul_id: '{{ $p->ekstrakurikuler_id }}' }; openEdit = true"
                                        class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.pembina.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pembina ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
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
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="font-medium text-lg text-slate-500">Belum ada data pembina</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openAdd" 
            class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-cloak>
        <div @click.away="openAdd = false" class="bg-white rounded-[2rem] w-full max-w-md p-8 shadow-2xl relative">
            <h4 class="text-xl font-bold text-slate-800 mb-6">Registrasi Pembina</h4>
            
            <form action="{{ route('admin.pembina.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Informasi Akun</label>
                    <div class="space-y-3">
                        <input type="text" name="name" placeholder="Nama Lengkap" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                        <input type="email" name="email" placeholder="Email (untuk login)" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                        <input type="password" name="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Detail Tambahan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="nip" placeholder="NIP" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
                        <input type="text" name="no_telp" placeholder="No HP" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition">
                    </div>
                </div>

                <select name="ekstrakurikuler_id" class="w-full px-4 py-3 rounded-xl border border-slate-200" required>
                    <option value="">Pilih Ekskul</option>
                    @foreach($ekskuls as $e)
                        <option value="{{ $e->id }}">{{ $e->nama }}</option>
                    @endforeach
                </select>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openAdd = false" class="flex-1 px-4 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="flex-[2] bg-blue-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEdit" 
     class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-cloak>
    
    <div @click.away="openEdit = false" class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl">
        <div class="mb-6">
            <h4 class="text-xl font-bold text-slate-800">Perbarui Data Pembina</h4>
            <p class="text-slate-500 text-xs mt-1">Pastikan data NIP dan kontak sudah benar.</p>
        </div>
        
        <form :action="'{{ url('admin/pembina') }}/' + editData.id" method="POST" class="space-y-4">
            @csrf 
            @method('PUT')
            
            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editData.name" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" 
                           required>
                </div>
                
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" name="nip" x-model="editData.nip" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition"
                           placeholder="Masukkan NIP">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nomor Telepon / WA</label>
                    <input type="text" name="no_telp" x-model="editData.no_telp" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition"
                           placeholder="Contoh: 08123456789">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Penugasan Ekstrakurikuler</label>
                    <select name="ekstrakurikuler_id" 
                            x-model="editData.ekskul_id" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]"
                            style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E');"
                            required>
                        <option value="">Pilih Ekskul</option>
                        @foreach($ekskuls as $e)
                            <option value="{{ $e->id }}">{{ $e->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-6">
                <button type="button" @click="openEdit = false" 
                        class="flex-1 px-4 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition border border-slate-100">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-[2] bg-amber-500 text-white py-3 rounded-xl font-bold shadow-lg shadow-amber-200 hover:bg-amber-600 transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                    </svg>
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection