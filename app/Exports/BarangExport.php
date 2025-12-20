<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;

class BarangExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Barang::query();

        // Terapkan filter yang sama seperti di Controller
        if ($this->request->has('search') && $this->request->search != '') {
            $query->where('nama_barang', 'like', '%' . $this->request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $this->request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $this->request->search . '%');
        }

        if ($this->request->has('status') && $this->request->status != '') {
            $query->where('status', $this->request->status);
        }

        // Pilih kolom yang ingin diexport
        return $query->select('kode_barang', 'nama_barang', 'serial_number', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Serial Number',
            'Status',
        ];
    }
}
