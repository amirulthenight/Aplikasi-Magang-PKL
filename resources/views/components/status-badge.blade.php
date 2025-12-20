@props(['status'])

@php
$colors = [
'Tersedia' => 'green',
'Dipinjam' => 'yellow',
'Rusak' => 'red',
];
$color = $colors[$status] ?? 'gray';
@endphp

<span class="bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300">
    {{ $status }}
</span>