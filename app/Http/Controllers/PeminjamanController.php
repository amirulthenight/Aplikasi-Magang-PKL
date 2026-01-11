<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman (Riwayat)
     */
    public function index(Request $request)
    {
        // 1. Ambil inputan dari form filter
        $status = $request->input('status');
        $search = $request->input('search');

        // 2. Query Dasar
        $query = Peminjaman::with(['karyawan', 'barang']);

        // 3. LOGIKA SEARCH (Ini yang kemaren KURANG)
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari di tabel Karyawan (Nama atau NIK)
                $q->whereHas('karyawan', function ($k) use ($search) {
                    $k->where('nama_karyawan', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                })
                    // Atau cari di tabel Barang (Nama Barang)
                    ->orWhereHas('barang', function ($b) use ($search) {
                        $b->where('nama_barang', 'like', '%' . $search . '%');
                    });
            });
        }

        // 4. LOGIKA STATUS (Saya rapikan biar cocok sama View)
        if ($status === 'selesai') {
            // Tampilkan yang sudah kembali
            $query->where('status_peminjaman', 'Kembali')
                ->orderBy('tanggal_kembali_aktual', 'desc');
        } elseif ($status === 'berjalan' || $status == '') { // Default 'Berjalan'
            // Tampilkan Dipinjam / Terlambat
            $query->whereIn('status_peminjaman', ['Dipinjam', 'Terlambat'])
                ->orderBy('tanggal_kembali_rencana', 'asc');
        }
        // Kalau 'semua', dia gak masuk if/else di atas (alias tampilkan semua)

        $peminjamans = $query->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        // Urutkan nama karyawan sesuai kolom database baru
        $karyawans = Karyawan::orderBy('nama_karyawan', 'asc')->get();

        // Cuma ambil barang yang stoknya ada
        $barangs = Barang::where('stok', '>', 0)->get();

        return view('peminjaman.create', compact('karyawans', 'barangs'));
    }

    /**
     * Menyimpan Data Peminjaman (Mengurangi Stok)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'barang_id' => 'required|exists:barangs,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_wajib_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'alasan_pinjam' => 'nullable', // Boleh kosong biar gak ribet
        ]);

        // 2. Cek Stok
        $barang = Barang::findOrFail($validatedData['barang_id']);
        if ($barang->stok < 1) {
            return redirect()->back()->with('error', 'Stok habis!')->withInput();
        }

        // 3. Simpan ke Database
        Peminjaman::create([
            'karyawan_id' => $validatedData['karyawan_id'],
            'barang_id' => $validatedData['barang_id'],
            'tanggal_pinjam' => $validatedData['tanggal_pinjam'],

            // Kolom tanggal balik (JANGAN DIHAPUS)
            'tanggal_kembali_rencana' => $validatedData['tanggal_wajib_kembali'],

            // KITA KEMBALIKAN JADI ALASAN_PINJAM (Sesuai database Abang)
            'alasan_pinjam' => $request->input('alasan_pinjam'),

            'status_peminjaman' => 'Dipinjam',
        ]);

        // 4. Kurangi Stok
        $barang->decrement('stok');

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disimpan.');
    }

    /**
     * Pengembalian Barang
     */
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status_peminjaman' => 'Kembali',
            'tanggal_kembali_aktual' => now()
        ]);

        // Balikin Stok
        $peminjaman->barang->increment('stok');

        return back()->with('success', 'Barang dikembalikan & Stok bertambah!');
    }

    public function edit(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman != 'Dipinjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Hanya peminjaman aktif yang bisa diedit.');
        }
        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_wajib_kembali' => 'required|date|after_or_equal:' . $peminjaman->tanggal_pinjam,
        ]);

        $peminjaman->update([
            'tanggal_kembali_rencana' => $request->tanggal_wajib_kembali, // MAPPING PENTING DISINI
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Waktu peminjaman diperpanjang.');
    }

    // --- FITUR BARU: PENGEMBALIAN CEPAT ---
    public function indexPengembalianCepat()
    {
        return view('peminjaman.kembali_khusus', [
            'karyawan' => null,
            'peminjamans' => collect([])
        ]);
    }

    public function cariByNik(Request $request)
    {
        $nik = $request->input('nik');
        $karyawan = Karyawan::where('nik', $nik)->first();

        if (!$karyawan) {
            return redirect()->route('peminjaman.indexPengembalianCepat')
                ->with('error', 'NIK tidak ditemukan.');
        }

        $peminjamans = Peminjaman::with('barang')
            ->where('karyawan_id', $karyawan->id)
            ->where('status_peminjaman', 'Dipinjam')
            ->get();

        return view('peminjaman.kembali_khusus', compact('karyawan', 'peminjamans'));
    }
}
