<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        $siswas = Siswa::with(['user','absensis' => function($q) use ($bulan,$tahun){
            $q->whereMonth('tanggal',$bulan)
                ->whereYear('tanggal',$tahun);
        }])
        ->where('ekstrakurikuler_id',$ekskulId)
        ->get();

        $namaBulan = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        return view('pembina.rekap_absensi', compact(
            'siswas','bulan','tahun','jumlahHari','namaBulan'
        ));
    }

    public function updateStatus(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:siswas,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:hadir,izin,sakit,alpha'
    ]);

    $user = auth()->user();
    $ekskulId = $user->pembina->ekstrakurikuler_id;

    $hariInput = Carbon::parse($request->tanggal)->translatedFormat('l'); 
    $hariMap = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $hariEnglish = Carbon::parse($request->tanggal)->format('l');
    $hariIndo = $hariMap[$hariEnglish];

    // 2. Cek apakah ada jadwal ekskul di hari tersebut
    $cekJadwal = \App\Models\Jadwal::where('ekstrakurikuler_id', $ekskulId)
                ->where('hari', $hariIndo)
                ->exists();

    if (!$cekJadwal) {
        return back()->with('error', "Tidak ada jadwal latihan pada hari $hariIndo (" . date('d/m/Y', strtotime($request->tanggal)) . ")");
    }

    // 3. Cek apakah tanggal tersebut terdaftar sebagai Hari Libur Ekskul
    $cekLibur = \App\Models\HariLibur::where('ekstrakurikuler_id', $ekskulId)
                ->whereDate('tanggal', $request->tanggal)
                ->exists();
    
    if ($cekLibur) {
        return back()->with('error', "Gagal absen! Tanggal tersebut ditandai sebagai hari libur ekskul.");
    }

    // --- Logika update/create absensi tetap sama ---
    $absensi = Absensi::where('siswa_id', $request->siswa_id)
        ->whereDate('tanggal', $request->tanggal)
        ->first();

    if ($absensi && $absensi->status === 'hadir') {
        return back()->with('error', 'Status hadir tidak bisa diubah!');
    }

    if ($absensi) {
        $absensi->update(['status' => $request->status]);
    } else {
        Absensi::create([
            'siswa_id' => $request->siswa_id,
            'tanggal'  => $request->tanggal,
            'status'   => $request->status,
        ]);
    }
    return redirect()->back()->with('success', 'Status absensi berhasil diperbarui');
}

    public function manage(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        $siswas = Siswa::with(['user', 'absensis' => function($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        }])
        ->where('ekstrakurikuler_id', $ekskulId)
        ->get();

        return view('pembina.absensi_manage', compact('siswas', 'tanggal'));
    }

    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $ekskulId = $user->pembina->ekstrakurikuler_id;

        $riwayat = Absensi::whereHas('siswa', function($query) use ($ekskulId) {
            $query->where('ekstrakurikuler_id', $ekskulId);
        })
        ->with('siswa.user') 
        ->latest('tanggal')
        ->latest('jam_masuk')
        ->paginate(15); 

        return view('pembina.riwayat_absensi', compact('riwayat'));
    }
    
}