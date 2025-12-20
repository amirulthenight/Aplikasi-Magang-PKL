<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Barang Paling Populer') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">

                    <!-- Form Filter dan Tombol Export -->
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-4">
                        <form action="{{ route('laporan.populer') }}" method="GET" class="flex items-center gap-2">
                            <label for="limit" class="text-sm">Tampilkan Peringkat:</label>
                            <select name="limit" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                @php
                                $limitOptions = [5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
                                @endphp
                                @foreach ($limitOptions as $option)
                                <option value="{{ $option }}" @if($limit==$option) selected @endif>
                                    Top {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                        <div class="flex items-center gap-2">
                            <button onclick="printReport()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800
                            uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">PRINT</button>
                            <a href="{{ route('laporan.populer.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700">PDF</a>
                        </div>
                    </div>

                    <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                        Kembali ke Menu Laporan
                    </a>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Peringkat</th>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Peminjam Terakhir</th>
                                    <th class="px-6 py-3">Dept. Peminjam Terbanyak</th>
                                    <th class="px-6 py-3 text-center">Total Dipinjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangPopuler as $barang)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-bold text-lg text-gray-700 dark:text-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $barang->nama_barang }}
                                        <span class="block text-xs text-gray-500">{{ $barang->kode_barang }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-status-badge :status="$barang->status" />
                                    </td>

                                    <td class="px-6 py-4">{{ $barang->peminjam_terakhir ?? '-' }}

                                    </td>

                                    <td class="px-6 py-4">{{ $barang->departemen_populer ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center font-bold">{{ $barang->total_dipinjam }} kali</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">Belum ada data peminjaman untuk ditampilkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printReport() {
            const url = "{{ route('laporan.populer.pdf', request()->query()) }}";
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</x-app-layout>