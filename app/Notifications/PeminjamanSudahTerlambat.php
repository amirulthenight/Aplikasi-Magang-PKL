<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PeminjamanSudahTerlambat extends Notification
{
    use Queueable;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $namaKaryawan = $this->peminjaman->karyawan->nama_karyawan;
        $namaBarang = $this->peminjaman->barang->nama_barang;
        $kodeBarang = $this->peminjaman->barang->kode_barang;
        $tanggalWajibKembali = Carbon::parse($this->peminjaman->tanggal_wajib_kembali)->format('d F Y, H:i');
        $durasiTelat = Carbon::parse($this->peminjaman->tanggal_wajib_kembali)->diffInDays(now());

        return (new MailMessage)
            ->subject('[PERINGATAN] Peminjaman Aset IT Anda Telah Melewati Batas Waktu')
            ->greeting('Halo ' . $namaKaryawan . ',')
            ->line('Menurut catatan kami, peminjaman aset di bawah ini telah melewati batas waktu pengembalian:')
            ->line('**Nama Barang:** ' . $namaBarang . ' (' . $kodeBarang . ')')
            ->line('**Seharusnya Kembali pada:** ' . $tanggalWajibKembali)
            ->line('**Status Saat Ini:** Terlambat ' . $durasiTelat . ' Hari')
            ->line('Mohon untuk **segera** mengembalikan aset tersebut ke Departemen IT hari ini untuk menghindari eskalasi lebih lanjut.')
            ->action('Lihat Dashboard', url('/dashboard'))
            ->line('Terima kasih,')
            ->salutation('Sistem Manajemen Aset IT PT BUMA');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    
}
