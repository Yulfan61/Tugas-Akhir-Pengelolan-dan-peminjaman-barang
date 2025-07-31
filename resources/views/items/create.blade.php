<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form action="{{ route('items.store') }}" method="POST" class="space-y-6">
                    @csrf

                    @php
                        $inputClass = 'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50';
                        $labelClass = 'block font-medium text-gray-700 dark:text-gray-200';
                    @endphp

                    <div>
                        <label for="code" class="{{ $labelClass }}">Kode Barang</label>
                        <input type="text" name="code" id="code" required class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="name" class="{{ $labelClass }}">Nama Barang</label>
                        <input type="text" name="name" id="name" required class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="category_id" class="{{ $labelClass }}">Kategori</label>
                        <select name="category_id" id="category_id" required class="{{ $inputClass }}">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="brand" class="{{ $labelClass }}">Merk</label>
                        <input type="text" name="brand" id="brand" required class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="model" class="{{ $labelClass }}">Model</label>
                        <input type="text" name="model" id="model" required class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="year_of_purchase" class="{{ $labelClass }}">Tahun Pembelian</label>
                        <input type="number" name="year_of_purchase" id="year_of_purchase" required min="1900" max="{{ date('Y') }}" class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="price" class="{{ $labelClass }}">Harga</label>
                        <input type="number" name="price" id="price" required step="0.01" min="0" class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="stock" class="{{ $labelClass }}">Stok</label>
                        <input type="number" name="stock" id="stock" required min="0" class="{{ $inputClass }}" />
                    </div>

                    <div>
                        <label for="specification" class="{{ $labelClass }}">Spesifikasi</label>
                        <textarea name="specification" id="specification" rows="3" class="{{ $inputClass }}"></textarea>
                    </div>

                    <div>
                        <label for="condition" class="{{ $labelClass }}">Kondisi</label>
                        <select name="condition" id="condition" required class="{{ $inputClass }}">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                            <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        </select>
                    </div>

                    <div>
                        <label for="location_id" class="{{ $labelClass }}">Lokasi</label>
                        <select name="location_id" id="location_id" required class="{{ $inputClass }}">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
