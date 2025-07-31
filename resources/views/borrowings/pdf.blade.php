<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Peminjaman - M-Inventory</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-logo {
            display: flex;
            align-items: center;
        }
        .header-logo img {
            height: 60px;
            margin-right: 15px;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .meta-info {
            font-size: 14px;
            text-align: right;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <div>
                <div class="header-title">M-Inventory</div>
                <div style="font-size: 14px;">Sistem Peminjaman Barang</div>
            </div>
        </div>
        <div class="meta-info">
            <p><strong>No. Invoice:</strong> #{{ $borrowing->id }}</p>
            <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
        </div>
    </div>

    <div>
        <p><strong>Peminjam:</strong> {{ $borrowing->user->name }}</p>
        <p><strong>Email:</strong> {{ $borrowing->user->email }}</p>
        <p><strong>Telepon:</strong> {{ $borrowing->user->phone_number ?? '-' }}</p>
    </div>

    <div>
        <p><strong>Status:</strong> {{ $borrowing->status }}</p>
        <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</p>
        <p><strong>Tanggal Kembali:</strong> {{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') : '—' }}</p>

        @if(in_array($borrowing->status, ['Approved', 'Returned']) && $borrowing->approver)
            <p><strong>Disetujui oleh:</strong> {{ $borrowing->approver->name }}</p>
        @endif

        @if($borrowing->status === 'Returned' && $borrowing->returnApprover)
            <p><strong>Diverifikasi oleh:</strong> {{ $borrowing->returnApprover->name }}</p>
        @endif
    </div>

    <div class="section-title">Daftar Item yang Dipinjam</div>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowing->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Catatan:</div>
    <p>Silakan mengembalikan item tepat waktu dan dalam kondisi baik. Jika ada kerusakan, harap konfirmasi ke admin.</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} M-Inventory. Dokumen ini dicetak secara otomatis dan tidak memerlukan tanda tangan basah.</p>
    </div>
</body>
</html>
