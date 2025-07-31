<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Laporan Kerusakan') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6 px-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form action="{{ route('reports.store_damage_report') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Lokasi Gudang --}}
                    <div>
                        <label for="location_id" class="block font-medium text-gray-700 dark:text-gray-300">
                            Lokasi Gudang
                        </label>
                        <select 
                            name="location_id" 
                            id="location_id" 
                            class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 p-2"
                            required
                        >
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Barang --}}
                    <div>
                        <label for="item_id" class="block font-medium text-gray-700 dark:text-gray-300">
                            Barang
                        </label>
                        <select 
                            name="item_id" 
                            id="item_id" 
                            class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 p-2"
                            required
                        >
                            <option value="">-- Pilih Barang --</option>
                            {{-- Akan diisi via AJAX --}}
                        </select>
                        @error('item_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block font-medium text-gray-700 dark:text-gray-300">
                            Deskripsi
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4" 
                            class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 p-2"
                            placeholder="Tuliskan deskripsi kerusakan di sini..."
                            required
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div>
                        <button 
                            type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-200"
                        >
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tambahkan Script AJAX --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#location_id').on('change', function() {
            const locationId = $(this).val();
            if (locationId) {
                $.ajax({
                    url: '/reports/items/by-location/' + locationId,
                    type: 'GET',
                    success: function(data) {
                        $('#item_id').empty();
                        $('#item_id').append('<option value="">-- Pilih Barang --</option>');
                        $.each(data, function(index, item) {
                            $('#item_id').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    }
                });
            } else {
                $('#item_id').empty();
                $('#item_id').append('<option value="">-- Pilih Barang --</option>');
            }
        });
    </script>
</x-app-layout>
