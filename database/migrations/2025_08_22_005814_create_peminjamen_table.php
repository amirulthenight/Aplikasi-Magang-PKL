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
        // Pastikan isinya kurang lebih begini:
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');

            // Kita pakai dateTime agar mencatat Jam juga, bukan cuma Tanggal
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_kembali_rencana');
            $table->dateTime('tanggal_kembali_aktual')->nullable();

            $table->string('status_peminjaman')->default('Dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
