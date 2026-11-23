<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeliharaans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->restrictOnDelete();

            $table->foreignId('peminjaman_id')
                ->unique()
                ->constrained('peminjamans')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('keterangan_kerusakan');

            $table->json('checklist_kerusakan')->nullable();
            $table->json('checklist_perbaikan')->nullable();

            $table->text('tindakan_perbaikan')->nullable();

            $table->decimal('biaya_perbaikan', 15, 2)
                ->default(0);

            $table->string('status', 20)->default('Rusak');

            $table->dateTime('tanggal_mulai_perbaikan')->nullable();
            $table->dateTime('tanggal_selesai_perbaikan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaans');
    }
};
