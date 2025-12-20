<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KaryawansImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Karyawan([
            'nik'           => $row['nik'] ?? null,
            'nama_karyawan' => $row['nama_karyawan'] ?? null,
            'email'         => $row['email'] ?? null,
            'jabatan'       => $row['jabatan'] ?? null,
            'departemen'    => $row['departemen'] ?? null,
            'site'          => $row['site'] ?? null,
            'keterangan'    => $row['keterangan'] ?? null,
        ]);
    }

    /**
     * Aturan validasi untuk setiap baris di Excel.
     */
    public function rules(): array
    {
        return [
            'nik' => 'required|string|max:255|unique:karyawans,nik',
            'nama_karyawan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:karyawans,email',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'site' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
