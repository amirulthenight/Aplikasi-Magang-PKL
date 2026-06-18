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
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-surat-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 120px;
        }

        .kop-logo img {
            height: 55px;
            width: auto;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }

        .kop-text h2 {
            margin: 4px 0 0 0;
            font-size: 14px;
            font-weight: normal;
            color: #333;
        }

        .kop-text p {
            margin: 2px 0;
            font-size: 10px;
            color: #555;
        }

        /* JUDUL LAPORAN */
        .judul-laporan {
            text-align: center;
            margin: 24px 0 16px 0;
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
            width: 280px;
            text-align: left;
        }

        .signature-box p {
            margin: 4px 0;
            font-size: 12px;
        }

        .signature-scribble {
            min-height: 60px;
            margin: 10px 0;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 4px;
        }

        .signature-title {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    @php
        $tipe = $tipe ?? 'peminjaman';
        $tanggalTtd = \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y');
    @endphp

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <table class="kop-surat-table">
            <tr>
                <td class="kop-logo">
                    @if (file_exists(public_path('images/buma-logo.png')))
                    @php
                        $logoData = base64_encode(file_get_contents(public_path('images/buma-logo.png')));
                        $logoSrc = 'data:image/png;base64,' . $logoData;
                    @endphp
                    <img src="{{ $logoSrc }}" alt="Logo BUMA">
                    @endif
                </td>
                <td class="kop-text">
                    <h1>PT BUKIT MAKMUR MANDIRI UTAMA JOBSITE ADARO</h1>
                    <h2>SISTEM MANAJEMEN ASET IT</h2>
                    <p>Office T300, Area Operasional Tambang, Kabupaten Balangan, Kalimantan Selatan</p>
                    <p>Departemen IT Support | Email: it.support.adaro@buma.com</p>
                </td>
                <td class="kop-logo"></td>
            </tr>
        </table>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="judul-laporan">
        <h3>{{ strtoupper($judul) }}</h3>
    </div>

    {{-- INFO TANGGAL --}}
    <div class="info-tanggal">
        <p><strong>Tanggal Cetak:</strong> {{ $tanggalTtd }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                @if($tipe === 'departemen')
                <th>Departemen</th>
                <th>Total Peminjaman</th>
                <th>Sedang Dipinjam</th>
                <th>Sudah Kembali</th>
                <th>Terlambat</th>
                @elseif($tipe === 'pengembalian_terlambat')
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Departemen</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Rencana Kembali</th>
                <th>Tgl Kembali Aktual</th>
                <th>Keterlambatan</th>
                @elseif($tipe === 'kerusakan')
                <th>Barang</th>
                <th>Kode Barang</th>
                <th>Peminjam Terakhir</th>
                <th>Tgl Kembali</th>
                <th>Detail Kerusakan</th>
                <th>Status</th>
                @elseif(isset($data[0]) && isset($data[0]->stok))
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
            @forelse($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                @if($tipe === 'departemen')
                <td>{{ $row->departemen }}</td>
                <td style="text-align: center;">{{ $row->total }}</td>
                <td style="text-align: center;">{{ $row->dipinjam }}</td>
                <td style="text-align: center;">{{ $row->kembali }}</td>
                <td style="text-align: center;">{{ $row->terlambat }}</td>
                @elseif($tipe === 'pengembalian_terlambat')
                <td>{{ $row->barang->nama_barang ?? '-' }}</td>
                <td>{{ $row->karyawan->nama_karyawan ?? '-' }}</td>
                <td>{{ $row->karyawan->departemen ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($row->tanggal_kembali_rencana)->format('d-m-Y') }}</td>
                <td>{{ $row->tanggal_kembali_aktual ? \Carbon\Carbon::parse($row->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}</td>
                <td>
                    @php
                    $hariTerlambat = \Carbon\Carbon::parse($row->tanggal_kembali_rencana)->diffInDays(\Carbon\Carbon::parse($row->tanggal_kembali_aktual));
                    @endphp
                    {{ $hariTerlambat }} Hari
                </td>
                @elseif($tipe === 'kerusakan')
                <td>{{ $row->barang->nama_barang ?? '-' }}</td>
                <td>{{ $row->barang->kode_barang ?? '-' }}</td>
                <td>{{ $row->karyawan->nama_karyawan ?? '-' }}</td>
                <td>{{ $row->tanggal_kembali_aktual ? \Carbon\Carbon::parse($row->tanggal_kembali_aktual)->format('d-m-Y') : '-' }}</td>
                <td>{{ $row->keterangan_kerusakan ?? 'Ada Kerusakan' }}</td>
                <td>{{ $row->status_aset ?? 'Rusak' }}</td>
                @elseif(isset($row->stok))
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
            @empty
            <tr>
                <td colspan="10" style="text-align: center;">Tidak ada data laporan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- BAGIAN TANDA TANGAN --}}
    <div class="signature-section clearfix">
        <div class="signature-box">
            <p>Balangan, {{ $tanggalTtd }}</p>
            <p>Mengetahui,</p>
            <p class="signature-title">Manager IT</p>
            <div class="signature-scribble">
                @if (file_exists(public_path('images/signature-manager-it.png')))
                @php
                    $ttdData = base64_encode(file_get_contents(public_path('images/signature-manager-it.png')));
                    $ttdSrc = 'data:image/png;base64,' . $ttdData;
                @endphp
                <img src="{{ $ttdSrc }}" alt="Tanda Tangan" style="height: 75px; width: auto; display: block;">
                @endif
            </div>
            <p class="signature-name">Budi Santoso</p>
            <p style="font-size: 10px; margin-top: 2px;">NIK. 19850315</p>
        </div>
    </div>
</body>

</html>
