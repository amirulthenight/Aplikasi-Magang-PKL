<!DOCTYPE html>
<html>

<head>
    <title>Laporan Stok Barang</title>
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
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        /*.header {
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
        }

        .report-title {
            font-size: 18px;
        }

        .date {
            text-align: right;
        }*/
        .header-table {
            width: 100%;
            border: none;
            margin-bottom: 20px;
        }

        .header-table td {
            border: none;
            padding: 0;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
        }

        .report-title {
            font-size: 16px;
        }

        .date {
            text-align: right;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="company-name">PT BUKIT MAKMUR MANDIRI UTAMA (BUMA)</div>
                <div class="report-title">Laporan Stok Barang IT</div>
            </td>
            <td class="date">
                <div>Tanggal Cetak: {{ date('d F Y') }}</div>
            </td>
        </tr>
    </table>

    {{-- <div class="header">
        <div class="company-name">PT BUKIT MAKMUR MANDIRI UTAMA (BUMA)</div>
        <div class="report-title">Laporan Stok Barang IT</div>
        <div class="date">Tanggal Cetak: {{ date('d F Y') }}</div>
    </div> --}}

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Serial Number</th>
                <th>Site</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->serial_number ?? '-' }}</td>
                <td>{{ $barang->site ?? '-' }}</td>
                <td>{{ $barang->status }}</td>
                <td>{{ $barang->keterangan ?? '-' }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data yang ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>