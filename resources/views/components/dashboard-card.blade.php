@props(['title', 'value'])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $title }}</div>
    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</div>
</div>
