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

        // Data peminjaman yang bervariasi untuk semua jenis laporan
        $peminjamans = [
            // 1. Peminjaman aktif (belum dikembalikan) - TIDAK TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(3),
                'tanggal_kembali_rencana' => Carbon::now()->addDays(4), // Masih 4 hari lagi
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Untuk keperluan project development sistem inventory',
            ],
            // 2. Peminjaman aktif - TIDAK TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(1),
                'tanggal_kembali_rencana' => Carbon::now()->addDays(6),
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Backup data dan maintenance server',
            ],
            // 3. Peminjaman TERLAMBAT (masih dipinjam, sudah lewat deadline)
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(10),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(3), // Terlambat 3 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Testing aplikasi mobile di perangkat fisik',
            ],
            // 4. Peminjaman TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(15),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(5), // Terlambat 5 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Presentasi client di site Banjarmasin',
            ],
            // 5. Pengembalian TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(14),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(7),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(8), // Kembali 1 hari lebih awal
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Training karyawan baru menggunakan software SAP',
            ],
            // 6. Pengembalian TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(20),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(13),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(13), // Tepat waktu
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Rapat koordinasi dengan vendor IT',
            ],
            // 7. Pengembalian TERLAMBAT (sudah dikembalikan tapi lewat deadline)
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(30),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(23),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(20), // Kembali 3 hari terlambat
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Audit internal sistem IT dan inventory',
            ],
            // 8. Pengembalian TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(25),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(18),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(16), // Kembali 2 hari terlambat
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Instalasi software dan konfigurasi network',
            ],
            // 9. Peminjaman aktif - TIDAK TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(5),
                'tanggal_kembali_rencana' => Carbon::now()->addDays(2),
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Dokumentasi proses bisnis departemen operasional',
            ],
            // 10. Peminjaman TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(12),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(2), // Terlambat 2 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Survey dan pemetaan lokasi tambang baru',
            ],
            // 11. Pengembalian TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(18),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(11),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(12), // Kembali 1 hari lebih awal
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Pembuatan laporan keuangan triwulan',
            ],
            // 12. Pengembalian TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(28),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(21),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(19), // Kembali 2 hari terlambat
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Monitoring CCTV dan sistem keamanan site',
            ],
            // 13. Peminjaman TERLAMBAT
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(20),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(6), // Terlambat 6 hari
                'tanggal_kembali_aktual' => null,
                'status_peminjaman' => 'Dipinjam',
                'alasan_pinjam' => 'Update sistem HRIS dan payroll',
            ],
            // 14. Pengembalian TEPAT WAKTU
            [
                'karyawan_id' => $karyawans->random()->id,
                'barang_id' => $barangs->random()->id,
                'tanggal_pinjam' => Carbon::now()->subDays(22),
                'tanggal_kembali_rencana' => Carbon::now()->subDays(15),
                'tanggal_kembali_aktual' => Carbon::now()->subDays(15), // Tepat waktu
                'status_peminjaman' => 'Kembali',
                'alasan_pinjam' => 'Sosialisasi kebijakan K3 kepada seluruh staff',
            ],
        ];

        foreach ($peminjamans as $data) {
            Peminjaman::create($data);
        }

        $this->command->info('✅ Berhasil membuat ' . count($peminjamans) . ' data peminjaman!');
        $this->command->info('📊 Statistik:');
        $this->command->info('   - Peminjaman Aktif (Tidak Terlambat): 3 data');
        $this->command->info('   - Peminjaman Aktif (Terlambat): 5 data');
        $this->command->info('   - Pengembalian Tepat Waktu: 4 data');
        $this->command->info('   - Pengembalian Terlambat: 4 data');
    }
}
