<?php

namespace App\Notifications;

use App\Models\Peminjaman; // <-- Tambahkan ini
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon; // <-- Tambahkan ini

class PeminjamanAkanJatuhTempo extends Notification
{
    use Queueable;

    // Variabel untuk menyimpan data peminjaman
    public $peminjaman;

    /**
     * Create a new notification instance.
     */
    // PERUBAHAN 1: Buat constructor untuk menerima data peminjaman
    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // PERUBAHAN 2: Bangun isi emailnya di sini
    public function toMail(object $notifiable): MailMessage
    {
        $namaKaryawan = $this->peminjaman->karyawan->nama_karyawan;
        $namaBarang = $this->peminjaman->barang->nama_barang;
        $kodeBarang = $this->peminjaman->barang->kode_barang;
        $tanggalKembali = Carbon::parse($this->peminjaman->tanggal_wajib_kembali)->format('d F Y, H:i');

        return (new MailMessage)
            ->subject('Pengingat Pengembalian Aset IT BUMA')
            ->greeting('Halo ' . $namaKaryawan . ',')
            ->line('Ini adalah pengingat ramah bahwa peminjaman aset di bawah ini akan jatuh tempo besok.')
            ->line('**Nama Barang:** ' . $namaBarang . ' (' . $kodeBarang . ')')
            ->line('**Wajib Kembali pada:** ' . $tanggalKembali)
            ->line('Mohon untuk mempersiapkan pengembalian aset ke Departemen IT tepat waktu.')
            ->action('Lihat Dashboard', url('/dashboard'))
            ->line('Terima kasih,')
            ->salutation('Sistem Manajemen Aset IT PT BUMA');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
