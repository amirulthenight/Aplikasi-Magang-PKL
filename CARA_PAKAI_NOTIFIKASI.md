# 🎯 Cara Cepat Pakai Sistem Notifikasi

## 1️⃣ Setup WhatsApp (5 Menit)

### Daftar Fonnte (GRATIS)
1. Buka: **https://fonnte.com**
2. Klik **Daftar** (dapat 100 pesan gratis/bulan)
3. Verifikasi email
4. Hubungkan nomor WhatsApp kamu dengan scan QR Code
5. Salin **Token API** dari dashboard

### Masukkan Token ke .env
Buka file `.env` lalu tambahkan:
```env
FONNTE_TOKEN=paste_token_kamu_disini
```

**Selesai!** WhatsApp siap dipakai.

---

## 2️⃣ Input Nomor WhatsApp Karyawan

1. Login sebagai Admin
2. Buka menu **Karyawan**
3. Klik **Edit** pada karyawan
4. Isi kolom **Nomor WhatsApp**: 
   - Format: `08123456789` atau `628123456789`
   - Tanpa tanda + atau spasi
5. Simpan

---

## 3️⃣ Kirim Notifikasi Manual (MUDAH!)

### Via Dashboard (Paling Mudah)
1. Login sebagai Admin
2. Klik menu **Notifikasi** di sidebar
3. Pilih kategori:
   - **H-3** = 3 hari sebelum jatuh tempo
   - **H-2** = 2 hari sebelum jatuh tempo
   - **H-1** = 1 hari sebelum jatuh tempo (urgent!)
   - **Terlambat** = Yang sudah lewat deadline
4. Pilih channel:
   - **Email Saja** = Kirim via email
   - **WhatsApp Saja** = Kirim via WA
   - **Email + WhatsApp** = Kirim keduanya
5. Klik tombol → Tunggu → Lihat hasilnya di Log

### Via Command (Terminal)
```bash
php artisan peminjaman:kirim-pengingat
```
Ini akan otomatis kirim semua notifikasi (H-3, H-2, H-1, terlambat).

---

## 4️⃣ Setup Otomatis (Opsional)

Agar notifikasi kirim otomatis setiap hari jam 8 pagi.

### Windows (Laragon)
1. Buka **Task Scheduler** Windows
2. Klik **Create Basic Task**
3. Name: "Laravel Scheduler"
4. Trigger: Daily, 00:00 (tengah malam)
5. Action: Start a program
   - Program: `C:\laragon\bin\php\php-8.x\php.exe`
   - Arguments: `C:\laragon\www\buma-it-asset\artisan schedule:run`
6. Repeat task every: 1 minute
7. Save

**ATAU** jalankan command ini setiap hari jam 8:
```bash
php C:\laragon\www\buma-it-asset\artisan peminjaman:kirim-pengingat
```

### Linux/Ubuntu
Edit crontab:
```bash
crontab -e
```

Tambahkan:
```
* * * * * cd /var/www/buma-it-asset && php artisan schedule:run >> /dev/null 2>&1
```

---

## 5️⃣ Test Kirim Notifikasi

### Test WhatsApp ke Nomor Kamu
```bash
php artisan tinker
```
Lalu ketik:
```php
$wa = new \App\Services\WhatsAppService();
$wa->kirimPesan('628123456789', 'Test dari sistem IT Asset BUMA');
```
(Ganti nomor dengan nomor WhatsApp kamu)

### Test Email
Dashboard → Notifikasi → Pilih H-3 → Email Saja → Kirim

---

## 📋 Kapan Notifikasi Terkirim?

| Waktu | Notifikasi | Isi Pesan |
|-------|-----------|-----------|
| **H-3** | 📋 Info | "Peminjaman akan jatuh tempo 3 hari lagi" |
| **H-2** | ⚠️ Peringatan | "Peminjaman akan jatuh tempo 2 hari lagi" |
| **H-1** | 🚨 Urgent | "**BESOK** harus dikembalikan!" |
| **Terlambat** | 🚨 Peringatan | "Sudah terlambat X hari, **segera kembalikan!**" |

---

## ❓ FAQ

**Q: Apakah Fonnte benar-benar gratis?**
A: Ya, gratis 100 pesan/bulan. Cukup untuk notifikasi rutin. Kalau mau unlimited bisa pakai Baileys (self-hosted, 100% gratis).

**Q: WhatsApp tidak terkirim, kenapa?**
A:
1. Cek token Fonnte di `.env` sudah benar
2. Cek saldo/quota di dashboard Fonnte
3. Pastikan nomor format 628xxx (awalan 62, bukan 0)
4. Cek log: `storage/logs/laravel.log`

**Q: Bisa kirim ke grup WhatsApp?**
A: Bisa! Pakai ID grup WhatsApp. Tapi untuk notifikasi karyawan lebih baik personal.

**Q: Email masuk spam?**
A: Normal kalau pakai Mailtrap (development). Untuk production pakai SMTP sungguhan (Gmail, SendGrid, dll).

**Q: Bisa custom isi pesan?**
A: Bisa! Edit file:
- Email: `app/Notifications/PeminjamanAkanJatuhTempo.php`
- WhatsApp: `app/Services/WhatsAppService.php` method `buatPesanPengingat()`

---

## 🎉 Selesai!

Sistem notifikasi sudah siap pakai. Tinggal:
1. Setup token Fonnte ✅
2. Input nomor WA karyawan ✅
3. Klik tombol kirim ✅

**Semua notifikasi H-3, H-2, H-1, dan terlambat otomatis terhandle!** 🚀
