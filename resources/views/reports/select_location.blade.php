<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pilih Lokasi - Cetak Laporan Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <form method="GET" action="{{ route('reports.export_by_location', ['location' => '']) }}">
                    <label for="location" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Lokasi:</label>
                    <select id="location" name="location_id" class="w-full p-2 rounded dark:bg-gray-700 dark:text-white">
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                        onclick="this.form.action = '{{ route('reports.export_by_location', '') }}/' + document.getElementById('location').value"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Cetak PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
