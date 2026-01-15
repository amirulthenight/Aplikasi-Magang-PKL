<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================================
        // 1. PERBAIKAN LOGIKA STATISTIK (Supaya Total Aset Benar)
        // ==========================================================

        // Stok Tersedia = Jumlah barang yang nyata ada di lemari saat ini
        $stokTersedia = Barang::sum('stok');

        // Sedang Dipinjam = Jumlah transaksi yang statusnya masih 'Dipinjam'
        $sedangDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->count();

        // Total Aset = Stok di Lemari + Stok yang dibawa Orang
        // INI PERBAIKANNYA: Dijumlahkan agar Total Aset masuk akal
        $totalAset = $stokTersedia + $sedangDipinjam;

        // Persentase
        $availablePercentage = $totalAset > 0 ? round(($stokTersedia / $totalAset) * 100) : 0;

        // Hitung Terlambat
        $terlambatKembali = Peminjaman::where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now()) // Pastikan nama kolom ini benar di DB kamu
            ->count();

        // ==========================================================
        // 2. GRAFIK TOP 5 BARANG + FILTER TANGGAL (GABUNGAN)
        // ==========================================================

        // Default: 1 Bulan terakhir jika tidak ada filter
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();

        // Cek Filter Tanggal dari Form
        if ($request->filled('tanggal')) {
            $dates = explode(' to ', $request->input('tanggal'));
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
            } elseif (count($dates) == 1) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[0])->endOfDay();
            }
        }

        // Query: Cari 5 barang paling laku DALAM RENTANG TANGGAL TERSEBUT
        $populerData = Peminjaman::select('barang_id', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate]) // Filter Waktu
            ->groupBy('barang_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('barang')
            ->get();

        $labels = [];
        $data = [];

        foreach ($populerData as $item) {
            $labels[] = $item->barang->nama_barang ?? 'Barang Dihapus';
            $data[] = $item->total;
        }

        if (empty($labels)) {
            $labels = ['Tidak ada data'];
            $data = [0];
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // ==========================================================
        // 3. DATA PENDUKUNG (SIDEBAR & AKTIVITAS)
        // ==========================================================

        // Tabel Sidebar Kanan (Peminjaman Terlambat)
        $peminjamanTerlambat = Peminjaman::with(['karyawan', 'barang'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now())
            ->orderBy('tanggal_kembali_rencana', 'asc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->durasi_telat = Carbon::parse($item->tanggal_kembali_rencana)->diffForHumans(null, true) . ' overdue';
                return $item;
            });

        // Tabel Bawah (Aktivitas Admin)
        $aktivitasTerkini = Peminjaman::with(['karyawan', 'barang'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $showPeringatanModal = $terlambatKembali > 2;

        return view('dashboard', compact(
            'totalAset',
            'stokTersedia',
            'sedangDipinjam',
            'terlambatKembali',
            'availablePercentage',
            'chartData',
            'peminjamanTerlambat',
            'aktivitasTerkini',
            'showPeringatanModal'
        ));
    }
}
