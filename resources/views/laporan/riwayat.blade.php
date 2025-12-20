<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Riwayat Peminjaman') }}
        </h2>
    </x-slot>

    <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
        Kembali
    </a>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('laporan.riwayat') }}" method="GET" class="mb-4">
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <div>
                                <label for="start_date" class="text-sm">Dari Tanggal Pinjam:</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="text-sm">Sampai Tanggal Pinjam:</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-md hover:bg-blue-700">Filter</button>
                            <button onclick="printReport()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">PRINT</button>
                            <a href="{{ route('laporan.riwayat.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700">PDF</a>
                        </div>
                    </form>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Peminjam</th>
                                    <th class="px-6 py-3">Alasan Peminjam</th>
                                    <th class="px-6 py-3">Tgl Pinjam</th>
                                    <th class="px-6 py-3">Rencana Kembali</th>
                                    <th class="px-6 py-3">Tgl Kembali Aktual</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPeminjaman as $peminjaman)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $peminjaman->barang->nama_barang }}</td>
                                    <td class="px-6 py-4">{{ $peminjaman->karyawan->nama_karyawan }}</td>
                                    <td class="px-6 py-4">{{ $peminjaman->alasan_pinjam ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        <x-peminjaman-status-badge :peminjaman="$peminjaman" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center">Tidak ada data riwayat peminjaman.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $riwayatPeminjaman->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printReport() {
            const url = "{{ route('laporan.riwayat.pdf', request()->query()) }}";
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</x-app-layout>