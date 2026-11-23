<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $judul }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
                <a href="{{ route('laporan.pemeliharaan', array_merge(request()->query(), ['export' => 'pdf'])) }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold">
                    Download PDF
                </a>
            </div>

            {{-- Filter tanggal --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('laporan.pemeliharaan') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                    <div>
                        <label class="block text-sm font-medium mb-2">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Terapkan
                        </button>

                        <a href="{{ route('laporan.pemeliharaan') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 rounded-lg">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Ringkasan biaya --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Catatan Pemeliharaan</p>
                    <p class="text-2xl font-bold mt-2">{{ $data->count() }} Data</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Biaya Perbaikan</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">
                        Rp {{ number_format((float) $totalBiaya, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Tabel laporan --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-3">No.</th>
                                    <th class="px-4 py-3">Aset</th>
                                    <th class="px-4 py-3">Peminjam</th>
                                    <th class="px-4 py-3">Tanggal Kembali</th>
                                    <th class="px-4 py-3">Kerusakan</th>
                                    <th class="px-4 py-3">Perbaikan</th>
                                    <th class="px-4 py-3">Biaya</th>
                                    <th class="px-4 py-3">Mulai</th>
                                    <th class="px-4 py-3">Selesai</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($data as $index => $row)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-4">{{ $index + 1 }}</td>

                                        <td class="px-4 py-4">
                                            <div class="font-semibold">
                                                {{ $row->barang->nama_barang ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $row->barang->kode_barang ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            {{ $row->peminjaman->karyawan->nama_karyawan ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            {{ $row->peminjaman?->tanggal_kembali_aktual
                                                ? \Carbon\Carbon::parse($row->peminjaman->tanggal_kembali_aktual)->format('d M Y')
                                                : '-' }}
                                        </td>

                                        <td class="px-4 py-4 min-w-48">
                                            <div>{{ $row->keterangan_kerusakan }}</div>
                                            <ul class="mt-1 text-xs text-gray-500 list-disc pl-4">
                                                @foreach ($row->checklist_kerusakan ?? [] as $key)
                                                    <li>{{ $kerusakanOptions[$key] ?? $key }}</li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td class="px-4 py-4 min-w-48">
                                            <div>{{ $row->tindakan_perbaikan ?? '-' }}</div>
                                            <ul class="mt-1 text-xs text-gray-500 list-disc pl-4">
                                                @foreach ($row->checklist_perbaikan ?? [] as $key)
                                                    <li>{{ $perbaikanOptions[$key] ?? $key }}</li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            Rp {{ number_format((float) $row->biaya_perbaikan, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            {{ $row->tanggal_mulai_perbaikan ? $row->tanggal_mulai_perbaikan->format('d M Y') : '-' }}
                                        </td>

                                        <td class="px-4 py-4 whitespace-nowrap">
                                            {{ $row->tanggal_selesai_perbaikan ? $row->tanggal_selesai_perbaikan->format('d M Y') : '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-semibold
                                                {{ $row->status === 'Tersedia'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($row->status === 'Perbaikan'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : 'bg-red-100 text-red-800') }}">
                                                {{ $row->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                                            Belum ada data pemeliharaan sesuai filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if ($data->isNotEmpty())
                                <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold">
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-right">Total Biaya:</td>
                                        <td colspan="4" class="px-4 py-4 text-green-600">
                                            Rp {{ number_format((float) $totalBiaya, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
