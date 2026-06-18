<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end gap-3 mb-6">
                <a href="{{ url()->current() }}?export=pdf" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Daftar riwayat aset IT yang mengalami kerusakan atau kendala teknis setelah dikembalikan oleh peminjam.
                    </p>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Kode Barang</th>
                                    <th class="px-6 py-3">Peminjam Terakhir</th>
                                    <th class="px-6 py-3">Tgl Kembali</th>
                                    <th class="px-6 py-3">Detail Kerusakan</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $index => $row)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $row->barang->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $row->barang->kode_barang ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $row->karyawan->nama_karyawan ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $row->tanggal_kembali_aktual ? \Carbon\Carbon::parse($row->tanggal_kembali_aktual)->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4">{{ $row->keterangan_kerusakan ?? 'Ada Kerusakan' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            {{ $row->status_aset ?? 'Rusak' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data laporan kerusakan aset.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
