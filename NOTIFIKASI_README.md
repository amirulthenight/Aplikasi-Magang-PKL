# 📱 Sistem Notifikasi Pengingat Peminjaman

Sistem notifikasi lengkap untuk mengirim pengingat peminjaman aset melalui **Email** dan **WhatsApp**.

## ✨ Fitur

### 1. **Notifikasi Otomatis**
- ✅ H-3: Pengingat 3 hari sebelum jatuh tempo
- ✅ H-2: Pengingat 2 hari sebelum jatuh tempo  
- ✅ H-1: Pengingat URGENT 1 hari sebelum jatuh tempo
- ✅ Peringatan keterlambatan setelah jatuh tempo

### 2. **Multi-Channel**
- 📧 Email (via SMTP/Mailtrap)
- 💬 WhatsApp (via Fonnte - GRATIS)

### 3. **Tombol Manual di Dashboard**
- Panel admin untuk kirim notifikasi manual
- Pilih tipe: Email saja, WhatsApp saja, atau keduanya
- Log real-time hasil pengiriman

---

## 🚀 Cara Setup

### Step 1: Jalankan Migration
```bash
php artisan migrate
```

Ini akan menambahkan kolom `no_whatsapp` di tabel `karyawans`.

### Step 2: Setup WhatsApp (Fonnte - GRATIS)

#### Daftar Fonnte
1. Buka [https://fonnte.com](https://fonnte.com)
2. Daftar akun gratis (dapat 100 pesan/bulan)
3. Hubungkan nomor WhatsApp Anda
4. Salin **Token API** dari dashboard

#### Tambahkan Token ke .env
```env
FONNTE_TOKEN=token_dari_fonnte_disini
```

**Alternatif WhatsApp Gratis Lainnya:**
- **Baileys** (WhatsApp Web API) - 100% gratis unlimited, tapi perlu setup server Node.js
- **WhatsAuth** - Ada paket gratis dengan limit
- **WAHA** (WhatsApp HTTP API) - Self-hosted, gratis unlimited

### Step 3: Setup Email (Sudah ada)
Email menggunakan konfigurasi SMTP yang sudah ada di `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username_mailtrap
MAIL_PASSWORD=password_mailtrap
```

### Step 4: Input Nomor WhatsApp Karyawan
1. Buka menu **Karyawan**
2. Edit data karyawan
3. Isi kolom **Nomor WhatsApp** dengan format: `08123456789` atau `628123456789`

### Step 5: Setup Cron Job untuk Otomatis (Opsional)

**Windows (Task Scheduler):**
```
php C:\laragon\www\buma-it-asset\artisan schedule:run
```
Jalankan setiap 1 menit.

**Linux/Mac (Crontab):**
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🎮 Cara Pakai

### Notifikasi Manual via Dashboard

1. Login sebagai Admin
2. Buka menu **Notifikasi** (sidebar)
3. Pilih kategori:
   - **H-3, H-2, H-1**: Kirim pengingat berdasarkan hari
   - **Terlambat**: Kirim peringatan ke yang sudah terlambat
4. Pilih channel: Email, WhatsApp, atau Keduanya
5. Klik tombol kirim
6. Lihat hasilnya di Log Notifikasi

### Notifikasi Otomatis via Command

Jalankan manual dari terminal:
```bash
php artisan peminjaman:kirim-pengingat
```

Output:
```
=== MULAI PROSES PENGINGAT PEMINJAMAN ===

🔔 Memeriksa peminjaman H-3...
   ✓ Email H-3 ke: Ahmad Sutrisno
   ✓ Email H-3 ke: Budi Santoso
   Total: 2 notifikasi H-3 terkirim

🔔 Memeriksa peminjaman H-2...
   Tidak ada peminjaman H-2.

🔔 Memeriksa peminjaman H-1...
   ✓ Email H-1 ke: Citra Dewi
   Total: 1 notifikasi H-1 terkirim

⚠️  Memeriksa peminjaman yang terlambat...
   ✓ Peringatan terlambat ke: Dedi Kurniawan (3 item)
   Total: 3 item terlambat, 1 karyawan

=== PROSES SELESAI ===
```

---

## 📝 Format Pesan

### Email H-3
```
📋 PENGINGAT PENGEMBALIAN ASET IT

Halo Ahmad Sutrisno,

Peminjaman aset Anda akan jatuh tempo dalam 3 HARI:

📦 Barang: Laptop Dell XPS 13
🔖 Kode: LPT-001
📅 Wajib Kembali: 11/06/2026 15:00
⏰ Sisa Waktu: 3 hari

Mohon segera persiapkan pengembalian.

Terima kasih.
Sistem Manajemen Aset IT PT BUMA
```

### WhatsApp H-1
```
🚨 PENGINGAT PENGEMBALIAN ASET IT

Halo Ahmad Sutrisno,

🚨 URGENT! Peminjaman aset Anda akan jatuh tempo dalam 1 HARI:

📦 *Barang:* Laptop Dell XPS 13
🔖 *Kode:* LPT-001
📅 *Wajib Kembali:* 09/06/2026 15:00
⏰ *Sisa Waktu:* 1 hari

🚨 *HARAP DIKEMBALIKAN BESOK!*

Terima kasih.
_Sistem Manajemen Aset IT PT BUMA_
```

### Peringatan Terlambat
```
🚨 PERINGATAN KETERLAMBATAN

Halo Ahmad Sutrisno,

Peminjaman aset Anda telah *MELEWATI BATAS WAKTU*:

📦 *Barang:* Laptop Dell XPS 13
🔖 *Kode:* LPT-001
📅 *Seharusnya Kembali:* 06/06/2026 15:00
⏰ *Terlambat:* 2 hari

‼️ *HARAP SEGERA DIKEMBALIKAN HARI INI!*

Hubungi Departemen IT untuk informasi lebih lanjut.

_Sistem Manajemen Aset IT PT BUMA_
```

---

## 🔧 Testing

### Test Kirim Email
```bash
php artisan tinker
```
```php
$peminjaman = \App\Models\Peminjaman::with(['barang', 'karyawan'])->first();
$peminjaman->karyawan->notify(new \App\Notifications\PeminjamanAkanJatuhTempo($peminjaman, 1));
```

### Test Kirim WhatsApp
```bash
php artisan tinker
```
```php
$wa = new \App\Services\WhatsAppService();
$wa->kirimPesan('628123456789', 'Test pesan dari sistem');
```

### Test Cek Status Fonnte
```bash
php artisan tinker
```
```php
$wa = new \App\Services\WhatsAppService();
$wa->cekStatus();
```

---

## 📊 Monitoring

### Cek Log Notifikasi
```bash
tail -f storage/logs/laravel.log
```

### Cek Antrian (Jika pakai Queue)
```bash
php artisan queue:work
```

---

## ❓ Troubleshooting

### WhatsApp tidak terkirim
1. Cek token Fonnte di `.env`
2. Cek saldo/quota di dashboard Fonnte
3. Pastikan nomor WhatsApp valid (format 628xxx)
4. Lihat log error: `storage/logs/laravel.log`

### Email tidak terkirim
1. Cek konfigurasi SMTP di `.env`
2. Cek email karyawan sudah benar
3. Cek spam folder
4. Lihat log Mailtrap

### Command tidak jalan otomatis
1. Pastikan cron job sudah disetup
2. Test manual: `php artisan schedule:run`
3. Cek log cron: `/var/log/cron.log` (Linux)

---

## 🎯 Tips

1. **Setup WhatsApp Self-Hosted** untuk unlimited gratis
2. **Gunakan Queue** untuk pengiriman massal:
   ```php
   implements ShouldQueue
   ```
3. **Tambahkan rate limiting** untuk hindari spam
4. **Backup nomor WhatsApp** karyawan secara berkala

---

## 📞 Support

Jika ada pertanyaan atau butuh bantuan:
1. Cek dokumentasi Laravel Notifications
2. Cek dokumentasi Fonnte API
3. Hubungi tim IT

---

**Dibuat dengan ❤️ untuk PT BUMA**
