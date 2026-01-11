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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique(); // LP-001
            $table->string('nama_barang');           // Laptop Thinkpad
            $table->string('kategori');              // Laptop/Monitor (Buat hitung jenis)
            $table->string('merk')->nullable();      // Lenovo/Dell
            $table->integer('stok')->default(0);     // Stok: 10
            $table->text('keterangan')->nullable();  // Keterangan (Sesuai Video)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
