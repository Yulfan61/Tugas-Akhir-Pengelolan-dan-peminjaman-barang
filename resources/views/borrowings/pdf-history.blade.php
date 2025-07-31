<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - Riwayat Peminjaman</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #444;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header small {
            font-size: 12px;
            color: #666;
        }

        .content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
        }

        .footer {
            text-align: right;
            margin-top: 40px;
            font-size: 11px;
            color: #888;
        }

        ul {
            margin: 0;
            padding-left: 1em;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Riwayat Peminjaman Barang</h1>
        <small>Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</small>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Nama Peminjam</th>
                    <th>Status</th>
                    <th>Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowings as $index => $borrowing)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                        <td>{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</td>
                        <td>{{ $borrowing->user->name }}</td>
                        <td>{{ $borrowing->status }}</td>
                        <td>
                            <ul>
                                @foreach($borrowing->items as $item)
                                    <li>{{ $item->name }} (x{{ $item->pivot->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            &copy; {{ date('Y') }} M-Inventory | Sistem Informasi Peminjaman Barang
        </div>
    </div>

</body>
</html>
