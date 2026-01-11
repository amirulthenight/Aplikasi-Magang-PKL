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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama_karyawan');
            $table->string('email')->unique()->nullable(); // Tambahkan kolom email
            $table->string('jabatan');
            $table->string('departemen');
            $table->string('site')->nullable(); // Tambahkan kolom site
            $table->text('keterangan')->nullable(); // Tambahkan kolom keterangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
