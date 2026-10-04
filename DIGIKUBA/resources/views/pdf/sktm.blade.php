@extends('pdf.surat-layout')

@section('document-title', 'SURAT KETERANGAN TIDAK MAMPU')

@section('opening', 'Yang bertanda tangan di bawah ini, Lurah Kuta Baru, Kecamatan Pasar Kemis, Kabupaten Tangerang, menerangkan dengan sebenarnya bahwa:')

@section('surat-content')
<div class="paragraf">
    Berdasarkan surat pernyataan yang bersangkutan serta keterangan Ketua RT dan RW setempat,
    menerangkan bahwa nama tersebut di atas adalah benar warga Kelurahan Kuta Baru, Kecamatan
    Pasar Kemis, Kabupaten Tangerang dan tergolong sebagai warga yang
    <strong>TIDAK MAMPU</strong>.
</div>

<div class="paragraf">
    Surat Keterangan ini dibuat untuk keperluan
    <strong>{{ $dataPengajuan['keperluan'] ?? $keperluan ?? 'keperluan administrasi' }}</strong>
    @php($catatanTambahan = $dataPengajuan['keterangan'] ?? $keterangan ?? '')
    @if(!empty($catatanTambahan)). {{ $catatanTambahan }}@endif.
</div>

<div class="penutup">
    Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
</div>
@endsection
