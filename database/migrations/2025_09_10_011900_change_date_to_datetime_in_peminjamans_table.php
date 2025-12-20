<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dateTime('tanggal_pinjam')->change();
            $table->dateTime('tanggal_wajib_kembali')->change();
            $table->dateTime('tanggal_kembali')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Kembalikan tipe data ke 'date' jika di-rollback
            $table->date('tanggal_pinjam')->change();
            $table->date('tanggal_wajib_kembali')->change();
            $table->date('tanggal_kembali')->nullable()->change();
        });
    }
};
