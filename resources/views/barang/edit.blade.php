<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="kode_barang" class="block text-gray-700 text-sm font-bold mb-2">Kode Barang</label>
                            <input type="text" name="kode_barang" id="kode_barang" value="{{ $barang->kode_barang }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="nama_barang" class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang" value="{{ $barang->nama_barang }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="kategori" class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                            <select name="kategori" id="kategori" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="Laptop" {{ $barang->kategori == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="Monitor" {{ $barang->kategori == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="Peripheral" {{ $barang->kategori == 'Peripheral' ? 'selected' : '' }}>Peripheral</option>
                                <option value="Network" {{ $barang->kategori == 'Network' ? 'selected' : '' }}>Network</option>
                                <option value="Lainnya" {{ $barang->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="merk" class="block text-gray-700 text-sm font-bold mb-2">Merk</label>
                            <input type="text" name="merk" id="merk" value="{{ $barang->merk }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="stok" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Stok</label>
                            <input type="number" name="stok" id="stok" min="0" value="{{ $barang->stok }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Data
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