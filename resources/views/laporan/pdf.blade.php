<!DOCTYPE html>
<html>

<head>
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        /* KOP SURAT */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }

        .kop-surat h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
            color: #333;
        }

        .kop-surat p {
            margin: 3px 0;
            font-size: 11px;
            color: #555;
        }

        /* JUDUL LAPORAN */
        .judul-laporan {
            text-align: center;
            margin: 30px 0 20px 0;
        }

        .judul-laporan h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* INFO TANGGAL */
        .info-tanggal {
            margin-bottom: 15px;
            font-size: 12px;
        }

        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        /* TANDA TANGAN */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-box p {
            margin: 5px 0;
            font-size: 12px;
        }

        .signature-space {
            height: 60px;
            margin: 10px 0;
        }

        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding: 0 20px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <h1>PT BUKIT MAKMUR MANDIRI UTAMA JOBSITE ADARO</h1>
        <h2>SISTEM MANAJEMEN ASET IT</h2>
        <p>Office T300, Area Operasional Tambang, Kabupaten Balangan, Kalimantan Selatan</p>
        <p>Departemen IT Support | Email: it.support.adaro@buma.com</p>
    </div>

    {{-- <div class="kop-surat" style="text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; margin-bottom: 20px;">
    <h2 style="margin: 0; font-size: 20px;">PT BUKIT MAKMUR MANDIRI UTAMA </h2>
    <h3 style="margin: 5px 0; font-size: 18px;">JOBSITE ADARO INDONESIA</h3>
    <p style="margin: 2px 0; font-size: 14px;">Office T300, Area Operasional Tambang, Kabupaten Balangan, Kalimantan Selatan</p>
    <p style="margin: 2px 0; font-size: 14px;">Departemen IT Support | Email: it.support.adaro@buma-mining.com</p>
</div> --}}

    {{-- JUDUL LAPORAN --}}
    <div class="judul-laporan">
        <h3>{{ strtoupper($judul) }}</h3>
    </div>

    {{-- INFO TANGGAL --}}
    <div class="info-tanggal">
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
    </div>

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
                <th>{{ $judul == 'Laporan Pengembalian' ? 'Tanggal Kembali' : 'Status' }}</th>
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
                <td>
                    @if($judul == 'Laporan Pengembalian')
                    {{ $row->tanggal_kembali_aktual ? \Carbon\Carbon::parse($row->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}
                    @elseif($row->status_peminjaman == 'Dipinjam')
                    @php
                    $jatuhTempo = \Carbon\Carbon::parse($row->tanggal_kembali_rencana);
                    $isTerlambat = now()->gt($jatuhTempo);
                    $hariTerlambat = now()->diffInDays($jatuhTempo);
                    @endphp
                    @if($isTerlambat)
                    {{-- Gunakan inline style karena ini PDF --}}
                    <span style="color: red; font-weight: bold;">Terlambat {{ $hariTerlambat == 0 ? '< 1' : $hariTerlambat }} Hari</span>
                    @else
                    Dipinjam
                    @endif
                    @else
                    {{ $row->status_peminjaman }}
                    @endif
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- BAGIAN TANDA TANGAN --}}
    <div class="signature-section clearfix">
        <div class="signature-box">
            <p>Mengetahui,</p>
            {{-- <p><strong>Manager IT</strong></p> --}}
            {{-- <p>____________________</p> --}}
            <div class="signature-space"></div>
            <p class="signature-name">____________________</p>
            <p style="font-size: 10px; margin-top: 3px;">NIK: ______________</p>
        </div>
    </div>
</body>

</html>
