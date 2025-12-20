<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BarangsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Barang([
            'kode_barang'   => $row['kode_barang'] ?? null,
            'nama_barang'   => $row['nama_barang'] ?? null,
            'serial_number' => $row['serial_number'] ?? null,
            'site'          => $row['site'] ?? null,
            'keterangan'    => $row['keterangan'] ?? null,
            'status'        => 'Tersedia', // Status default saat import
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris di Excel.
     */
    public function rules(): array
    {
        return [
            'kode_barang' => 'required|string|max:255|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ];
    }

    public function batchSize(): int
    {
        return 100; // Proses 100 baris sekali insert ke database
    }

    public function chunkSize(): int
    {
        return 100; // Baca 100 baris dari file Excel sekali waktu
    }
}
