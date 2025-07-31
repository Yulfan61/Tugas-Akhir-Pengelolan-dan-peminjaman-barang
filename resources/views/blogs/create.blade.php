<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Artikel Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="title"
                            class="block font-medium text-sm text-gray-700 dark:text-gray-200">Judul</label>
                        <input type="text" name="title" id="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50"
                            placeholder="Judul artikel...">
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


                    <div class="mb-6">
                        <label for="content"
                            class="block font-medium text-sm text-gray-700 dark:text-gray-200">Konten</label>
                        <textarea name="content" id="content" rows="15"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50"
                            placeholder="Tulis isi artikel di sini..."></textarea>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('blogs.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-white uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Batal
                        </a>

                        <button type="submit"
                            class="ml-3 inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:outline-none focus:border-orange-800 focus:ring ring-orange-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Simpan Artikel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
    </script>
</x-app-layout>