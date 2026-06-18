<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\Karyawan;
use App\Models\Barang;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil karyawan dan barang yang ada
        $karyawans = Karyawan::all();
        $barangs = Barang::all();

        if ($karyawans->isEmpty() || $barangs->isEmpty()) {
            $this->command->error('Pastikan data karyawan dan barang sudah ada!');
            return;
        }

        // 10 Data peminjaman yang bervariasi (Mixed: Aktif, Kembali, Normal, Terlambat)
        $peminjamans = [
            // 1. Aktif - TIDAK TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(2),
                'tanggal_kembali_rencana' => Carbon::now()->addDays(3),
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Untuk keperluan project lapangan',
            ],
            // 2. Aktif - TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(10),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(3), // Terlambat 3 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Testing aplikasi mobile di perangkat fisik',
            ],
            // 3. Kembali - TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(15),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(10),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(11), // Kembali 1 hari lebih awal
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Training karyawan baru',
            ],
            // 4. Kembali - TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(20),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(15),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(12), // Kembali 3 hari terlambat
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Presentasi client',
            ],
            // 5. Aktif - TIDAK TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(1),
                'tanggal_kembali_rencana' => Carbon::now()->addDays(5),
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Backup data server mingguan',
            ],
            // 6. Aktif - TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(8),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(1), // Terlambat 1 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Audit internal IT',
            ],
            // 7. Kembali - TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(12),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(7),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(7), // Tepat waktu (Hari H)
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Rapat koordinasi vendor',
            ],
            // 8. Kembali - TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(25),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(20),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(18), // Kembali 2 hari terlambat
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Instalasi jaringan site',
            ],
            // 9. Aktif - TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(14),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(5), // Terlambat 5 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Survey pemetaan lokasi tambang',
            ],
            // 10. Kembali - TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(5),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(1),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(2), // Kembali 1 hari lebih awal
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Dokumentasi kegiatan operasional',
            ],
        ];

        foreach ($peminjamans as $data) {
            Peminjaman::create($data);
        }

        $this->command->info('✅ Berhasil membuat ' . count($peminjamans) . ' data peminjaman!');
        $this->command->info('📊 Statistik:');
        $this->command->info('   - Peminjaman Aktif (Tidak Terlambat): 2 data');
        $this->command->info('   - Peminjaman Aktif (Terlambat): 3 data');
        $this->command->info('   - Pengembalian (Tepat Waktu/Normal): 3 data');
        $this->command->info('   - Pengembalian (Terlambat): 2 data');
    }
}
