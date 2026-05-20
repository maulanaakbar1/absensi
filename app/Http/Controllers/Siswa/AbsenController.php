<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AbsenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $isComplete = $siswa->nisn && $siswa->alamat && $siswa->nama_ayah;

        $today = Carbon::today();
        $hariIni = $today->translatedFormat('l'); 

        $ekskulId = $siswa->ekstrakurikuler_id;

        $adaJadwal = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariIni)
            ->exists();

        $isLibur = HariLibur::where('ekstrakurikuler_id', $ekskulId)
            ->whereDate('tanggal', $today)
            ->exists();

        $absenHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('siswa.absen', compact(
            'absenHariIni',
            'isComplete',
            'adaJadwal',
            'isLibur'
        ));
    }

    public function store(Request $request)
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa->nisn || !$siswa->alamat || !$siswa->nama_ayah) {
            return redirect()->route('siswa.profile')
                ->with('error', 'Lengkapi biodata dulu sebelum absen!');
        }

        $today = Carbon::today();
        $hariIni = $today->translatedFormat('l');

        $adaJadwal = Jadwal::where('ekstrakurikuler_id', $siswa->ekstrakurikuler_id)
            ->where('hari', $hariIni)
            ->exists();

        if (!$adaJadwal) {
            return back()->with('error', 'Tidak ada jadwal latihan hari ini!');
        }

        $isLibur = HariLibur::where('ekstrakurikuler_id', $siswa->ekstrakurikuler_id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($isLibur) {
            return back()->with('error', 'Hari ini adalah hari libur!');
        }

        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('siswa.absen.riwayat')
                ->with('error', 'Anda sudah absen hari ini!');
        }

        $request->validate([
            'foto' => 'required',
            'lokasi' => 'required',
            'status' => 'required|in:hadir,izin,sakit'
        ]);

        // =======================
        // SIMPAN FOTO
        // =======================

        $image = $request->foto;

        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageName = 'absensi_' . time() . '.jpg';

        Storage::disk('public')->put(
            'absensi/' . $imageName,
            base64_decode($image)
        );

        // URL FOTO PUBLIC
        $fotoUrl = asset('storage/absensi/' . $imageName);

        // =======================
        // SIMPAN ABSENSI
        // =======================

        $absensi = Absensi::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->toTimeString(),

            // simpan path file
            'foto' => 'absensi/' . $imageName,

            'lokasi' => $request->lokasi,
            'status' => $request->status,
            'keterangan' => $request->keterangan ?? 'Absensi Kamera',
        ]);

        $user = Auth::user();

        $pesan = "📸 ABSENSI EKSTRAKURIKULER\n\n";

        $pesan .= "Nama: " . $user->name . "\n";
        $pesan .= "Status: " . strtoupper($request->status) . "\n";
        $pesan .= "Tanggal: " . Carbon::now()->translatedFormat('d F Y') . "\n";
        $pesan .= "Jam: " . Carbon::now()->format('H:i') . " WIB\n";
        $pesan .= "Lokasi GPS:\n";
        $pesan .= $request->lokasi . "\n\n";

        if ($request->keterangan) {
            $pesan .= "Keterangan:\n";
            $pesan .= $request->keterangan . "\n\n";
        }

        $pesan .= "Siswa telah melakukan absensi ekskul.";

        // Kirim ke ayah
        $this->kirimWaAbsensi(
            $siswa->no_telp_ayah,
            $pesan,
            $fotoUrl
        );

        // Kirim ke ibu
        $this->kirimWaAbsensi(
            $siswa->no_telp_ibu,
            $pesan,
            $fotoUrl
        );

        return redirect()->route('siswa.absen.riwayat')
            ->with('success', 'Berhasil melakukan absensi!');
    }

    public function riwayat()
    {
        $siswa = Auth::user()->siswa;

        $semuaRiwayat = Absensi::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('siswa.riwayat', compact('semuaRiwayat'));
    }

    private function kirimWaAbsensi($nomor, $pesan, $foto = null)
    {
        if (!$nomor) {
            return;
        }

        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        $payload = [
            'phone' => $nomor,
            'message' => $pesan,
        ];

        // kirim media
        if ($foto) {
            $payload['media_url'] = $foto;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('WA_API_TOKEN'),
        ])->post(env('WA_API_URL'), $payload);

        dd($response->json());
    }
}