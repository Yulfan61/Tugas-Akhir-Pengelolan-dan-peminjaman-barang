<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Status Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('borrowings.update', $borrowing) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Status --}}
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Pending" {{ $borrowing->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $borrowing->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ $borrowing->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Returned" {{ $borrowing->status == 'Returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    {{-- Penalti --}}
                    <div class="mt-4">
                        <x-input-label for="penalty" :value="__('Penalti (jika ada)')" />
                        <x-text-input 
                            id="penalty"
                            name="penalty"
                            type="number"
                            step="0.01"
                            min="0"
                            :value="old('penalty', $borrowing->penalty)"
                            class="block w-full"
                        />
                        <x-input-error :messages="$errors->get('penalty')" class="mt-2" />
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end mt-6">
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
