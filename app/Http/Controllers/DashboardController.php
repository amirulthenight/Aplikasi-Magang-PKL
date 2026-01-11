<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with key metrics and recent activities.
     */

    public function index()
    {
        // 1. STATISTIK DASAR
        $totalAset = Barang::count();
        $stokTersedia = Barang::sum('stok'); // Menjumlahkan semua stok
        $sedangDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->count();

        // 2. HITUNG PERSENTASE KETERSEDIAAN (Untuk Bar Grafik)
        $totalUnit = $stokTersedia + $sedangDipinjam;
        $availablePercentage = $totalUnit > 0 ? round(($stokTersedia / $totalUnit) * 100) : 0;

        // 3. DATA PEMINJAMAN TERLAMBAT (Rename variable agar cocok dengan View)
        $peminjamanTerlambat = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now())
            ->get();

        // Hitung durasi telat untuk tampilan
        foreach ($peminjamanTerlambat as $p) {
            $p->durasi_telat = Carbon::parse($p->tanggal_kembali_rencana)->diffForHumans();
        }

        $terlambatKembali = $peminjamanTerlambat->count();

        // Variabel Modal Peringatan
        $showPeringatanModal = $terlambatKembali > 0;

        // 4. DATA GRAFIK (7 Hari Terakhir)
        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M'); // Label Tgl
            // Hitung peminjaman pada tanggal tersebut
            $chartValues[] = Peminjaman::whereDate('created_at', $date->format('Y-m-d'))->count();
        }
        $chartData = [
            'labels' => $chartLabels,
            'data' => $chartValues
        ];

        // 5. AKTIVITAS TERKINI (5 Transaksi Terakhir)
        $aktivitasTerkini = Peminjaman::with(['barang', 'karyawan'])
            ->latest()
            ->take(5)
            ->get();

        // Penyesuaian status untuk view
        foreach ($aktivitasTerkini as $aktivitas) {
            $aktivitas->status = ($aktivitas->status_peminjaman == 'Kembali') ? 'Selesai' : 'Dipinjam';
        }

        // Kirim semua ke View
        return view('dashboard', compact(
            'totalAset',
            'stokTersedia',
            'sedangDipinjam',
            'availablePercentage',
            'terlambatKembali',
            'peminjamanTerlambat', // View minta variable ini
            'showPeringatanModal',
            'chartData',
            'aktivitasTerkini'
        ));
    }

    // app/Http/Controllers/DashboardController.php

    // public function index(Request $request)
    // {
    //     // ==========================================================
    //     // BAGIAN 1: AMBIL SEMUA DATA MENTAH DARI DATABASE
    //     // ==========================================================
    //     $totalAset = Barang::count();
    //     $stokTersedia = Barang::where('status', 'Tersedia')->count();
    //     $sedangDipinjam = Peminjaman::where('status', 'Dipinjam')->count();

    //     // Ambil data untuk MODAL (termasuk Peminjam Terakhir)
    //     $asetRusak = Barang::where('status', 'Rusak')
    //         ->addSelect([
    //             'peminjam_terakhir' => Peminjaman::select('karyawans.nama_karyawan')
    //                 ->join('karyawans', 'peminjamans.karyawan_id', '=', 'karyawans.id')
    //                 ->whereColumn('peminjamans.barang_id', 'barangs.id')
    //                 ->latest('peminjamans.created_at')->limit(1)
    //         ])->get();

    //     // Ambil SEMUA peminjaman terlambat SEKALI SAJA
    //     $semuaPeminjamanTerlambat = Peminjaman::with(['barang', 'karyawan'])
    //         ->where('status', 'Dipinjam')
    //         ->where('tanggal_wajib_kembali', '<', now())
    //         ->orderBy('tanggal_wajib_kembali', 'asc')->get();

    //     $aktivitasTerkini = Peminjaman::with(['barang', 'karyawan'])->orderBy('updated_at', 'desc')->take(5)->get();

    //     // ==========================================================
    //     // BAGIAN 2: HITUNG STATISTIK DARI DATA YANG SUDAH ADA
    //     // ==========================================================
    //     $jumlahRusak = $asetRusak->count();
    //     $terlambatKembali = $semuaPeminjamanTerlambat->count();
    //     $availablePercentage = ($totalAset > 0) ? round(($stokTersedia / $totalAset) * 100) : 0;
    //     $showPeringatanModal = $asetRusak->isNotEmpty() || $semuaPeminjamanTerlambat->isNotEmpty();

    //     // Ambil 5 data teratas untuk panel samping dari data yang sudah kita ambil
    //     $peminjamanTerlambat = $semuaPeminjamanTerlambat->take(5);

    //     // ==========================================================
    //     // BAGIAN 3: SIAPKAN DATA UNTUK GRAFIK INTERAKTIF
    //     // ==========================================================
    //     $queryGrafik = Peminjaman::join('barangs', 'peminjamans.barang_id', '=', 'barangs.id');
    //     if ($request->filled('tanggal')) {
    //         $rentangTanggal = explode(' to ', $request->tanggal);
    //         $tanggalMulai = $rentangTanggal[0];
    //         $tanggalSelesai = $rentangTanggal[1] ?? $tanggalMulai;
    //         $queryGrafik->whereBetween('peminjamans.created_at', [$tanggalMulai, $tanggalSelesai . ' 23:59:59']);
    //     }
    //     $chartData = $queryGrafik->select('barangs.nama_barang', DB::raw('count(peminjamans.barang_id) as total'))
    //         ->groupBy('barangs.nama_barang')->orderBy('total', 'desc')->take(5)->get()
    //         ->pipe(fn($data) => ['labels' => $data->pluck('nama_barang'), 'data' => $data->pluck('total')]);

    //     // ==========================================================
    //     // BAGIAN 4: KIRIM SEMUA DATA YANG SUDAH RAPI KE VIEW
    //     // ==========================================================
    //     return view('dashboard', compact(
    //         'totalAset',
    //         'stokTersedia',
    //         'sedangDipinjam',
    //         'jumlahRusak',
    //         'terlambatKembali',
    //         'availablePercentage',
    //         'chartData',
    //         'aktivitasTerkini',
    //         'asetRusak',
    //         'semuaPeminjamanTerlambat',
    //         'showPeringatanModal',
    //         'peminjamanTerlambat'
    //     ));
    // }
}
