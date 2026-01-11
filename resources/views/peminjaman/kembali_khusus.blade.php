<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proses Pengembalian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Form Pencarian NIK --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('peminjaman.cariByNik') }}" method="GET" class="flex gap-4 items-end">
                        <div class="w-full md:w-1/2">
                            <label for="nik" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Masukkan NIK Karyawan</label>
                            <input type="text" name="nik" id="nik" class="shadow appearance-none border dark:border-gray-600 rounded w-full py-3 px-4 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-900" placeholder="Contoh: 12345678" value="{{ request('nik') }}" autofocus required>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline">
                            Cari Data
                        </button>
                    </form>
                </div>
            </div>

            {{-- Pesan Error/Sukses --}}
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            {{-- Hasil Pencarian --}}
            @if(isset($karyawan))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="border-b dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Hasil Pencarian:</h3>
                        <p class="text-gray-600 dark:text-gray-400">Nama: <span class="font-bold text-black dark:text-white">{{ $karyawan->nama_karyawan }}</span></p>
                        <p class="text-gray-600 dark:text-gray-400">Jabatan: {{ $karyawan->jabatan }} - {{ $karyawan->departemen }}</p>
                    </div>

                    <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-3">Barang yang sedang dipinjam:</h4>

                    @if($peminjamans->count() > 0)
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Kode Barang</th>
                                    <th scope="col" class="py-3 px-6">Nama Barang</th>
                                    <th scope="col" class="py-3 px-6 text-center">Tgl Pinjam</th>
                                    <th scope="col" class="py-3 px-6 text-center">Jatuh Tempo</th>
                                    <th scope="col" class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamans as $pinjam)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="py-3 px-6 whitespace-nowrap font-medium text-gray-900 dark:text-white">{{ $pinjam->barang->kode_barang }}</td>
                                    <td class="py-3 px-6 text-left">{{ $pinjam->barang->nama_barang }}</td>
                                    <td class="py-3 px-6 text-center">{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-center">
                                        @if(\Carbon\Carbon::now()->startOfDay() > \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->startOfDay())
                                        <span class="text-red-500 font-bold">{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->format('d M Y') }} (Telat)</span>
                                        @else
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <form action="{{ route('peminjaman.kembalikan', $pinjam->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian barang ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                                                Kembali
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Karyawan ini tidak memiliki pinjaman barang aktif saat ini.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
