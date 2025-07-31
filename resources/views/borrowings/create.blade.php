<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Dropdown Lokasi Gudang --}}
                <form method="GET" action="{{ route('borrowings.create') }}">
                    <div class="mb-4">
                        <x-input-label for="location_id" :value="__('Pilih Gudang')" />
                        <select name="location_id" id="location_id"
                            class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            onchange="this.form.submit()">
                            <option value="">-- Pilih Gudang --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $selectedLocationId == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($items->count())
                    <form method="POST" action="{{ route('borrowings.store') }}">
                        @csrf
                        <input type="hidden" name="location_id" value="{{ $selectedLocationId }}">

                        {{-- Tanggal Pinjam --}}
                        <div>
                            <x-input-label for="borrow_date" :value="__('Tanggal Pinjam')" />
                            <x-text-input id="borrow_date" name="borrow_date" type="date"
                                class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                :value="old('borrow_date')" required autofocus />
                            <x-input-error :messages="$errors->get('borrow_date')" class="mt-2" />
                        </div>

                        {{-- Tanggal Kembali --}}
                        <div class="mt-4">
                            <x-input-label for="return_date" :value="__('Tanggal Kembali')" />
                            <x-text-input id="return_date" name="return_date" type="date"
                                class="mt-1 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                :value="old('return_date')" required />
                            <x-input-error :messages="$errors->get('return_date')" class="mt-2" />
                        </div>

                        {{-- Pilih Barang --}}
                        <div class="mt-6">
                            <x-input-label :value="__('Pilih Barang & Jumlah')" />
                            <div class="space-y-3">
                                @foreach($items as $index => $item)
                                    <div class="flex items-center gap-3">
                                        <input id="item_{{ $index }}" type="checkbox" name="item_ids[]"
                                            value="{{ $item->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-indigo-400">
                                        <label for="item_{{ $index }}"
                                            class="block text-sm text-gray-700 dark:text-gray-200 w-full">
                                            {{ $item->name }} (Stok: {{ $item->stock }})
                                        </label>
                                        <x-text-input name="quantities[{{ $item->id }}]" type="number" min="1"
                                            class="w-24 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                            placeholder="Jumlah" />
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('item_ids')" class="mt-2" />
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex justify-end mt-6">
                            <x-primary-button>
                                {{ __('Ajukan Peminjaman') }}
                            </x-primary-button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
