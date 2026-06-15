<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com';

    public function __construct()
    {
        // Token Fonnte dari .env
        $this->token = config('services.fonnte.token');
    }

    /**
     * Kirim pesan WhatsApp
     *
     * @param string $nomorTujuan Format: 628123456789 (tanpa +)
     * @param string $pesan
     * @return array
     */
    public function kirimPesan($nomorTujuan, $pesan)
    {
        // Validasi nomor
        $nomorTujuan = $this->formatNomor($nomorTujuan);

        if (!$this->token) {
            Log::warning('Token Fonnte tidak ditemukan di .env');
            return [
                'status' => false,
                'message' => 'Token WhatsApp tidak dikonfigurasi'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->baseUrl . '/send', [
                'target' => $nomorTujuan,
                'message' => $pesan,
                'countryCode' => '62' // Indonesia
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status']) {
                Log::info('WhatsApp terkirim ke: ' . $nomorTujuan);
                return [
                    'status' => true,
                    'message' => 'Pesan WhatsApp berhasil dikirim',
                    'data' => $result
                ];
            } else {
                Log::error('Gagal kirim WhatsApp: ' . json_encode($result));
                return [
                    'status' => false,
                    'message' => $result['reason'] ?? 'Gagal mengirim pesan',
                    'data' => $result
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error kirim WhatsApp: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kirim notifikasi pengingat H-3, H-2, H-1
     */
    public function kirimPengingatPeminjaman($peminjaman, $hariSebelum)
    {
        $karyawan = $peminjaman->karyawan;

        if (!$karyawan->no_telp) {
            return [
                'status' => false,
                'message' => 'Nomor telepon karyawan tidak tersedia'
            ];
        }

        $pesan = $this->buatPesanPengingat($peminjaman, $hariSebelum);

        return $this->kirimPesan($karyawan->no_telp, $pesan);
    }

    /**
     * Kirim notifikasi keterlambatan
     */
    public function kirimPeringatanTerlambat($peminjaman)
    {
        $karyawan = $peminjaman->karyawan;

        if (!$karyawan->no_telp) {
            return [
                'status' => false,
                'message' => 'Nomor telepon karyawan tidak tersedia'
            ];
        }

        $pesan = $this->buatPesanTerlambat($peminjaman);

        return $this->kirimPesan($karyawan->no_telp, $pesan);
    }

    /**
     * Format nomor WhatsApp
     */
    protected function formatNomor($nomor)
    {
        // Hapus semua karakter non-digit
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // Jika diawali 0, ganti dengan 62
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        // Jika belum ada 62, tambahkan
        if (substr($nomor, 0, 2) !== '62') {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }

    /**
     * Buat pesan pengingat
     */
    protected function buatPesanPengingat($peminjaman, $hariSebelum)
    {
        $emoji = ['3' => '📋', '2' => '⚠️', '1' => '🚨'];
        $urgency = ['3' => '', '2' => '⚠️ ', '1' => '🚨 URGENT! '];

        $namaBarang = $peminjaman->barang->nama_barang;
        $kodeBarang = $peminjaman->barang->kode_barang;
        $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y H:i');

        $pesan = "*{$emoji[$hariSebelum]} PENGINGAT PENGEMBALIAN ASET IT*\n\n";
        $pesan .= "Halo {$peminjaman->karyawan->nama_karyawan},\n\n";
        $pesan .= "{$urgency[$hariSebelum]}Peminjaman aset Anda akan jatuh tempo dalam *{$hariSebelum} HARI*:\n\n";
        $pesan .= "📦 *Barang:* {$namaBarang}\n";
        $pesan .= "🔖 *Kode:* {$kodeBarang}\n";
        $pesan .= "📅 *Wajib Kembali:* {$tanggalKembali}\n";
        $pesan .= "⏰ *Sisa Waktu:* {$hariSebelum} hari\n\n";

        if ($hariSebelum == 1) {
            $pesan .= "🚨 *HARAP DIKEMBALIKAN BESOK!*\n\n";
        } else {
            $pesan .= "Mohon segera persiapkan pengembalian.\n\n";
        }

        $pesan .= "Terima kasih.\n";
        $pesan .= "_Sistem Manajemen Aset IT PT BUMA_";

        return $pesan;
    }

    /**
     * Buat pesan keterlambatan
     */
    protected function buatPesanTerlambat($peminjaman)
    {
        $namaBarang = $peminjaman->barang->nama_barang;
        $kodeBarang = $peminjaman->barang->kode_barang;
        $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y H:i');
        $hariTerlambat = now()->diffInDays($peminjaman->tanggal_kembali_rencana);

        $pesan = "*🚨 PERINGATAN KETERLAMBATAN*\n\n";
        $pesan .= "Halo {$peminjaman->karyawan->nama_karyawan},\n\n";
        $pesan .= "Peminjaman aset Anda telah *MELEWATI BATAS WAKTU*:\n\n";
        $pesan .= "📦 *Barang:* {$namaBarang}\n";
        $pesan .= "🔖 *Kode:* {$kodeBarang}\n";
        $pesan .= "📅 *Seharusnya Kembali:* {$tanggalKembali}\n";
        $pesan .= "⏰ *Terlambat:* {$hariTerlambat} hari\n\n";
        $pesan .= "‼️ *HARAP SEGERA DIKEMBALIKAN HARI INI!*\n\n";
        $pesan .= "Hubungi Departemen IT untuk informasi lebih lanjut.\n\n";
        $pesan .= "_Sistem Manajemen Aset IT PT BUMA_";

        return $pesan;
    }

    /**
     * Cek saldo/status Fonnte
     */
    public function cekStatus()
    {
        if (!$this->token) {
            return [
                'status' => false,
                'message' => 'Token tidak dikonfigurasi'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->baseUrl . '/validate');

            return [
                'status' => true,
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
