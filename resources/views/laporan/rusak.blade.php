<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Barang Rusak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col sm:flex-row items-center justify-between mb-4">
                        <form action="{{ route('laporan.rusak') }}" method="GET" class="flex items-center gap-2">
                            <label for="site" class="text-sm">Filter per Site:</label>
                            <select name="site" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Site</option>
                                @foreach ($sites as $siteOption)
                                <option value="{{ $siteOption->site }}" {{ request('site') == $siteOption->site ? 'selected' : '' }}>{{ $siteOption->site }}</option>
                                @endforeach
                            </select>
                        </form>
                        <div class="flex items-center gap-2 mt-2 sm:mt-0">
                            <button type="button" onclick="printReport()" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold rounded-md hover:bg-gray-700">Print</button>
                            <a href="{{ route('laporan.rusak.pdf', request()->query()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700">PDF</a>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Kode Barang</th>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Serial Number</th>
                                    <th class="px-6 py-3">Site</th>
                                    <th class="px-6 py-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangRusak as $barang)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $loop->iteration + ($barangRusak->currentPage() - 1) * $barangRusak->perPage() }}</td>
                                    <td class="px-6 py-4">{{ $barang->kode_barang }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $barang->nama_barang }}</td>
                                    <td class="px-6 py-4">{{ $barang->serial_number ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $barang->site ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $barang->keterangan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">Tidak ada data barang rusak yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $barangRusak->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printReport() {
            const url = "{{ route('laporan.rusak.pdf', request()->query()) }}";
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</x-app-layout>