<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini ada
use Exception; // Tambahkan ini untuk menangani error

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Peminjaman::with(['barang', 'karyawan']);

        // Logika Pencarian Baru
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('karyawan', function ($subq) use ($search) {
                    $subq->where('nama_karyawan', 'like', "%{$search}%");
                })->orWhereHas('barang', function ($subq) use ($search) {
                    $subq->where('nama_barang', 'like', "%{$search}%");
                });
            });
        }

        if ($status === 'Selesai') {
            // Jika filter "Selesai", tampilkan yang selesai dan urutkan berdasarkan tanggal kembali terbaru
            $query->where('status', 'Selesai')->orderBy('tanggal_kembali', 'desc');
        } elseif ($status === 'Semua') {
            // Jika filter "Semua", urutkan berdasarkan tanggal dibuat terbaru
            $query->latest();
        } else {
            // Secara default, tampilkan yang masih berjalan dan urutkan berdasarkan tanggal wajib kembali terdekat
            $query->whereIn('status', ['Dipinjam', 'Terlambat'])->orderBy('tanggal_wajib_kembali', 'asc');
        }

        $peminjamans = $query->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'Tersedia')->get();
        $karyawans = Karyawan::all();
        return view('peminjaman.create', compact('barangs', 'karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_wajib_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'alasan_pinjam' => 'required|string',
        ]);

        // Cek ketersediaan barang SEBELUM memulai transaksi
        $barang = Barang::find($request->barang_id);
        if ($barang->status !== 'Tersedia') {
            return redirect()->back()->with('error', 'Barang sedang tidak tersedia untuk dipinjam.')->withInput();
        }

        try {
            // Mulai transaksi
            DB::transaction(function () use ($request, $barang) {
                // 1. Buat catatan peminjaman
                Peminjaman::create($request->all());

                // 2. Ubah status barang
                $barang->update(['status' => 'Dipinjam']);
            });

            // Redirect jika semua operasi dalam transaksi berhasil
            return redirect()->route('peminjaman.index')->with('success', 'Barang berhasil dipinjam.');
        } catch (Exception $e) {
            // Jika terjadi error APAPUN di dalam transaksi, redirect dengan pesan error
            return redirect()->back()->with('error', 'Gagal memproses peminjaman. Silakan coba lagi.')->withInput();
        }
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        // Terapkan juga di sini untuk konsistensi
        DB::transaction(function () use ($peminjaman) {
            // 1. Update status peminjaman
            $peminjaman->update([
                'status' => 'Selesai',
                'tanggal_kembali' => now(), // now() adalah helper yang lebih singkat
            ]);

            // 2. Update status barang
            $peminjaman->barang->update(['status' => 'Tersedia']);
        });

        return redirect()->route('peminjaman.index')->with('success', 'Barang berhasil dikembalikan.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        // Pastikan hanya peminjaman yang sedang berjalan yang bisa diedit
        if ($peminjaman->status != 'Dipinjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Hanya peminjaman yang sedang berjalan yang bisa diedit.');
        }

        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_wajib_kembali' => 'required|date|after_or_equal:' . $peminjaman->tanggal_pinjam,
        ]);

        $peminjaman->update([
            'tanggal_wajib_kembali' => $request->tanggal_wajib_kembali,
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Waktu peminjaman berhasil diperpanjang.');
    }

    // Fungsi-fungsi lain di bawah ini biarkan kosong
    // public function edit(Peminjaman $peminjaman) {}
    // public function update(Request $request, Peminjaman $peminjaman) {}
    public function show(Peminjaman $peminjaman) {}
    public function destroy(Peminjaman $peminjaman) {}
}
