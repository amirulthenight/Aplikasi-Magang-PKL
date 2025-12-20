<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Notifikasi Sukses --}}
                    @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg">
                        {{ session('error') }}
                    </div>
                    @endif

                    {{-- Notifikasi Error Import --}}
                    @if (session('import_errors'))
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg">
                        <p class="font-bold">{{ session('error') }}</p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach (session('import_errors') as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Actions: Search, Filter, Add, and Reports -->
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-2">
                        <form action="{{ route('barang.index') }}" method="GET" class="w-full sm:w-auto sm:flex sm:items-center">
                            <div class="flex items-center">
                                <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}" class="w-full sm:w-64 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <select name="status" onchange="this.form.submit()" class="ml-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                                <button type="submit" class="ml-2 px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">Cari</button>
                            </div>
                        </form>
                        <div class="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                            {{-- Grup Tombol Report --}}
                            <div class="flex items-center gap-2">
                                <button onclick="printReport()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">Print</button>
                                <a href="{{ route('barang.pdf', ['search' => request('search'), 'status' => request('status')]) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">PDF</a>
                                <a href="{{ route('barang.excel', ['search' => request('search'), 'status' => request('status')]) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">Excel</a>
                            </div>
                            <a href="{{ route('barang.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 -ms-1 me-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>Tambah Barang</span>
                            </a>
                        </div>
                    </div>

                    <!-- {{-- Fitur Import Excel --}}
                    <div class="flex items-center gap-4 mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 flex-grow">
                            @csrf
                            <label for="file-upload-barang" class="text-sm font-medium">Import dari Excel:</label>
                            <input id="file-upload-barang" type="file" name="file_import" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-200 dark:hover:file:bg-blue-800"/>
                            <x-primary-button type="submit">
                                Import
                            </x-primary-button>
                        </form>
                        <a href="{{ route('barang.template') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 whitespace-nowrap">
                            Download Template
                        </a>
                    </div> -->

                    {{-- Tabel Data Barang --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Kode Barang</th>
                                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3">Serial Number</th>
                                    <th scope="col" class="px-6 py-3">Site</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Keterangan</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($barangs as $barang)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $loop->iteration + ($barangs->currentPage() - 1) * $barangs->perPage() }}</td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $barang->kode_barang }}
                                    </th>
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $barang->nama_barang }}
                                    </td>
                                    {{--<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="{{ route('barang.show', $barang->id) }}" class="hover:underline">
                                    {{ $barang->nama_barang }}
                                    </a>
                                    </td>--}}
                                    <td class="px-6 py-4">
                                        {{ $barang->serial_number ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">{{ $barang->site ?? '-' }}</td>

                                    {{-- Kolom Status --}}
                                    <td class="px-6 py-4">

                                        <x-status-badge :status="$barang->status" />

                                        {{-- @if ($barang->status == 'Tersedia')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Tersedia</span>
                                        @elseif ($barang->status == 'Dipinjam')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Dipinjam</span>
                                        @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Rusak</span>
                                        @endif --}}

                                    </td>

                                    {{-- Kolom Keterangan --}}
                                    <td class="px-6 py-4" title="{{ $barang->keterangan }}">
                                        {{-- buat teks suapaya di lihat enak --}} {{ \Illuminate\Support\Str::limit($barang->keterangan, 50, '...') }}
                                    </td>

                                    {{-- Kolom Aksi (Dengan tombol Detail baru) --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('barang.show', $barang->id) }}" class="p-1 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('barang.edit', $barang->id) }}" class="p-1 text-green-500 hover:text-green-600 dark:hover:text-green-400" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-red-500 hover:text-red-600 dark:hover:text-red-400" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data barang yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            </tbody>
                        </table>
                    </div>

                    {{-- Link Pagination --}}
                    <div class="mt-4">
                        {{ $barangs->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK CETAK LANGSUNG --}}
    <script>
        function printReport() {
            const url = "{{ route('barang.pdf', ['search' => request('search'), 'status' => request('status')]) }}";
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                // Beri sedikit waktu agar PDF sempat termuat di tab baru sebelum dialog cetak muncul
                setTimeout(function() {
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</x-app-layout>