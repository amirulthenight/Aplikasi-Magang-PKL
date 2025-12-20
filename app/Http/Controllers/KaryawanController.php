<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Notifications\PeringatanTerlambatTerkonsolidasi;
use App\Imports\KaryawansImport; // Tambahkan ini
use Maatwebsite\Excel\Facades\Excel; // Tambahkan ini
use Maatwebsite\Excel\Validators\ValidationException; // Tambahkan ini
use Illuminate\Support\Facades\Notification;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Karyawan::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_karyawan', 'like', '%' . $request->search . '%')
                ->orWhere('nik', 'like', '%' . $request->search . '%')
                ->orWhere('jabatan', 'like', '%' . $request->search . '%')
                ->orWhere('departemen', 'like', '%' . $request->search . '%');
        }

        $karyawans = $query->withCount(['peminjamans as peminjaman_terlambat_count' => function ($query) {
            $query->where('status', 'Dipinjam')->where('tanggal_wajib_kembali', '<', now());
        }])->latest()->paginate(10);
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255|unique:karyawans',
            'nama_karyawan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'site' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nik' => 'required|string|max:255|unique:karyawans,nik,' . $karyawan->id,
            'nama_karyawan' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'site' => 'required|string|max:255',
            'keterangan' => 'nullable|string',

        ]);

        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        // Cek apakah karyawan memiliki riwayat peminjaman
        if ($karyawan->peminjamans()->exists()) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Gagal! Karyawan tidak bisa dihapus karena memiliki riwayat peminjaman.');
        }

        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

    // Gantikan method bulkDestroy() Anda dengan versi yang lebih efisien ini

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:karyawans,id',
        ]);

        $karyawanIds = $request->ids;

        // --- PERBAIKAN DI SINI ---
        // Query 1: Ambil semua ID karyawan dari daftar yang punya riwayat peminjaman
        $undeletableIds = Karyawan::whereIn('id', $karyawanIds)
            ->has('peminjamans')
            ->pluck('id')
            ->all();

        // Pisahkan mana ID yang bisa dihapus dan yang tidak
        $deletableIds = array_diff($karyawanIds, $undeletableIds);

        $successMessage = '';
        $errorMessage = '';

        if (!empty($deletableIds)) {
            // Query 2: Hapus semua karyawan yang boleh dihapus dalam satu perintah
            Karyawan::whereIn('id', $deletableIds)->delete();
            $successMessage = count($deletableIds) . ' data karyawan berhasil dihapus.';
        }

        if (!empty($undeletableIds)) {
            $errorMessage = count($undeletableIds) . ' data tidak bisa dihapus karena memiliki riwayat peminjaman.';
        }

        return redirect()->route('karyawan.index')
            ->with('success', $successMessage)
            ->with('error', $errorMessage);
    }

    public function bulkEdit(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:karyawans,id',
        ]);

        $karyawanIds = $request->ids;
        $karyawans = Karyawan::whereIn('id', $karyawanIds)->get();

        return view('karyawan.bulk-edit', compact('karyawans', 'karyawanIds'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:karyawans,id',
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'site' => 'required|string|max:255',
        ]);

        $updateData = [];
        if ($request->filled('jabatan')) {
            $updateData['jabatan'] = $request->jabatan;
        }
        if ($request->filled('departemen')) {
            $updateData['departemen'] = $request->departemen;
        }
        if ($request->filled('site')) {
            $updateData['site'] = $request->site;
        }

        if (!empty($updateData)) {
            Karyawan::whereIn('id', $request->ids)->update($updateData);
        }

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan yang dipilih berhasil diperbarui.');
    }

    public function show(Karyawan $karyawan)
    {
        // Ambil riwayat peminjaman milik karyawan ini, urutkan dari yang terbaru
        // Muat juga relasi 'barang' agar kita bisa menampilkan nama barangnya
        $peminjamans = $karyawan->peminjamans()->with('barang')->latest()->paginate(5);

        // Kirim data karyawan dan riwayat peminjamannya ke view
        return view('karyawan.show', compact('karyawan', 'peminjamans'));
    }

    // Method untuk mengirim peringatan manual ke karyawan tertentu
    public function kirimPeringatanManual(Karyawan $karyawan)
    {
        // Cek apakah karyawan ini punya email
        if (!$karyawan->email) {
            return redirect()->route('karyawan.index')->with('error', 'Gagal! Karyawan ini tidak memiliki alamat email.');
        }

        // Cari semua peminjaman yang sedang terlambat HANYA untuk karyawan ini
        $peminjamanTerlambat = $karyawan->peminjamans()
            ->where('status', 'Dipinjam')
            ->where('tanggal_wajib_kembali', '<', now())
            ->get();

        if ($peminjamanTerlambat->isEmpty()) {
            return redirect()->route('karyawan.index')->with('info', 'Karyawan ini tidak memiliki peminjaman yang sedang terlambat.');
        }

        // Kirim notifikasi ringkasan (bisa dimasukkan ke antrean jika mau)
        Notification::send($karyawan, new PeringatanTerlambatTerkonsolidasi($peminjamanTerlambat));

        // INI DIA TONGKAT SIHIRNYA
        return redirect()->back()->with('success', 'Email peringatan berhasil dikirim ke ' . $karyawan->nama_karyawan);
    }

    public function downloadTemplate()
    {
        $path = public_path('templates/template_karyawan.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        // Pastikan file template ada di public/templates/template_karyawan.xlsx
        if (!file_exists($path)) {
            return redirect()->route('karyawan.index')->with('error', 'File template tidak ditemukan.');
        }

        return response()->download($path, 'template_import_karyawan.xlsx', $headers);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new KaryawansImport, $request->file('file_import'));

            return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diimpor!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $attribute = $failure->attribute();
                $value = $failure->values()[$attribute] ?? '[KOSONG]';
                $errorMessages[] = 'Baris ' . $failure->row() . ' (' . $attribute . '): ' . implode(', ', $failure->errors()) . '. Nilai yang diberikan: "' . $value . '"';
            }

            return redirect()->route('karyawan.index')
                ->with('error', 'Gagal mengimpor data. Silakan periksa kesalahan berikut:')
                ->with('import_errors', $errorMessages);
        } catch (\Exception $e) {
            return redirect()->route('karyawan.index')->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }
}
