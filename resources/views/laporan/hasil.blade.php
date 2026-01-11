<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TOMBOL AKSI --}}
            <div class="flex justify-end gap-3 mb-6">
                {{-- Tombol PDF --}}
                <a href="{{ url()->current() }}?export=pdf" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
            </div>

            {{-- CARD TABEL --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3">Peminjam</th>
                                    <th scope="col" class="px-6 py-3">Tanggal Pinjam</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $index => $row)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $row->barang->nama_barang ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $row->karyawan->nama_karyawan ?? '-' }}
                                        <div class="text-xs text-gray-500">{{ $row->karyawan->nik ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($row->status_peminjaman == 'Dipinjam')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Dipinjam</span>
                                        @elseif($row->status_peminjaman == 'Kembali')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Kembali</span>
                                        @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $row->status_peminjaman }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data laporan.
                                    </td>
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
