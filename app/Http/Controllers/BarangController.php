<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use PDF;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangsImport; // Tambahkan ini
use Maatwebsite\Excel\Validators\ValidationException; // Tambahkan ini

class BarangController extends Controller
{

    public function index(Request $request)
    {
        $query = Barang::query();

        // Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cukup tampilkan view formulir
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barangs',
            'nama_barang' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        // Ambil semua riwayat peminjaman untuk barang ini,
        // muat juga data karyawannya (eager loading),
        // dan urutkan dari yang terbaru.
        $riwayatPeminjaman = $barang->peminjamans()
            ->with('karyawan')
            ->latest()
            ->paginate(10);

        // Kirim data barang dan riwayatnya ke view baru
        return view('barang.show', compact('barang', 'riwayatPeminjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        // Tampilkan view edit dan kirim data barang yang mau diedit
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'status' => 'required|in:Tersedia,Dipinjam,Rusak',
            'keterangan' => 'nullable|string',
        ]);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        // Cek apakah barang memiliki riwayat peminjaman
        if ($barang->peminjamans()->exists()) {
            return redirect()->route('barang.index')
                ->with('error', 'Gagal! Barang tidak bisa dihapus karena memiliki riwayat peminjaman.');
        }

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus.');
    }

    public function cetakPdf(Request $request)
    {
        // Panggil scope yang sama di sini, tanpa duplikasi kode
        $barangs = Barang::filter($request)->latest()->get();

        $pdf = PDF::loadView('barang.pdf', compact('barangs'));
        $fileName = 'laporan-stok-barang-' . date('Y-m-d') . '.pdf';
        return $pdf->stream($fileName);
    }

    public function cetakExcel(Request $request)
    {
        $fileName = 'laporan-stok-barang-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new BarangExport($request), $fileName);
    }

    public function downloadTemplate()
    {
        $path = public_path('templates/template_barang.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        // Pastikan file template ada di public/templates/template_barang.xlsx
        if (!file_exists($path)) {
            // Jika file tidak ada, berikan pesan error
            return redirect()->route('barang.index')->with('error', 'File template tidak ditemukan.');
        }

        return response()->download($path, 'template_import_barang.xlsx', $headers);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new BarangsImport, $request->file('file_import'));

            return redirect()->route('barang.index')->with('success', 'Data barang berhasil diimpor!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $attribute = $failure->attribute();
                $value = $failure->values()[$attribute] ?? '[KOSONG]';
                $errorMessages[] = 'Baris ' . $failure->row() . ' (' . $attribute . '): ' . implode(', ', $failure->errors()) . '. Nilai yang diberikan: "' . $value . '"';
            }

            return redirect()->route('barang.index')
                ->with('error', 'Gagal mengimpor data. Silakan periksa kesalahan berikut:')
                ->with('import_errors', $errorMessages);
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }
}
