<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Mail; // <-- Tambahkan ini di bagian atas
use App\Http\Controllers\PusatAksiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // == GRUP KHUSUS ADMIN ==
    Route::middleware('isAdmin')->group(function () {
        // --- Barang ---
        Route::get('barang/pdf', [BarangController::class, 'cetakPdf'])->name('barang.pdf');
        Route::get('barang/excel', [BarangController::class, 'cetakExcel'])->name('barang.excel');
        Route::get('barang/template', [BarangController::class, 'downloadTemplate'])->name('barang.template');
        Route::post('barang/import', [BarangController::class, 'importExcel'])->name('barang.import');
        Route::resource('barang', BarangController::class);

        // --- Karyawan ---
        Route::post('karyawan/bulk-edit', [KaryawanController::class, 'bulkEdit'])->name('karyawan.bulkEdit');
        Route::put('karyawan/bulk-update', [KaryawanController::class, 'bulkUpdate'])->name('karyawan.bulkUpdate');
        Route::delete('karyawan/bulk-delete', [KaryawanController::class, 'bulkDestroy'])->name('karyawan.bulkDestroy');
        Route::get('karyawan/template', [KaryawanController::class, 'downloadTemplate'])->name('karyawan.template');
        Route::post('karyawan/import', [KaryawanController::class, 'importExcel'])->name('karyawan.import');
        Route::resource('karyawan', KaryawanController::class);

        // --- Peminjaman ---
        // Ganti Route::post menjadi Route::patch
        Route::patch('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
        Route::resource('peminjaman', PeminjamanController::class);
        Route::get('peminjaman/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
        Route::put('peminjaman/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update');

        // --- Laporan ---
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/terlambat', [LaporanController::class, 'terlambat'])->name('laporan.terlambat');
        Route::get('laporan/terlambat/pdf', [LaporanController::class, 'terlambatPdf'])->name('laporan.terlambat.pdf');
        Route::get('laporan/terlambat/excel', [LaporanController::class, 'terlambatExcel'])->name('laporan.terlambat.excel');
        Route::get('laporan/riwayat', [LaporanController::class, 'riwayat'])->name('laporan.riwayat');
        Route::get('laporan/riwayat/pdf', [LaporanController::class, 'riwayatPdf'])->name('laporan.riwayat.pdf');
        Route::get('laporan/populer', [LaporanController::class, 'populer'])->name('laporan.populer');
        Route::get('laporan/populer/pdf', [LaporanController::class, 'populerPdf'])->name('laporan.populer.pdf');
        Route::get('laporan/per-karyawan', [LaporanController::class, 'perKaryawan'])->name('laporan.perKaryawan');
        Route::get('laporan/per-karyawan/pdf', [LaporanController::class, 'perKaryawanPdf'])->name('laporan.perKaryawan.pdf');

        // ROUTE UNTUK KIRIM PENGINGATAN EMAIL
        Route::post('laporan/kirim-pengingat-terlambat', [LaporanController::class, 'kirimPengingatTerlambat'])->name('laporan.kirimPengingat');

        // ROUTE UNTUK KIRIM PERINGATAN MANUAL
        Route::post('karyawan/{karyawan}/kirim-peringatan', [KaryawanController::class, 'kirimPeringatanManual'])->name('karyawan.kirimPeringatan');

        // --- Pusat Aksi ---
        // Route::get('pusat-aksi', [PusatAksiController::class, 'index'])->name('pusat.aksi');


        // ROUTE TAMBAHAN UNTUK MENGUJI KONEKSI EMAIL
        /*Route::get('/tes-email', function () {
            try {
                Mail::raw('Ini adalah email tes dari aplikasi Laravel Anda.', function ($message) {
                    // Alamat email ini tidak penting, Mailtrap akan menangkapnya
                    $message->to('tes@contoh.com')
                        ->subject('Tes Koneksi Email Berhasil');
                });

                return 'Berhasil mencoba mengirim email! Cek inbox Mailtrap Anda sekarang.';
            } catch (\Exception $e) {
                // Jika ada error, kita akan menampilkannya
                return 'Gagal mengirim email. Error: <pre>' . $e->getMessage() . '</pre>';
            }
        }); */
    });
});

require __DIR__ . '/auth.php';
