@props(['active' => false])

@php
$classes = $active
    ? 'w-12 h-12 flex items-center justify-center rounded-xl bg-white text-green-600 shadow-md'
    : 'w-12 h-12 flex items-center justify-center rounded-xl text-gray-600 dark:text-gray-300 hover:bg-white hover:text-green-600 transition';
@endphp

<a {{ $attributes->class([$classes]) }}>

    {{ $slot }}
</a>
