<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request; // <-- Jangan lupa tambahkan ini


class Barang extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'merk',
        'stok', // Ganti site/rusak jadi stok
        'status',
        'keterangan', // Ditambahkan agar detail kerusakan diizinkan masuk
    ];

    // Accessor: Untuk mendapatkan status secara otomatis
    // Cara panggil di view: {{ $barang->status_barang }}
    public function getStatusBarangAttribute()
    {
        return $this->stok > 0 ? 'Tersedia' : 'Tidak Tersedia';
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }
    // ==========================================================

    // Scope untuk Laporan Barang Rusak
    public function scopeRusak($query, Request $request)
    {
        // ==================================================================================
        // PERBAIKAN KRUSIAL: Logika diubah total untuk membaca dari sumber data yang benar.
        // Laporan kerusakan sekarang menampilkan ASET (Barang) yang PERNAH memiliki
        // riwayat pengembalian (`peminjamans`) dengan kondisi rusak.
        // Ini tidak lagi membutuhkan kolom 'status' di tabel `barangs`.
        // ==================================================================================
        $query->whereHas('peminjamans', function ($peminjamanQuery) use ($request) {
            $peminjamanQuery->where('status_peminjaman', 'Kembali')
                ->whereNotNull('keterangan_kerusakan')
                ->where('keterangan_kerusakan', '!=', '');

            // Filter site juga diperbaiki agar mencari di data karyawan yang meminjam
            if ($request->filled('site')) {
                $peminjamanQuery->whereHas('karyawan', function ($karyawanQuery) use ($request) {
                    $karyawanQuery->where('site', $request->site);
                });
            }
        });
    }
}
