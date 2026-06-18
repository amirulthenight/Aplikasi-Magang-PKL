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
        $judul = "Laporan Pengembalian";

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul'));
            return $pdf->download('laporan_pengembalian.pdf');
        }
        return view('laporan.hasil', compact('data', 'judul'));
    }

    // 3. LAPORAN KERUSAKAN ASET (PENGGANTI LAPORAN TERLAMBAT)
    public function kerusakan(Request $request)
    {
        $data = collect();

        // Cek dulu apakah kolomnya sudah dibuat di database untuk mencegah error
        if (\Illuminate\Support\Facades\Schema::hasColumn('peminjamans', 'keterangan_kerusakan')) {
            $data = Peminjaman::with(['barang', 'karyawan'])
                ->whereNotNull('keterangan_kerusakan')
                ->where('keterangan_kerusakan', '!=', '')
                ->where('status_peminjaman', 'Kembali')
                ->latest('tanggal_kembali_aktual')
                ->get();
        } else {
            session()->now('error', 'Kolom keterangan_kerusakan belum ada di database. Laporan belum bisa menampilkan data.');
        }

        $judul = "Laporan Kerusakan Aset";
        $tipe = 'kerusakan'; // Penting untuk PDF

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul', 'tipe'));
            return $pdf->download('laporan_kerusakan_aset.pdf');
        }

        // Menggunakan view 'laporan.kerusakan' yang sudah dibuat
        return view('laporan.kerusakan', compact('data', 'judul'));
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

    // 6. LAPORAN PEMINJAMAN PER DEPARTEMEN
    public function perDepartemen(Request $request)
    {
        $peminjamans = Peminjaman::with(['barang', 'karyawan'])->get();

        $data = $peminjamans
            ->groupBy(fn($p) => $p->karyawan->departemen ?? 'Tidak Diketahui')
            ->map(function ($items, $departemen) {
                return (object) [
                    'departemen' => $departemen,
                    'total' => $items->count(),
                    'dipinjam' => $items->where('status_peminjaman', 'Dipinjam')->count(),
                    'kembali' => $items->where('status_peminjaman', 'Kembali')->count(),
                    'terlambat' => $items->filter(fn($p) => $p->is_overdue)->count(),
                ];
            })
            ->sortBy('departemen')
            ->values();

        $judul = 'Laporan Peminjaman per Departemen';
        $tipe = 'departemen';

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul', 'tipe'));
            return $pdf->download('laporan_peminjaman_per_departemen.pdf');
        }

        return view('laporan.per-departemen', compact('data', 'judul'));
    }

    // 7. LAPORAN PENGEMBALIAN TERLAMBAT (setelah masa peminjaman)
    public function pengembalianTerlambat(Request $request)
    {
        $data = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Kembali')
            ->whereNotNull('tanggal_kembali_aktual')
            ->get()
            ->filter(fn($p) => $p->was_returned_late)
            ->values();

        $judul = 'Laporan Pengembalian Terlambat';
        $tipe = 'pengembalian_terlambat';

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('data', 'judul', 'tipe'));
            return $pdf->download('laporan_pengembalian_terlambat.pdf');
        }

        return view('laporan.pengembalian-terlambat', compact('data', 'judul'));
    }

    /**
     * Laporan Peminjaman yang sedang terlambat (belum dikembalikan)
     */
    public function terlambat(Request $request)
    {
        $query = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now());

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_kembali_rencana', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_kembali_rencana', '<=', $request->end_date);
        }

        $peminjamanTerlambatQuery = $query->oldest('tanggal_kembali_rencana');

        if ($request->has('export') && $request->export == 'pdf') {
            $peminjamanTerlambat = (clone $peminjamanTerlambatQuery)->get()->map(function ($p) {
                // For PDF, use days
                $p->durasi_telat = \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->diffInDays(now());
                return $p;
            });
            $tanggalMulai = $request->start_date;
            $tanggalSelesai = $request->end_date;
            $pdf = Pdf::loadView('laporan.terlambat_pdf', compact('peminjamanTerlambat', 'tanggalMulai', 'tanggalSelesai'));
            return $pdf->stream('laporan_peminjaman_terlambat.pdf');
        }

        $peminjamanTerlambat = $peminjamanTerlambatQuery->paginate(10)->through(function ($p) {
            // For view, use forHumans
            $p->durasi_telat = \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->diffForHumans(now(), ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]);
            return $p;
        });

        return view('laporan.terlambat', compact('peminjamanTerlambat'));
    }
}
