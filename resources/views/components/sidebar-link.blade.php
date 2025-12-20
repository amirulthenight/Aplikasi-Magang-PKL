@props(['active'])

@php
$classes = ($active ?? false)
? 'flex items-center px-4 py-2 text-gray-900 bg-gray-200 dark:bg-gray-700 dark:text-white rounded-md'
: 'flex items-center px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-md transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="mr-3">
        {{ $icon ?? '' }}
    </span>

    <span>
        {{ $slot }}
    </span>
</a>