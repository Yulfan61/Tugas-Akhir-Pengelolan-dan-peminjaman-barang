<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        if (
            localStorage.getItem("color-theme") === "dark" ||
            (!("color-theme" in localStorage) &&
                window.matchMedia("(prefers-color-scheme: dark)").matches)
        ) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() }}">


    <title>{{ config('app.name', 'M-Inventory') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex relative">

        {{-- Sidebar Desktop --}}
        <aside class="hidden sm:block w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
            @include('layouts.navigation')
        </aside>

        {{-- Sidebar Mobile --}}
        <div id="mobile-sidebar"
            class="fixed inset-y-0 left-0 w-64 z-50 bg-white dark:bg-gray-800 shadow-lg p-4 hidden sm:hidden overflow-y-auto">
            @include('layouts.navigation')
        </div>

        {{-- Mobile Header --}}
        <div
            class="sm:hidden fixed top-0 left-0 w-full z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between p-4">
                {{-- Button Sidebar di KIRI --}}
                <button onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')"
                    class="text-gray-700 dark:text-gray-300 mr-3">
                    ☰
                </button>

                {{-- Judul / Logo --}}
                <div class="font-semibold text-lg text-gray-800 dark:text-white">
                    M-Inventory
                </div>

                {{-- Spacer agar justify-between tetap rata --}}
                <div style="width: 1.5rem;"></div>
            </div>
        </div>


        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen pt-16 sm:pt-0">
            {{-- TOPBAR --}}
            <div
                class="w-full flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                {{-- Search --}}
                <div class="w-1/2">
                    <div class="relative">
                    </div>
                </div>

                {{-- Profile Info --}}
                <div class="flex items-center space-x-4 relative">

                    {{-- Notification Bell --}}
                    <div class="relative">
                        <button onclick="document.getElementById('notifDropdown').classList.toggle('hidden')"
                            class="relative focus:outline-none">
                            <i data-lucide="bell" class="w-5 h-5 text-gray-700 dark:text-gray-300"></i>

                            <span id="notificationBadge"
                                class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                                !
                            </span>
                        </button>


                        <div id="notifDropdown"
                            class="hidden absolute right-0 mt-2 w-72 sm:w-72 max-w-xs sm:max-w-none bg-white dark:bg-gray-800 rounded shadow-lg z-50">
                            <div
                                class="p-2 border-b border-gray-200 dark:border-gray-700 font-semibold text-sm text-gray-700 dark:text-gray-200">
                                Notifikasi Terbaru
                            </div>
                            <ul id="notificationList"
                                class="max-h-60 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-600 text-sm text-gray-800 dark:text-gray-200">
                                @foreach(auth()->user()->notifications->take(3) as $notification)
                                    <li class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                        <a href="{{ $notification->data['link'] ?? '#' }}"
                                            class="block text-sm text-gray-800 dark:text-gray-100">
                                            {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>


                    {{-- Theme toggle --}}
                    <button id="theme-toggle" type="button"
                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- User info --}}
                    <div class="flex items-center space-x-2">
                        <img src="{{ Auth::user()->profile_photo
    ? asset('storage/' . Auth::user()->profile_photo)
    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" class="w-8 h-8 rounded-full object-cover"
                            alt="profile">
                        <div class="text-sm leading-tight">
                            <div class="font-medium text-gray-800 dark:text-gray-100">
                                {{ Auth::user()->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ Auth::user()->getRoleNames()->first() ?? 'No Role' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Page header --}}
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Page content --}}
            <main class="flex-1 p-4">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="text-center text-sm text-gray-400 py-4">
                &copy; {{ date('Y') }} M-Inventory. All rights reserved.
            </footer>
        </div>


    </div>

    @stack('scripts')

    {{-- Lucide icon refresh --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('mobile-sidebar');
            const toggleButton = document.getElementById('toggle-sidebar');

            // Tampilkan tombol kembali ketika sidebar ditutup
            const observer = new MutationObserver(() => {
                if (sidebar.classList.contains('hidden')) {
                    toggleButton.classList.remove('hidden');
                }
            });

            observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        });
    </script>

</body>

</html>