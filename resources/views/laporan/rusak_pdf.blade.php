<!DOCTYPE html>
<html>

<head>
    <title>Laporan Barang Rusak</title>
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
    <h3>Laporan Daftar Barang Rusak</h3>
    @if($site)
    <p style="text-align: center;">Lokasi Site: {{ $site }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Serial Number</th>
                <th>Site</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangRusak as $barang)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->serial_number ?? '-' }}</td>
                <td>{{ $barang->site ?? '-' }}</td>
                <td>{{ $barang->keterangan ?? '-' }}</td>
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