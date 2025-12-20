<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Karyawan') }}
            </h2>
            <a href="{{ route('karyawan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-bold">{{ $karyawan->nama_karyawan }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $karyawan->jabatan }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p><strong>NIK:</strong> {{ $karyawan->nik }}</p>
                            <p><strong>Departemen:</strong> {{ $karyawan->departemen }}</p>
                            <p><strong>Site:</strong> {{ $karyawan->site ?? '-' }}</p>
                        </div>
                        @if($karyawan->keterangan)
                        <div class="md:col-span-2 mt-4">
                            <p><strong>Keterangan:</strong></p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $karyawan->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Riwayat Peminjaman Barang</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3">Tgl Pinjam</th>
                                    <th scope="col" class="px-6 py-3">Rencana Kembali</th>
                                    <th scope="col" class="px-6 py-3">Tgl Kembali Aktual</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamans as $peminjaman)

                                {{--@php
                                $isOverdue = $peminjaman->status == 'Dipinjam' && \Carbon\Carbon::now()->gt($peminjaman->tanggal_wajib_kembali);
                                @endphp--}}

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 {{ $peminjaman->is_overdue ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $peminjaman->barang->nama_barang }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($peminjaman->status == 'Dipinjam')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Dipinjam</span>
                                        @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Karyawan ini belum pernah meminjam barang.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $peminjamans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
