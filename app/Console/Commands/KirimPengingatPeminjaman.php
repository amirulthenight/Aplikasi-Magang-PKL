<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Notifications\PeminjamanAkanJatuhTempo;
use App\Notifications\PeminjamanSudahTerlambat; // <-- INI DIA YANG HILANG!
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class KirimPengingatPeminjaman extends Command
{
    /**
     * Nama perintah untuk dijalankan di terminal.
     */
    // PERUBAHAN 1: Kita buat namanya lebih singkat dan jelas
    protected $signature = 'peminjaman:kirim-pengingat';

    /**
     * Deskripsi dari perintah ini.
     */
    // PERUBAHAN 2: Kita beri deskripsi yang jelas
    protected $description = 'Mencari peminjaman yang akan jatuh tempo besok dan mengirimkan notifikasi pengingat.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa semua peminjaman yang sedang terlambat...');

        // 1. Cari SEMUA peminjaman yang sedang terlambat
        $semuaPeminjamanTerlambat = Peminjaman::with(['barang', 'karyawan'])
            ->where('status', 'Dipinjam')
            ->where('tanggal_wajib_kembali', '<', now())
            ->get();

        if ($semuaPeminjamanTerlambat->isEmpty()) {
            $this->info('Tidak ada peminjaman yang terlambat hari ini. Pekerjaan selesai.');
            return;
        }

        // 2. Kelompokkan peminjaman berdasarkan karyawan
        $terlambatPerKaryawan = $semuaPeminjamanTerlambat->groupBy('karyawan_id');

        $this->info('Ditemukan ' . $semuaPeminjamanTerlambat->count() . ' item terlambat oleh ' . $terlambatPerKaryawan->count() . ' karyawan. Mengirim email ringkasan...');

        // 3. Kirim SATU email ringkasan untuk setiap karyawan
        foreach ($terlambatPerKaryawan as $karyawanId => $daftarPeminjaman) {
            $karyawan = $daftarPeminjaman->first()->karyawan;

            if ($karyawan && $karyawan->email) {
                Notification::send($karyawan, new PeringatanTerlambatTerkonsolidasi($daftarPeminjaman));
                $this->line('-> Mengirim ringkasan ke: ' . $karyawan->nama_karyawan . ' (' . $daftarPeminjaman->count() . ' item)');
                sleep(2); // Jeda 2 detik agar tidak di-block Mailtrap
            }
        }

        $this->info('Semua email ringkasan berhasil dikirim. Pekerjaan selesai.');
    }
}
