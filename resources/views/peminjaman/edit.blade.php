<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perpanjang Waktu Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama Barang</label>
                            <input type="text" value="{{ $peminjaman->barang->nama_barang }}" class="block mt-1 w-full bg-gray-100 dark:bg-gray-900 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Peminjam</label>
                            <input type="text" value="{{ $peminjaman->karyawan->nama_karyawan }} ({{ $peminjaman->karyawan->nik }})" class="block mt-1 w-full bg-gray-100 dark:bg-gray-900 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" disabled>
                        </div>

                        <div class="mb-6">
                            <label for="tanggal_wajib_kembali" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Rencana Kembali (Baru)</label>
                            <x-text-input type="datetime-local" id="tanggal_wajib_kembali" name="tanggal_wajib_kembali" value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('Y-m-d\TH:i') }}" class="block mt-1 w-full" required />
                            @error('tanggal_wajib_kembali')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('peminjaman.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                Simpan Perubahan
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>