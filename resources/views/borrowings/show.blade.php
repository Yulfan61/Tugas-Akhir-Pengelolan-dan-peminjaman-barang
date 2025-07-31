<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Detail Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Informasi Umum --}}
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Informasi Peminjaman</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-200">
                        <p><strong>Peminjam:</strong> {{ $borrowing->user->name }}</p>

                        <p><strong>Status:</strong>
                            <span class="px-2 py-1 rounded text-white
                                @switch($borrowing->status)
                                    @case('Pending') bg-orange-500 @break
                                    @case('Approved') bg-blue-500 @break
                                    @case('Rejected') bg-red-500 @break
                                    @case('Returned') bg-green-500 @break
                                    @case('Waiting Approval') bg-purple-500 @break
                                    @default bg-gray-500
                                @endswitch
                            ">
                                {{ $borrowing->status }}
                            </span>
                        </p>

                        @if(in_array($borrowing->status, ['Approved', 'Returned']) && $borrowing->approver)
                            <p><strong>Disetujui oleh:</strong> {{ $borrowing->approver->name }}</p>
                        @endif

                        @if($borrowing->status === 'Returned' && $borrowing->returnApprover)
                            <p><strong>Diverifikasi oleh:</strong> {{ $borrowing->returnApprover->name }}</p>
                        @endif

                        <p><strong>Tanggal Pinjam:</strong>
                            {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                        </p>
                        <p><strong>Tanggal Kembali:</strong>
                            {{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') : '—' }}
                        </p>

                        @if($borrowing->penalty > 0 && $borrowing->penalty_status === 'Unpaid')
    <div class="mt-4 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 p-4 rounded shadow-sm">
        <h4 class="font-semibold mb-2">Informasi Pembayaran Penalti</h4>
        <p>Silakan transfer penalti ke rekening berikut:</p>
        <ul class="mt-2 text-sm space-y-1">
            <li><strong>Bank:</strong> BNI</li>
            <li><strong>No. Rekening:</strong> 1234567890</li>
            <li><strong>Atas Nama:</strong> PT M-Inventory</li>
        </ul>
    </div>
@endif


                        @if($borrowing->penalty > 0)
    <p><strong>Status Penalti:</strong>
        <span class="font-medium 
            @if($borrowing->penalty_status === 'Paid') text-green-500 
            @else text-yellow-500 
            @endif">
            {{ ucfirst($borrowing->penalty_status) }}
        </span>
    </p>
@endif

                    </div>

                    {{-- Form Pembayaran Penalti --}}
                    @if($borrowing->penalty > 0 && $borrowing->penalty_status === 'Unpaid')
                        <div class="mt-6">
                            <form action="{{ route('borrowings.penalty.pay', $borrowing) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Unggah Bukti Pembayaran Penalti:</label>
                                <input type="file" name="penalty_proof" class="block w-full text-sm mb-2" accept="image/*" required>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Bayar Penalti
                                </button>
                            </form>
                        </div>
                    @elseif($borrowing->penalty_proof)
                        <div class="mt-6">
                            <p class="font-semibold text-gray-700 dark:text-gray-200">Bukti Pembayaran Penalti:</p>
                            <img src="{{ asset('storage/' . $borrowing->penalty_proof) }}"
                                 alt="Bukti Pembayaran"
                                 class="mt-2 w-64 h-auto rounded border shadow">
                        </div>
                    @endif
                </div>

                {{-- Daftar Item --}}
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Daftar Barang yang Dipinjam:</h4>
                    <ul class="list-disc list-inside text-gray-700 dark:text-gray-200">
                        @foreach($borrowing->items as $item)
                            <li>{{ $item->name }} ({{ $item->pivot->quantity }})</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Detail Pengembalian Barang --}}
@if(in_array($borrowing->status, ['Waiting Approval', 'Returned']))
    <div class="mt-8">
        <h4 class="font-semibold text-gray-800 dark:text-white mb-2">Detail Pengembalian Barang</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($borrowing->items as $item)
                <div class="p-4 border rounded shadow bg-white dark:bg-gray-900">
                    <p class="font-medium text-gray-800 dark:text-white mb-1">
                        {{ $item->name }}
                    </p>

                    {{-- Foto --}}
                    @if($item->pivot->return_photo)
                        <img src="{{ asset('storage/' . $item->pivot->return_photo) }}"
                             alt="Foto {{ $item->name }}"
                             class="w-full h-40 object-cover rounded mb-2 border">
                    @else
                        <p class="text-sm italic text-gray-500 dark:text-gray-400">Tidak ada foto pengembalian.</p>
                    @endif

                    {{-- Kondisi Barang Setelah Dikembalikan --}}
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
    <strong>Kondisi Setelah Dikembalikan:</strong>
    @if ($item->pivot->condition)
        <span class="font-semibold text-gray-900 dark:text-white">
            {{ $item->pivot->condition }}
        </span>
    @else
        <span class="italic text-gray-500">Belum diisi</span>
    @endif
</p>


                </div>
            @endforeach
        </div>
    </div>
@endif


                {{-- Tombol Aksi --}}
                <div class="mt-8 flex gap-3">
                    <a href="{{ route('borrowings.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        ← Kembali
                    </a>
                    <a href="{{ route('borrowings.downloadPDF', $borrowing->id) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        🖨️ Cetak PDF
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
