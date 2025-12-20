<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Peminjaman Terlambat') }}
        </h2>
    </x-slot>
    <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
        Kembali
    </a>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('laporan.terlambat') }}" method="GET" class="mb-4">
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <div>
                                <label for="start_date" class="text-sm">Dari Tanggal:</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="text-sm">Sampai Tanggal:</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-md hover:bg-blue-700">Filter</button>
                            <button onclick="printReport()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">PRINT</button>
                            <a href="{{ route('laporan.terlambat.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700">PDF</a>
                        </div>
                    </form>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Kode Barang</th>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Peminjam</th>
                                    <th class="px-6 py-3">Tgl Pinjam</th>
                                    <th class="px-6 py-3">Rencana Kembali</th>
                                    <th class="px-6 py-3">Telat</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamanTerlambat as $peminjaman)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $peminjaman->barang->kode_barang }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $peminjaman->barang->nama_barang }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('karyawan.show', $peminjaman->karyawan_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
                                            {{ $peminjaman->karyawan->nama_karyawan }}
                                            <span class="block text-xs font-normal text-gray-500">{{ $peminjaman->karyawan->nik }}</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-red-500 font-bold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-red-500 font-bold">{{ $peminjaman->durasi_telat }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('karyawan.kirimPeringatan', $peminjaman->karyawan_id) }}" method="POST" onsubmit="return confirm('Kirim email peringatan ke {{ $peminjaman->karyawan->nama_karyawan }}?');">
                                            @csrf
                                            <button type="submit" class="p-1 text-yellow-500 hover:text-yellow-600 dark:hover:text-yellow-400" title="Kirim Peringatan">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center">Tidak ada data peminjaman terlambat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $peminjamanTerlambat->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            const url = "{{ route('laporan.terlambat.pdf', request()->query()) }}";
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</x-app-layout>
