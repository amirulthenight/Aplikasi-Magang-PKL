<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Aset IT (Barang)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- BAGIAN ATAS: TOMBOL TAMBAH & FILTER KATEGORI --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <a href="{{ route('barang.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">
                            + Tambah Barang
                        </a>

                        {{-- Filter Kategori (Baru) --}}
                        <form action="{{ route('barang.index') }}" method="GET" class="flex items-center">
                            <select name="kategori" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm">
                                <option value="">-- Semua Kategori --</option>
                                <option value="Laptop" {{ request('kategori') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Monitor" {{ request('kategori') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="Printer" {{ request('kategori') == 'Printer' ? 'selected' : '' }}>Printer</option>
                                <option value="Aksesoris" {{ request('kategori') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </form>
                    </div>

                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">No</th>
                                    <th scope="col" class="py-3 px-6">Kode</th>
                                    <th scope="col" class="py-3 px-6">Nama Barang</th>
                                    <th scope="col" class="py-3 px-6">Kategori</th>
                                    <th scope="col" class="py-3 px-6">Merk</th>
                                    <th scope="col" class="py-3 px-6 text-center">Stok</th>
                                    {{-- Kolom Keterangan Baru --}}
                                    <th scope="col" class="py-3 px-6">Keterangan</th>
                                    <th scope="col" class="py-3 px-6 text-center">Status</th>
                                    <th scope="col" class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangs as $index => $barang)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
                                    <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                                    <td class="py-3 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $barang->kode_barang }}</td>
                                    <td class="py-3 px-6">{{ $barang->nama_barang }}</td>
                                    <td class="py-3 px-6">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ $barang->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">{{ $barang->merk }}</td>
                                    <td class="py-3 px-6 text-center font-bold text-gray-900 dark:text-white text-lg">{{ $barang->stok }}</td>
                                    {{-- Data Keterangan Baru --}}
                                    <td class="py-3 px-6 italic text-gray-500">{{ Str::limit($barang->keterangan, 30) ?? '-' }}</td>

                                    <td class="py-3 px-6 text-center">
                                        @if($barang->stok > 0)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Tersedia</span>
                                        @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Tidak Tersedia</span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <a href="{{ route('barang.edit', $barang->id) }}" class="w-4 mr-2 transform text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-200 hover:scale-110">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 transform text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 hover:scale-110">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">Belum ada data barang. Silakan tambah.</td>
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
