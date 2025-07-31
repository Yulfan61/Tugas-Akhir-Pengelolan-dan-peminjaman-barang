<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Artikel Blog') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @role('Admin|Staff')
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('blogs.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:outline-none focus:border-orange-800 focus:ring ring-orange-300 transition">
                        + Tambah Artikel
                    </a>
                </div>
            @endrole

            @forelse ($blogs as $blog)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6 p-6 border border-gray-100 dark:border-gray-700">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                            {{ $blog->title }}
                        </h3>
                        @role('Admin|Staff')
                            <div class="flex gap-2">
                                <a href="{{ route('blogs.edit', $blog) }}"
                                    class="text-sm px-3 py-1 rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-800 dark:text-white">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('blogs.destroy', $blog) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-sm px-3 py-1 rounded-md bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-800 dark:text-white">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        @endrole
                    </div>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        Oleh <span class="font-medium">{{ $blog->user->name }}</span> • {{ $blog->created_at->diffForHumans() }}
                    </p>

                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                        {!! Str::limit(strip_tags($blog->content), 200, '...') !!}
                    </p>

                    <a href="{{ route('blogs.show', $blog) }}">Baca selengkapnya</a>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400">Belum ada artikel blog.</p>
            @endforelse

            <div class="mt-6">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
