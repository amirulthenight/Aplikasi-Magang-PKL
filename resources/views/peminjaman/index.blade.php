<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Riwayat Peminjaman') }}
            </h2>
            <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 -ms-1 me-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Peminjaman Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Form Pencarian dan Filter -->
                    <div class="mb-6">
                        <form action="{{ route('peminjaman.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                            <div> {{-- Hapus flex-grow dari sini --}}
                                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="w-full sm:w-64 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            </div>
                            <div class="flex-shrink-0">
                                <select name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="berjalan" @selected(!request('status') || request('status')==='berjalan' )>Berjalan</option>
                                    <option value="selesai" @selected(request('status')==='selesai' )>Selesai</option>
                                    <option value="semua" @selected(request('status')==='semua' )>Semua</option>
                                </select>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Barang</th>
                                    <th class="px-6 py-3">Peminjam</th>
                                    <th class="px-6 py-3">Alasan Peminjam</th>
                                    <th class="px-6 py-3">Tgl/Waktu Pinjam</th>
                                    <th class="px-6 py-3">Wajib Kembali</th>
                                    <th class="px-6 py-3">Kembali Aktual</th>
                                    <!-- <th class="px-6 py-3">Status</th> -->
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamans as $peminjaman)
                                <tr class="border-b dark:border-gray-700 {{ $peminjaman->is_overdue ? 'bg-red-50 dark:bg-red-900/20' : 'bg-white dark:bg-gray-800' }}">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $peminjaman->barang->nama_barang }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 dark:text-gray-200">
                                            {{ $peminjaman->karyawan->nama_karyawan }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $peminjaman->karyawan->nik }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $peminjaman->alasan_pinjam }}</td>

                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold {{ $peminjaman->is_overdue ? 'text-red-500' : '' }}">
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($peminjaman->tanggal_kembali_aktual)
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d M Y, H:i') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <!-- <td class="px-6 py-4">
                                        @if ($peminjaman->status !== 'Selesai')
                                        @if($peminjaman->is_overdue)
                                        {{-- STATUS TERLAMBAT (MERAH) --}}
                                        <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300 border border-red-400">
                                            <span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span>
                                            Terlambat
                                        </span>
                                        @else
                                        {{-- STATUS SEDANG DIPINJAM (KUNING/BIRU) --}}
                                        <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-300">
                                            <svg class="w-3 h-3 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Sedang Dipinjam
                                        </span>
                                        @endif
                                        @else
                                        {{-- STATUS SELESAI (HIJAU) --}}
                                        <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300 border border-green-400">
                                            <svg class="w-3 h-3 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Selesai
                                        </span>
                                        @endif
                                    </td> -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            @if ($peminjaman->status !== 'Selesai')
                                            <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="p-1 text-green-500 hover:text-green-600 dark:hover:text-green-400" title="Edit / Perpanjang Waktu">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 19.5l-4.243 1.5 1.5-4.243L16.862 4.487z" />
                                                </svg>
                                            </a>

                                            <!-- <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengembalikan barang ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-1 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400" title="Kembalikan Barang">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                                    </svg>
                                                </button>
                                            </form> -->
                                            @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat peminjaman.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{-- Ini akan otomatis menyimpan filter saat pindah halaman --}}
                        {{ $peminjamans->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
