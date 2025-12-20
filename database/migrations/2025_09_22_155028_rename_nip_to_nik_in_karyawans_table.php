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
        Schema::table('karyawans', function (Blueprint $table) {
            // Mengubah nama kolom 'nip' menjadi 'nik'
            $table->renameColumn('nip', 'nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            // Jika di-rollback, kembalikan 'nik' menjadi 'nip'
            $table->renameColumn('nik', 'nip');
        });
    }
};
