<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pusat Laporan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- {{-- Kartu Pengantar --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Selamat Datang di Pusat Analitik Aset</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Pilih salah satu modul laporan di bawah ini untuk mendapatkan wawasan mendalam mengenai manajemen aset IT Anda.
                    </p>
                </div>
            </div> -->

            {{-- Grid untuk Kartu Laporan --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- 1. Kartu Laporan: Peminjaman --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <!-- <div class="flex-shrink-0 bg-red-500/10 dark:bg-red-500/20 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                                </svg>
                            </div> -->
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Peminjaman</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            <!-- Laporan krusial untuk memantau semua peminjaman yang telah melewati tanggal wajib kembali. -->
                        </p>
                        <a href="{{ route('laporan.peminjaman') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

                {{-- 2. Kartu Laporan: Pengembalian --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <!-- <div class="flex-shrink-0 bg-gray-500/10 dark:bg-gray-400/20 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                </svg>
                            </div> -->
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Pengembalian</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            <!-- Tampilkan semua catatan transaksi peminjaman, baik yang sudah selesai maupun yang masih berjalan. -->
                        </p>
                        <a href="{{ route('laporan.pengembalian') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

                {{-- 3. Kartu Laporan: Kerusakan Aset --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Kerusakan Aset</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            Daftar aset IT yang dilaporkan rusak atau mengalami kendala teknis setelah proses peminjaman selesai.
                        </p>
                        <a href="{{ route('laporan.kerusakan') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

                {{-- 4. Kartu Laporan: Stok Barang --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <!-- <div class="flex-shrink-0 bg-cyan-500/10 dark:bg-cyan-500/20 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-cyan-600 dark:text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </div> -->
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Stok Barang</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            <!-- Lihat inventaris lengkap semua aset IT beserta status ketersediaan dan kondisinya saat ini. -->
                        </p>
                        <a href="{{ route('laporan.stok') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

                {{-- 5. Kartu Laporan: Peminjaman per Karyawan --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Peminjaman per Karyawan</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            Riwayat peminjaman aset IT per individu karyawan beserta status pengembalian.
                        </p>
                        <a href="{{ route('laporan.perKaryawan') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

                {{-- 6. Kartu Laporan: Peminjaman per Departemen --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Peminjaman per Departemen</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            Rekapitulasi total peminjaman, aset yang masih dipinjam, sudah kembali, dan terlambat per departemen.
                        </p>
                        <a href="{{ route('laporan.perDepartemen') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

                {{-- 7. Kartu Laporan: Pengembalian Terlambat --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex items-center">
                            <h3 class="ml-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Laporan Pengembalian Terlambat</h3>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex-grow">
                            Daftar aset IT yang dikembalikan melewati batas waktu peminjaman, lengkap dengan durasi keterlambatan.
                        </p>
                        <a href="{{ route('laporan.pengembalianTerlambat') }}" class="mt-6 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                            Buka Laporan
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
