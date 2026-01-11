<!DOCTYPE html>
<html>

<head>
    <title>{{ $judul }}</title>
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
            border: 1px solid black;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background-color: #eee;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>{{ $judul }}</h2>
    <p>Tanggal: {{ date('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                {{-- Header dinamis --}}
                @if(isset($data[0]->stok))
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                @else
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                @if(isset($row->stok))
                <td>{{ $row->kode_barang }}</td>
                <td>{{ $row->nama_barang }}</td>
                <td>{{ $row->stok }}</td>
                @else
                <td>{{ $row->barang->nama_barang ?? '-' }}</td>
                <td>{{ $row->karyawan->nama_karyawan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td>{{ $row->status_peminjaman }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
