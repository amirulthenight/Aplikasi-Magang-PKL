<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Peminjaman Baru') }}
        </h2>
        <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
            Kembali
        </a>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('peminjaman.store') }}">
                        @csrf




                        {{-- Pilih Karyawan --}}
                        <div class="mt-4">
                            <x-input-label for="karyawan_id" :value="__('Pilih Karyawan Peminjam')" />
                            <select name="karyawan_id" id="karyawan_id" class="block mt-1 w-full select2-search" required>
                                <option></option>
                                @foreach ($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}" @if(old('karyawan_id')==$karyawan->id) selected @endif>
                                    {{ $karyawan->nama_karyawan }} ({{ $karyawan->nik }})
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('karyawan_id')" class="mt-2" />
                        </div>
                        {{-- Pilih Barang --}}
                        <div class="mt-4">
                            <x-input-label for="barang_id" :value="__('Pilih Barang (Hanya yang Tersedia)')" />
                            <select name="barang_id" id="barang_id" class="block mt-1 w-full select2-search" required>
                                <option></option>
                                @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}" @if(old('barang_id')==$barang->id) selected @endif>
                                    {{ $barang->nama_barang }} ({{ $barang->kode_barang }})
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('barang_id')" class="mt-2" />
                        </div>

                        {{-- Tanggal Pinjam dan Wajib Kembali --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="tanggal_pinjam" :value="__('Waktu Pinjam')" />
                                {{-- PERUBAHAN DI SINI: type="datetime-local" --}}
                                <x-text-input id="tanggal_pinjam" class="block mt-1 w-full" type="datetime-local" name="tanggal_pinjam" :value="old('tanggal_pinjam', now()->format('Y-m-d\TH:i'))" required />
                                <x-input-error :messages="$errors->get('tanggal_pinjam')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_wajib_kembali" :value="__('Rencana Kembali')" />
                                {{-- PERUBAHAN DI SINI: type="datetime-local" --}}
                                <x-text-input id="tanggal_wajib_kembali" class="block mt-1 w-full" type="datetime-local" name="tanggal_wajib_kembali" :value="old('tanggal_wajib_kembali')" required />
                                <x-input-error :messages="$errors->get('tanggal_wajib_kembali')" class="mt-2" />
                            </div>
                        </div>

                        {{-- TAMBAHKAN INPUT BARU INI --}}
                        <div class="mt-4">
                            <x-input-label for="alasan_pinjam" :value="__('Alasan Peminjaman')" />
                            <textarea id="alasan_pinjam" name="alasan_pinjam" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('alasan_pinjam') }}</textarea>
                            <x-input-error :messages="$errors->get('alasan_pinjam')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('peminjaman.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">Batal</a>
                            <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada semua elemen dengan class 'select2-search'
            $('.select2-search').select2({
                placeholder: "Ketik untuk mencari...",
                allowClear: true
            });
        });
    </script>
    @endpush

    <style>
        .dark .flatpickr-input {
            background-image: url("data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 17'%3E%3Cg%3E%3C/g%3E%3Cpath d='M14 2V1h-1v1H4V1H3v1H0v15h17V2h-3zm.5 13.5h-14v-12h2v1h1V3h8v1h1V3h2v12z' fill='%239CA3AF'/%3E%3C/svg%3E") !important;
        }
    </style>
    <style>
        .dark .flatpickr-input {
            background-image: url("data:image/svg+xml,%3Csvg version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 17'%3E%3Cg%3E%3C/g%3E%3Cpath d='M14 2V1h-1v1H4V1H3v1H0v15h17V2h-3zm.5 13.5h-14v-12h2v1h1V3h8v1h1V3h2v12z' fill='%239CA3AF'/%3E%3C/svg%3E") !important;
        }
    </style>
</x-app-layout>