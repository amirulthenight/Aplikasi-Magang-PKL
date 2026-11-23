<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaans';

    protected $fillable = [
        'barang_id',
        'peminjaman_id',
        'user_id',
        'keterangan_kerusakan',
        'checklist_kerusakan',
        'checklist_perbaikan',
        'tindakan_perbaikan',
        'biaya_perbaikan',
        'status',
        'tanggal_mulai_perbaikan',
        'tanggal_selesai_perbaikan',
    ];

    protected $casts = [
        'checklist_kerusakan' => 'array',
        'checklist_perbaikan' => 'array',
        'biaya_perbaikan' => 'decimal:2',
        'tanggal_mulai_perbaikan' => 'datetime',
        'tanggal_selesai_perbaikan' => 'datetime',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
