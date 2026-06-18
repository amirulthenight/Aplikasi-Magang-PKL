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
                        Rekapitulasi jumlah peminjaman aset IT berdasarkan departemen karyawan peminjam.
                    </p>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Departemen</th>
                                    <th class="px-6 py-3 text-center">Total Peminjaman</th>
                                    <th class="px-6 py-3 text-center">Sedang Dipinjam</th>
                                    <th class="px-6 py-3 text-center">Sudah Kembali</th>
                                    <th class="px-6 py-3 text-center">Terlambat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $index => $row)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $row->departemen }}</td>
                                    <td class="px-6 py-4 text-center">{{ $row->total }}</td>
                                    <td class="px-6 py-4 text-center">{{ $row->dipinjam }}</td>
                                    <td class="px-6 py-4 text-center">{{ $row->kembali }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($row->terlambat > 0)
                                        <span class="text-red-600 dark:text-red-400 font-semibold">{{ $row->terlambat }}</span>
                                        @else
                                        {{ $row->terlambat }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data laporan.</td>
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
