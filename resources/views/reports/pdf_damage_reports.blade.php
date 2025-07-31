<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kerusakan Barang</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Kerusakan Barang</h2>
    <p>Tanggal Cetak: {{ $tanggal }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Lokasi</th>
                <th>Pelapor</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Tanggal Lapor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $i => $report)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $report->item->name }}</td>
                    <td>{{ $report->item->location->name ?? '-' }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->status }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
