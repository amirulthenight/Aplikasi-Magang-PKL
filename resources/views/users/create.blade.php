<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" value="Email (Username Login)" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="role" value="Role (Jabatan)" />
                        <select name="role" id="role" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="admin">Admin</option>
                            <option value="kepala">Kepala</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Simpan Pengguna</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>