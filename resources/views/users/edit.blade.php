<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Form mengarah ke route update dengan ID user --}}
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Wajib pakai PUT untuk edit data di Laravel --}}

                    <div class="mb-4">
                        <x-input-label for="name" value="Nama Lengkap" />
                        {{-- Value diambil dari old input atau data user yang sedang diedit --}}
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" value="Email (Username Login)" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="role" value="Role (Jabatan)" />
                        <select name="role" id="role" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            {{-- Logika: Jika role user sama dengan value, maka tambahkan attribute 'selected' --}}
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <!-- <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option> -->
                            <option value="kepala" {{ old('role', $user->role) == 'kepala' ? 'selected' : '' }}>Kepala</option>
                        </select>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-500 mb-4 dark:text-gray-400">Kosongkan password jika tidak ingin mengubahnya.</p>

                    <div class="mb-4">
                        <x-input-label for="password" value="Password Baru (Opsional)" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Isi hanya jika ingin ganti password" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                            Batal
                        </a>
                        <x-primary-button>Update Data</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>