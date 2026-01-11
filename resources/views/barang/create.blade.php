<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Barang Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="kode_barang" class="block text-gray-700 text-sm font-bold mb-2">Kode Barang</label>
                            <input type="text" name="kode_barang" id="kode_barang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: LP-001" required>
                        </div>

                        <div class="mb-4">
                            <label for="nama_barang" class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: Laptop Thinkpad X1" required>
                        </div>

                        <div class="mb-4">
                            <label for="kategori" class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                            <select name="kategori" id="kategori" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Laptop">Laptop</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Peripheral">Peripheral (Mouse/Keyboard)</option>
                                <option value="Network">Network Device</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="merk" class="block text-gray-700 text-sm font-bold mb-2">Merk</label>
                            <input type="text" name="merk" id="merk" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="" required>
                        </div>

                        <div class="mb-4">
                            <label for="stok" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Stok</label>
                            <input type="number" name="stok" id="stok" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="0" required>
                            <p class="text-xs text-gray-500 mt-1">*Masukkan jumlah unit yang tersedia.</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan
                            </button>
                            <a href="{{ route('barang.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
