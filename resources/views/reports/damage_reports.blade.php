<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Kerusakan') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6 px-4">

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 px-4 py-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('reports.create_damage_report') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-200">
                + Buat Laporan
            </a>
            <a href="{{ route('reports.damage_reports.export') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition duration-200 ml-2">
                🖨️ Cetak PDF
            </a>
        </div>

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-max w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Tanggal</th>
                        <th class="py-2 px-4 border-b text-left">Barang</th>
                        <th class="py-2 px-4 border-b text-left">Pelapor</th>
                        <th class="py-2 px-4 border-b text-left">Deskripsi</th>
                        <th class="py-2 px-4 border-b text-left">Status</th>
                        <th class="py-2 px-4 border-b text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-2 px-4 border-b">{{ $report->created_at->format('d-m-Y H:i') }}</td>
                            <td class="py-2 px-4 border-b">{{ $report->item->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $report->user->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $report->description }}</td>
                            <td class="py-2 px-4 border-b">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                    @if($report->status === 'Reported') bg-yellow-100 text-yellow-800
                                    @elseif($report->status === 'In Progress') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <form action="{{ route('reports.update_damage_report', $report) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label for="status-select-{{ $report->id }}" class="sr-only">Ubah Status</label>
                                    <select id="status-select-{{ $report->id }}" name="status" onchange="this.form.submit()"
                                        class="border rounded p-1 dark:bg-gray-700 dark:text-gray-200">
                                        <option value="Reported" {{ $report->status == 'Reported' ? 'selected' : '' }}>Reported</option>
                                        <option value="In Progress" {{ $report->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada laporan kerusakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="md:hidden space-y-4">
            @forelse($reports as $report)
                <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ $report->item->name }}
                        </h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $report->created_at->format('d-m-Y H:i') }}
                        </span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-1"><strong>Pelapor:</strong> {{ $report->user->name }}</p>
                    <p class="text-gray-700 dark:text-gray-300 mb-1"><strong>Deskripsi:</strong> {{ $report->description }}</p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm px-2 py-1 rounded
                            @if($report->status === 'Reported') bg-yellow-100 text-yellow-800
                            @elseif($report->status === 'In Progress') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $report->status }}
                        </span>
                        <form action="{{ route('reports.update_damage_report', $report) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label for="status-mobile-{{ $report->id }}" class="sr-only">Ubah Status</label>
                            <select id="status-mobile-{{ $report->id }}" name="status" onchange="this.form.submit()"
                                class="border rounded p-1 dark:bg-gray-700 dark:text-gray-200">
                                <option value="Reported" {{ $report->status == 'Reported' ? 'selected' : '' }}>Dilaporkan</option>
                                <option value="In Progress" {{ $report->status == 'In Progress' ? 'selected' : '' }}>Sedang perbaikan</option>
                                <option value="Resolved" {{ $report->status == 'Resolved' ? 'selected' : '' }}>Terselesaikan</option>
                            </select>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400">Tidak ada laporan kerusakan.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
</x-app-layout>
