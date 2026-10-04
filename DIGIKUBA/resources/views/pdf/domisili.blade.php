@extends('pdf.surat-layout')

@section('document-title', 'SURAT KETERANGAN DOMISILI')

@section('opening', 'Yang bertanda tangan di bawah ini, Lurah Kuta Baru, Kecamatan Pasar Kemis, Kabupaten Tangerang, menerangkan dengan sebenarnya bahwa:')

@section('surat-content')
@php
    $pengajuan = $pengajuan ?? $surat->pengajuan;
    $tanggalPersetujuan = $pengajuan?->disetujui_at
        ?? $surat->tandaTangan?->signed_at
        ?? $surat->tanggal_surat;
    $tanggalBerlakuSampai = $tanggalPersetujuan
        ? \Illuminate\Support\Carbon::parse($tanggalPersetujuan)->addMonthNoOverflow()
        : null;
@endphp

<div class="paragraf">
    Berdasarkan keterangan Ketua RT/RW setempat benar nama tersebut diatas berdomisili di wilayah
    Kelurahan Kutabaru Kecamatan Pasar Kemis Kabupaten Tangerang. Surat Keterangan ini dibuat dan
    berlaku hingga tanggal <strong>{{ $tanggalBerlakuSampai?->translatedFormat('d F Y') ?? '-' }}</strong>.
</div>

<div class="penutup">
    Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
</div>
@endsection
