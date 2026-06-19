<!DOCTYPE html>
<html>
<head>
    <title>Jurnal Pembina</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2>
    Jurnal Pembina - {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
</h2>

<p>
    Tahun Ajaran: {{ $tahunAjaran }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Lokasi</th>
            <th>Keterangan</th>
            <th>Kehadiran</th>
        </tr>
    </thead>

    <tbody>
        @foreach($events as $i => $event)
        <tr>
            <td>{{ $i+1 }}</td>

            <td>
                {{ $event['tanggal']->translatedFormat('d F Y') }}
            </td>

            <td>{{ $event['jam'] }}</td>

            <td>{{ $event['lokasi'] }}</td>

            <td>
                @if($event['libur'])
                    <strong style="color:red">
                        {{ $event['keterangan_libur'] }}
                    </strong>
                @else
                    {{ $event['keterangan'] ?: '-' }}
                @endif
            </td>

            <td>
                @if($event['libur'])
                    <strong>LIBUR</strong>
                @else
                    {{ $event['hadir'] }}/{{ $event['total'] }}
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>