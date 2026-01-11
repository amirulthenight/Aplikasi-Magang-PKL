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
        $query->where('status', 'Rusak');

        if ($request->filled('site')) {
            $query->where('site', $request->site);
        }
    }
}
