<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Daftar Peminjaman yang Telah Dikembalikan
                </h3>
                <div class="mb-4">
                    <a href="{{ route('borrowings.history.pdf') }}"
                        class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                        ⬇️ Unduh PDF
                    </a>
                </div>

                @if($borrowings->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                    <th class="px-4 py-2 text-left">Tanggal Pinjam</th>
                                    <th class="px-4 py-2 text-left">Tanggal Kembali</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowings as $borrowing)
                                    <tr onclick="window.location='{{ route('borrowings.show', $borrowing->id) }}'"
                                        class="cursor-pointer border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-2">
                                            {{ $borrowing->borrow_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium
                                                        {{ $borrowing->status === 'Returned' ? 'bg-green-200 text-green-900 dark:bg-green-700 dark:text-green-100' : 'bg-red-200 text-red-900 dark:bg-red-700 dark:text-red-100' }}">
                                                {{ $borrowing->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <ul class="list-disc list-inside text-gray-700 dark:text-gray-200">
                                                @foreach($borrowing->items as $item)
                                                    <li>{{ $item->name }} (x{{ $item->pivot->quantity }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $borrowings->links() }}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-300">Tidak ada peminjaman yang telah dikembalikan.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>