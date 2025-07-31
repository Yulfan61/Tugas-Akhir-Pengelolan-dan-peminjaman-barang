<div class="h-full flex flex-col text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800">

    {{-- Tombol Tutup Sidebar Mobile --}}
    <div class="sm:hidden flex justify-end p-4 pb-2">
        <button onclick="document.getElementById('mobile-sidebar').classList.add('hidden')"
            class="flex items-center justify-center p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
            <i data-lucide="x" class="w-5 h-5 text-gray-600 dark:text-gray-300"></i>
        </button>
    </div>

    {{-- Logo --}}
    <div class="px-4 py-6 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
            <x-application-logo class="h-8 w-8 flex-shrink-0" />
            <span class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">M-Inventory</span>
        </a>
    </div>

    {{-- Sidebar Menu --}}
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="home" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Dashboard</span>
            </div>
        </x-nav-link>

        <x-nav-link :href="route('borrowings.index')" :active="request()->routeIs('borrowings.*')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="calendar-check" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Peminjaman</span>
            </div>
        </x-nav-link>

        @role('Peminjam')
        <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="calendar-days" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Kalender</span>
            </div>
        </x-nav-link>
        @endrole

        @role('Admin|Staff')
        <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="package" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Barang</span>
            </div>
        </x-nav-link>

        <x-nav-link :href="route('reports.damage_reports')" :active="request()->routeIs('reports.*')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Laporan</span>
            </div>
        </x-nav-link>

        <x-nav-link :href="route('blogs.index')" :active="request()->routeIs('blogs.*')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="table-of-contents" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Artikel</span>
            </div>
        </x-nav-link>
        @endrole

        @role('Admin')
        <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="map-pin" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Gudang</span>
            </div>
        </x-nav-link>

        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Pengguna</span>
            </div>
        </x-nav-link>

        <x-nav-link :href="route('announcement.edit')" :active="request()->routeIs('announcement.edit')">
            <div class="flex items-center gap-3 w-full">
                <i data-lucide="megaphone" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium">Edit Sambutan</span>
            </div>
        </x-nav-link>
        @endrole
    </nav>

    {{-- Account Section --}}
    <div class="px-4 py-6 border-t border-gray-200 dark:border-gray-600">
        <div class="text-xs uppercase tracking-wide font-semibold mb-3 text-gray-400 dark:text-gray-500">
            Account
        </div>
        
        <div class="space-y-1">
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                <div class="flex items-center gap-3 w-full">
                    <i data-lucide="user" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-medium">Profile</span>
                </div>
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-nav-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="flex items-center gap-3 w-full">
                        <i data-lucide="log-out" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="font-medium">Logout</span>
                    </div>
                </x-nav-link>
            </form>
        </div>
    </div>
</div>