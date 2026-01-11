<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Riwayat Per Karyawan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTER --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('laporan.perKaryawan') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full sm:w-1/2">
                            <label class="block mb-2 font-bold text-sm">Cari Nama Karyawan:</label>
                            <select name="karyawan_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_karyawan }} - {{ $k->nik }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 transition ease-in-out duration-150 h-10">
                            Lihat Data
                        </button>
                    </form>
                </div>
            </div>

            @if($selectedKaryawan)
            {{-- HASIL TABEL --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                    Riwayat: {{ $selectedKaryawan->nama_karyawan }}
                </h3>
                <a href="{{ url()->current() }}?karyawan_id={{ $selectedKaryawan->id }}&export=pdf" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 transition">
                    Download PDF
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Nama Barang</th>
                                <th class="px-6 py-3">Tanggal Pinjam</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $row)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $row->barang->nama_barang }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $row->status_peminjaman }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
