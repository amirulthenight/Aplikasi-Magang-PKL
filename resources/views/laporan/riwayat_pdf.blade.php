<!DOCTYPE html>
<html>

<head>
    <title>Laporan Riwayat Peminjaman</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>PT BUKIT MAKMUR MANDIRI UTAMA (BUMA)</h1>
        <h2>Laporan Riwayat Peminjaman Aset IT</h2>
        @if ($tanggalMulai && $tanggalSelesai)
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}</p>
        @else
        <p>Periode: Semua Waktu</p>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Alasan Peminjam</th>
                <th>Tgl/Waktu Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Kembali Aktual</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayatPeminjaman as $peminjaman)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $peminjaman->barang->nama_barang }}</td>
                <td>{{ $peminjaman->karyawan->nama_karyawan }}</td>
                <td>{{ $peminjaman->alasan_pinjam ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}</td>
                <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                <td>
                    @if ($peminjaman->status == 'Selesai')
                    Selesai
                    @elseif ($peminjaman->is_overdue)
                    Terlambat
                    @else
                    Dipinjam
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
