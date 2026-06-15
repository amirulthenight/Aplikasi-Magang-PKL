<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PeminjamanAkanJatuhTempo extends Notification
{
    use Queueable;

    public $peminjaman;
    public $hariSebelum; // H-3, H-2, atau H-1

    /**
     * Create a new notification instance.
     */
    public function __construct(Peminjaman $peminjaman, int $hariSebelum = 1)
    {
        $this->peminjaman = $peminjaman;
        $this->hariSebelum = $hariSebelum;
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
    public function toMail(object $notifiable): MailMessage
    {
        $namaKaryawan = $this->peminjaman->karyawan->nama_karyawan;
        $namaBarang = $this->peminjaman->barang->nama_barang;
        $kodeBarang = $this->peminjaman->barang->kode_barang;
        $tanggalKembali = Carbon::parse($this->peminjaman->tanggal_kembali_rencana)->format('d F Y, H:i');

        // Sesuaikan urgency berdasarkan H berapa
        $urgency = $this->getUrgencyLevel();
        $subject = $this->getSubject();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $namaKaryawan . ',')
            ->line($message)
            ->line('**Nama Barang:** ' . $namaBarang . ' (' . $kodeBarang . ')')
            ->line('**Wajib Kembali pada:** ' . $tanggalKembali)
            ->line('**Sisa Waktu:** ' . $this->hariSebelum . ' hari lagi')
            ->line($urgency)
            ->action('Lihat Detail Peminjaman', url('/peminjaman'))
            ->line('Terima kasih atas perhatiannya.')
            ->salutation('Sistem Manajemen Aset IT PT BUMA');
    }

    protected function getSubject(): string
    {
        switch ($this->hariSebelum) {
            case 3:
                return '📋 Pengingat: Peminjaman Aset akan Jatuh Tempo dalam 3 Hari';
            case 2:
                return '⚠️ Pengingat: Peminjaman Aset akan Jatuh Tempo dalam 2 Hari';
            case 1:
                return '🚨 URGENT: Peminjaman Aset akan Jatuh Tempo BESOK!';
            default:
                return 'Pengingat Pengembalian Aset IT BUMA';
        }
    }

    protected function getMessage(): string
    {
        switch ($this->hariSebelum) {
            case 3:
                return 'Ini adalah pengingat bahwa peminjaman aset di bawah ini akan jatuh tempo dalam **3 hari**.';
            case 2:
                return 'Peminjaman aset di bawah ini akan jatuh tempo dalam **2 hari**. Mohon segera persiapkan pengembalian.';
            case 1:
                return '**PERHATIAN!** Peminjaman aset di bawah ini akan jatuh tempo **BESOK**. Harap segera kembalikan!';
            default:
                return 'Ini adalah pengingat ramah bahwa peminjaman aset di bawah ini akan segera jatuh tempo.';
        }
    }

    protected function getUrgencyLevel(): string
    {
        switch ($this->hariSebelum) {
            case 3:
                return 'Mohon untuk mempersiapkan pengembalian aset ke Departemen IT.';
            case 2:
                return '⚠️ Segera persiapkan pengembalian untuk menghindari keterlambatan.';
            case 1:
                return '🚨 **SEGERA KEMBALIKAN BESOK** untuk menghindari sanksi keterlambatan!';
            default:
                return 'Mohon kembalikan aset tepat waktu.';
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'hari_sebelum' => $this->hariSebelum,
            'barang' => $this->peminjaman->barang->nama_barang,
        ];
    }
}
