@props(['active' => false, 'title' => null])

@php
    $classes = ($active ?? false)
                ? 'bg-gray-100 dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-600 dark:border-indigo-400'
                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => "$classes px-3 py-2.5 rounded-l-lg transition-all duration-200 ease-in-out flex items-center w-full", 'title' => $title]) }}>
    {{ $slot }}
</a>