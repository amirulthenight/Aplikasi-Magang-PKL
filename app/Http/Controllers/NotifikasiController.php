<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\WhatsAppService;
use App\Notifications\PeminjamanAkanJatuhTempo;
use App\Notifications\PeminjamanSudahTerlambat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Tampilkan halaman kelola notifikasi
     */
    public function index()
    {
        return view('notifikasi.index');
    }

    /**
     * Kirim notifikasi manual untuk satu peminjaman
     */
    public function kirimNotifikasiManual(Request $request, $id)
    {
        $peminjaman = Peminjaman::with(['barang', 'karyawan'])->findOrFail($id);

        $tipeNotifikasi = $request->input('tipe', 'email'); // email atau whatsapp
        $hariSebelum = $request->input('hari', 1); // H-1, H-2, H-3

        $hasil = [
            'email' => false,
            'whatsapp' => false,
            'pesan' => []
        ];

        // Kirim Email
        if ($tipeNotifikasi === 'email' || $tipeNotifikasi === 'semua') {
            if ($peminjaman->karyawan && $peminjaman->karyawan->email) {
                try {
                    Notification::send($peminjaman->karyawan, new PeminjamanAkanJatuhTempo($peminjaman, $hariSebelum));
                    $hasil['email'] = true;
                    $hasil['pesan'][] = 'Email berhasil dikirim ke ' . $peminjaman->karyawan->email;
                } catch (\Exception $e) {
                    $hasil['pesan'][] = 'Gagal kirim email: ' . $e->getMessage();
                }
            } else {
                $hasil['pesan'][] = 'Email karyawan tidak tersedia';
            }
        }

        // Kirim WhatsApp
        if ($tipeNotifikasi === 'whatsapp' || $tipeNotifikasi === 'semua') {
            if ($peminjaman->karyawan && $peminjaman->karyawan->no_telp) {
                try {
                    $response = $this->whatsapp->kirimPengingatPeminjaman($peminjaman, $hariSebelum);
                    $hasil['whatsapp'] = $response['status'];
                    $hasil['pesan'][] = $response['message'];
                } catch (\Exception $e) {
                    $hasil['pesan'][] = 'Gagal kirim WhatsApp: ' . $e->getMessage();
                }
            } else {
                $hasil['pesan'][] = 'Nomor WhatsApp karyawan tidak tersedia';
            }
        }

        return response()->json($hasil);
    }

    /**
     * Kirim notifikasi batch untuk H-3, H-2, atau H-1
     */
    public function kirimNotifikasiBatch(Request $request)
    {
        $hariSebelum = $request->input('hari', 1); // 1, 2, atau 3
        $tipeNotifikasi = $request->input('tipe', 'email'); // email, whatsapp, atau semua

        $tanggalTarget = now()->addDays($hariSebelum)->startOfDay();

        $peminjamans = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->whereDate('tanggal_kembali_rencana', $tanggalTarget)
            ->get();

        if ($peminjamans->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada peminjaman untuk H-' . $hariSebelum,
                'total' => 0
            ]);
        }

        $berhasil = 0;
        $gagal = 0;
        $detail = [];

        foreach ($peminjamans as $peminjaman) {
            $hasilItem = [
                'karyawan' => $peminjaman->karyawan->nama_karyawan,
                'barang' => $peminjaman->barang->nama_barang,
                'email' => false,
                'whatsapp' => false,
                'pesan_error' => null
            ];

            // Kirim Email
            if (in_array($tipeNotifikasi, ['email', 'semua']) && $peminjaman->karyawan->email) {
                try {
                    Notification::send($peminjaman->karyawan, new PeminjamanAkanJatuhTempo($peminjaman, $hariSebelum));
                    $hasilItem['email'] = true;
                    $berhasil++;
                } catch (\Exception $e) {
                    $gagal++;
                    $hasilItem['pesan_error'] = 'Email Error: ' . $e->getMessage();
                    Log::error("Gagal kirim Email ke {$peminjaman->karyawan->nama_karyawan}: " . $e->getMessage());
                }
            }

            // Kirim WhatsApp
            if (in_array($tipeNotifikasi, ['whatsapp', 'semua']) && $peminjaman->karyawan->no_telp) {
                try {
                    $response = $this->whatsapp->kirimPengingatPeminjaman($peminjaman, $hariSebelum);
                    $hasilItem['whatsapp'] = $response['status'];
                    if ($response['status']) {
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                } catch (\Exception $e) {
                    $gagal++;
                    $hasilItem['pesan_error'] = 'WA Error: ' . $e->getMessage();
                    Log::error("Gagal kirim WA ke {$peminjaman->karyawan->nama_karyawan}: " . $e->getMessage());
                }
            }

            $detail[] = $hasilItem;
            sleep(1); // Delay untuk menghindari spam
        }

        return response()->json([
            'status' => true,
            'message' => "Notifikasi H-{$hariSebelum} selesai dikirim",
            'total' => $peminjamans->count(),
            'berhasil' => $berhasil,
            'gagal' => $gagal,
            'detail' => $detail
        ]);
    }

    /**
     * Kirim notifikasi keterlambatan
     */
    public function kirimNotifikasiTerlambat(Request $request)
    {
        $tipeNotifikasi = $request->input('tipe', 'email');

        $peminjamans = Peminjaman::with(['barang', 'karyawan'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('tanggal_kembali_rencana', '<', now())
            ->get();

        if ($peminjamans->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada peminjaman yang terlambat',
                'total' => 0
            ]);
        }

        $berhasil = 0;
        $gagal = 0;
        $detail = [];

        foreach ($peminjamans as $peminjaman) {
            $hasilItem = [
                'karyawan' => $peminjaman->karyawan->nama_karyawan,
                'barang' => $peminjaman->barang->nama_barang,
                'email' => false,
                'whatsapp' => false,
                'pesan_error' => null
            ];

            // Kirim Email
            if (in_array($tipeNotifikasi, ['email', 'semua']) && $peminjaman->karyawan->email) {
                try {
                    Notification::send($peminjaman->karyawan, new PeminjamanSudahTerlambat($peminjaman));
                    $hasilItem['email'] = true;
                    $berhasil++;
                } catch (\Exception $e) {
                    $gagal++;
                    $hasilItem['pesan_error'] = 'Email Error: ' . $e->getMessage();
                    Log::error("Gagal kirim Email (Terlambat) ke {$peminjaman->karyawan->nama_karyawan}: " . $e->getMessage());
                }
            }

            // Kirim WhatsApp
            if (in_array($tipeNotifikasi, ['whatsapp', 'semua']) && $peminjaman->karyawan->no_telp) {
                try {
                    $response = $this->whatsapp->kirimPeringatanTerlambat($peminjaman);
                    $hasilItem['whatsapp'] = $response['status'];
                    if ($response['status']) {
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                } catch (\Exception $e) {
                    $gagal++;
                    $hasilItem['pesan_error'] = 'WA Error: ' . $e->getMessage();
                    Log::error("Gagal kirim WA (Terlambat) ke {$peminjaman->karyawan->nama_karyawan}: " . $e->getMessage());
                }
            }

            $detail[] = $hasilItem;
            sleep(1);
        }

        return response()->json([
            'status' => true,
            'message' => "Notifikasi keterlambatan selesai dikirim",
            'total' => $peminjamans->count(),
            'berhasil' => $berhasil,
            'gagal' => $gagal,
            'detail' => $detail
        ]);
    }

    /**
     * Cek status WhatsApp Service
     */
    public function cekStatusWhatsApp()
    {
        $status = $this->whatsapp->cekStatus();
        return response()->json($status);
    }
}
