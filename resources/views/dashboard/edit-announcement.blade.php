<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ubah Pesan Sambutan
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-6">
        <form method="POST" action="{{ route('announcement.update') }}">
            @csrf
            <label for="message" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
                Pesan Sambutan:
            </label>
            <textarea id="message" name="message" rows="5"
                class="w-full p-3 rounded border dark:bg-gray-800 dark:text-white">{{ old('message', $announcement->message ?? '') }}</textarea>

            <button type="submit"
                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</x-app-layout>
