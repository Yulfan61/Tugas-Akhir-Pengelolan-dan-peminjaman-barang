<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>M-Inventory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800 antialiased font-sans">
    <div class="min-h-screen flex flex-col justify-between">

        {{-- Navbar --}}
        <nav class="flex justify-between items-center px-6 py-4 shadow-sm">
            <div class="flex items-center space-x-2">
                <x-application-logo class="h-6 w-6 text-orange-600" />
                <span class="font-bold text-lg text-gray-800">M-Inventory</span>
            </div>
            <ul class="hidden md:flex space-x-8 text-sm font-medium text-gray-600">
                <li><a href="#" class="hover:text-orange-600 transition">Tentang</a></li>
                <li><a href="#" class="hover:text-orange-600 transition">Ketersediaan Barang</a></li>
                <li><a href="#" class="hover:text-orange-600 transition">Kontak</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-orange-600 transition">Login</a></li>
            </ul>
        </nav>

        {{-- Hero Section --}}
        <section class="flex flex-col-reverse md:flex-row items-center justify-between px-6 md:px-20 py-16">
            <div class="md:w-1/2 space-y-6">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Selamat Datang di M-Inventory 🎉
                </h1>
                <p class="text-lg text-gray-600">
                    M-Inventory adalah sistem informasi manajemen barang yang dirancang untuk mempermudah proses
                    inventarisasi, peminjaman, dan pelaporan kerusakan dalam satu platform yang efisien.
                </p>
                <a href="{{ route('login') }}"
                    class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg shadow transition">
                    Masuk Sekarang →
                </a>
            </div>

            <div class="md:w-1/2 mb-10 md:mb-0">
                <img src="{{ asset('images/package-delivery.svg') }}" alt="Illustration"
                    class="w-full max-w-md mx-auto">
            </div>
        </section>

        {{-- Informasi Stok Barang per Gudang --}}
        <section class="px-6 md:px-20 py-12 bg-gray-50 border-t border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Stok Barang per Gudang</h2>

            {{-- Grid Gudang --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($locations as $location)
                    <div class="bg-white rounded shadow p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-orange-600 mb-2">{{ $location->name }}</h3>

                        <ul class="text-sm text-gray-700 space-y-1">
                            @forelse ($location->items->take(4) as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item->name }}</span>
                                    <span class="font-medium text-gray-900">{{ $item->stock }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500 italic">Tidak ada barang.</li>
                            @endforelse
                        </ul>
                    </div>
                @endforeach
            </div>

            {{-- Paginate --}}
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
                <div>
                    {{ $locations->onEachSide(1)->links() }}
                </div>
            </div>
        </section>

        {{-- Artikel Terbaru --}}
        <section class="bg-white px-6 md:px-20 py-12 border-t border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Berita & Artikel</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach (\App\Models\Blog::latest()->take(3)->get() as $blog)
                    <div class="bg-gray-50 rounded-lg shadow p-4 border">
                        @if ($blog->thumbnail)
                            <img src="{{ asset('storage/' . $blog->thumbnail) }}" alt="{{ $blog->title }}"
                                class="w-full h-48 object-cover rounded mb-3">
                        @endif
                        <h3 class="text-lg font-bold text-gray-900">{{ $blog->title }}</h3>
                        <p class="text-sm text-gray-500 mb-2">Oleh {{ $blog->user->name }} •
                            {{ $blog->created_at->diffForHumans() }}</p>
                        <p class="text-gray-700 text-sm">{{ Str::limit(strip_tags($blog->content), 100) }}</p>
                        <a href="{{ route('blogs.show', $blog) }}">Baca selengkapnya</a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Footer --}}
        <footer class="text-center text-sm text-gray-400 py-4">
            &copy; {{ date('Y') }} M-Inventory. All rights reserved.
        </footer>
    </div>
</body>

</html>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Pusher Config
    Pusher.logToConsole = false;

    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        forceTLS: true
    });

    const channel = pusher.subscribe('stock-channel');

    channel.bind('item.stock.updated', function (data) {
        console.log('Stok diperbarui:', data);

        const itemEl = document.getElementById('item-' + data.item_id);
        if (itemEl) {
            const span = itemEl.querySelector('.stock-value');
            if (span) span.textContent = data.stock;
        }
    });
</script>