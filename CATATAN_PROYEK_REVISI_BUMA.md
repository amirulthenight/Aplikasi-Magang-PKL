# CATATAN PROYEK REVISI SKRIPSI BUMA

Dokumen acuan utama untuk melanjutkan pekerjaan lintas percakapan. Perbarui setiap kali ada perubahan kode, keputusan, atau pengujian.

## 1. Identitas proyek
- Judul: Aplikasi Pengelolaan Peminjaman Aset IT pada PT. Bukit Makmur Mandiri Utama Jobsite Adaro Balangan Berbasis Web.
- Repository: https://github.com/amirulthenight/Aplikasi-Magang-PKL
- Pemilik: amirulthenight
- Branch acuan: main
- Framework: Laravel
- Tujuan percakapan: mengerjakan revisi aplikasi dari dosen terlebih dahulu, baru revisi naskah skripsi.

## 2. Aturan kerja yang wajib diikuti
1. Jangan menganggap revisi sudah dikerjakan hanya karena pernah dibahas. Status selesai hanya setelah kode diimplementasikan dan diverifikasi.
2. Sebelum memberi instruksi perubahan, baca source code terbaru dari repository pada branch acuan.
3. Periksa relasi antar route, migration, model, controller, request/validation, Blade, dan alur role/otorisasi sebelum mengubah fitur.
4. Jangan mengarang nama file, nama kolom, relasi, route, atau struktur database. Pastikan dari source.
5. Jangan menulis ulang atau mengganti file penuh sebelum membaca versi terkini dan memahami bagian lain yang terdampak.
6. Kerjakan bertahap, satu revisi/subfitur pada satu waktu. Berikan lokasi file, kode lengkap yang diperlukan, dan langkah uji.
7. Jangan melakukan commit, push, merge, atau mengubah repository tanpa permintaan/konfirmasi eksplisit pengguna.
8. Setelah perubahan, catat file yang berubah, migration yang diperlukan, perintah yang dijalankan, hasil pengujian, dan status revisi.
9. Bedakan fakta dari repository, catatan dosen, keputusan bersama, dan saran teknis.
10. Jangan campurkan proyek ini dengan proyek PLN JSO atau proyek lain.

## 3. Catatan revisi dosen
### Penguji 1 — Agus Alim Muin, S.Kom., M.Kom
- [ ] Tambahkan Form Pemeliharaan/Perbaikan Aset (rusak, perbaikan, tersedia).
- [ ] Tambahkan Form Persetujuan Peminjaman untuk mencegah peminjaman tanpa otorisasi.
- [ ] Tambahkan Laporan Pemeliharaan Aset agar memberi gambaran biaya dan riwayat perbaikan untuk perencanaan anggaran IT.
- [ ] Perbaiki penulisan naskah skripsi. Ini dikerjakan setelah revisi aplikasi, sesuai urutan kerja yang disepakati.

### Penguji 2 — Zaenuddin
- [ ] Tambahkan checklist data perbaikan/kerusakan.
- Catatan status sebelumnya menyebut “selesai”, tetapi belum diverifikasi ulang terhadap source code pada sesi ini. Jangan anggap tuntas sebelum pemeriksaan.

### Penguji 3 — Fitrah Yurdika, S.Pd., S.Kom., M.Kom
- [ ] QR Code/Barcode untuk validasi lokasi penyimpanan atau pergerakan fisik saat peminjaman/penyerahan.
- [ ] Audit trail/riwayat perpindahan lokasi fisik aset.
- [ ] Unggah foto kondisi fisik aset ketika dipinjam dan dikembalikan.
- Catatan status sebelumnya menyebut item-item ini “selesai”, tetapi belum diverifikasi ulang terhadap source code pada sesi ini. Jangan anggap tuntas sebelum pemeriksaan.

## 4. Hasil pemeriksaan repository yang sudah dilakukan
Tanggal konteks: 21 November 2026.
- Repository berhasil dibaca melalui koneksi GitHub.
- Root repository memuat struktur Laravel: app, bootstrap, config, database, public, resources, routes, storage, tests.
- app/Models yang terlihat: Barang.php, Karyawan.php, Peminjaman.php, User.php.
- app/Http/Controllers yang terlihat: BarangController.php, DashboardController.php, KaryawanController.php, LaporanController.php, NotifikasiController.php, PeminjamanController.php, ProfileController.php, UserController.php, dan folder Auth.
- database/migrations yang terlihat meliputi tabel users, barangs, karyawans, peminjamen, jobs, perubahan nomor telepon karyawan, keterangan kerusakan peminjaman (dua migration), dan lokasi simpan barang.
- resources/views memiliki folder barang, karyawan, laporan, notifikasi, peminjaman, profile, users, layouts, auth, components.
- routes/web.php telah dibaca. Terlihat route laporan peminjaman, pengembalian, kerusakan, stok, per karyawan, per departemen, pengembalian terlambat, terlambat, tracking lokasi. Route resource barang, karyawan, peminjaman dan users. Belum terlihat route khusus persetujuan peminjaman atau pemeliharaan/perbaikan aset pada pembacaan awal.
- Pemeriksaan di atas hanya inventaris awal. Belum audit seluruh file/fitur. Ketidakadaan route pada web.php bukan bukti final bahwa tidak ada implementasi di tempat lain.

## 5. Status pekerjaan saat dokumen dibuat
- Revisi aplikasi dari catatan dosen: BELUM ADA YANG DIIMPLEMENTASIKAN menurut konfirmasi pengguna.
- Audit source code menyeluruh: BELUM SELESAI.
- Implementasi revisi aplikasi: BELUM DIMULAI.
- Pengujian revisi: BELUM DIMULAI.
- Revisi naskah: ditunda sampai revisi aplikasi selesai.

## 6. Prosedur melanjutkan di percakapan baru
1. Baca dokumen ini terlebih dahulu.
2. Baca ulang repository pada branch main untuk mendapatkan kode TERBARU; jangan hanya mengandalkan ringkasan ini.
3. Tunjukkan ringkasan posisi proyek dan sebutkan apa yang sudah/belum terverifikasi.
4. Mulai dari revisi pertama Penguji 1: Form Pemeliharaan/Perbaikan Aset, setelah audit struktur data dan alur aset.
5. Setelah setiap langkah, perbarui bagian status pekerjaan dan riwayat perubahan di dokumen ini.

## 7. Riwayat perubahan
- 2026-11-21: Dokumen acuan awal dibuat. Koneksi GitHub berhasil digunakan untuk membaca metadata repository, struktur folder, dan routes/web.php. Tidak ada perubahan kode atau commit yang dilakukan.
