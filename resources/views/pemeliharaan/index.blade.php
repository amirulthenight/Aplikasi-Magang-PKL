
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Data Pemeliharaan Aset IT
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-5">
                        <h3 class="text-lg font-semibold">
                            Daftar Aset Mengalami Kerusakan
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Data aset yang dikembalikan dengan catatan kerusakan.
                        </p>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">No.</th>
                                    <th class="px-4 py-3">Kode Barang</th>
                                    <th class="px-4 py-3">Nama Barang</th>
                                    <th class="px-4 py-3">Peminjam</th>
                                    <th class="px-4 py-3">Tanggal Kembali</th>
                                    <th class="px-4 py-3">Detail Kerusakan</th>
                                    <th class="px-4 py-3">Status Pemeliharaan</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($peminjamans as $index => $peminjaman)
                                    @php
                                        $status = $peminjaman->pemeliharaan?->status ?? 'Belum dicatat';

                                        $warnaStatus = [
                                            'Rusak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'Perbaikan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'Tersedia' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'Belum dicatat' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        ][$status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp

                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-4 py-4">
                                            {{ $peminjamans->firstItem() + $index }}
                                        </td>

                                        <td class="px-4 py-4 font-medium">
                                            {{ $peminjaman->barang->kode_barang ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $peminjaman->barang->nama_barang ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $peminjaman->karyawan->nama_karyawan ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            {{ $peminjaman->tanggal_kembali_aktual
                                                ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d M Y H:i')
                                                : '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $peminjaman->keterangan_kerusakan ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $warnaStatus }}">
                                                {{ $status }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if (!$peminjaman->pemeliharaan)
                                                <a href="{{ route('pemeliharaan.create', $peminjaman->id) }}"
                                                   class="inline-block px-3 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                                                    Catat Pemeliharaan
                                                </a>
                                            @else
                                                <a href="{{ route('pemeliharaan.edit', $peminjaman->pemeliharaan->id) }}"
                                                   class="inline-block px-3 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                                                    {{ $status === 'Tersedia' ? 'Lihat Detail' : 'Proses Perbaikan' }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada aset yang dikembalikan dalam kondisi rusak.
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
