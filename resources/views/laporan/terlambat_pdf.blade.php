<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman Terlambat</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2,
        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>PT BUKIT MAKMUR MANDIRI UTAMA (BUMA)</h2>
    <h3>Laporan Peminjaman Terlambat</h3>
    @if($tanggalMulai && $tanggalSelesai)
    <p style="text-align: center;">Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Telat (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamanTerlambat as $peminjaman)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $peminjaman->barang->nama_barang }}</td>
                <td>{{ $peminjaman->karyawan->nama_karyawan }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y, H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->format('d M Y, H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->diffInDays(now()) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>