@extends('pdf.surat-layout')

@section('document-title', strtoupper($jenisSurat?->nama ?? $surat->perihal ?? 'SURAT KETERANGAN'))

@section('surat-content')
<div class="paragraf">
    Berdasarkan data pengajuan surat, pemohon mengajukan
    <strong>{{ $jenisSurat?->nama ?? 'surat keterangan' }}</strong>
    @if(!empty($keperluan))
        untuk keperluan <strong>{{ $keperluan }}</strong>
    @endif
    @if(!empty($keterangan))
        . {{ $keterangan }}
    @endif
    .
</div>

<div class="penutup">
    Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
</div>
@endsection
