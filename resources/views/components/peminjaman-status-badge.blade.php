@props(['peminjaman'])

@php
$status = $peminjaman->status;
$label = $status;
$color = 'yellow'; // Default untuk 'Dipinjam'
$icon = '';

if ($status == 'Selesai') {
// INI DIA LOGIKA BARUNYA
if ($peminjaman->was_returned_late) {
$label = 'Selesai (Terlambat)';
$color = 'red';
$icon = '<svg class="w-4 h-4 -ms-1 me-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
</svg>';
} else {
$label = 'Selesai';
$color = 'green'; // Ganti dari 'green' ke 'blue' agar lebih konsisten
$icon = '<svg class="w-4 h-4 -ms-1 me-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>';
}
} elseif ($peminjaman->is_overdue) {
$label = 'Terlambat';
$color = 'red';
$icon = '<svg class="w-4 h-4 -ms-1 me-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
</svg>';
} else {
// Ini untuk status 'Dipinjam' (Tepat Waktu)
$color = 'yellow';
$icon = '<svg class="w-4 h-4 -ms-1 me-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>';
}
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900 dark:text-{{ $color }}-300">
    {!! $icon !!}
    {{ $label }}
</span>