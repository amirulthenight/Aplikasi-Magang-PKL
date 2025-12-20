<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12"
        x-data="{
             selectedIds: [],
             selectAll: false,

             toggleSelectAll(event) {
                 const checkboxes = document.querySelectorAll('table tbody input[type=checkbox]');
                 this.selectedIds = event.target.checked
                     ? Array.from(checkboxes).map(cb => cb.value)
                     : [];
                 this.selectAll = event.target.checked;
             },

             updateSelectAllState() {
                const totalCheckboxes = document.querySelectorAll('table tbody input[type=checkbox]').length;
                this.selectAll = (totalCheckboxes > 0 && this.selectedIds.length === totalCheckboxes);
             }
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Notifikasi --}}
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

                    {{-- Toolbar --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-2">
                        {{-- Grup Tombol Aksi Massal --}}
                        <div x-show="selectedIds.length > 0" class="flex items-center gap-2">
                            {{-- Form Edit Massal --}}
                            <form action="{{ route('karyawan.bulkEdit') }}" method="POST">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                    Edit (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                            {{-- Form Hapus Massal --}}
                            <form action="{{ route('karyawan.bulkDestroy') }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' data karyawan yang dipilih?');">
                                @csrf
                                @method('DELETE')
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                    Hapus (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                        </div>

                        {{-- Form Search --}}
                        <form action="{{ route('karyawan.index') }}" method="GET"
                            class="w-full sm:w-auto sm:flex sm:items-center"
                            :class="{'w-full': selectedIds.length === 0, 'ml-auto': selectedIds.length > 0}">
                            <input type="text" name="search" placeholder="Cari karyawan..." value="{{ request('search') }}"
                                class="w-full sm:w-64 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <button type="submit"
                                class="w-full mt-2 sm:mt-0 sm:w-auto sm:ml-2 inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                                Cari
                            </button>
                        </form>

                        {{-- Tambah Karyawan --}}
                        <a href="{{ route('karyawan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 -ms-1 me-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Tambah Karyawan</span>
                        </a>
                    </div>

                    <!-- {{-- Fitur Import Excel --}}
                    <div class="flex items-center gap-4 mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 flex-grow">
                            @csrf
                            <label for="file-upload-karyawan" class="text-sm font-medium">Import dari Excel:</label>
                            <input id="file-upload-karyawan" type="file" name="file_import" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-200 dark:hover:file:bg-blue-800" />
                            <x-primary-button type="submit">
                                Import
                            </x-primary-button>
                        </form>
                        <a href="{{ route('karyawan.template') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 whitespace-nowrap">
                            Download Template
                        </a>
                    </div> -->

                    {{-- Tabel --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input @click="toggleSelectAll($event)" :checked="selectAll" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded
                                                    focus:ring-blue-500 dark:focus:ring-blue-600">
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">NIK</th>
                                    <th scope="col" class="px-6 py-3">Nama Karyawan</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Jabatan</th>
                                    <th scope="col" class="px-6 py-3">Departemen</th>
                                    <th scope="col" class="px-6 py-3">Site</th>
                                    <th scope="col" class="px-6 py-3">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($karyawans as $karyawan)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="w-4 p-4">
                                        <input x-model="selectedIds" @change="updateSelectAllState()" type="checkbox" value="{{ $karyawan->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </td>

                                    {{-- PERBAIKAN 1: Kode penomoran dipindahkan ke dalam <td> --}}
                                    <td class="px-6 py-4">{{ $loop->iteration + ($karyawans->currentPage() - 1) * $karyawans->perPage() }}</td>

                                    <td class="px-6 py-4">{{ $karyawan->nik }}</td>

                                    {{-- PERBAIKAN 2: Link di nama dihapus --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $karyawan->nama_karyawan }}
                                    </td>

                                    <td class="px-6 py-4">{{ $karyawan->email ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $karyawan->jabatan }}</td>
                                    <td class="px-6 py-4">{{ $karyawan->departemen ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $karyawan->site ?? '-' }}</td>

                                    {{-- PERBAIKAN 3: Kolom keterangan dirapikan --}}
                                    <td class="px-6 py-4" title="{{ $karyawan->keterangan }}">
                                        {{ \Illuminate\Support\Str::limit($karyawan->keterangan, 50, '...') }}
                                    </td>

                                    {{-- Kolom Aksi dengan Tombol Pintar --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('karyawan.show', $karyawan->id) }}" class="p-1 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639l4.433-7.447A1.012 1.012 0 017.633 4h8.734c.486 0 .918.293 1.042.738l4.433 7.447a1.012 1.012 0 010 .639l-4.433 7.447a1.012 1.012 0 01-1.042.738H7.633a1.012 1.012 0 01-1.042-.738L2.036 12.322z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="p-1 text-green-500 hover:text-green-600 dark:hover:text-green-400" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-red-500 hover:text-red-600 dark:hover:text-red-400" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @if ($karyawan->peminjaman_terlambat_count > 0)
                                            <form action="{{ route('karyawan.kirimPeringatan', $karyawan->id) }}" method="POST" onsubmit="return confirm('Kirim email peringatan ke {{ $karyawan->nama_karyawan }}?');">
                                                @csrf
                                                <button type="submit" class="p-1 text-yellow-500 hover:text-yellow-600 dark:hover:text-yellow-400" title="Kirim Peringatan Terlambat ({{ $karyawan->peminjaman_terlambat_count }} item)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data karyawan yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $karyawans->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>