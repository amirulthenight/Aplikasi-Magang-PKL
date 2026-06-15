<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Notifications\PeminjamanAkanJatuhTempo;
use App\Notifications\PeminjamanSudahTerlambat;
use App\Notifications\PeringatanTerlambatTerkonsolidasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class KirimPengingatPeminjaman extends Command
{
    /**
     * Nama perintah untuk dijalankan di terminal.
     */
    protected $signature = 'peminjaman:kirim-pengingat';

    /**
     * Deskripsi dari perintah ini.
     */
    protected $description = 'Kirim pengingat peminjaman untuk H-3, H-2, H-1, dan yang sudah terlambat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== MULAI PROSES PENGINGAT PEMINJAMAN ===');

        // 1. H-3: Pengingat 3 hari sebelum jatuh tempo
        $this->kirimPengingatH3();

        // 2. H-2: Pengingat 2 hari sebelum jatuh tempo
        $this->kirimPengingatH2();

        // 3. H-1: Pengingat 1 hari sebelum jatuh tempo
        $this->kirimPengingatH1();

        // 4. Sudah Terlambat
        $this->kirimPengingatTerlambat();

        $this->info('=== PROSES SELESAI ===');
    }

    protected function kirimPengingatH3()
    {
        $this->line('');
        $this->info('🔔 Memeriksa peminjaman H-3...');

        $tanggalH3 = now()->addDays(3)->startOfDay();

        $peminjamans = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->whereDate('tanggal_kembali_rencana', $tanggalH3)
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->line('   Tidak ada peminjaman H-3.');
            return;
        }

        foreach ($peminjamans as $peminjaman) {
            if ($peminjaman->karyawan && $peminjaman->karyawan->email) {
                Notification::send($peminjaman->karyawan, new PeminjamanAkanJatuhTempo($peminjaman, 3));
                $this->line('   ✓ Email H-3 ke: ' . $peminjaman->karyawan->nama_karyawan);
                sleep(1);
            }
        }

        $this->info('   Total: ' . $peminjamans->count() . ' notifikasi H-3 terkirim');
    }

    protected function kirimPengingatH2()
    {
        $this->line('');
        $this->info('🔔 Memeriksa peminjaman H-2...');

        $tanggalH2 = now()->addDays(2)->startOfDay();

        $peminjamans = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->whereDate('tanggal_kembali_rencana', $tanggalH2)
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->line('   Tidak ada peminjaman H-2.');
            return;
        }

        foreach ($peminjamans as $peminjaman) {
            if ($peminjaman->karyawan && $peminjaman->karyawan->email) {
                Notification::send($peminjaman->karyawan, new PeminjamanAkanJatuhTempo($peminjaman, 2));
                $this->line('   ✓ Email H-2 ke: ' . $peminjaman->karyawan->nama_karyawan);
                sleep(1);
            }
        }

        $this->info('   Total: ' . $peminjamans->count() . ' notifikasi H-2 terkirim');
    }

    protected function kirimPengingatH1()
    {
        $this->line('');
        $this->info('🔔 Memeriksa peminjaman H-1...');

        $tanggalH1 = now()->addDay()->startOfDay();

        $peminjamans = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->whereDate('tanggal_kembali_rencana', $tanggalH1)
            ->get();

        if ($peminjamans->isEmpty()) {
            $this->line('   Tidak ada peminjaman H-1.');
            return;
        }

        foreach ($peminjamans as $peminjaman) {
            if ($peminjaman->karyawan && $peminjaman->karyawan->email) {
                Notification::send($peminjaman->karyawan, new PeminjamanAkanJatuhTempo($peminjaman, 1));
                $this->line('   ✓ Email H-1 ke: ' . $peminjaman->karyawan->nama_karyawan);
                sleep(1);
            }
        }

        $this->info('   Total: ' . $peminjamans->count() . ' notifikasi H-1 terkirim');
    }

    protected function kirimPengingatTerlambat()
    {
        $this->line('');
        $this->info('⚠️  Memeriksa peminjaman yang terlambat...');

        $semuaPeminjamanTerlambat = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now())
            ->get();

        if ($semuaPeminjamanTerlambat->isEmpty()) {
            $this->line('   Tidak ada peminjaman terlambat.');
            return;
        }

        // Kelompokkan peminjaman berdasarkan karyawan
        $terlambatPerKaryawan = $semuaPeminjamanTerlambat->groupBy('karyawan_id');

        foreach ($terlambatPerKaryawan as $karyawanId => $daftarPeminjaman) {
            $karyawan = $daftarPeminjaman->first()->karyawan;

            if ($karyawan && $karyawan->email) {
                Notification::send($karyawan, new PeringatanTerlambatTerkonsolidasi($daftarPeminjaman));
                $this->line('   ✓ Peringatan terlambat ke: ' . $karyawan->nama_karyawan . ' (' . $daftarPeminjaman->count() . ' item)');
                sleep(2);
            }
        }

        $this->info('   Total: ' . $semuaPeminjamanTerlambat->count() . ' item terlambat, ' . $terlambatPerKaryawan->count() . ' karyawan');
    }
}
