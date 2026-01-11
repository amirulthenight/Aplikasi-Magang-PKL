<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    // 1. LAPORAN PEMINJAMAN (SEMUA)
    public function peminjaman(Request $request)
    {
        $data = Peminjaman::with(['barang', 'karyawan'])->latest()->get();
        $judul = "Laporan Riwayat Peminjaman";

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul'));
            return $pdf->download('laporan_peminjaman.pdf');
        }
        return view('laporan.hasil', compact('data', 'judul'));
    }

    // 2. LAPORAN PENGEMBALIAN (SUDAH KEMBALI)
    public function pengembalian(Request $request)
    {
        $data = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Kembali')
            ->get();
        $judul = "Laporan Barang Sudah Kembali";

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul'));
            return $pdf->download('laporan_pengembalian.pdf');
        }
        return view('laporan.hasil', compact('data', 'judul'));
    }

    // 3. LAPORAN TERLAMBAT
    public function terlambat(Request $request)
    {
        $data = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now())
            ->get();
        $judul = "Laporan Barang Terlambat";

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul'));
            return $pdf->download('laporan_terlambat.pdf');
        }
        return view('laporan.hasil', compact('data', 'judul'));
    }

    // 4. LAPORAN STOK
    public function stok(Request $request)
    {
        $data = Barang::all();
        $judul = "Laporan Stok Barang";

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul'));
            return $pdf->download('laporan_stok.pdf');
        }
        return view('laporan.stok', compact('data', 'judul'));
    }

    // 5. LAPORAN PER KARYAWAN
    public function perKaryawan(Request $request)
    {
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();
        $data = [];
        $selectedKaryawan = null;

        if ($request->has('karyawan_id')) {
            $selectedKaryawan = Karyawan::find($request->karyawan_id);
            if ($selectedKaryawan) {
                $data = Peminjaman::with('barang')
                    ->where('karyawan_id', $request->karyawan_id)
                    ->latest()
                    ->get();
            }
        }

        // Logic PDF Per Karyawan
        if ($request->has('export') && $request->export == 'pdf' && $selectedKaryawan) {
            $judul = "Laporan Peminjaman: " . $selectedKaryawan->nama_karyawan;
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul'));
            return $pdf->download('laporan_' . $selectedKaryawan->nik . '.pdf');
        }

        return view('laporan.per_karyawan', compact('karyawans', 'data', 'selectedKaryawan'));
    }
}
