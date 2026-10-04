<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->perihal ?? $jenisSurat?->nama ?? 'Surat Keterangan' }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 1cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #000;
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.2;
        }

        .kop {
            width: 100%;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
            margin-bottom: 22px;
        }

        .kop-table,
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 95px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 78px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 55px;
        }

        .kop-text div {
            margin: 0;
            padding: 0;
        }

        .kabupaten {
            font-size: 16pt;
            font-weight: bold;
        }

        .kecamatan {
            font-size: 15pt;
            font-weight: bold;
        }

        .kelurahan {
            margin-top: 1px !important;
            font-size: 20pt;
            font-weight: bold;
        }

        .alamat {
            margin-top: 3px !important;
            font-size: 9.5pt;
        }

        .judul {
            text-align: center;
            margin-bottom: 2px;
        }

        .judul h1 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .nomor {
            text-align: center;
            margin-bottom: 22px;
            font-size: 11.5pt;
        }

        .paragraf {
            margin: 0 0 14px;
            text-align: justify;
            text-indent: 40px;
        }

        .identitas {
            width: 100%;
            margin: 0 0 14px 20px;
            border-collapse: collapse;
        }

        .identitas td {
            vertical-align: top;
            padding: 2px 0;
        }

        .identitas .label {
            width: 175px;
        }

        .identitas .colon {
            width: 15px;
            text-align: left;
        }

        .identitas .value {
            padding-left: 5px;
        }

        .penutup {
            margin-top: 15px;
            text-align: justify;
            text-indent: 40px;
        }

        .signature {
            width: 100%;
            margin-top: 35px;
        }

        .signature-left {
            width: 50%;
        }

        .signature-right {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-title {
            margin-bottom: 3px;
        }

        .signature-space {
            height: 85px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-nip {
            margin-top: 2px;
        }

        .qr-code {
            width: 75px;
            height: 75px;
            margin: 3px auto 5px;
        }

        .qr-code img {
            width: 75px;
            height: 75px;
        }

        .qr-description {
            font-size: 7.5pt;
            line-height: 1.1;
        }
    </style>
</head>
<body>
@php
    $pengajuan = $pengajuan ?? $surat->pengajuan;
    $masyarakat = $masyarakat ?? $pengajuan?->masyarakat;
    $jenisSurat = $jenisSurat ?? $pengajuan?->jenisSurat;

    $nama = $masyarakat?->nama_lengkap ?? $masyarakat?->nama ?? '-';
    $nik = $masyarakat?->nik ?? '-';
    $jenisKelamin = $masyarakat?->jenis_kelamin ?? '-';
    $tempatLahir = $masyarakat?->tempat_lahir ?? '-';
    $tanggalLahir = $masyarakat?->tanggal_lahir;
    $kewarganegaraan = $masyarakat?->kewarganegaraan ?? 'Indonesia';
    $statusPerkawinan = $masyarakat?->status_perkawinan ?? '-';
    $agama = $masyarakat?->agama ?? '-';
    $pekerjaan = $masyarakat?->pekerjaan_label ?? '-';
    $alamat = $masyarakat?->alamat ?? '-';

    $rawData = $dataPengajuan ?? $pengajuan?->data_pengajuan ?? [];
    if (!is_array($rawData)) {
        $dataPengajuan = [];
    } elseif (isset($rawData['data_pengajuan']) && is_array($rawData['data_pengajuan'])) {
        $dataPengajuan = $rawData['data_pengajuan'];
    } else {
        $dataPengajuan = $rawData;
    }

    $keperluan = $dataPengajuan['keperluan'] ?? 'keperluan administrasi';
    $keterangan = $dataPengajuan['keterangan'] ?? '';
    $tandaTangan = $surat->tandaTangan;
    $pegawaiPenandatangan = $tandaTangan?->pegawai;
    $namaPejabat = $pegawaiPenandatangan?->nama ?? $tandaTangan?->lurah?->name ?? 'Lurah Kuta Baru';
    $jabatanPejabat = $pegawaiPenandatangan?->jabatan ?? 'Lurah Kuta Baru';
    $nipPejabat = $pegawaiPenandatangan?->nip;
    $tanggalSurat = $surat->tanggal_surat ?? now();

    $qrBase64 = null;
    if ($tandaTangan?->qr_code) {
        $disk = \Illuminate\Support\Facades\Storage::disk('private');
        if ($disk->exists($tandaTangan->qr_code)) {
            $qrBase64 = base64_encode($disk->get($tandaTangan->qr_code));
        }
    }
@endphp

<div class="kop">
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(file_exists(public_path('images/logo-kuta-baru.png')))
                    <img src="{{ public_path('images/logo-kuta-baru.png') }}" alt="Logo Kelurahan Kuta Baru">
                @endif
            </td>
            <td class="kop-text">
                <div class="kabupaten">PEMERINTAH KABUPATEN TANGERANG</div>
                <div class="kecamatan">KECAMATAN PASAR KEMIS</div>
                <div class="kelurahan">KELURAHAN KUTA BARU</div>
                <div class="alamat">
                    Jl. Pinus II No. 1 Pondok Rejeki Kelurahan Kuta Baru,
                    Kec. Pasar Kemis, Kabupaten Tangerang 15561
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="judul">
    <h1>@yield('document-title', strtoupper($jenisSurat?->nama ?? $surat->perihal ?? 'SURAT KETERANGAN'))</h1>
</div>

<div class="nomor">Nomor : {{ $surat->nomor_surat ?? '-' }}</div>

<div class="paragraf">
    @yield('opening', 'Yang bertanda tangan di bawah ini, Lurah Kuta Baru, Kecamatan Pasar Kemis, Kabupaten Tangerang, menerangkan dengan sebenarnya bahwa:')
</div>

<table class="identitas">
    <tr><td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value">{{ $nama }}</td></tr>
    <tr><td class="label">NIK</td><td class="colon">:</td><td class="value">{{ $nik }}</td></tr>
    <tr><td class="label">Jenis Kelamin</td><td class="colon">:</td><td class="value">{{ $jenisKelamin }}</td></tr>
    <tr>
        <td class="label">Tempat / Tanggal Lahir</td>
        <td class="colon">:</td>
        <td class="value">{{ $tempatLahir }}, {{ $tanggalLahir ? \Illuminate\Support\Carbon::parse($tanggalLahir)->format('d-m-Y') : '-' }}</td>
    </tr>
    <tr><td class="label">Kewarganegaraan</td><td class="colon">:</td><td class="value">{{ $kewarganegaraan }}</td></tr>
    <tr><td class="label">Status Perkawinan</td><td class="colon">:</td><td class="value">{{ $statusPerkawinan }}</td></tr>
    <tr><td class="label">Agama</td><td class="colon">:</td><td class="value">{{ $agama }}</td></tr>
    <tr><td class="label">Pekerjaan</td><td class="colon">:</td><td class="value">{{ $pekerjaan }}</td></tr>
    <tr><td class="label">Alamat</td><td class="colon">:</td><td class="value">{{ $alamat }}</td></tr>
</table>

@yield('surat-content')

<div class="signature">
    <table class="signature-table">
        <tr>
            <td class="signature-left">&nbsp;</td>
            <td class="signature-right">
                <div class="signature-title">
                    Kuta Baru, {{ \Illuminate\Support\Carbon::parse($tanggalSurat)->translatedFormat('d F Y') }}
                </div>
                <div>{{ $jabatanPejabat }}</div>
                @if($qrBase64)
                    <div class="qr-code">
                        <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Code">
                    </div>
                @else
                    <div class="signature-space"></div>
                @endif
                <div class="signature-name">{{ strtoupper($namaPejabat) }}</div>
                @if($nipPejabat)
                    <div class="signature-nip">NIP. {{ $nipPejabat }}</div>
                @endif
            </td>
        </tr>
    </table>
</div>

</body>
</html>
