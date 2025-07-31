<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 text-red-800 px-4 py-2 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('borrowings.create') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Tambah Peminjaman
                </a>
                @hasanyrole('Admin|Staff|Peminjam')
                <a href="{{ route('borrowings.history') }}"
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                    Riwayat Peminjaman
                </a>
                @endhasanyrole
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($borrowings->count())
                    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($borrowings as $borrowing)
                            <div
                                data-id="{{ $borrowing->id }}"
                                onclick="window.location='{{ route('borrowings.show', $borrowing) }}'"
                                class="cursor-pointer transition hover:shadow-lg bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow relative group"
                            >
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $borrowing->user->name }}
                                </h3>

                                <p class="text-sm">
                                    <span class="font-semibold text-gray-700 dark:text-gray-200">Status:</span>
                                    <span class="status-badge px-2 py-1 rounded text-white text-xs
                                        @switch($borrowing->status)
                                            @case('Pending') bg-orange-500 @break
                                            @case('Approved') bg-blue-500 @break
                                            @case('Rejected') bg-red-500 @break
                                            @case('Returned') bg-green-500 @break
                                            @case('Waiting Approval') bg-purple-500 @break
                                            @default bg-gray-500
                                        @endswitch">
                                        {{ $borrowing->status }}
                                    </span>
                                </p>

                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Tanggal Pinjam:
                                    <span class="font-medium">
                                        {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Tanggal Kembali:
                                    <span class="font-medium">
                                        {{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') : '—' }}
                                    </span>
                                </p>

                                @if($borrowing->items && $borrowing->items->count())
                                    <div class="mt-2">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Item:</p>
                                        <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300">
                                            @foreach($borrowing->items as $item)
                                                <li>{{ $item->name }} ({{ $item->pivot->quantity }})</li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="mt-2">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Lokasi:</p>
                                        <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300">
                                            @foreach($borrowing->items->pluck('location.name')->unique() as $locationName)
                                                <li>{{ $locationName }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <p class="penalty" style="{{ !$borrowing->penalty ? 'display:none' : '' }}">
                                    <strong>Penalti:</strong>
                                    <span class="text-red-500 font-semibold">
                                        Rp{{ number_format($borrowing->penalty, 0, ',', '.') }}
                                    </span>
                                </p>

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



                                <div class="actions flex flex-wrap gap-2 mt-4" onclick="event.stopPropagation();">
                                    @role('Admin|Staff')
                                        <a href="{{ route('borrowings.edit', $borrowing) }}"
                                           class="text-sm bg-orange-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                            Edit
                                        </a>
                                        <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    @endrole

                                    @if($borrowing->status === 'Approved')
                                        <a href="{{ route('borrowings.return.form', $borrowing->id) }}"
                                           class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 return-button">
                                            Kembalikan Barang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $borrowings->links() }}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-300">Belum ada data peminjaman.</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = false;

        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true,
        });

        const channel = pusher.subscribe('borrowings');

        // CREATE
        channel.bind('borrowing.created', function (data) {
            console.log('Peminjaman baru:', data);
            alert('Peminjaman baru dari: ' + data.user);
            location.reload();
        });

        // DELETE
        channel.bind('BorrowingDeleted', function (data) {
            const card = document.querySelector('[data-id="' + data.id + '"]');
            if (card) card.remove();
        });

        // UPDATE
        channel.bind('borrowing.updated', function(data) {
            const card = document.querySelector(`[data-id="${data.id}"]`);
            if (!card) return;

            const badge = card.querySelector('.status-badge');
            if (badge) {
                badge.innerText = data.status;
                badge.className = 'px-2 py-1 rounded text-white text-xs status-badge';
                if (data.status === 'Pending') badge.classList.add('bg-orange-500');
                else if (data.status === 'Approved') badge.classList.add('bg-blue-500');
                else if (data.status === 'Returned') badge.classList.add('bg-green-500');
                else if (data.status === 'Rejected') badge.classList.add('bg-red-500');
                else if (data.status === 'Waiting Approval') badge.classList.add('bg-purple-500');
            }

            // UPDATE PENALTI REALTIME
            const penaltyElement = card.querySelector('.penalty');

            if (penaltyElement) {
                if (data.penalty && data.penalty > 0) {
                    penaltyElement.style.display = 'block';
                    penaltyElement.innerHTML = `
                        <strong>Penalti:</strong>
                        <span class="text-red-500 font-semibold">
                        Rp${parseInt(data.penalty).toLocaleString('id-ID')}
                        </span>`;
                } else {
                penaltyElement.style.display = 'none';
            }
            }


            // Tambahkan tombol kembalikan jika Approved
            if (data.status === 'Approved') {
                const returnBtn = card.querySelector('.return-button');
                if (!returnBtn) {
                    const button = document.createElement('a');
                    button.href = `/borrowings/${data.id}/return`;
                    button.innerText = 'Kembalikan Barang';
                    button.className = 'text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 return-button';
                    card.querySelector('.actions').appendChild(button);
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
