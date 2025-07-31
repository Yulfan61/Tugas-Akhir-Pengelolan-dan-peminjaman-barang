<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    @php
        $allItems = \App\Models\Item::with('location')
            ->where('stock', '>', 0)
            ->get()
            ->groupBy(function ($item) {
                return $item->location->name ?? 'Tanpa Lokasi';
            });

        $locations = $allItems->keys();
        $perPage = 1;
        $currentPage = request()->get('page', 1);
        $currentPageLocations = $locations->slice(($currentPage - 1) * $perPage, $perPage);

        $paginatedLocations = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageLocations,
            $locations->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    @endphp

    @php
        $announcement = \App\Models\Announcement::first();
        $borrowingsPerDate = \App\Models\Borrowing::with('items')
            ->where('status', '!=', 'Returned')
            ->get()
            ->groupBy(fn($b) => $b->borrow_date->format('Y-m-d'))
            ->map(function ($borrowings) {
                return $borrowings->map(function ($b) {
                    return [
                        'return_date' => optional($b->return_date)->format('Y-m-d'),
                        'items' => $b->items->pluck('name')->unique()->values()->toArray(),
                    ];
                });
            });

        $monthlyStats = \App\Models\Borrowing::selectRaw('MONTH(borrow_date) as month, COUNT(*) as count')
            ->whereYear('borrow_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartLabels = json_encode($months);
        $chartData = json_encode(array_map(fn($i) => $monthlyStats[$i] ?? 0, range(1, 12)));
    @endphp


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ADMIN --}}
            @role('Admin')
            <div class="mb-6">
                <div
                    class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-md rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h2 class="text-2xl md:text-3xl font-bold mb-2">
                            Selamat Datang {{ Auth::user()->getRoleNames()->first() ?? 'No Role' }} di M-Inventory 🎉
                        </h2>
                        <p class="text-sm md:text-base">
                            {{ $announcement->message ?? 'Kelola data barang, peminjaman, dan laporan kerusakan dengan lebih efisien.' }}
                        </p>
                    </div>

                    <div class="hidden md:block w-40 shrink-0">
                        <img src="images/shopping-cart.svg" alt="Illustration"
                            class="w-full h-auto rounded-lg shadow-lg">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <x-dashboard-card title="Total Users" :value="\App\Models\User::count()" />
                <x-dashboard-card title="Total Peminjaman" :value="\App\Models\Borrowing::whereIn('status', ['Pending', 'Approved'])->count()" />
                <x-dashboard-card title="Laporan Kerusakan" :value="\App\Models\DamageReport::whereIn('status', ['Reported', 'In Progress'])->count()" />
                <x-dashboard-card title="Stock Barang Kosong" :value="\App\Models\Item::where('stock', '<', 0)->count()" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Kalender Peminjaman</h3>
                    <div id="borrowing-calendar" class="rounded-lg"></div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Statistik Peminjaman / Bulan
                    </h3>
                    <canvas id="borrowingChart" class="w-full h-64"></canvas>
                </div>
            </div>

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <!-- Tippy.js -->
                <script src="https://unpkg.com/@popperjs/core@2"></script>
                <script src="https://unpkg.com/tippy.js@6"></script>
                <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css" />

                <!-- Flatpickr -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const borrowingData = @json($borrowingsPerDate);

                        flatpickr("#borrowing-calendar", {
                            inline: true,
                            locale: "id",
                            onDayCreate: function (dObj, dStr, fp, dayElem) {
                                const dateStr = dayElem.dateObj.toLocaleDateString("sv-SE");
                                if (borrowingData[dateStr]) {
                                    let tooltip = borrowingData[dateStr].map(entry => {
                                        return `Kembali: ${entry.return_date ?? '-'}\nBarang: ${entry.items.join(', ')}`;
                                    }).join('\n\n');
                                    dayElem.setAttribute("title", tooltip);
                                    dayElem.style.backgroundColor = "#4ade80";
                                    dayElem.style.borderRadius = "50%";
                                    dayElem.style.color = "#000";
                                }
                            }
                        });

                        const ctx = document.getElementById('borrowingChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: {!! $chartLabels !!},
                                datasets: [{
                                    label: 'Jumlah Peminjaman',
                                    data: {!! $chartData !!},
                                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                    borderColor: 'rgba(99, 102, 241, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endpush
            @endrole

            {{-- STAFF --}}
            @role('Staff')
            {{-- Welcome Card untuk semua user --}}
            <div class="mb-6">
                <div
                    class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-md rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h2 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang
                            {{ Auth::user()->getRoleNames()->first() ?? 'No Role' }} di M-Inventory 🎉
                        </h2>
                        <p class="text-sm md:text-base">
                            {{ $announcement->message ?? 'Kelola data barang, peminjaman, dan laporan kerusakan dengan lebih efisien.' }}
                        </p>
                    </div>
                    <div class="hidden md:block w-40 shrink-0">
                        <img src="images/shopping-cart.svg" alt="Illustration"
                            class="w-full h-auto rounded-lg shadow-lg">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <x-dashboard-card title="Total Peminjaman" :value="\App\Models\Borrowing::whereIn('status', ['Pending', 'Approved'])->count()" />
                <x-dashboard-card title="Total Barang" :value="\App\Models\Item::count()" />
                <x-dashboard-card title="Laporan Kerusakan" :value="\App\Models\DamageReport::whereIn('status', ['Reported', 'In Progress'])->count()" />
                <x-dashboard-card title="Stock Barang" :value="\App\Models\Item::where('stock', '<', 5)->count()" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Kalender Peminjaman</h3>
                    <div id="borrowing-calendar" class="rounded-lg"></div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Statistik Peminjaman / Bulan
                    </h3>
                    <canvas id="borrowingChart" class="w-full h-64"></canvas>
                </div>
            </div>

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const borrowingData = @json($borrowingsPerDate);

                        flatpickr("#borrowing-calendar", {
                            inline: true,
                            locale: "id",
                            onDayCreate: function (dObj, dStr, fp, dayElem) {
                                const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                                if (borrowingData[dateStr]) {
                                    let tooltip = borrowingData[dateStr].map(entry => {
                                        return `Kembali: ${entry.return_date ?? '-'}\nBarang: ${entry.items.join(', ')}`;
                                    }).join('\n\n');
                                    dayElem.setAttribute("title", tooltip);
                                    dayElem.style.backgroundColor = "#4ade80";
                                    dayElem.style.borderRadius = "50%";
                                    dayElem.style.color = "#000";
                                }
                            }
                        });

                        const ctx = document.getElementById('borrowingChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: {!! $chartLabels !!},
                                datasets: [{
                                    label: 'Jumlah Peminjaman',
                                    data: {!! $chartData !!},
                                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                    borderColor: 'rgba(99, 102, 241, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endpush
            @endrole

            {{-- PEMINJAM --}}
            @role('Peminjam')
            {{-- Welcome Card untuk semua user --}}
            <div class="mb-6">
                <div
                    class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white shadow-md rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h2 class="text-2xl md:text-3xl font-bold mb-2">Selamat Datang {{ Auth::user()->name }} di
                            M-Inventory 🎉</h2>
                        <p class="text-sm md:text-base">
                            {{ $announcement->message ?? 'Kelola data barang, peminjaman, dan laporan kerusakan dengan lebih efisien.' }}
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('borrowings.index') }}"
                                class="inline-block bg-white text-indigo-600 font-semibold px-4 py-2 rounded shadow hover:bg-gray-100 transition">
                                Mulai Peminjaman
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:block w-40 shrink-0">
                        <img src="images/shopping-cart.svg" alt="Illustration"
                            class="w-full h-auto rounded-lg shadow-lg">
                    </div>
                </div>
            </div>
            <div class="mb-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Barang Ready per Lokasi</h3>

                @forelse ($paginatedLocations as $location)
                    @php $items = $allItems[$location]; @endphp
                    <div class="mb-4">
                        <h4 class="font-semibold text-indigo-600 dark:text-indigo-300">{{ $location }}</h4>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-200 text-sm">
                            @foreach ($items as $item)
                                <li>{{ $item->name }} (Stok: {{ $item->stock }})</li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <p class="text-gray-500 italic">Tidak ada barang yang tersedia saat ini.</p>
                @endforelse
                <div class="mt-4">
                    {{ $paginatedLocations->withQueryString()->links() }}
                </div>


            </div>
            <div class="mb-6">
                <x-dashboard-card title="Peminjaman Anda" :value="auth()->user()->peminjaman()->count()" />
            </div>
            @endrole
        </div>
    </div>
</x-app-layout>