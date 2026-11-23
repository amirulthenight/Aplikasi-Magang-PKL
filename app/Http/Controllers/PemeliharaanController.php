<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pemeliharaan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PemeliharaanController extends Controller
{
    private const CHECKLIST_KERUSAKAN = [
        'layar' => 'Layar atau monitor',
        'keyboard' => 'Keyboard atau tombol',
        'mouse_touchpad' => 'Mouse atau touchpad',
        'baterai_power' => 'Baterai atau sumber daya',
        'port_konektor' => 'Port atau konektor',
        'bodi_fisik' => 'Bodi atau casing perangkat',
        'jaringan' => 'Jaringan atau konektivitas',
        'software' => 'Sistem operasi atau perangkat lunak',
        'lainnya' => 'Kerusakan lainnya',
    ];

    private const CHECKLIST_PERBAIKAN = [
        'pemeriksaan' => 'Pemeriksaan kondisi komponen',
        'pembersihan' => 'Pembersihan perangkat',
        'perbaikan_komponen' => 'Perbaikan komponen',
        'penggantian_komponen' => 'Penggantian komponen',
        'perbaikan_software' => 'Perbaikan perangkat lunak',
        'pengujian' => 'Pengujian fungsi perangkat',
        'lainnya' => 'Tindakan perbaikan lainnya',
    ];

    /**
     * Daftar aset yang dikembalikan dalam kondisi rusak.
     */
    public function index()
    {
        $peminjamans = Peminjaman::with([
            'barang',
            'karyawan',
            'pemeliharaan',
        ])
            ->where('status_peminjaman', 'Kembali')
            ->whereNotNull('keterangan_kerusakan')
            ->where('keterangan_kerusakan', '!=', '')
            ->orderByDesc('tanggal_kembali_aktual')
            ->paginate(10);

        return view('pemeliharaan.index', compact('peminjamans'));
    }

    /**
     * Form pencatatan awal kerusakan.
     */
    public function create(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'barang',
            'karyawan',
            'pemeliharaan',
        ]);

        abort_unless(
            $peminjaman->status_peminjaman === 'Kembali'
                && filled($peminjaman->keterangan_kerusakan),
            404
        );

        if ($peminjaman->pemeliharaan) {
            return redirect()->route(
                'pemeliharaan.edit',
                $peminjaman->pemeliharaan->id
            );
        }

        return view('pemeliharaan.form', [
            'peminjaman' => $peminjaman,
            'pemeliharaan' => null,
            'kerusakanOptions' => self::CHECKLIST_KERUSAKAN,
            'perbaikanOptions' => self::CHECKLIST_PERBAIKAN,
        ]);
    }

    /**
     * Menyimpan catatan awal kerusakan.
     */
    public function store(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'checklist_kerusakan' => [
                'required',
                'array',
                'min:1',
            ],
            'checklist_kerusakan.*' => [
                'required',
                'string',
                Rule::in(array_keys(self::CHECKLIST_KERUSAKAN)),
            ],
        ]);

        DB::transaction(function () use ($peminjaman, $validated) {
            $transaksi = Peminjaman::whereKey($peminjaman->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $transaksi->status_peminjaman !== 'Kembali'
                || blank($transaksi->keterangan_kerusakan)
            ) {
                throw ValidationException::withMessages([
                    'peminjaman' => 'Transaksi tidak memenuhi syarat pemeliharaan.',
                ]);
            }

            $sudahDicatat = Pemeliharaan::where(
                'peminjaman_id',
                $transaksi->id
            )->exists();

            if ($sudahDicatat) {
                throw ValidationException::withMessages([
                    'peminjaman' => 'Catatan pemeliharaan untuk transaksi ini sudah tersedia.',
                ]);
            }

            Pemeliharaan::create([
                'barang_id' => $transaksi->barang_id,
                'peminjaman_id' => $transaksi->id,
                'user_id' => auth()->id(),

                'keterangan_kerusakan' => $transaksi->keterangan_kerusakan,
                'checklist_kerusakan' => $validated['checklist_kerusakan'],

                'checklist_perbaikan' => null,
                'tindakan_perbaikan' => null,
                'biaya_perbaikan' => 0,

                'status' => 'Rusak',
                'tanggal_mulai_perbaikan' => null,
                'tanggal_selesai_perbaikan' => null,
            ]);
        });

        return redirect()->route('pemeliharaan.index')
            ->with('success', 'Catatan kerusakan berhasil disimpan. Status aset: Rusak.');
    }

    /**
     * Menampilkan form proses perbaikan.
     */
    public function edit(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->load([
            'barang',
            'peminjaman.karyawan',
        ]);

        $peminjaman = $pemeliharaan->peminjaman;

        abort_unless($peminjaman, 404);

        return view('pemeliharaan.form', [
            'peminjaman' => $peminjaman,
            'pemeliharaan' => $pemeliharaan,
            'kerusakanOptions' => self::CHECKLIST_KERUSAKAN,
            'perbaikanOptions' => self::CHECKLIST_PERBAIKAN,
        ]);
    }

    /**
     * Memproses perbaikan dan menyelesaikan pemeliharaan.
     */
    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        if ($pemeliharaan->status === 'Tersedia') {
            return redirect()->route('pemeliharaan.index')
                ->with('error', 'Pemeliharaan aset ini sudah selesai.');
        }

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['Perbaikan', 'Tersedia']),
            ],
            'checklist_perbaikan' => [
                'required',
                'array',
                'min:1',
            ],
            'checklist_perbaikan.*' => [
                'required',
                'string',
                Rule::in(array_keys(self::CHECKLIST_PERBAIKAN)),
            ],
            'tindakan_perbaikan' => [
                'required',
                'string',
                'max:5000',
            ],
            'biaya_perbaikan' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::transaction(function () use ($pemeliharaan, $validated) {
            // Kunci catatan supaya dua permintaan bersamaan
            // tidak menambahkan stok dua kali.
            $catatan = Pemeliharaan::whereKey($pemeliharaan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $statusLama = $catatan->status;
            $statusBaru = $validated['status'];

            $transisiDiizinkan = (
                $statusLama === 'Rusak'
                && $statusBaru === 'Perbaikan'
            ) || (
                $statusLama === 'Perbaikan'
                && in_array($statusBaru, ['Perbaikan', 'Tersedia'], true)
            );

            if (!$transisiDiizinkan) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status pemeliharaan tidak diizinkan.',
                ]);
            }

            $data = [
                'checklist_perbaikan' => $validated['checklist_perbaikan'],
                'tindakan_perbaikan' => $validated['tindakan_perbaikan'],
                'biaya_perbaikan' => $validated['biaya_perbaikan'],
                'status' => $statusBaru,
            ];

            if (
                $statusLama === 'Rusak'
                && $statusBaru === 'Perbaikan'
            ) {
                $data['tanggal_mulai_perbaikan'] = now();
            }

            if ($statusBaru === 'Tersedia') {
                $barang = Barang::whereKey($catatan->barang_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$catatan->tanggal_mulai_perbaikan) {
                    $data['tanggal_mulai_perbaikan'] = now();
                }

                $data['tanggal_selesai_perbaikan'] = now();

                // Simpan status dan penambahan stok
                // dalam satu transaksi database.
                $catatan->update($data);

                // Hanya transisi Perbaikan -> Tersedia
                // yang menambahkan stok.
                $barang->increment('stok');

                return;
            }

            $catatan->update($data);
        });

        return redirect()->route('pemeliharaan.index')
            ->with(
                'success',
                $validated['status'] === 'Tersedia'
                    ? 'Perbaikan selesai. Status aset Tersedia dan stok telah dikembalikan.'
                    : 'Proses perbaikan berhasil diperbarui.'
            );
    }
}
