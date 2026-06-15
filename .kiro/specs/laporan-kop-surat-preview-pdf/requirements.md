# Requirements: Laporan dengan Kop Surat dan Preview PDF

## 1. Kop Surat Dinamis

### 1.1 Konfigurasi Kop Surat per Site
**Deskripsi**: Sistem harus menyimpan dan mengelola konfigurasi kop surat yang berbeda untuk setiap site/tempat penelitian.

**Acceptance Criteria**:
- Konfigurasi kop surat disimpan dalam config file (`config/letterhead.php`)
- Setiap site memiliki: nama_instansi, alamat, telp, email, dan logo_path
- Minimal mendukung 3 site (Site A, Site B, Site C) dengan konfigurasi lengkap
- Harus ada konfigurasi default (Head Office) sebagai fallback

**Priority**: High

---

### 1.2 Service untuk Mengelola Letterhead
**Deskripsi**: Sistem harus memiliki service class yang mengelola pengambilan data letterhead berdasarkan site.

**Acceptance Criteria**:
- Service class `LetterheadService` tersedia dengan method `getLetterheadBySite(string $site): array`
- Service class memiliki method `getApproverBySite(string $site): array`
- Service class memiliki method `getDefaultLetterhead(): array` untuk fallback
- Service terdaftar dalam Laravel service container
- Jika site tidak ditemukan dalam config, return default letterhead

**Priority**: High

---

### 1.3 Deteksi Site dari Data Laporan
**Deskripsi**: Sistem harus dapat menentukan site yang tepat berdasarkan data yang ada dalam laporan.

**Acceptance Criteria**:
- Untuk laporan peminjaman/pengembalian/terlambat: ambil site dari `karyawan.site` (first item)
- Untuk laporan per karyawan: ambil site langsung dari objek `Karyawan`
- Untuk laporan stok: gunakan site default (Head Office)
- Jika site tidak dapat ditentukan atau null, gunakan 'Head Office' sebagai fallback
- Method `determineSiteFromData()` tidak boleh return null atau empty string

**Priority**: High

---

### 1.4 Render Kop Surat di PDF
**Deskripsi**: Setiap PDF yang dihasilkan harus menampilkan kop surat yang sesuai dengan site di bagian header.

**Acceptance Criteria**:
- Kop surat ditampilkan di bagian atas PDF sebelum judul laporan
- Kop surat menampilkan logo instansi (jika file exists)
- Kop surat menampilkan nama instansi, alamat lengkap, nomor telepon, dan email
- Kop surat memiliki border bottom sebagai pemisah dengan konten laporan
- Style kop surat konsisten dan profesional (centered, font hierarchy jelas)

**Priority**: High

---

## 2. Bagian "Mengetahui" di Footer

### 2.1 Konfigurasi Pejabat Penandatangan
**Deskripsi**: Sistem harus menyimpan data pejabat yang mengetahui/menandatangani laporan untuk setiap site.

**Acceptance Criteria**:
- Konfigurasi approver disimpan dalam config file yang sama (`config/letterhead.php`)
- Setiap site memiliki approver dengan: nama lengkap dan jabatan
- Harus ada approver default untuk fallback

**Priority**: High

---

### 2.2 Render Bagian "Mengetahui" di PDF
**Deskripsi**: Setiap PDF harus menampilkan bagian "Mengetahui" di footer dengan nama dan jabatan pejabat yang sesuai.

**Acceptance Criteria**:
- Bagian "Mengetahui" ditampilkan di bagian bawah PDF setelah tabel data
- Teks "Mengetahui," ditampilkan
- Jabatan pejabat ditampilkan di bawah teks "Mengetahui,"
- Ada ruang kosong untuk tanda tangan (minimal 60px height)
- Nama pejabat ditampilkan dengan style bold dan underline di bawah ruang tanda tangan
- Posisi section ini di kanan (text-align: right)

**Priority**: High

---

## 3. Preview PDF (Bukan Download Langsung)

### 3.1 Ubah Behavior dari Download ke Stream
**Deskripsi**: Sistem harus mengubah semua method PDF generation dari download langsung menjadi preview di browser.

**Acceptance Criteria**:
- Semua method di `LaporanController` yang menghasilkan PDF menggunakan `->stream()` bukan `->download()`
- Method yang harus diubah: `peminjaman()`, `pengembalian()`, `terlambat()`, `stok()`, `perKaryawan()`
- PDF terbuka dalam tab browser baru (inline content disposition)
- User masih bisa download PDF melalui tombol download di PDF viewer browser
- Filename PDF tetap descriptive (contoh: 'laporan_peminjaman.pdf')

**Priority**: High

---

### 3.2 Response Type Validation
**Deskripsi**: Sistem harus memastikan response yang dikembalikan adalah StreamedResponse dengan header yang benar.

**Acceptance Criteria**:
- Response type adalah `Symfony\Component\HttpFoundation\StreamedResponse`
- Header `Content-Type` adalah `application/pdf`
- Header `Content-Disposition` mengandung `inline`, bukan `attachment`
- Browser secara otomatis membuka PDF preview (tidak trigger save dialog)

**Priority**: Medium

---

## 4. Konsistensi Seluruh Laporan

### 4.1 Modifikasi Semua Controller Methods
**Deskripsi**: Semua method laporan di `LaporanController` harus dimodifikasi untuk mendukung kop surat, approver, dan stream behavior.

**Acceptance Criteria**:
- Method `peminjaman()` diupdate dengan letterhead, approver, dan stream
- Method `pengembalian()` diupdate dengan letterhead, approver, dan stream
- Method `terlambat()` diupdate dengan letterhead, approver, dan stream
- Method `stok()` diupdate dengan letterhead, approver, dan stream
- Method `perKaryawan()` diupdate dengan letterhead, approver, dan stream
- Setiap method mengirim variable `$letterhead` dan `$approver` ke view

**Priority**: High

---

### 4.2 Update PDF Blade Template
**Deskripsi**: Template blade untuk PDF (`resources/views/laporan/pdf.blade.php`) harus diupdate untuk mendukung kop surat dan bagian mengetahui.

**Acceptance Criteria**:
- Template menerima variable `$letterhead` dan `$approver` dari controller
- Template memiliki section kop surat di bagian header
- Template memiliki section "Mengetahui" di bagian footer
- Style CSS untuk kop surat dan footer section sudah ditambahkan
- Template tetap kompatibel dengan semua jenis laporan (handling conditional rendering untuk stok vs peminjaman)

**Priority**: High

---

### 4.3 Handling Logo Files
**Deskripsi**: Sistem harus menghandle logo files untuk kop surat dengan proper error handling.

**Acceptance Criteria**:
- Logo files disimpan di `public/images/letterhead/` directory
- Template mengecek keberadaan file logo sebelum render (`file_exists()`)
- Jika logo tidak ditemukan, kop surat tetap ditampilkan tanpa logo (graceful degradation)
- Path logo menggunakan `public_path()` helper untuk dompdf compatibility
- Minimal ada 3 logo files: logo-site-a.png, logo-site-b.png, logo-site-c.png, logo-default.png

**Priority**: Medium

---

## 5. Testing & Validation

### 5.1 Unit Tests untuk LetterheadService
**Deskripsi**: Service `LetterheadService` harus memiliki unit tests yang comprehensive.

**Acceptance Criteria**:
- Test `getLetterheadBySite()` untuk site yang terdaftar
- Test `getLetterheadBySite()` untuk site yang tidak terdaftar (fallback)
- Test `getApproverBySite()` untuk site yang terdaftar
- Test `getApproverBySite()` untuk site yang tidak terdaftar (fallback)
- Test `getDefaultLetterhead()` return valid structure
- Test bahwa semua return values memiliki keys yang required

**Priority**: Medium

---

### 5.2 Feature Tests untuk PDF Generation
**Deskripsi**: Semua endpoint PDF generation harus memiliki feature tests.

**Acceptance Criteria**:
- Test setiap endpoint laporan PDF (5 endpoints)
- Test response adalah StreamedResponse
- Test response status 200
- Test Content-Disposition header contains 'inline'
- Test Content-Type adalah 'application/pdf'
- Test PDF generation tidak error (no exceptions thrown)

**Priority**: Medium

---

### 5.3 Integration Tests untuk Complete Flow
**Deskripsi**: Test end-to-end flow dari request hingga PDF dengan letterhead dan approver.

**Acceptance Criteria**:
- Test pembuatan data peminjaman dengan karyawan yang memiliki site
- Test request PDF generation untuk data tersebut
- Test PDF yang dihasilkan contains letterhead content (verify HTML content)
- Test PDF yang dihasilkan contains approver section
- Test dengan berbagai site (Site A, Site B, Site C, Unknown)

**Priority**: Low

---

## 6. Documentation

### 6.1 Config File Documentation
**Deskripsi**: Config file letterhead harus memiliki dokumentasi yang jelas.

**Acceptance Criteria**:
- Config file memiliki comments menjelaskan structure
- Ada contoh penambahan site baru
- Ada penjelasan tentang logo file path requirements
- Ada penjelasan tentang fallback mechanism

**Priority**: Low

---

### 6.2 Code Comments
**Deskripsi**: Code yang kompleks harus memiliki comments yang memadai.

**Acceptance Criteria**:
- Method `determineSiteFromData()` memiliki docblock explaining logic
- Method di LetterheadService memiliki docblock dengan @param dan @return
- Conditional logic di blade template memiliki inline comments
- Complex CSS styling memiliki section comments

**Priority**: Low

---

## 7. Non-Functional Requirements

### 7.1 Performance
**Deskripsi**: PDF generation dengan letterhead tidak boleh significantly menurunkan performance.

**Acceptance Criteria**:
- PDF generation time tidak bertambah lebih dari 20% dibanding sebelumnya
- Logo loading tidak menyebabkan timeout
- Config loading efficient (use Laravel config caching)

**Priority**: Medium

---

### 7.2 Maintainability
**Deskripsi**: Code harus mudah dimaintain dan dikembangkan untuk site baru.

**Acceptance Criteria**:
- Penambahan site baru hanya butuh update config file (no code changes)
- Service menggunakan interface untuk future flexibility
- Separation of concerns antara controller, service, dan view jelas
- No hardcoded values di controller atau view (semua di config)

**Priority**: Medium

---

### 7.3 Backward Compatibility
**Deskripsi**: Perubahan tidak boleh break existing functionality.

**Acceptance Criteria**:
- HTML view laporan (non-PDF) tetap berfungsi normal
- Existing routes tidak berubah
- Existing database schema tidak terpengaruh
- View laporan lama (jika ada PDF specific views) tetap bisa digunakan sementara

**Priority**: High

---

## Summary

Total Requirements: **21 requirements** across 7 categories

### Breakdown by Priority:
- **High Priority**: 13 requirements (critical untuk functionality inti)
- **Medium Priority**: 6 requirements (penting untuk quality dan maintainability)
- **Low Priority**: 2 requirements (nice to have, documentation)

### Implementation Order Recommendation:
1. Requirements 1.1, 1.2 (Setup config dan service)
2. Requirements 1.3, 2.1 (Data retrieval logic)
3. Requirements 4.2 (Update blade template)
4. Requirements 1.4, 2.2 (Rendering logic)
5. Requirements 3.1, 4.1 (Controller modifications)
6. Requirements 4.3 (Logo handling)
7. Requirements 5.x (Testing)
8. Requirements 6.x, 7.x (Documentation & Non-functional)
