<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Lokasi') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded shadow">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Tambah --}}
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Lokasi Tersimpan</h3>
                <a href="{{ route('locations.create') }}"
                    class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    + Tambah Lokasi
                </a>
            </div>

            {{-- Card List --}}
            @if($locations->count())
                <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($locations as $location)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-2">
                            <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ $location->name }}
                            </p>

                            <div class="flex flex-wrap gap-2 pt-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('locations.edit', $location->id) }}"
                                    class="px-3 py-1 text-sm bg-orange-500 text-white rounded hover:bg-orange-500 transition">
                                    Edit
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                                        Hapus
                                    </button>
                                </form>

                                {{-- Tombol Cetak PDF --}}
                                <a href="{{ route('reports.export_by_location', $location->id) }}" target="_blank"
                                    class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    🖨️ Cetak Laporan
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $locations->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <p class="text-gray-600 dark:text-gray-300">Tidak ada lokasi.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>