<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // <-- Pastikan ini ada
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class PeringatanTerlambatTerkonsolidasi extends Notification
{
    use Queueable;

    public $daftarPeminjaman;
    public $jumlahPeminjaman;

    public function __construct(Collection $daftarPeminjaman)
    {
        $this->daftarPeminjaman = $daftarPeminjaman;
        $this->jumlahPeminjaman = $daftarPeminjaman->count();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('[PENTING] Anda Memiliki ' . $this->jumlahPeminjaman . ' Aset yang Terlambat Dikembalikan')
            ->greeting('Pemberitahuan Keterlambatan Aset untuk ' . $notifiable->nama_karyawan . ',')
            ->line('Sistem kami mencatat adanya **' . $this->jumlahPeminjaman . ' aset** atas nama Anda yang status pengembaliannya telah melewati batas waktu yang ditentukan.')
            ->line('**Rincian Aset:**');

        // Membuat daftar barang yang terlambat di dalam email
        foreach ($this->daftarPeminjaman as $peminjaman) {
            $mailMessage->line('**- ' . $peminjaman->barang->nama_barang . '** (' . $peminjaman->barang->kode_barang . ')');
            $mailMessage->line('> Status: Terlambat **' . $peminjaman->overdue_days . ' Hari**');
        }

        $mailMessage->line('Mohon untuk **segera** mengembalikan semua aset tersebut ke Departemen IT untuk menyelesaikan status keterlambatan Anda.')
            ->line('Jika Anda memiliki pertanyaan atau merasa ada kekeliruan data, silakan hubungi Departemen IT secara langsung.')
            ->line('Atas perhatian dan kerja sama Anda, kami ucapkan terima kasih.')
            ->salutation('Sistem Manajemen Aset IT PT BUKIT MAKMUR MANDIRI UTAMA JOBSITE ADT');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
