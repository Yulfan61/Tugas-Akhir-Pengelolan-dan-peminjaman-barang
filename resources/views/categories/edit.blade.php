<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Edit Kategori</h1>
        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-gray-700 dark:text-gray-300">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-input w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ $category->name }}" required>
            </div>
            <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Update
            </button>
        </form>
    </div>
</x-app-layout>
