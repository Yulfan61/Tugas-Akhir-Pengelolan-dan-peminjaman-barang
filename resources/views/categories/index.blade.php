<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Kategori') }}
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

            {{-- Tombol Tambah --}}
            <div class="mb-6">
                <a href="{{ route('categories.create') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Tambah Kategori
                </a>
            </div>

            {{-- Grid Card Kategori --}}
            @if($categories->count())
                <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($categories as $category)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                {{ $category->name }}
                            </h3>

                            <div class="flex gap-2">
                                <a href="{{ route('categories.edit', $category->id) }}"
                                   class="text-sm bg-orange-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                    Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <p class="text-gray-600 dark:text-gray-300">Belum ada kategori tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
