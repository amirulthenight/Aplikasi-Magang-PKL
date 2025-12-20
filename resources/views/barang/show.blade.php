<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Aset & Riwayat Peminjaman') }}
            </h2>
            <a href="{{ route('barang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                Kembali ke Daftar Barang
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-bold">{{ $barang->nama_barang }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $barang->kode_barang }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p><strong>Status Saat Ini:</strong> <x-status-badge :status="$barang->status" /></p>
                            <p><strong>Serial Number:</strong> {{ $barang->serial_number ?? '-' }}</p>
                            <p><strong>Site:</strong> {{ $barang->site ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Riwayat Peminjaman Aset Ini</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Peminjam</th>
                                    <th class="px-6 py-3">Tgl Pinjam</th>
                                    <th class="px-6 py-3">Rencana Kembali</th>
                                    <th class="px-6 py-3">Tgl Kembali Aktual</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPeminjaman as $peminjaman)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('karyawan.show', $peminjaman->karyawan_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $peminjaman->karyawan->nama_karyawan }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        <x-peminjaman-status-badge :peminjaman="$peminjaman" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">Aset ini belum pernah dipinjam.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $riwayatPeminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
