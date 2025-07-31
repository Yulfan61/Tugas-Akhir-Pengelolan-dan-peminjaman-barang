<x-app-layout>
    <x-slot name="header">{{ $blog->title }}</x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if ($blog->thumbnail)
            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}"
                class="w-full h-72 object-cover rounded mb-6">
        @endif

        <p class="text-sm text-gray-500 mb-4">
            Oleh {{ $blog->user->name }} • {{ $blog->created_at->translatedFormat('d F Y') }}
        </p>

        <div class="prose max-w-none dark:prose-invert">
            {!! $blog->content !!}
        </div>
    </div>
</x-app-layout>
