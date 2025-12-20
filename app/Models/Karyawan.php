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
        'email', // <-- TAMBAHKAN INI
        'jabatan',
        'departemen',
        'site',
        'keterangan',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
