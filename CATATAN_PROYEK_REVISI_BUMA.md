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


ADDENDUM CATATAN PROYEK REVISI BUMA
Tanggal pembaruan: 24 November 2026
Repository: https://github.com/amirulthenight/Aplikasi-Magang-PKL
Branch kerja: revisi-buma
Tujuan: Tambahan catatan untuk ditempelkan di bagian paling akhir CATATAN_PROYEK_REVISI_BUMA.md. Jangan menghapus isi catatan lama; pertahankan sebagai riwayat awal.
8. Posisi GitHub dan aturan penyimpanan
- Repository saat pemeriksaan berstatus PUBLIC.
- Branch main: ba212be9b0318143192786392e9d587d87f7b91d.
- Branch revisi-buma: 1116ae8b9d44ee4514b0bd11131ddc9deeeab80e.
- Perbandingan branch: revisi-buma 1 commit di depan main, 0 commit tertinggal.
- Commit pada revisi-buma menambahkan fitur pemeliharaan aset dan laporan pemeliharaan.
- Perubahan role dan fitur persetujuan yang dikerjakan setelah commit tersebut masih berupa perubahan lokal dan belum masuk GitHub.
- Jangan mengunggah PDF skripsi ke repository selama repository masih public. PDF memuat identitas mahasiswa dan dokumen akademik. Simpan PDF di Project/penyimpanan privat. Mengubah repository menjadi private di kemudian hari tidak menjamin salinan publik yang sudah terlanjur diakses akan hilang.
- Tidak ada commit, push, merge, atau perubahan GitHub yang dilakukan pada pembaruan catatan ini.
9. Progres revisi aplikasi dan hasil pengujian
Penguji 1
1. Form Pemeliharaan/Perbaikan Aset — implementasi sudah ada pada branch revisi-buma dan sudah diuji pada tahap pengerjaan fitur pemeliharaan.
2. Form Persetujuan Peminjaman — implementasi lokal selesai dan pengujian dua hasil keputusan berhasil:
   - Pengajuan ID 27, barang Dell Latitude 5440: status berubah dari Menunggu Persetujuan menjadi Dipinjam; stok sebelum persetujuan 6 dan setelah persetujuan 5; pengaju Admin BUMA; pemutus Kepala Divisi IT; tanggal keputusan 2026-11-24 04:25:01.
   - Pengajuan ID 28, barang Dell Latitude 5440: status Ditolak; stok sebelum dan setelah penolakan tetap 5; pengaju Admin BUMA; pemutus Kepala Divisi IT; tanggal keputusan 2026-11-24 04:49:25; alasan penolakan barang sebelumnya belum dikembalikan.
   - Route yang berhasil terdaftar: persetujuan.index, persetujuan.setujui, persetujuan.tolak.
   - Middleware IsKepalaDivisi dibuat dan alias isKepalaDivisi sudah terdaftar di app/Http/Kernel.php.
   - Tampilan desktop halaman Persetujuan Peminjaman sudah diperiksa dan dirapikan. Perbaikan tampilan mobile ditunda karena fokus penggunaan aplikasi adalah PC/laptop.
3. Laporan Pemeliharaan Aset — implementasi dan pengujian sudah dilakukan dan termasuk dalam commit fitur pemeliharaan pada branch revisi-buma.
4. Perbaikan penulisan naskah — ditunda sampai seluruh revisi aplikasi dari para penguji selesai.
Penguji 2 — Checklist kerusakan/perbaikan
- Source pada branch revisi-buma telah diperiksa. PemeliharaanController.php memiliki 9 opsi checklist kerusakan dan 7 opsi checklist perbaikan.
- Validasi mewajibkan minimal satu pilihan checklist pada masing-masing tahap.
- Pemeliharaan model menggunakan cast array untuk checklist_kerusakan dan checklist_perbaikan.
- Migration create_pemeliharaans_table menyimpan kedua checklist sebagai kolom JSON.
- View resources/views/pemeliharaan/form.blade.php menyediakan tampilan checklist kerusakan dan checklist tindakan perbaikan.
- Status: Implementasi terlihat pada source branch; verifikasi interaksi dan penyimpanan data pada aplikasi lokal masih perlu dilakukan sebelum ditandai lulus.
Penguji 3 — QR/Barcode, riwayat lokasi, dan foto kondisi
- Field lokasi_simpan pada tabel barangs telah tersedia.
- Laporan tracking_lokasi menampilkan stok gudang dan lokasi berdasarkan informasi peminjaman aktif serta data karyawan.
- Dari file yang diperiksa (Barang model/controller, Peminjaman model/controller, view tambah peminjaman/pengembalian, migration lokasi, dan view tracking lokasi), belum ditemukan bukti implementasi lengkap untuk:
  1. QR Code/Barcode yang dipakai untuk memindai aset dalam proses peminjaman/pengembalian;
  2. audit trail atau riwayat perubahan lokasi fisik aset dari waktu ke waktu;
  3. penyimpanan foto kondisi aset pada saat dipinjam dan dikembalikan.
- Status: Belum terverifikasi selesai. Audit source lokal secara menyeluruh diperlukan sebelum memutuskan file/migration/fitur apa yang benar-benar perlu ditambahkan. Jangan membuat fitur duplikat.
10. Status perubahan lokal terakhir
Pada pemeriksaan terakhir, branch aktif adalah revisi-buma. Output git status --short yang dikirim pengguna menunjukkan:
File dimodifikasi
- app/Http/Controllers/PeminjamanController.php
- app/Http/Controllers/UserController.php
- app/Http/Kernel.php
- app/Models/Peminjaman.php
- database/seeders/DatabaseSeeder.php
- resources/views/layouts/app.blade.php
- resources/views/layouts/navigation.blade.php
- resources/views/pemeliharaan/form.blade.php
- resources/views/pemeliharaan/index.blade.php
- resources/views/peminjaman/index.blade.php
- resources/views/users/create.blade.php
- resources/views/users/edit.blade.php
- routes/web.php
File/folder belum dilacak (untracked)
- app/Http/Controllers/PersetujuanPeminjamanController.php
- app/Http/Middleware/IsKepalaDivisi.php
- database/migrations/2026_11_24_013804_normalize_kepala_divisi_role_in_users_table.php
- database/migrations/2026_11_24_022707_add_approval_fields_to_peminjamans_table.php
- resources/views/laporan/pemeliharaan_pdf.blade.php
- resources/views/persetujuan/
Semua perubahan di atas harus dipertahankan. Jangan jalankan git reset, git clean, penghapusan massal, git add ., atau commit/push tanpa persetujuan eksplisit.
11. Dokumen skripsi PDF yang diterima
- PDF yang diunggah bernama Skripsi_MUHAMMAD AMIRUL EHSAN_2210020021_compressed(2).pdf, berjumlah 120 halaman.
- PDF menjadi dokumen acuan awal naskah sebelum revisi, bukan file untuk dimasukkan ke repository source code.
- Struktur utama terdiri dari Bab I Pendahuluan, Bab II Tinjauan Pustaka, Bab III Analisis dan Perancangan Sistem, Bab IV Implementasi dan Pengujian, dan Bab V Penutup.
- Catatan pada halaman tercetak 97 (halaman PDF 114) berjudul Berita Acara Seminar Proposal Skripsi, dengan masukan panelis Adan Dharmawati dan Agus Setiawan. Ini adalah catatan seminar proposal, bukan lembar revisi sidang akhir.
- Halaman tercetak 100 (halaman PDF 117) hanya menampilkan judul Lembar Revisi Berita Acara Ujian Skripsi (Penguji I, II, III) dan tidak berisi rincian komentar pada salinan PDF ini. Karena itu, rincian revisi sidang akhir harus mengacu pada catatan/pesan final penguji yang sudah dikonfirmasi, bukan menyalin masukan seminar proposal seolah-olah komentar sidang akhir.
- Satu hal yang perlu diselaraskan saat revisi naskah: Batasan Masalah Bab I pada PDF masih menyatakan pemeliharaan aset tidak dibahas, sementara aplikasi hasil revisi telah memiliki fitur pemeliharaan dan laporan pemeliharaan. Jangan ubah sampai tahap revisi naskah dimulai dan cakupan final dikonfirmasi.
- Naskah revisi nantinya dikerjakan pada DOCX privat. Riwayat setiap revisi kalimat dicatat dalam CATATAN_PENULISAN_NASKAH_BUMA.md; kedua file ini tidak diunggah ke repository public.
12. Urutan kerja berikutnya
1. Perbarui catatan proyek lokal dengan addendum ini; jangan mengubah kode saat memperbarui catatan.
2. Uji checklist kerusakan dan checklist perbaikan pada aplikasi lokal, lalu simpan bukti hasilnya.
3. Audit file lokal untuk QR/Barcode, audit trail lokasi, dan foto kondisi aset. Cocokkan dengan arahan final Penguji 3 sebelum membuat perubahan.
4. Implementasikan hanya kebutuhan Penguji 3 yang memang belum ada, kemudian uji.
5. Setelah seluruh revisi aplikasi selesai, tinjau semua perubahan lokal dan tentukan bersama apakah akan commit/push ke branch revisi-buma. Jangan merge ke main tanpa keputusan terpisah.
6. Setelah tahap aplikasi selesai, mulai revisi naskah Bab I–V dengan PDF ini sebagai pembanding dan DOCX sebagai dokumen kerja.
13. Riwayat pembaruan
- 2026-11-24: Pengujian persetujuan ID 27 lulus (stok 6 menjadi 5).
- 2026-11-24: Pengujian penolakan ID 28 lulus (stok tetap 5 dan alasan penolakan tersimpan).
- 2026-11-24: Tampilan desktop Persetujuan Peminjaman diperiksa; perbaikan mobile ditunda.
- 2026-11-24: Source checklist kerusakan/perbaikan diverifikasi secara statis; pengujian interaktif lokal masih menunggu.
- 2026-11-24: Repository terkonfirmasi masih public; PDF skripsi tidak diunggah ke GitHub.