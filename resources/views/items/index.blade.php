<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tombol Tambah dan Kategori --}}
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('items.create') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Tambah Barang
                </a>
                @role('Admin')
                <a href="{{ route('categories.index') }}"
                   class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Kelola Kategori
                </a>
                @endrole
            </div>

            {{-- Grouped Item List --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($items->count())
                    @php
                        // Kelompokkan items berdasarkan lokasi
                        $groupedItems = $items->groupBy('location.name');
                    @endphp

                    @foreach($groupedItems as $locationName => $locationItems)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                                Lokasi: {{ $locationName ?? 'Tidak Diketahui' }}
                            </h3>
                            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($locationItems as $item)
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow mb-2">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $item->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Kode: <span class="font-medium">{{ $item->code }}</span>
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Kategori: {{ $item->category->name }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Stock: {{ $item->stock }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Kondisi: {{ $item->condition }}
                                        </p>

                                        <div class="flex gap-2 mt-4">
                                            <a href="{{ route('items.edit', $item->id) }}"
                                               class="text-sm bg-orange-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-sm bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $items->links() }}
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-300">Tidak ada barang.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
