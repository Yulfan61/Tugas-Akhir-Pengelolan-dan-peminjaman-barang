<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $blog->title }} - M-Inventory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800 font-sans antialiased">

    <div class="min-h-screen flex flex-col justify-between">

        {{-- Navbar --}}
        <nav class="flex justify-between items-center px-6 py-4 shadow-sm">
            <div class="flex items-center space-x-2">
                <x-application-logo class="h-6 w-6 text-orange-600" />
                <span class="font-bold text-lg text-gray-800">M-Inventory</span>
            </div>
            <ul class="hidden md:flex space-x-8 text-sm font-medium text-gray-600">
                <li><a href="{{ url('/') }}" class="hover:text-orange-600 transition">Beranda</a></li>
                <li><a href="#" class="hover:text-orange-600 transition">Tentang</a></li>
                <li><a href="#" class="hover:text-orange-600 transition">Ketersediaan</a></li>
                <li><a href="#" class="hover:text-orange-600 transition">Kontak</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-orange-600 transition">Login</a></li>
            </ul>
        </nav>

        {{-- Blog Content --}}
        <main class="px-6 md:px-20 py-12 max-w-4xl mx-auto">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $blog->title }}</h1>
                <p class="text-sm text-gray-500">
                    Oleh <span class="font-medium">{{ $blog->user->name }}</span> •
                    {{ $blog->created_at->translatedFormat('d F Y') }}
                </p>
            </div>

            @if ($blog->thumbnail)
                <img src="{{ asset('storage/' . $blog->thumbnail) }}"
                    alt="{{ $blog->title }}"
                    class="w-full h-64 object-cover rounded mb-6 shadow">
            @endif

            <article class="prose max-w-none">
                {!! $blog->content !!}
            </article>
        </main>

        {{-- Footer --}}
        <footer class="text-center text-sm text-gray-400 py-6 border-t mt-12">
            &copy; {{ date('Y') }} M-Inventory. All rights reserved.
        </footer>

    </div>
</body>

</html>
