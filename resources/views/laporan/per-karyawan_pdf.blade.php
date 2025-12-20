<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman - {{ $karyawanDipilih->nama_karyawan }}</title>
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
        h3,
        p {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>PT BUKIT MAKMUR MANDIRI UTAMA (BUMA)</h2>
    <h3>Laporan Riwayat Peminjaman Aset</h3>
    <p>
        <strong>Nama Karyawan:</strong> {{ $karyawanDipilih->nama_karyawan }} <br>
        <strong>NIK:</strong> {{ $karyawanDipilih->nik }}
    </p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Tgl Pinjam</th>
                <th>Wajib Kembali</th>
                <th>Tgl Kembali Aktual</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayatPeminjaman as $peminjaman)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $peminjaman->barang->nama_barang }}</td>
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
                <td colspan="6" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
