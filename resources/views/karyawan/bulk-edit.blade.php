<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Karyawan Sekaligus') }}
        </h2>
        <a href="{{ route('karyawan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
            Kembali
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Anda akan mengedit {{ count($karyawans) }} karyawan:</h3>
                    <ul class="list-disc list-inside mb-6">
                        @foreach ($karyawans as $karyawan)
                        <li>{{ $karyawan->nama_karyawan }} ({{ $karyawan->nik }})</li>
                        @endforeach
                    </ul>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Isi hanya kolom yang ingin Anda ubah. Kolom yang kosong tidak akan mengubah data asli.
                    </p>

                    <form method="POST" action="{{ route('karyawan.bulkUpdate') }}">
                        @csrf
                        @method('PUT')

                        @foreach ($karyawanIds as $id)
                        <input type="hidden" name="ids[]" value="{{ $id }}">
                        @endforeach

                        <div class="mt-4">
                            <x-input-label for="jabatan" :value="__('Jabatan Baru')" />
                            <x-text-input id="jabatan" class="block mt-1 w-full" type="text" name="jabatan" placeholder="Biarkan kosong jika tidak ingin diubah" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="departemen" :value="__('Departemen Baru')" />
                            <x-text-input id="departemen" class="block mt-1 w-full" type="text" name="departemen" placeholder="Biarkan kosong jika tidak ingin diubah" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="site" :value="__('Site / Lokasi Baru')" />
                            <x-text-input id="site" class="block mt-1 w-full" type="text" name="site" placeholder="Biarkan kosong jika tidak ingin diubah" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">Batal</a>
                            <x-primary-button class="ms-4">{{ __('Update Data Terpilih') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>