<!DOCTYPE html>
<html>

<head>
    <title>Laporan Barang Paling Populer</title>
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
    <h3>Laporan Peringkat Barang Paling Sering Dipinjam (Top {{ $limit }})</h3>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Status</th>
                <th>Peminjam Terakhir</th>
                <th>Dept. Terbanyak</th>
                <th style="text-align: center;">Total Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangPopuler as $barang)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->status }}</td>
                <td>{{ $barang->peminjam_terakhir ?? '-' }}</td>
                <td>{{ $barang->departemen_populer ?? '-' }}</td>
                <td style="text-align: center;">{{ $barang->total_dipinjam }} kali</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>