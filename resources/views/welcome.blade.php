<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BUMA IT Asset Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Menambahkan style untuk animasi fade-in --}}
    <style>
        @keyframes entry {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-entry {
            animation: entry 1s ease-out forwards;
        }

        .logo-letter {
            display: inline-block;
            /* Penting untuk animasi transform */
            opacity: 0;
            animation: entry 0.5s ease-out forwards;
        }

        .logo-letter:hover {
            transform: translateY(-5px);
            transition: transform 0.2s ease-in-out;
        }
    </style>
</head>

<body class="antialiased">
    {{-- Menambahkan gradien halus di belakang pola titik-titik --}}
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-gray-100 dark:bg-gray-900 selection:bg-blue-500 selection:text-white">
        <div class="absolute inset-0 bg-dots-darker bg-center dark:bg-dots-lighter"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-transparent to-green-50/50 dark:from-gray-800/50 dark:via-transparent dark:to-black/50"></div>
        <div class="max-w-7xl mx-auto p-6 lg:p-8 relative animate-entry">
            <div class="flex justify-center">
                <div class="text-5xl font-bold tracking-wider text-center">
                    <span class="logo-letter text-green-600 dark:text-green-500" style="animation-delay: 0.2s;">B</span>
                    <span class="logo-letter text-green-600 dark:text-green-500" style="animation-delay: 0.3s;">U</span>
                    <span class="logo-letter bg-gradient-to-r from-blue-500 to-orange-400 text-transparent bg-clip-text" style="animation-delay: 0.4s;">M</span>
                    <span class="logo-letter text-green-600 dark:text-green-500" style="animation-delay: 0.5s;">A</span>
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-400 mt-2 tracking-normal">IT ASSET
                        MANAGEMENT</p>
                </div>
            </div>

            <div class="mt-12 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Selamat Datang di Sistem Peminjaman Aset IT
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Aplikasi internal untuk manajemen, pelacakan, dan pelaporan aset IT di PT Bukit Makmur Mandiri Utama Jobsite ADT.
                </p>
                <div class="mt-8">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-8 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg hover:shadow-xl transform hover:-translate-y-1.5 transition-all duration-300">
                        Login Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>