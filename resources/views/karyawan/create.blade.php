<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Karyawan Baru') }}
        </h2>
        <a href="{{ route('karyawan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
            Kembali
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('karyawan.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="nik" :value="__('NIK')" />
                            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" required autofocus />
                            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="nama_karyawan" :value="__('Nama Karyawan')" />
                            <x-text-input id="nama_karyawan" class="block mt-1 w-full" type="text" name="nama_karyawan" :value="old('nama_karyawan')" required />
                            <x-input-error :messages="$errors->get('nama_karyawan')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Alamat Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="jabatan" :value="__('Jabatan')" />
                            <x-text-input id="jabatan" class="block mt-1 w-full" type="text" name="jabatan" :value="old('jabatan')" required />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="departemen" :value="__('Departemen')" />
                            <x-text-input id="departemen" class="block mt-1 w-full" type="text" name="departemen" :value="old('departemen')" required />
                            <x-input-error :messages="$errors->get('departemen')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="site" :value="__('Site')" />
                            <x-text-input id="site" class="block mt-1 w-full" type="text" name="site" :value="old('site')" required />
                            <x-input-error :messages="$errors->get('site')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="keterangan" :value="__('Keterangan')" />
                            {{-- PERBAIKAN DI SINI: atribut 'required' dihapus --}}
                            <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('karyawan.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">Batal</a>
                            <x-primary-button class="ms-4">{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
