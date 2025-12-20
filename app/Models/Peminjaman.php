<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjamans';
    // app/Models/Peminjaman.php
    protected $fillable = [
        'barang_id',
        'karyawan_id',
        'tanggal_pinjam',
        'tanggal_wajib_kembali', // <-- TAMBAHKAN INI
        'tanggal_kembali',
        'status',
        'alasan_pinjam', // <-- TAMBAHKAN INI
    ];

    public function getDurasiTelatAttribute(): ?string
    {
        if (!$this->is_overdue) {
            return null;
        }

        $wajibKembali = \Carbon\Carbon::parse($this->tanggal_wajib_kembali);
        $sekarang = now();

        // PERBAIKAN: Gunakan diffInDays untuk total hari yang akurat
        $totalDays = $wajibKembali->diffInDays($sekarang);
        if ($totalDays > 0) {
            return $totalDays . " Hari";
        }

        // Jika kurang dari sehari, baru hitung jam
        $totalHours = $wajibKembali->diffInHours($sekarang);
        if ($totalHours > 0) {
            return $totalHours . " Jam";
        }

        // Jika kurang dari sejam, baru hitung menit
        $totalMinutes = $wajibKembali->diffInMinutes($sekarang);
        if ($totalMinutes > 0) {
            return $totalMinutes . " Menit";
        }

        return "Baru Saja Terlambat";
    }

    public function scopeTerlambat($query, Request $request)
    {
        $query->with(['barang', 'karyawan'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_wajib_kembali', '<', Carbon::now());

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_wajib_kembali', [$request->start_date, $request->end_date]);
        }
    }

    // Scope untuk Laporan Riwayat
    public function scopeRiwayat($query, Request $request)
    {
        $query->with(['barang', 'karyawan'])->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pinjam', [$request->start_date, $request->end_date]);
        }
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status == 'Dipinjam' && now()->gt($this->tanggal_wajib_kembali),
        );
    }

    protected function overdueDays(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_overdue
                ? now()->diffInDays($this->tanggal_wajib_kembali)
                : 0,
        );
    }

    protected function wasReturnedLate(): Attribute
    {
        return Attribute::make(
            get: fn() => (
                // Hanya berlaku jika statusnya sudah Selesai
                $this->status === 'Selesai' &&
                // Pastikan ada tanggal kembali
                $this->tanggal_kembali &&
                // Bandingkan tanggal kembali dengan tanggal wajib kembali
                \Carbon\Carbon::parse($this->tanggal_kembali)->gt(\Carbon\Carbon::parse($this->tanggal_wajib_kembali))
            )
        );
    }
}
