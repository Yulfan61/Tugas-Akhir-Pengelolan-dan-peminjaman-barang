<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengembalian Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('borrowings.return.process', $borrowing->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Info Peminjaman --}}
                    <div class="mb-4">
                        <x-input-label :value="__('Tanggal Pinjam')" />
                        <div class="text-gray-800 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label :value="__('Tanggal Kembali (Rencana)')" />
                        <div class="text-gray-800 dark:text-gray-100">
                            {{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') : '—' }}
                        </div>
                    </div>

                    {{-- Daftar Barang --}}
                    <div class="mb-6">
                        <x-input-label :value="__('Barang yang Dikembalikan')" />
                        @foreach($borrowing->items as $item)
                            <div class="mt-4 p-4 rounded border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                <label for="item_condition_{{ $item->id }}" class="block text-sm font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $item->name }} <span class="text-sm text-gray-500">(Jumlah: {{ $item->pivot->quantity }})</span>
                                </label>

                                <select name="item_conditions[{{ $item->id }}]" id="item_condition_{{ $item->id }}"
                                    class="mt-2 w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:ring focus:ring-indigo-200">
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>

                                {{-- Input Foto --}}
                                <label for="return_photo_{{ $item->id }}" class="block mt-4 text-sm font-medium text-gray-800 dark:text-gray-100">
                                    Foto Barang Saat Dikembalikan:
                                </label>
                                <input
                                    type="file"
                                    name="return_photos[{{ $item->id }}]"
                                    id="return_photo_{{ $item->id }}"
                                    accept="image/*"
                                    capture="environment"
                                    class="mt-2 block w-full text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer focus:outline-none"
                                >
                            </div>
                        @endforeach
                        <x-input-error :messages="$errors->get('item_conditions')" class="mt-2" />
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="flex justify-end">
                        <x-primary-button>
                            {{ __('Proses Pengembalian') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
