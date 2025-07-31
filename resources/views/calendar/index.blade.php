<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kalender Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div id="calendar" class="rounded"></div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const borrowings = @json($borrowings);

        flatpickr("#calendar", {
            inline: true,
            locale: "id",
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const dateStr = dayElem.dateObj.toLocaleDateString("sv-SE");
                if (borrowings[dateStr]) {
                    let tooltip = borrowings[dateStr].map(entry => {
                        return `Kembali: ${entry.return_date ?? '-'}\nBarang: ${entry.items.join(', ')}`;
                    }).join('\n\n');
                    dayElem.setAttribute("title", tooltip);
                    dayElem.style.backgroundColor = "#facc15"; // kuning
                    dayElem.style.borderRadius = "50%";
                    dayElem.style.color = "#000";
                }
            }
        });
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush
</x-app-layout>
