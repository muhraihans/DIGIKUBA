@extends('pdf.surat-layout')

@section('document-title', 'SURAT PENGANTAR PENERBITAN SPPT-PBB')

@section('opening', 'Yang bertanda tangan di bawah ini, Lurah Kuta Baru, Kecamatan Pasar Kemis, Kabupaten Tangerang, menerangkan data objek pajak sebagai berikut:')

@section('surat-content')
<table class="identitas">
    <tr><td class="label">Nama Wajib Pajak</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['nama_wajib_pajak'] ?? '-' }}</td></tr>
    <tr><td class="label">Alamat Wajib Pajak</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['alamat_wajib_pajak'] ?? '-' }}</td></tr>
    <tr><td class="label">Bukti Hak Milik</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['bukti_hak_milik'] ?? '-' }}</td></tr>
    <tr><td class="label">Objek Pajak</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['objek_pajak'] ?? '-' }}</td></tr>
    <tr><td class="label">Alamat Objek Pajak</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['alamat_objek_pajak'] ?? '-' }}</td></tr>
    <tr><td class="label">Luas Tanah</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['luas_tanah'] ?? '-' }} m²</td></tr>
    <tr><td class="label">Luas Bangunan</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['luas_bangunan'] ?? '-' }} m²</td></tr>
    <tr><td class="label">SPPT-PBB / NOP</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['nop'] ?? '-' }}</td></tr>
</table>

<div class="paragraf"><strong>Batas-batas objek pajak:</strong></div>
<table class="identitas">
    <tr><td class="label">Sebelah Utara</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['batas_utara'] ?? '-' }}</td></tr>
    <tr><td class="label">Sebelah Timur</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['batas_timur'] ?? '-' }}</td></tr>
    <tr><td class="label">Sebelah Selatan</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['batas_selatan'] ?? '-' }}</td></tr>
    <tr><td class="label">Sebelah Barat</td><td class="colon">:</td><td class="value">{{ $dataPengajuan['batas_barat'] ?? '-' }}</td></tr>
</table>

<div class="paragraf">
    Benar objek pajak tersebut di atas SPPT-PBB-nya belum pernah diterbitkan.
</div>

<div class="paragraf">
    Surat Pengantar ini dibuat untuk memenuhi persyaratan permohonan penerbitan SPPT-PBB a/n
    <strong>{{ $dataPengajuan['nama_wajib_pajak'] ?? '-' }}</strong>.
</div>

<div class="penutup">
    Demikian Surat Pengantar ini dibuat untuk dipergunakan sebagaimana mestinya.
</div>
@endsection
