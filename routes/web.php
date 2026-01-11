<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD (Bisa diakses Admin & Kepala)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================================
    // AREA LAPORAN (BISA DIAKSES ADMIN & KEPALA)
    // ==========================================================
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
    Route::get('/laporan/terlambat', [LaporanController::class, 'terlambat'])->name('laporan.terlambat');
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/laporan/per-karyawan', [LaporanController::class, 'perKaryawan'])->name('laporan.perKaryawan');

    // ==========================================================
    // AREA KHUSUS ADMIN (Barang, Karyawan, Input Peminjaman)
    // ==========================================================
    Route::middleware('isAdmin')->group(function () {

        // --- BARANG ---
        Route::get('barang/pdf', [BarangController::class, 'cetakPdf'])->name('barang.pdf');
        Route::get('barang/excel', [BarangController::class, 'cetakExcel'])->name('barang.excel');
        Route::get('barang/template', [BarangController::class, 'downloadTemplate'])->name('barang.template');
        Route::post('barang/import', [BarangController::class, 'importExcel'])->name('barang.import');
        Route::resource('barang', BarangController::class);

        // --- KARYAWAN ---
        Route::post('karyawan/bulk-edit', [KaryawanController::class, 'bulkEdit'])->name('karyawan.bulkEdit');
        Route::put('karyawan/bulk-update', [KaryawanController::class, 'bulkUpdate'])->name('karyawan.bulkUpdate');
        Route::delete('karyawan/bulk-delete', [KaryawanController::class, 'bulkDestroy'])->name('karyawan.bulkDestroy');
        Route::get('karyawan/template', [KaryawanController::class, 'downloadTemplate'])->name('karyawan.template');
        Route::post('karyawan/import', [KaryawanController::class, 'importExcel'])->name('karyawan.import');
        Route::post('karyawan/{karyawan}/kirim-peringatan', [KaryawanController::class, 'kirimPeringatanManual'])->name('karyawan.kirimPeringatan');
        Route::resource('karyawan', KaryawanController::class);

        // --- PEMINJAMAN ---
        Route::get('/pengembalian-cepat', [PeminjamanController::class, 'indexPengembalianCepat'])->name('peminjaman.indexPengembalianCepat');
        Route::get('/pengembalian-cepat/cari', [PeminjamanController::class, 'cariByNik'])->name('peminjaman.cariByNik');
        Route::patch('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
        Route::resource('peminjaman', PeminjamanController::class); // Resource sudah mencakup index, create, store, edit, update, destroy

        // --- EMAIL & LAINNYA ---
        Route::post('laporan/kirim-pengingat-terlambat', [LaporanController::class, 'kirimPengingatTerlambat'])->name('laporan.kirimPengingat');
    });
});

require __DIR__ . '/auth.php';
