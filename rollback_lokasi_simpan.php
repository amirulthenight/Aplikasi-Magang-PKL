<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasColumn('barangs', 'lokasi_simpan')) {
    Schema::table('barangs', function (Blueprint $table) {
        $table->dropColumn('lokasi_simpan');
    });
    echo "Kolom lokasi_simpan berhasil dihapus dari tabel barangs.\n";
} else {
    echo "Kolom lokasi_simpan tidak ditemukan di tabel barangs (sudah bersih).\n";
}

// Juga hapus record migration dari tabel migrations jika ada
$deleted = DB::table('migrations')->where('migration', '2026_06_19_234241_add_lokasi_simpan_to_barangs_table')->delete();
if ($deleted) {
    echo "Record migration juga dihapus dari tabel migrations.\n";
}
