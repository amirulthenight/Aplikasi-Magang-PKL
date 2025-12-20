<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Peminjaman per Karyawan') }}
            </h2>
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('laporan.perKaryawan') }}" method="GET" class="mb-4">
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <label for="karyawan_id" class="text-sm font-bold">PILIH KARYAWAN:</label>
                            <select name="karyawan_id" id="karyawan_id" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm sm:w-80 select2-search">
                                <option value="">-- Tampilkan Riwayat untuk --</option>
                                @foreach ($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}" {{ optional($karyawanDipilih)->id == $karyawan->id ? 'selected' : '' }}>
                                    {{ $karyawan->nama_karyawan }} ({{ $karyawan->nik }})
                                </option>
                                @endforeach
                            </select>

                            @if($karyawanDipilih)
                            <div class="flex items-center gap-2">
                                <button onclick="printReport()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">PRINT</button>
                                <a href="{{ route('laporan.perKaryawan.pdf', ['karyawan_id' => $karyawanDipilih->id]) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700">PDF</a>
                            </div>
                            @endif
                        </div>
                    </form>

                    @if($karyawanDipilih)
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pinjam</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $statistik['total_pinjam'] }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sedang Dipinjam</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $statistik['sedang_dipinjam'] }}</p>
                        </div>
                        <div class="p-4 bg-red-50 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800">
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">Terlambat Sekarang</p>
                            <p class="mt-1 text-3xl font-semibold text-red-500">
                                {{ $statistik['terlambat_sekarang'] }}
                            </p>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Rekor Terlambat</p>
                            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                {{ $statistik['total_rekor_terlambat'] }}
                            </p>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Barang</th>
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
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        <x-peminjaman-status-badge :peminjaman="$peminjaman" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">Karyawan ini belum memiliki riwayat peminjaman.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $riwayatPeminjaman->withQueryString()->links() }}
                    </div>

                    @else
                    <div class="mt-6 p-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-center">
                        <p class="text-gray-500">Silakan pilih karyawan di atas untuk menampilkan riwayat peminjaman mereka.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#karyawan_id').select2({
                placeholder: "-- Tampilkan Riwayat untuk --",
                allowClear: true
            });
        });
    </script>
    @endpush

    <script>
        function printReport() {
            const url = "{{ route('laporan.perKaryawan.pdf', ['karyawan_id' => optional($karyawanDipilih)->id]) }}";
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</x-app-layout>
