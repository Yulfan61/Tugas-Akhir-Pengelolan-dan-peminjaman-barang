<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-200 text-green-800 px-4 py-2 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm whitespace-nowrap bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">
                                <th class="px-4 py-2 text-left">Nama</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Telepon</th>
                                <th class="px-4 py-2 text-left">Role Saat Ini</th>
                                <th class="px-4 py-2 text-left">Status Online</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-100">
                            @foreach($users as $user)
                                <tr class="border-t dark:border-gray-600" data-id="user-{{ $user->id }}">
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->phone_number }}</td>
                                    <td class="px-4 py-2">{{ $user->roles->pluck('name')->join(', ') }}</td>
                                    <td class="px-4 py-2">
                                        @php
                                            $isOnline = $user->last_seen_at && $user->last_seen_at->gt(now()->subMinutes(5));
                                        @endphp
                                        <span class="text-sm px-2 py-1 rounded-full font-medium
                                            {{ $isOnline ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                                            {{ $isOnline ? 'Online' : 'Offline' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                            <form action="{{ route('users.updateRole', $user) }}" method="POST" class="flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="rounded px-2 py-1 text-sm dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                                                    Update
                                                </button>
                                            </form>

                                            @if($user->phone_number)
                                                @php
                                                    $cleanedPhone = preg_replace('/\D/', '', $user->phone_number);
                                                    if (substr($cleanedPhone, 0, 1) === '0') {
                                                        $cleanedPhone = '62' . substr($cleanedPhone, 1);
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanedPhone }}" target="_blank"
                                                   class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 inline-flex items-center text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.52 3.48A11.84..." />
                                                    </svg>
                                                    WhatsApp
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = false;

        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            encrypted: true
        });

        const channel = pusher.subscribe('users');

        channel.bind('user.registered', function(data) {
            const tbody = document.querySelector('table tbody');
            const row = document.createElement('tr');
            row.setAttribute('data-id', 'user-' + data.id);
            row.className = "border-t dark:border-gray-600 text-gray-800 dark:text-gray-100";

            row.innerHTML = `
                <td class="px-4 py-2">${data.name}</td>
                <td class="px-4 py-2">${data.email}</td>
                <td class="px-4 py-2">—</td>
                <td class="px-4 py-2">Peminjam</td>
                <td class="px-4 py-2">
                    <span class="text-sm px-2 py-1 rounded-full bg-green-500 text-white">Online</span>
                </td>
                <td class="px-4 py-2">
                    <span class="text-xs text-gray-500">Baru terdaftar</span>
                </td>
            `;
            tbody.prepend(row);
        });
    </script>
    @endpush
</x-app-layout>
