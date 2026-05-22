<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AbsenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        // Cek biodata
        $isComplete = $siswa->nisn && $siswa->alamat && $siswa->nama_ayah;

        $today = Carbon::today();
        $hariIni = $today->translatedFormat('l');

        $ekskulId = $siswa->ekstrakurikuler_id;

        // CEK JADWAL
        $adaJadwal = Jadwal::where('ekstrakurikuler_id', $ekskulId)
            ->where('hari', $hariIni)
            ->exists();

        // CEK LIBUR
        $isLibur = HariLibur::where('ekstrakurikuler_id', $ekskulId)
            ->whereDate('tanggal', $today)
            ->exists();

        // CEK SUDAH ABSEN
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

        // PROTEK BIODATA
        if (!$siswa->nisn || !$siswa->alamat || !$siswa->nama_ayah) {
            return redirect()->route('siswa.profile')
                ->with('error', 'Lengkapi biodata dulu sebelum absen!');
        }

        $today = Carbon::today();
        $hariIni = $today->translatedFormat('l');

        // CEK JADWAL
        $adaJadwal = Jadwal::where('ekstrakurikuler_id', $siswa->ekstrakurikuler_id)
            ->where('hari', $hariIni)
            ->exists();

        if (!$adaJadwal) {
            return back()->with('error', 'Tidak ada jadwal latihan hari ini!');
        }

        // CEK LIBUR
        $isLibur = HariLibur::where('ekstrakurikuler_id', $siswa->ekstrakurikuler_id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($isLibur) {
            return back()->with('error', 'Hari ini adalah hari libur!');
        }

        // CEK SUDAH ABSEN
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('siswa.absen.riwayat')
                ->with('error', 'Anda sudah absen hari ini!');
        }

        // VALIDASI
        $request->validate([
            'foto' => 'required',
            'lokasi' => 'required',
            'status' => 'required|in:hadir,izin,sakit',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // ==========================
        // SIMPAN FOTO BASE64
        // ==========================

        $fotoPath = null;

        if ($request->foto) {

            $folderPath = public_path('storage/absensi/');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $image_parts = explode(";base64,", $request->foto);

            if (count($image_parts) > 1) {

                $image_base64 = base64_decode($image_parts[1]);

                $fileName = 'absen_' . time() . '_' . uniqid() . '.jpg';

                file_put_contents(
                    $folderPath . $fileName,
                    $image_base64
                );

                $fotoPath = 'absensi/' . $fileName;
            }
        }

        // ==========================
        // STATUS & KETERANGAN
        // ==========================

        $status = strtolower($request->status);

        $keteranganDefault = match ($status) {
            'hadir' => 'Hadir mengikuti kegiatan ekstrakurikuler.',
            'izin'  => 'Izin tidak mengikuti kegiatan ekstrakurikuler.',
            'sakit' => 'Sakit dan tidak dapat mengikuti kegiatan ekstrakurikuler.',
            default => 'Absensi ekstrakurikuler'
        };

        $keterangan = $request->keterangan ?: $keteranganDefault;

        // ==========================
        // FORMAT KELAS
        // ==========================

        $kelas = '-';

        if (!empty($siswa->kelas)) {

            $kelas = $siswa->kelas;

        } elseif (!empty($siswa->jurusan)) {

            $kelas = $siswa->jurusan;

        }

        // ==========================
        // SIMPAN ABSENSI
        // ==========================

        $absensi = Absensi::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->toTimeString(),
            'foto' => $fotoPath,
            'lokasi' => $request->lokasi,
            'status' => $status,
            'keterangan' => $keterangan,
        ]);

        // ==========================
        // TEMPLATE PESAN WA
        // ==========================

        $statusText = strtoupper($status);

        $emojiStatus = match ($status) {
            'hadir' => '✅',
            'izin'  => '🟡',
            'sakit' => '🤒',
            default => '📌'
        };

        $pesan =
            "📢 *NOTIFIKASI ABSENSI EKSTRAKURIKULER*\n\n" .
            "👤 Nama: {$siswa->user->name}\n" .
            "🏫 Kelas: {$kelas}\n" .
            "{$emojiStatus} Status: {$statusText}\n" .
            "📅 Tanggal: " . now()->format('d-m-Y') . "\n" .
            "⏰ Jam: " . now()->format('H:i') . " WIB\n\n" .
            "📝 Keterangan:\n" .
            ($keterangan ?: '-') . "\n\n" .
            "📍 Lokasi:\n" .
            "https://maps.google.com/?q={$request->lokasi}";

        // ==========================
        // KIRIM KE AYAH
        // ==========================

        if ($siswa->no_telp_ayah) {

            $nomorAyah = $this->formatNomor($siswa->no_telp_ayah);

            $this->kirimWhatsapp($nomorAyah, $pesan);

            // ⏳ jeda sebelum kirim ke ibu
            sleep(3);
        }

        // ==========================
        // KIRIM KE IBU
        // ==========================

        if ($siswa->no_telp_ibu) {

            $nomorIbu = $this->formatNomor($siswa->no_telp_ibu);

            $this->kirimWhatsapp($nomorIbu, $pesan);
        }

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

    // ==========================
    // FORMAT NOMOR INDONESIA
    // ==========================

    private function formatNomor($nomor)
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }

    // ==========================
    // KIRIM WHATSAPP
    // ==========================

    private function kirimWhatsapp($nomor, $pesan)
    {
        $token = env('WA_API_TOKEN');

        $client = new Client();

        try {

            $response = $client->post(env('WA_API_URL'), [

                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],

                'json' => [
                    'phone' => $nomor,
                    'message' => $pesan,
                ]

            ]);

            Log::info('WA BERHASIL', [
                'nomor' => $nomor,
                'response' => $response->getBody()->getContents()
            ]);

        } catch (\Exception $e) {

            Log::error('WA GAGAL', [
                'nomor' => $nomor,
                'error' => $e->getMessage()
            ]);
        }
    }
}