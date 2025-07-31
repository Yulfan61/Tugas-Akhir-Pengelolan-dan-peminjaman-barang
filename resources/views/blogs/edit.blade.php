<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Artikel Blog
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            @if ($errors->any())
                <div class="mb-4">
                    <div class="font-medium text-red-600">Ada beberapa kesalahan:</div>
                    <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul
                        Artikel</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                        required>
                </div>

                <div class="mb-4">
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        accept="image/*">
                    @error('thumbnail')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-4">
                    <label for="content"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konten</label>
                    <textarea name="content" id="content" rows="12"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                        required>{{ old('content', $blog->content) }}</textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('blogs.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-sm font-medium text-gray-700 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-700">
                        ← Kembali
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-semibold rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tambahkan editor jika menggunakan Rich Text --}}
    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
    </script>
</x-app-layout>