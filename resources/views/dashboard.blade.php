<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/50 rounded-full">
                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2.5a5.5 5.5 0 0 1 3.096 10.047 9.005 9.005 0 0 1 5.9 8.181.75.75 0 1 1-1.499.044 7.5 7.5 0 0 0-14.993 0 .75.75 0 0 1-1.5-.045 9.005 9.005 0 0 1 5.9-8.181A5.5 5.5 0 0 1 12 2.5ZM8 8a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold leading-tight text-gray-800 dark:text-gray-200">
                    Selamat Datang Kembali, {{ Auth::user()->name }}!
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Berikut adalah ringkasan aktivitas sistem hari ini.</p>
            </div>
        </div>
    </x-slot>

    {{-- ====================================================================== --}}
    {{-- || MODAL PERINGATAN AKSI WAJIB                                      || --}}
    {{-- ====================================================================== --}}
    <div x-data="{ showModal: {{ $showPeringatanModal ? 'true' : 'false' }} }"
        x-show="showModal"
        x-on:keydown.escape.window="showModal = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">

        {{-- Latar Belakang Modal --}}
        <div x-show="showModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/75"></div>

        {{-- Konten Modal --}}
        <div x-show="showModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-4xl px-4 mx-auto my-8 transform">
            <div class="relative p-6 bg-white rounded-xl shadow-xl sm:p-8 dark:bg-gray-800">
                {{-- Header Modal --}}

                <div class="mb-8 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900/50">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>
                    <h3 id="modal-title" class="text-2xl font-bold text-gray-900 dark:text-white">Peringatan Aksi Wajib</h3>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">Ditemukan beberapa isu kritis yang perlu Anda tindak lanjuti segera.</p>
                </div>

                {{-- ====================================================================== --}}
                {{-- || Konten Grid (Tabel Peringatan)                                     || --}}
                {{-- ====================================================================== --}}
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    {{-- ---------------------------------------------------------------------- --}}
                    {{-- | Tabel Peminjaman Terlambat                                           | --}}
                    {{-- ---------------------------------------------------------------------- --}}
                    <div class="flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Peminjaman Terlambat
                            </h4>
                            <a href="{{ route('laporan.terlambat') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">Lihat Semua &rarr;</a>
                        </div>
                        @if ($semuaPeminjamanTerlambat->isNotEmpty())
                        <div class="overflow-y-auto border rounded-lg shadow-sm max-h-64 dark:border-gray-700">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">NIK</th>
                                        <th class="px-4 py-3">Peminjam</th>
                                        <th class="px-4 py-3">Barang</th>
                                        <th class="px-4 py-3">Telat</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($semuaPeminjamanTerlambat as $peminjaman)
                                    <tr class="transition-colors duration-200 even:bg-gray-50 dark:even:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3">{{ $peminjaman->karyawan->nik }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $peminjaman->karyawan->nama_karyawan }}</td>
                                        <td class="px-4 py-3">{{ $peminjaman->barang->nama_barang }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ $peminjaman->durasi_telat ?? '-'}}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center p-6 text-center border-2 border-dashed rounded-lg h-64 dark:border-gray-600">
                            <svg class="w-12 h-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-3 font-semibold text-gray-800 dark:text-gray-200">Kerja Bagus!</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada peminjaman yang terlambat.</p>
                        </div>
                        @endif
                    </div>

                    {{-- ---------------------------------------------------------------------- --}}
                    {{-- | Tabel Aset Rusak                                                     | --}}
                    {{-- ---------------------------------------------------------------------- --}}
                    <div class="flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Aset Rusak</h4>
                            <a href="{{ route('barang.index', ['status' => 'Rusak']) }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">Lihat Semua &rarr;</a>
                        </div>
                        @if ($asetRusak->isNotEmpty())
                        <div class="overflow-y-auto border rounded-lg shadow-sm max-h-64 dark:border-gray-700">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">Kode Barang</th>
                                        <th class="px-4 py-3">Nama Barang</th>
                                        <th class="px-4 py-3">Peminjam Terakhir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($asetRusak as $barang)
                                    <tr class="transition-colors duration-200 even:bg-gray-50 dark:even:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $barang->kode_barang }}</td>
                                        <td class="px-4 py-3">{{ $barang->nama_barang }}</td>
                                        <td class="px-4 py-3">{{ $barang->peminjam_terakhir ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center p-6 text-center border-2 border-dashed rounded-lg h-64 dark:border-gray-600">
                            <svg class="w-12 h-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-3 font-semibold text-gray-800 dark:text-gray-200">Luar Biasa!</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada aset yang dilaporkan rusak.</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ====================================================================== --}}
                {{-- || Tombol Aksi Modal                                                || --}}
                {{-- ====================================================================== --}}
                <div class="flex justify-center mt-8">
                    <button @click="showModal = false" type="button"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white uppercase transition-colors duration-200 bg-blue-600 border border-transparent rounded-lg tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Tutup & Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ====================================================================== --}}
    {{-- || BAGIAN UTAMA DASHBOARD                                           || --}}
    {{-- ====================================================================== --}}
    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div x-data="{}" x-init="
                    let cards = $el.querySelectorAll('.stat-card');
                    cards.forEach((card, index) => {
                        card.style.transitionDelay = `${index * 75}ms`;
                    });
                    $nextTick(() => { cards.forEach(card => card.classList.remove('opacity-0', 'translate-y-4')); });
                " class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                {{-- Total Aset --}}
                <a href="{{ route('barang.index') }}" class="stat-card block transition-all duration-500 transform hover:-translate-y-1.5 group opacity-0 translate-y-4">
                    <div class="flex items-center p-6 space-x-4 bg-white rounded-xl shadow-lg dark:bg-gray-800 hover:shadow-xl h-full group-hover:ring-2 group-hover:ring-blue-300 dark:group-hover:ring-blue-600">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full dark:bg-gradient-to-br dark:from-blue-900/50 dark:to-blue-800/60">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Aset</p>
                            <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $totalAset }}</p>
                        </div>
                    </div>
                </a>
                {{-- Tersedia --}}
                <a href="{{ route('barang.index', ['status' => 'Tersedia']) }}" class="stat-card block transition-all duration-500 transform hover:-translate-y-1.5 group opacity-0 translate-y-4">
                    <div class="flex items-center p-6 space-x-4 bg-white rounded-xl shadow-lg dark:bg-gray-800 hover:shadow-xl h-full group-hover:ring-2 group-hover:ring-green-300 dark:group-hover:ring-green-600">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-full dark:bg-gradient-to-br dark:from-green-900/50 dark:to-green-800/60">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tersedia</p>
                            <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $stokTersedia }}</p>
                        </div>
                    </div>
                </a>
                {{-- Dipinjam --}}
                <a href="{{ route('barang.index', ['status' => 'Dipinjam']) }}" class="stat-card block transition-all duration-500 transform hover:-translate-y-1.5 group opacity-0 translate-y-4">
                    <div class="flex items-center p-6 space-x-4 bg-white rounded-xl shadow-lg dark:bg-gray-800 hover:shadow-xl h-full group-hover:ring-2 group-hover:ring-yellow-300 dark:group-hover:ring-yellow-600">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-full dark:bg-gradient-to-br dark:from-yellow-900/50 dark:to-yellow-800/60">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Dipinjam</p>
                            <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $sedangDipinjam }}</p>
                        </div>
                    </div>
                </a>

                {{-- Aset Rusak --}}
                <a href="{{ route('barang.index', ['status' => 'Rusak']) }}" class="stat-card block transition-all duration-500 transform hover:-translate-y-1.5 group opacity-0 translate-y-4">
                    <div class="flex items-center p-6 space-x-4 rounded-xl shadow-lg hover:shadow-xl h-full
                        {{ $jumlahRusak > 0 ? 'bg-orange-50 dark:bg-orange-900/20 group-hover:ring-2 group-hover:ring-orange-300 dark:group-hover:ring-orange-600' : 'bg-white dark:bg-gray-800 group-hover:ring-2 group-hover:ring-gray-300 dark:group-hover:ring-gray-600' }}">

                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full
                            {{ $jumlahRusak > 0 ? 'bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/50 dark:to-orange-800/60' : 'bg-gray-100 dark:bg-gray-700' }}">
                            <svg class="w-6 h-6
                                {{ $jumlahRusak > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.878-5.878m0 0a5.232 5.232 0 01-1.487-1.487l-2.047-2.047L4.5 12.75l2.047 2.047a5.232 5.232 0 011.487 1.487M12 10.5h.008v.008H12v-.008z" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-semibold
                                {{ $jumlahRusak > 0 ? 'text-orange-700 dark:text-orange-300' : 'text-gray-500 dark:text-gray-400' }}">
                                Aset Rusak
                            </p>
                            <p class="text-2xl font-bold {{ $jumlahRusak > 0 ? 'text-orange-800 dark:text-orange-200' : 'text-gray-800 dark:text-gray-200' }}">
                                {{ $jumlahRusak }}
                            </p>
                        </div>
                    </div>
                </a>

                {{-- Terlambat --}}
                <a href="{{ route('laporan.terlambat') }}" class="stat-card block transition-all duration-500 transform hover:-translate-y-1.5 group opacity-0 translate-y-4">
                    <div class="flex items-center p-6 space-x-4 rounded-xl shadow-lg hover:shadow-xl h-full
                        {{ $terlambatKembali > 0 ? 'bg-red-50 dark:bg-red-900/20 group-hover:ring-2 group-hover:ring-red-300 dark:group-hover:ring-red-600' : 'bg-white dark:bg-gray-800 group-hover:ring-2 group-hover:ring-gray-300 dark:group-hover:ring-gray-600' }}">

                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full
                            {{ $terlambatKembali > 0 ? 'bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/60' : 'bg-gray-100 dark:bg-gray-700' }}">
                            <svg class="w-6 h-6
                                {{ $terlambatKembali > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-semibold
                                {{ $terlambatKembali > 0 ? 'text-red-700 dark:text-red-300' : 'text-gray-500 dark:text-gray-400' }}">
                                Terlambat
                            </p>
                            <p class="text-2xl font-bold {{ $terlambatKembali > 0 ? 'text-red-800 dark:text-red-200' : 'text-gray-800 dark:text-gray-200' }}">
                                {{ $terlambatKembali }}
                            </p>
                        </div>
                    </div>
                </a>

                {{-- Ketersediaan (Final dengan Ikon Tren + Panah yang Konsisten) --}}
                @php
                if ($availablePercentage > 50) {
                // Kondisi Aman (Hijau)
                $iconPath = 'M3 17l6-6l4 4l8-8 M17 3h4v4';
                $ringClass = 'group-hover:ring-green-300 dark:group-hover:ring-green-600';
                $textClass = 'text-green-800 dark:text-green-200';
                $bgIconClass = 'bg-gradient-to-br from-green-100 to-green-200 dark:bg-gradient-to-br dark:from-green-900/50 dark:to-green-800/60';
                $iconColorClass = 'text-green-600 dark:text-green-400';
                $barBgClass = 'bg-green-200';
                $barFillClass = 'bg-green-500';
                } elseif ($availablePercentage > 30) {
                // Kondisi Waspada (Kuning)
                $iconPath = 'M3 13l6 2l4-2l8 2 M17 11h4v4';
                $ringClass = 'group-hover:ring-yellow-300 dark:group-hover:ring-yellow-600';
                $textClass = 'text-yellow-800 dark:text-yellow-200';
                $bgIconClass = 'bg-gradient-to-br from-yellow-100 to-yellow-200 dark:bg-gradient-to-br dark:from-yellow-900/50 dark:to-yellow-800/60';
                $iconColorClass = 'text-yellow-600 dark:text-yellow-400';
                $barBgClass = 'bg-yellow-200';
                $barFillClass = 'bg-yellow-500';
                } else {
                // Kondisi Bahaya (Merah)
                $iconPath = 'M3 7l6 6l4-4l8 8 M17 21h4v-4';
                $ringClass = 'group-hover:ring-red-300 dark:group-hover:ring-red-600';
                $textClass = 'text-red-800 dark:text-red-200';
                $bgIconClass = 'bg-gradient-to-br from-red-100 to-red-200 dark:bg-gradient-to-br dark:from-red-900/50 dark:to-red-800/60';
                $iconColorClass = 'text-red-600 dark:text-red-400';
                $barBgClass = 'bg-red-200';
                $barFillClass = 'bg-red-500';
                }
                @endphp
                <a href="{{ route('barang.index') }}" class="stat-card block transition-all duration-500 transform hover:-translate-y-1.5 group opacity-0 translate-y-4">
                    <div class="flex flex-col justify-between p-6 bg-white rounded-xl shadow-lg dark:bg-gray-800 hover:shadow-xl h-full group-hover:ring-2 {{ $ringClass }}">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Available %</p>
                                <p class="text-2xl font-bold {{ $textClass }}">{{ $availablePercentage }}%</p>
                            </div>
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full {{ $bgIconClass }}">
                                <svg class="w-6 h-6 {{ $iconColorClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full h-2 mt-4 rounded-full dark:bg-gray-700 {{ $barBgClass }}">
                            <div class="h-2 rounded-full {{ $barFillClass }}" style="width: {{ $availablePercentage.'%' }}"></div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Main Grid (Chart & Peminjaman Terlambat) --}}
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-5">
                {{-- Kolom Kiri: Chart --}}
                <div class="p-6 bg-white rounded-xl shadow-lg lg:col-span-3 dark:bg-gray-800">
                    <div class="flex flex-col items-start justify-between gap-4 mb-6 sm:flex-row sm:items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tren Peminjaman Barang</h3>
                        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2"
                            x-data="{ tanggal: '{{ request('tanggal', now()->subDays(30)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}', init() {
                                  flatpickr(this.$refs.kalender, {
                                      mode: 'range',
                                      dateFormat: 'Y-m-d',
                                      defaultDate: this.tanggal.split(' to '),
                                      onChange: (selectedDates, dateStr, instance) => {
                                          this.tanggal = dateStr;
                                      }
                                  });
                              }}">
                            <input type="text" x-ref="kalender" name="tanggal"
                                class="w-64 text-sm text-center bg-gray-50 border-gray-300 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <button type="submit"
                                class="p-2 text-white transition-colors duration-200 bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                                title="Filter Tanggal">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <div class="h-80">
                        <canvas id="barangPopulerChart" data-chart-data='{{ json_encode($chartData) }}'></canvas>
                    </div>
                </div>

                {{-- Kolom Kanan: Peminjaman Terlambat --}}
                <div class="flex flex-col p-6 bg-white rounded-xl shadow-lg lg:col-span-2 dark:bg-gray-800">
                    <h3 class="flex-shrink-0 mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Peminjaman Terlambat</h3>
                    <div class="flex-grow -mr-3 pr-3 overflow-y-auto" data-simplebar>
                        @forelse ($peminjamanTerlambat as $peminjaman)
                        <div class="flex items-center py-4 space-x-3 border-b dark:border-gray-700 last:border-b-0">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $peminjaman->karyawan->nama_karyawan }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Meminjam {{ $peminjaman->barang->nama_barang }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-sm font-semibold text-red-500">{{ $peminjaman->durasi_telat }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="flex flex-col items-center justify-center h-full text-center">
                            <svg class="w-12 h-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Tidak ada peminjaman terlambat saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tabel Aktivitas Terkini --}}
            <div class="p-6 mt-8 bg-white rounded-xl shadow-lg dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Aktivitas Sistem Terkini</h3>
                <div class="space-y-4">
                    @forelse($aktivitasTerkini as $aktivitas)
                    <div class="flex items-start space-x-4">
                        {{-- ========================================================== --}}
                        {{-- || LOGIKA IKON BARU YANG LEBIH PINTAR                            || --}}
                        {{-- ========================================================== --}}
                        @if ($aktivitas->status == 'Selesai')
                        {{-- IKON DIKEMBALIKAN (HIJAU) --}}
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-green-100 rounded-full dark:bg-green-900/50">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                            </svg>
                        </div>
                        @elseif ($aktivitas->updated_at->gt($aktivitas->created_at->addSeconds(5)))
                        {{-- IKON DIPERPANJANG (KUNING) --}}
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full dark:bg-yellow-900/50">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        @else
                        {{-- IKON PEMINJAMAN BARU/BIASA (BIRU) --}}
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full dark:bg-blue-900/50">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                        </div>
                        @endif

                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-gray-200">
                                {{-- ========================================================== --}}
                                {{-- || DESKRIPSI BARU YANG LEBIH PINTAR                           || --}}
                                {{-- ========================================================== --}}
                                @if ($aktivitas->status == 'Selesai')
                                <span class="font-bold">{{ $aktivitas->karyawan->nama_karyawan }}</span> telah <span class="font-semibold text-green-600 dark:text-green-400">mengembalikan</span> {{ $aktivitas->barang->nama_barang }}.
                                @elseif ($aktivitas->updated_at->gt($aktivitas->created_at->addSeconds(5)))
                                Admin telah <span class="font-semibold text-yellow-600 dark:text-yellow-400">memperpanjang</span> peminjaman {{ $aktivitas->barang->nama_barang }} untuk <span class="font-bold">{{ $aktivitas->karyawan->nama_karyawan }}</span>.
                                @else
                                <span class="font-bold">{{ $aktivitas->karyawan->nama_karyawan }}</span> telah <span class="font-semibold text-blue-600 dark:text-blue-400">meminjam</span> {{ $aktivitas->barang->nama_barang }}.
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{-- TANGGAL UPDATE AKAN SELALU AKURAT --}}
                                {{ $aktivitas->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================================== --}}
    {{-- || SCRIPT UNTUK GRAFIK (TIDAK ADA PERUBAHAN LOGIKA)                 || --}}
    {{-- ====================================================================== --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css" />
    <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('barangPopulerChart');
            if (chartCanvas) {
                const isDarkMode = document.documentElement.classList.contains('dark');
                const chartJsonData = JSON.parse(chartCanvas.dataset.chartData);

                new Chart(chartCanvas, {
                    type: 'bar',
                    data: {
                        labels: chartJsonData.labels,
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: chartJsonData.data,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: isDarkMode ? '#9CA3AF' : '#6B7280'
                                },
                                grid: {
                                    color: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: isDarkMode ? '#9CA3AF' : '#6B7280',
                                    stepSize: 1
                                },
                                grid: {
                                    color: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                bodyColor: isDarkMode ? '#E5E7EB' : '#1F2937',
                                titleColor: isDarkMode ? '#E5E7EB' : '#1F2937',
                                backgroundColor: isDarkMode ? '#1F2937' : '#FFFFFF',
                                borderColor: isDarkMode ? '#4B5563' : '#D1D5DB',
                                borderWidth: 1
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>