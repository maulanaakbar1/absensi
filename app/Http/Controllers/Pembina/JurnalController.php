<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\HariLibur;
use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $ekskulId = auth()->user()
            ->pembina
            ->ekstrakurikuler_id;

        $jadwals = Jadwal::where(
            'ekstrakurikuler_id',
            $ekskulId
        )->get();

        $liburs = HariLibur::where(
            'ekstrakurikuler_id',
            $ekskulId
        )->get();

        $totalAnggota = Siswa::where(
            'ekstrakurikuler_id',
            $ekskulId
        )->count();

        $events = collect();

        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $end = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $hariMap = [
            'Minggu' => 0,
            'Senin'  => 1,
            'Selasa' => 2,
            'Rabu'   => 3,
            'Kamis'  => 4,
            'Jumat'  => 5,
            'Sabtu'  => 6,
        ];

        /*
        |--------------------------------------------------------------------------
        | Jadwal Rutin
        |--------------------------------------------------------------------------
        */
        foreach ($jadwals->whereNull('tanggal') as $jadwal) {

            if (!isset($hariMap[$jadwal->hari])) {
                continue;
            }

            $targetDay = $hariMap[$jadwal->hari];

            $tanggalLoop = $start->copy();

            while ($tanggalLoop <= $end) {

                if ($tanggalLoop->dayOfWeek == $targetDay) {

                    $libur = $this->isLibur($tanggalLoop, $liburs);

                    $events->push(
                        $this->buildEvent(
                            $jadwal,
                            $tanggalLoop->copy(),
                            $totalAnggota,
                            $libur ? true : false,
                            $libur?->keterangan
                        )
                    );
                }

                $tanggalLoop->addDay();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Jadwal Dadakan
        |--------------------------------------------------------------------------
        */
        foreach ($jadwals->whereNotNull('tanggal') as $jadwal) {

            $tanggal = Carbon::parse($jadwal->tanggal);

            if (
                $tanggal->month == $bulan &&
                $tanggal->year == $tahun
            ) {

                $libur = $this->isLibur($tanggal, $liburs);

                    $events->push(
                        $this->buildEvent(
                            $jadwal,
                            $tanggal->copy(),
                            $totalAnggota,
                            $libur ? true : false,
                            $libur?->keterangan
                        )
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hari Libur Dadakan
        |--------------------------------------------------------------------------
        */
        foreach ($liburs->whereNotNull('tanggal') as $libur) {

            $tanggal = Carbon::parse($libur->tanggal);

            if (
                $tanggal->month == $bulan &&
                $tanggal->year == $tahun
            ) {

                $events->push([
                    'tanggal' => $tanggal,
                    'jam' => '-',
                    'lokasi' => '-',
                    'keterangan' => null,
                    'hadir' => 0,
                    'total' => $totalAnggota,
                    'libur' => true,
                    'keterangan_libur' => $libur->keterangan,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hari Libur Rutin
        |--------------------------------------------------------------------------
        */
        foreach ($liburs->whereNull('tanggal') as $libur) {

            $hariMap = [
                'Minggu' => 0,
                'Senin'  => 1,
                'Selasa' => 2,
                'Rabu'   => 3,
                'Kamis'  => 4,
                'Jumat'  => 5,
                'Sabtu'  => 6,
            ];

            if (!isset($hariMap[$libur->hari])) {
                continue;
            }

            $targetDay = $hariMap[$libur->hari];

            $tanggalLoop = $start->copy();

            while ($tanggalLoop <= $end) {

                if ($tanggalLoop->dayOfWeek == $targetDay) {

                    $events->push([
                        'tanggal' => $tanggalLoop->copy(),
                        'jam' => '-',
                        'lokasi' => '-',
                        'keterangan' => null,
                        'hadir' => 0,
                        'total' => $totalAnggota,
                        'libur' => true,
                        'keterangan_libur' => $libur->keterangan,
                    ]);
                }

                $tanggalLoop->addDay();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Urutkan berdasarkan tanggal lalu jam mulai
        |--------------------------------------------------------------------------
        */
        $events = $events
            ->sortBy([
                ['tanggal', 'asc'],
                ['jam', 'asc']
            ])
            ->values();

        return view(
            'pembina.jurnal',
            compact(
                'events',
                'bulan',
                'tahun'
            )
        );
    }

    private function buildEvent(
        $jadwal,
        $tanggal,
        $totalAnggota,
        $isLibur = false,
        $keteranganLibur = null
    )
    {
        $hadir = 0;

        if (!$isLibur) {
            $hadir = Absensi::whereDate(
                    'tanggal',
                    $tanggal->toDateString()
                )
                ->where('status', 'hadir')
                ->count();
        }

        return [
            'tanggal' => $tanggal->copy(),
            'jam' => $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai,
            'lokasi' => $jadwal->lokasi,
            'keterangan' => $jadwal->keterangan,
            'hadir' => $hadir,
            'total' => $totalAnggota,
            'libur' => $isLibur,
            'keterangan_libur' => $keteranganLibur,
        ];
    }

    private function isLibur(
        Carbon $tanggal,
        Collection $liburs
    )
    {
        foreach ($liburs as $libur) {

            if (
                $libur->tanggal &&
                Carbon::parse($libur->tanggal)
                    ->isSameDay($tanggal)
            ) {
                return $libur;
            }

            if (
                $libur->hari &&
                strtolower(trim($libur->hari))
                ==
                strtolower(trim(
                    $tanggal->translatedFormat('l')
                ))
            ) {
                return $libur;
            }
        }

        return null;
    }
}