<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang - {{ $location->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.6;
        }
        .header {
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            float: left;
            width: 80px;
            height: 80px;
            margin-right: 15px;
        }
        .title {
            font-size: 22px;
            margin: 0;
            padding: 0;
        }
        .subtitle {
            margin: 2px 0;
            font-size: 14px;
        }
        .clear {
            clear: both;
        }
        .section-title {
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <h1 class="title">Laporan Barang</h1>
            <p class="subtitle">Lokasi: <strong>{{ $location->name }}</strong></p>
            <p class="subtitle">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section-title">Daftar Barang</div>
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Kondisi</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->condition }}</td>
                    <td>{{ $item->stock }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada barang di lokasi ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} M-Inventory System. Dokumen ini dicetak otomatis dan tidak memerlukan tanda tangan basah.</p>
    </div>

</body>
</html>
