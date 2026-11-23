<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $pemeliharaan ? 'Form Proses Pemeliharaan Aset' : 'Form Pencatatan Kerusakan Aset' }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                        <p class="font-semibold mb-2">Periksa kembali data berikut:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Informasi Aset</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Kode Barang</p>
                            <p class="font-semibold">{{ $peminjaman->barang->kode_barang ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Nama Barang</p>
                            <p class="font-semibold">{{ $peminjaman->barang->nama_barang ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Peminjam</p>
                            <p class="font-semibold">{{ $peminjaman->karyawan->nama_karyawan ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Tanggal Kembali</p>
                            <p class="font-semibold">
                                {{ $peminjaman->tanggal_kembali_aktual
                                    ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d M Y H:i')
                                    : '-' }}
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 dark:text-gray-300">Keterangan Kerusakan</p>
                            <p class="font-semibold">{{ $peminjaman->keterangan_kerusakan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if ($pemeliharaan && $pemeliharaan->status === 'Tersedia')

                    <div class="p-4 mb-6 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <p class="font-semibold">Pemeliharaan telah selesai.</p>
                        <p class="text-sm mt-1">
                            Aset sudah berstatus Tersedia dan stok telah dikembalikan.
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold mb-3">Checklist Kerusakan</h3>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($kerusakanOptions as $key => $label)
                                @if (in_array($key, $pemeliharaan->checklist_kerusakan ?? []))
                                    <li>{{ $label }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold mb-3">Checklist Perbaikan</h3>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($perbaikanOptions as $key => $label)
                                @if (in_array($key, $pemeliharaan->checklist_perbaikan ?? []))
                                    <li>{{ $label }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-6">
                        <p class="font-semibold">Tindakan Perbaikan</p>
                        <p>{{ $pemeliharaan->tindakan_perbaikan ?? '-' }}</p>

                        <p class="font-semibold mt-4">Biaya Perbaikan</p>
                        <p>Rp {{ number_format((float) $pemeliharaan->biaya_perbaikan, 0, ',', '.') }}</p>
                    </div>
                @else
                    <form method="POST"
                        action="{{ $pemeliharaan
                            ? route('pemeliharaan.update', $pemeliharaan->id)
                            : route('pemeliharaan.store', $peminjaman->id) }}">

                        @csrf

                        @if ($pemeliharaan)
                            @method('PUT')
                        @endif

                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-3">
                                Checklist Pemeriksaan Kerusakan
                            </h3>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Tandai bagian perangkat yang mengalami kerusakan.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($kerusakanOptions as $key => $label)
                                    <label class="flex items-center gap-3 p-3 border dark:border-gray-600 rounded-lg">
                                        <input type="checkbox" name="checklist_kerusakan[]" value="{{ $key }}"
                                            @checked(in_array($key, old('checklist_kerusakan', $pemeliharaan?->checklist_kerusakan ?? []), true)) @disabled($pemeliharaan)
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @if ($pemeliharaan)
                                <p class="mt-2 text-xs text-gray-500">
                                    Checklist kerusakan merupakan catatan awal dan tidak diubah pada tahap perbaikan.
                                </p>
                            @endif
                        </div>

                        @if ($pemeliharaan)

                            <div class="border-t dark:border-gray-700 pt-6 mb-6">
                                <h3 class="text-lg font-semibold mb-3">
                                    Checklist Tindakan Perbaikan
                                </h3>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    Tandai tindakan yang sudah dilakukan terhadap aset.
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($perbaikanOptions as $key => $label)
                                        <label
                                            class="flex items-center gap-3 p-3 border dark:border-gray-600 rounded-lg">
                                            <input type="checkbox" name="checklist_perbaikan[]"
                                                value="{{ $key }}" @checked(in_array($key, old('checklist_perbaikan', $pemeliharaan->checklist_perbaikan ?? []), true))
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block font-semibold mb-2">
                                    Keterangan Tindakan Perbaikan
                                </label>
                                <textarea name="tindakan_perbaikan" rows="4" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    placeholder="Jelaskan tindakan yang dilakukan untuk memperbaiki aset...">{{ old('tindakan_perbaikan', $pemeliharaan->tindakan_perbaikan) }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block font-semibold mb-2">
                                    Biaya Perbaikan (Rp)
                                </label>
                                <input type="number" name="biaya_perbaikan" min="0" step="0.01" required
                                    value="{{ old('biaya_perbaikan', $pemeliharaan->biaya_perbaikan ?? 0) }}"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>

                            <div class="mb-6">
                                <label class="block font-semibold mb-2">
                                    Status Pemeliharaan
                                </label>

                                <select name="status" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @if ($pemeliharaan->status === 'Rusak')
                                        <option value="Perbaikan" @selected(old('status', 'Perbaikan') === 'Perbaikan')>
                                            Perbaikan - Mulai proses perbaikan
                                        </option>
                                    @else
                                        <option value="Perbaikan" @selected(old('status', 'Perbaikan') === 'Perbaikan')>
                                            Perbaikan - Proses masih berlangsung
                                        </option>

                                        <option value="Tersedia" @selected(old('status') === 'Tersedia')>
                                            Tersedia - Perbaikan telah selesai
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <button type="submit"
                                class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                                Simpan Proses Pemeliharaan
                            </button>
                        @else
                            <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/30">
                                <p class="font-semibold text-red-700 dark:text-red-300">
                                    Status Awal: Rusak
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    Setelah checklist kerusakan disimpan, aset akan masuk ke proses pemeliharaan dengan
                                    status Rusak.
                                </p>
                            </div>

                            <button type="submit"
                                class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                Simpan Catatan Kerusakan
                            </button>

                        @endif
                    </form>

                @endif

                <div class="mt-6">
                    <a href="{{ route('pemeliharaan.index') }}"
                        class="inline-block px-5 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg">
                        Kembali ke Daftar Pemeliharaan
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
