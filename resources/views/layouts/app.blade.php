<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BUMA IT Asset') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Styles --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Style untuk Select2 agar cocok dengan Tailwind */
        .select2-container .select2-selection--single {
            height: 2.625rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.5rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.5rem !important;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgb(17 24 39);
            border-color: rgb(55 65 81);
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(209 213 219);
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: rgb(156 163 175) transparent transparent transparent;
        }

        .dark .select2-dropdown {
            background-color: rgb(31 41 55);
            border-color: rgb(75 85 99);
        }

        .dark .select2-search__field {
            background-color: rgb(17 24 39);
            border-color: rgb(55 65 81) !important;
            color: rgb(209 213 219);
        }

        .dark .select2-results__option {
            color: rgb(209 213 219);
        }

        .dark .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(59 130 246);
            color: white;
        }

        .dark .select2-results__option[aria-selected=true] {
            background-color: rgb(37 99 235);
            color: white;
        }

        /* Animasi untuk Logo di Sidebar */
        .logo-letter-sidebar {
            display: inline-block;
            transition: transform 0.2s ease-in-out;
        }

        .logo-container:hover .logo-letter-sidebar {
            transform: translateY(-4px);
        }
    </style>

    {{-- Dark Mode Initializer --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>


</head>

<body class="font-sans antialiased">
    <div class="h-screen flex overflow-hidden bg-gray-100 dark:bg-gray-900">
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md flex-col hidden sm:flex flex-shrink-0">
            <div class="flex items-center justify-center h-20 border-b dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-center">
                    <div class="text-3xl font-bold tracking-wider">
                        {{--LOGO BUMA--}}
                        <span class="text-green-600 dark:text-green-500">L</span><span class="text-green-600 dark:text-green-500">O</span><span class="bg-gradient-to-r from-blue-500 to-orange-400 text-transparent bg-clip-text">G</span><span class="text-green-600 dark:text-green-500">O</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 tracking-wider">IT ASSET MANAGEMENT</p>
                </a>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

                {{-- 1. DASHBOARD --}}
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M1 2.75A.75.75 0 0 1 1.75 2h16.5a.75.75 0 0 1 0 1.5H18v8.75A2.75 2.75 0 0 1 15.25 15h-1.072l.798 3.06a.75.75 0 0 1-1.452.38L13.41 18H6.59l-.114.44a.75.75 0 0 1-1.452-.38L5.823 15H4.75A2.75 2.75 0 0 1 2 12.25V3.5h-.25A.75.75 0 0 1 1 2.75ZM7.373 15l-.391 1.5h6.037l-.392-1.5H7.373Zm7.49-8.931a.75.75 0 0 1-.175 1.046 19.326 19.326 0 0 0-3.398 3.098.75.75 0 0 1-1.097.04L8.5 8.561l-2.22 2.22A.75.75 0 1 1 5.22 9.72l2.75-2.75a.75.75 0 0 1 1.06 0l1.664 1.663a20.786 20.786 0 0 1 3.122-2.74.75.75 0 0 1 1.046.176Z" clip-rule="evenodd" />
                        </svg>
                    </x-slot>
                </x-sidebar-link>

                @admin
                <p class="px-4 pt-4 text-xs font-semibold text-gray-500 uppercase">Menu Utama</p>

                {{-- 2. DATA BARANG --}}
                <x-sidebar-link :href="route('barang.index')" :active="request()->routeIs('barang.*')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 4v8.25l6.862-3.786A.75.75 0 0 0 18 14.25V6.443ZM9.25 18.693v-8.25l-7.25-4v7.807a.75.75 0 0 0 .388.657l6.862 3.786Z" />
                        </svg>
                    </x-slot>
                    {{ __('Data Barang') }}
                </x-sidebar-link>

                {{-- 3. DATA KARYAWAN --}}
                <x-sidebar-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.*')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z" />
                        </svg>
                    </x-slot>
                    {{ __('Data Karyawan') }}
                </x-sidebar-link>

                {{-- 4. RIWAYAT PEMINJAMAN --}}
                <x-sidebar-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M15.988 3.012A2.25 2.25 0 0 1 18 5.25v6.5A2.25 2.25 0 0 1 15.75 14H13.5V7A2.5 2.5 0 0 0 11 4.5H8.128a2.252 2.252 0 0 1 1.884-1.488A2.25 2.25 0 0 1 12.25 1h1.5a2.25 2.25 0 0 1 2.238 2.012ZM11.5 3.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v.25h-3v-.25Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M2 7a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm2 3.25a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Zm0 3.5a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                    </x-slot>
                    {{ __('Riwayat Peminjaman') }}
                </x-sidebar-link>

                {{-- 5. FORM PENGEMBALIAN (FITUR KHUSUS) --}}
                {{-- SAYA PINDAHKAN KESINI SUPAYA URUTANNYA JELAS SETELAH RIWAYAT --}}
                <x-sidebar-link :href="route('peminjaman.indexPengembalianCepat')" :active="request()->routeIs('peminjaman.indexPengembalianCepat')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-orange-500">
                            <path fill-rule="evenodd" d="M15.28 3.22a.75.75 0 0 0-1.06 0l-6.5 6.5a.75.75 0 0 0 0 1.06l6.5 6.5a.75.75 0 0 0 1.06-1.06L9.31 10l5.97-5.97a.75.75 0 0 0 0-1.06ZM4.75 3.5a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Z" clip-rule="evenodd" />
                        </svg>
                    </x-slot>
                    <span class="font-bold text-orange-600 dark:text-orange-400">{{ __('Form Pengembalian') }}</span>
                </x-sidebar-link>
                @endadmin

                {{-- KHUSUS ADMIN & KEPALA --}}
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'kepala')
                <x-sidebar-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 0 0 2 4.25v11.5A2.25 2.25 0 0 0 4.25 18h11.5A2.25 2.25 0 0 0 18 15.75V4.25A2.25 2.25 0 0 0 15.75 2H4.25ZM15 5.75a.75.75 0 0 0-1.5 0v8.5a.75.75 0 0 0 1.5 0v-8.5Zm-8.5 6a.75.75 0 0 0-1.5 0v2.5a.75.75 0 0 0 1.5 0v-2.5ZM8.584 9a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5a.75.75 0 0 1 .75-.75Zm3.58-1.25a.75.75 0 0 0-1.5 0v6.5a.75.75 0 0 0 1.5 0v-6.5Z" clip-rule="evenodd" />
                        </svg>
                    </x-slot>
                    {{ __('Data Laporan') }}
                </x-sidebar-link>
                @endif
            </nav>
        </aside>

        <div x-show="sidebarOpen" class="sm:hidden" x-cloak>
            <div x-show="sidebarOpen" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
            <aside x-show="sidebarOpen" class="fixed top-0 left-0 bottom-0 z-40 w-64 bg-white dark:bg-gray-800 shadow-xl flex flex-col flex-shrink-0">
                <div class="flex items-center justify-center h-20 border-b border-gray-200 dark:border-gray-700/50 flex-shrink-0 ">
                    <a href="{{ route('dashboard') }}" class="text-center logo-container">
                        <div class="text-3xl font-bold tracking-wider">
                            <span class="logo-letter-sidebar text-green-600 dark:text-green-500">B</span><span class="logo-letter-sidebar text-green-600 dark:text-green-500">U</span><span class="logo-letter-sidebar bg-gradient-to-r from-blue-500 to-orange-400 text-transparent bg-clip-text">M</span><span class="logo-letter-sidebar text-green-600 dark:text-green-500">A</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 tracking-wider">IT ASSET MANAGEMENT</p>
                    </a>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M1 2.75A.75.75 0 0 1 1.75 2h16.5a.75.75 0 0 1 0 1.5H18v8.75A2.75 2.75 0 0 1 15.25 15h-1.072l.798 3.06a.75.75 0 0 1-1.452.38L13.41 18H6.59l-.114.44a.75.75 0 0 1-1.452-.38L5.823 15H4.75A2.75 2.75 0 0 1 2 12.25V3.5h-.25A.75.75 0 0 1 1 2.75ZM7.373 15l-.391 1.5h6.037l-.392-1.5H7.373Zm7.49-8.931a.75.75 0 0 1-.175 1.046 19.326 19.326 0 0 0-3.398 3.098.75.75 0 0 1-1.097.04L8.5 8.561l-2.22 2.22A.75.75 0 1 1 5.22 9.72l2.75-2.75a.75.75 0 0 1 1.06 0l1.664 1.663a20.786 20.786 0 0 1 3.122-2.74.75.75 0 0 1 1.046.176Z" clip-rule="evenodd" />
                            </svg>
                        </x-slot>
                    </x-sidebar-link>

                    @admin
                    <p class="px-4 pt-4 text-xs font-semibold text-gray-500 uppercase">Menu Utama</p>
                    <x-sidebar-link :href="route('barang.index')" :active="request()->routeIs('barang.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 4v8.25l6.862-3.786A.75.75 0 0 0 18 14.25V6.443ZM9.25 18.693v-8.25l-7.25-4v7.807a.75.75 0 0 0 .388.657l6.862 3.786Z" />
                            </svg>
                        </x-slot>
                        {{ __('Data Barang') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('karyawan.index')" :active="request()->routeIs('karyawan.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z" />
                            </svg>
                        </x-slot>
                        {{ __('Data Karyawan') }}
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M15.988 3.012A2.25 2.25 0 0 1 18 5.25v6.5A2.25 2.25 0 0 1 15.75 14H13.5V7A2.5 2.5 0 0 0 11 4.5H8.128a2.252 2.252 0 0 1 1.884-1.488A2.25 2.25 0 0 1 12.25 1h1.5a2.25 2.25 0 0 1 2.238 2.012ZM11.5 3.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v.25h-3v-.25Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M2 7a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7Zm2 3.25a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Zm0 3.5a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </svg>
                        </x-slot>
                        {{ __('Data Peminjaman') }}
                    </x-sidebar-link>

                    {{-- MOBILE MENU: FORM PENGEMBALIAN JUGA DI SINI --}}
                    <x-sidebar-link :href="route('peminjaman.indexPengembalianCepat')" :active="request()->routeIs('peminjaman.indexPengembalianCepat')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-orange-500">
                                <path fill-rule="evenodd" d="M15.28 3.22a.75.75 0 0 0-1.06 0l-6.5 6.5a.75.75 0 0 0 0 1.06l6.5 6.5a.75.75 0 0 0 1.06-1.06L9.31 10l5.97-5.97a.75.75 0 0 0 0-1.06ZM4.75 3.5a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Z" clip-rule="evenodd" />
                            </svg>
                        </x-slot>
                        <span class="font-bold text-orange-600 dark:text-orange-400">{{ __('Form Pengembalian') }}</span>
                    </x-sidebar-link>

                    @endadmin

                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'kepala')
                    <x-sidebar-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                        <x-slot name="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M4.25 2A2.25 2.25 0 0 0 2 4.25v11.5A2.25 2.25 0 0 0 4.25 18h11.5A2.25 2.25 0 0 0 18 15.75V4.25A2.25 2.25 0 0 0 15.75 2H4.25ZM15 5.75a.75.75 0 0 0-1.5 0v8.5a.75.75 0 0 0 1.5 0v-8.5Zm-8.5 6a.75.75 0 0 0-1.5 0v2.5a.75.75 0 0 0 1.5 0v-2.5ZM8.584 9a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5a.75.75 0 0 1 .75-.75Zm3.58-1.25a.75.75 0 0 0-1.5 0v6.5a.75.75 0 0 0 1.5 0v-6.5Z" clip-rule="evenodd" />
                            </svg>
                        </x-slot>
                        {{ __('Data Laporan') }}
                    </x-sidebar-link>
                    @endif
                </nav>
            </aside>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Header Area --}}
            @include('layouts.navigation')

            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                @if (isset($header))
                <header class="bg-white/90 dark:bg-gray-800/85 backdrop-blur-lg shadow-sm sticky top-0 z-20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                @endif
                {{ $slot }}
            </main>

            <footer class="text-center py-4 text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900">
                Copyright &copy; {{ date('Y') }} PT Bukit Makmur Mandiri Utama Jobsite ADT. All Rights Reserved.
            </footer>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @stack('scripts')

    <script>
        // Inisialisasi Tippy.js untuk tooltip di seluruh aplikasi
        document.addEventListener('DOMContentLoaded', function() {
            tippy('[data-tippy-content]');
        });
    </script>
</body>

</html>
