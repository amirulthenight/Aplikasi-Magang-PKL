<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Karyawan;
use PDF;
use App\Notifications\PeminjamanSudahTerlambat;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PeringatanTerlambatTerkonsolidasi;


class LaporanController extends Controller
{
    /**
     * Display the main reports page.
     */
    public function index()
    {
        return view('laporan.index');
    }

    // Laporan Peminjaman Terlambat
    public function terlambat(Request $request)
    {
        $peminjamanTerlambat = Peminjaman::terlambat($request)->paginate(10); // Lebih singkat
        return view('laporan.terlambat', [
            'peminjamanTerlambat' => $peminjamanTerlambat,
            'tanggalMulai' => $request->start_date,
            'tanggalSelesai' => $request->end_date,
        ]);
    }

    public function terlambatPdf(Request $request)
    {
        $peminjamanTerlambat = Peminjaman::terlambat($request)->get(); // Lebih singkat
        $tanggalMulai = $request->start_date;
        $tanggalSelesai = $request->end_date;

        $pdf = PDF::loadView('laporan.terlambat_pdf', compact('peminjamanTerlambat', 'tanggalMulai', 'tanggalSelesai'));
        return $pdf->stream('laporan-peminjaman-terlambat.pdf');
    }



    // Laporan Riwayat Peminjaman
    // Gantikan method riwayat() Anda dengan ini
    public function riwayat(Request $request)
    {
        $riwayatPeminjaman = Peminjaman::riwayat($request)->paginate(10); // Lebih singkat
        $tanggalMulai = $request->start_date;
        $tanggalSelesai = $request->end_date;

        return view('laporan.riwayat', compact('riwayatPeminjaman', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function riwayatPdf(Request $request)
    {
        $riwayatPeminjaman = Peminjaman::riwayat($request)->get(); // Lebih singkat
        $tanggalMulai = $request->start_date;
        $tanggalSelesai = $request->end_date;

        $pdf = PDF::loadView('laporan.riwayat_pdf', compact('riwayatPeminjaman', 'tanggalMulai', 'tanggalSelesai'));
        return $pdf->stream('laporan-riwayat-peminjaman.pdf');
    }

    // --- TAMBAHKAN DUA FUNGSI BARU DI BAWAH INI ---

    /**
     * Display the most popular items report page.
     */
    public function populer(Request $request)
    {
        $limit = $request->get('limit', 10);

        $barangPopuler = Barang::query()
            ->select([
                'barangs.kode_barang',
                'barangs.nama_barang',
                'barangs.status',
                // Menghitung total peminjaman
                DB::raw('COUNT(peminjamans.id) as total_dipinjam'),
                // Subquery untuk Peminjam Terakhir
                DB::raw('(SELECT k.nama_karyawan FROM peminjamans p_sub JOIN karyawans k ON p_sub.karyawan_id = k.id WHERE p_sub.barang_id = barangs.id ORDER BY p_sub.created_at DESC LIMIT 1) as peminjam_terakhir'),
                // Subquery untuk Departemen Terpopuler
                DB::raw('(SELECT k.departemen FROM peminjamans p_sub JOIN karyawans k ON p_sub.karyawan_id = k.id WHERE p_sub.barang_id = barangs.id GROUP BY k.departemen ORDER BY COUNT(k.departemen) DESC LIMIT 1) as departemen_populer')
            ])
            ->join('peminjamans', 'barangs.id', '=', 'peminjamans.barang_id')
            ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kode_barang', 'barangs.status')
            ->orderBy('total_dipinjam', 'DESC')
            ->orderBy('barangs.nama_barang', 'ASC')
            ->take($limit)
            ->get();

        return view('laporan.populer', compact('barangPopuler', 'limit'));
    }

    /**
     * Generate PDF for the most popular items report.
     */
    public function populerPdf(Request $request)
    {
        $limit = $request->get('limit', 10);

        // Menggunakan query yang sama persis dengan di atas
        $barangPopuler = Barang::query()
            ->select([
                'barangs.kode_barang',
                'barangs.nama_barang',
                'barangs.status',
                DB::raw('COUNT(peminjamans.id) as total_dipinjam'),
                DB::raw('(SELECT k.nama_karyawan FROM peminjamans p_sub JOIN karyawans k ON p_sub.karyawan_id = k.id WHERE p_sub.barang_id = barangs.id ORDER BY p_sub.created_at DESC LIMIT 1) as peminjam_terakhir'),
                DB::raw('(SELECT k.departemen FROM peminjamans p_sub JOIN karyawans k ON p_sub.karyawan_id = k.id WHERE p_sub.barang_id = barangs.id GROUP BY k.departemen ORDER BY COUNT(k.departemen) DESC LIMIT 1) as departemen_populer')
            ])
            ->join('peminjamans', 'barangs.id', '=', 'peminjamans.barang_id')
            ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kode_barang', 'barangs.status')
            ->orderBy('total_dipinjam', 'DESC')
            ->orderBy('barangs.nama_barang', 'ASC')
            ->take($limit)
            ->get();

        $pdf = PDF::loadView('laporan.populer_pdf', compact('barangPopuler', 'limit'));
        return $pdf->stream('laporan-barang-populer.pdf');
    }

    /**
     * Display the loan history report by employee page.
     */


    public function perKaryawan(Request $request)
    {
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();
        $riwayatPeminjaman = collect();
        $karyawanDipilih = null;

        $statistik = [
            'total_pinjam' => 0,
            'sedang_dipinjam' => 0,
            'terlambat_sekarang' => 0,
            'total_rekor_terlambat' => 0,
        ];

        if ($request->filled('karyawan_id')) {
            $karyawanDipilih = Karyawan::find($request->karyawan_id);
            if ($karyawanDipilih) {
                $riwayatPeminjaman = $karyawanDipilih->peminjamans()->with('barang')->latest()->paginate(10);

                // --- LOGIKA STATISTIK FINAL ---
                $statistik['total_pinjam'] = $karyawanDipilih->peminjamans()->count();

                $peminjamanAktif = $karyawanDipilih->peminjamans()->where('status', 'Dipinjam');
                $statistik['sedang_dipinjam'] = $peminjamanAktif->clone()->count();

                $terlambatSekarang = $peminjamanAktif->clone()->where('tanggal_wajib_kembali', '<', now())->count();
                $statistik['terlambat_sekarang'] = $terlambatSekarang;

                $terlambatHistoris = $karyawanDipilih->peminjamans()
                    ->where('status', 'Selesai')
                    ->whereColumn('tanggal_kembali', '>', 'tanggal_wajib_kembali')
                    ->count();

                $statistik['total_rekor_terlambat'] = $terlambatHistoris + $terlambatSekarang;
                // ------------------------------------
            }
        }

        return view('laporan.per-karyawan', compact('karyawans', 'riwayatPeminjaman', 'karyawanDipilih', 'statistik'));
    }

    /**
     * Generate PDF for the loan history report by employee.
     */
    public function perKaryawanPdf(Request $request)
    {
        $karyawanDipilih = null;
        $riwayatPeminjaman = collect();

        if ($request->filled('karyawan_id')) {
            $karyawanDipilih = Karyawan::find($request->karyawan_id);
            if ($karyawanDipilih) {
                $riwayatPeminjaman = $karyawanDipilih->peminjamans()->with('barang')->latest()->get();
            }
        } else {
            // Handle jika tidak ada karyawan yang dipilih, meskipun seharusnya tidak terjadi
            return redirect()->route('laporan.perKaryawan')->with('error', 'Silakan pilih karyawan terlebih dahulu.');
        }

        $pdf = PDF::loadView('laporan.per-karyawan_pdf', compact('riwayatPeminjaman', 'karyawanDipilih'));
        return $pdf->stream('laporan-peminjaman-' . $karyawanDipilih->nama_karyawan . '.pdf');
    }

    // Fungsi untuk mengirim pengingat email peminjaman yang sudah terlambat

    public function kirimPengingatTerlambat()
    {
        $semuaPeminjamanTerlambat = Peminjaman::with('karyawan') // Lebih efisien
            ->where('status', 'Dipinjam')
            ->where('tanggal_wajib_kembali', '<', now())
            ->get();

        if ($semuaPeminjamanTerlambat->isEmpty()) {
            return redirect()->route('dashboard')->with('info', 'Tidak ada peminjaman terlambat.');
        }

        $terlambatPerKaryawan = $semuaPeminjamanTerlambat->groupBy('karyawan_id');
        $jumlahKaryawan = $terlambatPerKaryawan->count();

        foreach ($terlambatPerKaryawan as $karyawanId => $daftarPeminjaman) {
            $karyawan = $daftarPeminjaman->first()->karyawan;

            if ($karyawan && $karyawan->email) {
                // Ini akan otomatis menaruh notifikasi di antrean, bukan mengirim langsung
                $karyawan->notify(new PeringatanTerlambatTerkonsolidasi($daftarPeminjaman));
            }
        }

        // GUNAKAN TONGKAT SIHIR YANG SAMA
        return redirect()->back()->with('success', $jumlahKaryawan . ' email ringkasan berhasil dikirim.');
    }
    // Fungsi untuk Excel akan kita buat nanti setelah instalasi paketnya
    public function terlambatExcel(Request $request)
    {
        // Logika untuk Excel akan ditambahkan di sini
        return "Fitur Laporan Excel akan segera dibuat.";
    }
}
