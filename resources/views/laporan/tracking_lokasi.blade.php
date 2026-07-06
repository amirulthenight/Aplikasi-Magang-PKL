<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                                Kembali ke Menu
                            </a>
                            <form action="{{ route('laporan.trackingLokasi') }}" method="GET" class="flex items-center gap-2">
                                <label for="sort_by" class="text-sm dark:text-gray-300 hidden sm:block">Urutkan:</label>
                                <select name="sort_by" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="nama_barang" {{ request('sort_by') == 'nama_barang' ? 'selected' : '' }}>Nama Barang</option>
                                    <option value="kategori" {{ request('sort_by') == 'kategori' ? 'selected' : '' }}>Kategori</option>
                                    <option value="stok" {{ request('sort_by') == 'stok' ? 'selected' : '' }}>Sisa Stok</option>
                                </select>
                            </form>
                        </div>
                        <div class="flex items-center gap-2 mt-4 sm:mt-0">
                            <a href="{{ route('laporan.trackingLokasi', array_merge(request()->query(), ['export' => 'pdf'])) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-md hover:bg-red-700 uppercase tracking-widest flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Cetak PDF
                            </a>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Aset IT</th>
                                    <th scope="col" class="px-6 py-3 text-center">Sisa Stok (Gudang)</th>
                                    <th scope="col" class="px-6 py-3 text-center">Total Dipinjam</th>
                                    <th scope="col" class="px-6 py-3">Detail Tracking Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangs as $barang)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }}<br>
                                        <span class="text-xs text-gray-500">(Kategori: {{ $barang->kategori }})</span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-lg">
                                        {{ $barang->stok }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-lg">
                                        {{ $barang->peminjamans->count() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <ul class="space-y-2 text-sm">
                                            @if($barang->stok > 0)
                                                <li class="text-green-600 dark:text-green-400 font-medium">
                                                    🟢 Tersedia di: {{ $barang->lokasi_simpan ?: 'Gudang Utama' }}
                                                </li>
                                            @endif

                                            @foreach($barang->peminjamans as $pinjam)
                                                <li class="text-orange-500 dark:text-orange-400">
                                                    🟠 Dipinjam oleh: {{ $pinjam->karyawan->nama_karyawan ?? '-' }} 
                                                    | Lokasi: Site {{ $pinjam->karyawan->site ?? '-' }} (Dept: {{ $pinjam->karyawan->departemen ?? '-' }}) 
                                                    | Sejak: {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }} 
                                                    | 📞 Telp: {{ $pinjam->karyawan->no_telp ?? '-' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Data tidak ditemukan.</td>
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
