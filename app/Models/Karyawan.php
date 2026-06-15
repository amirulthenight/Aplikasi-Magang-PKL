<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Karyawan extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'nama_karyawan',
        'email',
        'no_telp', // Nomor Telepon/WhatsApp untuk notifikasi
        'jabatan',
        'departemen',
        'site',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
