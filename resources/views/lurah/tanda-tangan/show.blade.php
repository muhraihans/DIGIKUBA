{{-- =========================================================
    LURAH - DETAIL TANDA TANGAN SURAT
    Tampilan dibuat mengikuti halaman Staff Verifikasi Pengajuan
========================================================= --}}

@extends('layouts.admin')

@section('title', 'Detail Tanda Tangan Surat')

@section('content')

@php

/*
|--------------------------------------------------------------------------
| DATA DASAR
|--------------------------------------------------------------------------
*/

$pengajuan = $surat->pengajuan;
$masyarakat = $pengajuan?->masyarakat;
$jenisSurat = $pengajuan?->jenisSurat;


/*
|--------------------------------------------------------------------------
| STATUS SURAT
|--------------------------------------------------------------------------
*/

$statusLabel = match ($surat->status) {

'menunggu_tanda_tangan'
=> 'Menunggu Tanda Tangan Lurah',

'ditandatangani'
=> 'Sudah Ditandatangani',

'selesai'
=> 'Selesai',

default
=> ucwords(
str_replace(
'_',
' ',
(string) $surat->status
)
),

};


$statusClass = match ($surat->status) {

'menunggu_tanda_tangan'
=> 'bg-warning text-dark',

'ditandatangani'
=> 'bg-success',

'selesai'
=> 'bg-success',

default
=> 'bg-secondary',

};


$statusIcon = match ($surat->status) {

'menunggu_tanda_tangan'
=> 'bi-hourglass-split',

'ditandatangani'
=> 'bi-patch-check-fill',

'selesai'
=> 'bi-check-circle-fill',

default
=> 'bi-file-earmark-text',

};


/*
|--------------------------------------------------------------------------
| STATUS PENGAJUAN
|--------------------------------------------------------------------------
*/

$pengajuanStatusLabel = match ($pengajuan?->status) {

'pending'
=> 'Menunggu Verifikasi',

'diproses'
=> 'Sedang Diproses',

'perlu_perbaikan'
=> 'Perlu Perbaikan',

'diverifikasi'
=> 'Terverifikasi',

'menunggu_tanda_tangan'
=> 'Menunggu Tanda Tangan Lurah',

'disetujui'
=> 'Disetujui',

'ditolak'
=> 'Ditolak',

'selesai'
=> 'Selesai',

default
=> ucwords(
str_replace(
'_',
' ',
(string) $pengajuan?->status
)
),

};


/*
|--------------------------------------------------------------------------
| DATA PENGAJUAN
|--------------------------------------------------------------------------
*/

$rawData = $pengajuan?->data_pengajuan;


if (!is_array($rawData)) {

$dataPengajuan = [];

} elseif (
isset($rawData['data_pengajuan']) &&
is_array($rawData['data_pengajuan'])
) {

$dataPengajuan =
$rawData['data_pengajuan'];

} else {

$dataPengajuan =
$rawData;

}


/*
|--------------------------------------------------------------------------
| HELPER FORMAT DATA
|--------------------------------------------------------------------------
*/

$formatValue = function ($value) {

if (is_array($value)) {

return implode(
', ',
array_map(
fn ($item) =>
is_scalar($item)
? (string) $item
: '',
$value
)
);

}

if (is_bool($value)) {
return $value ? 'Ya' : 'Tidak';
}

return $value ?? '-';
};


@endphp


<div class="pagetitle">

    <h1>
        Detail Tanda Tangan Surat
    </h1>

    <nav>

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('lurah.dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('lurah.tanda-tangan.index') }}">
                    Tanda Tangan Digital
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail Surat
            </li>

        </ol>

    </nav>

</div>


<section class="section">

    <div class="row">


        {{-- =====================================================
            KOLOM KIRI
        ====================================================== --}}

        <div class="col-lg-8">


            {{-- =================================================
                INFORMASI SURAT
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-4">

                        <div>

                            <h5 class="card-title mb-1">
                                Informasi Surat
                            </h5>

                            <p class="text-muted small mb-0">
                                Detail surat yang telah diverifikasi oleh Staff.
                            </p>

                        </div>


                        <span
                            class="badge {{ $statusClass }} px-3 py-2">

                            <i class="bi {{ $statusIcon }} me-1"></i>

                            {{ $statusLabel }}

                        </span>

                    </div>


                    <div class="row g-3">

                        {{-- Nomor Pengajuan --}}

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-hash"></i>
                                </div>

                                <div>

                                    <small>
                                        Nomor Pengajuan
                                    </small>

                                    <strong>
                                        {{ $pengajuan?->nomor_pengajuan ?? '-' }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- Nomor Surat --}}

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>

                                <div>

                                    <small>
                                        Nomor Surat
                                    </small>

                                    <strong>
                                        {{ $surat->nomor_surat ?? '-' }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- Jenis Surat --}}

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>

                                <div>

                                    <small>
                                        Jenis Surat
                                    </small>

                                    <strong>
                                        {{ $jenisSurat?->nama ?? '-' }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- Tanggal Surat --}}

                        <div class="col-md-6">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-calendar3"></i>
                                </div>

                                <div>

                                    <small>
                                        Tanggal Surat
                                    </small>

                                    <strong>

                                        @if($surat->tanggal_surat)

                                        {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}

                                        @else

                                        -

                                        @endif

                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- Perihal --}}

                        <div class="col-12">

                            <div class="info-box">

                                <div class="info-icon">
                                    <i class="bi bi-chat-left-text"></i>
                                </div>

                                <div>

                                    <small>
                                        Perihal
                                    </small>

                                    <strong>
                                        {{ $surat->perihal ?? '-' }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                DATA PEMOHON
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        Data Pemohon
                    </h5>

                    <div class="row g-3">

                        {{-- Nama --}}

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Nama Lengkap
                            </label>

                            <div class="detail-value">
                                {{ $masyarakat?->nama_lengkap ?? '-' }}
                            </div>

                        </div>


                        {{-- NIK --}}

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                NIK
                            </label>

                            <div class="detail-value">
                                {{ $masyarakat?->nik ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label text-muted">Jenis Kelamin</label>

                            <div class="detail-value">
                                {{ $masyarakat?->jenis_kelamin ?? '-' }}
                            </div>

                        </div>


                        {{-- Tempat Lahir --}}

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Tempat Lahir
                            </label>

                            <div class="detail-value">
                                {{ $masyarakat?->tempat_lahir ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label text-muted">Kewarganegaraan</label>

                            <div class="detail-value">
                                {{ $masyarakat?->kewarganegaraan ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label text-muted">Status Perkawinan</label>

                            <div class="detail-value">
                                {{ $masyarakat?->status_perkawinan ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label text-muted">Agama</label>

                            <div class="detail-value">
                                {{ $masyarakat?->agama ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label text-muted">Pekerjaan</label>

                            <div class="detail-value">
                                {{ $masyarakat?->pekerjaan_label ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label text-muted">Email</label>

                            <div class="detail-value">
                                {{ $masyarakat?->user?->email ?? '-' }}
                            </div>

                        </div>


                        {{-- Tanggal Lahir --}}

                        <div class="col-md-6">

                            <label class="form-label text-muted">
                                Tanggal Lahir
                            </label>

                            <div class="detail-value">

                                @if($masyarakat?->tanggal_lahir)

                                {{ \Carbon\Carbon::parse($masyarakat->tanggal_lahir)->translatedFormat('d F Y') }}

                                @else

                                -

                                @endif

                            </div>

                        </div>


                        {{-- Alamat --}}

                        <div class="col-12">

                            <label class="form-label text-muted">
                                Alamat
                            </label>

                            <div class="detail-value">
                                {{ $masyarakat?->alamat ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- DOKUMEN PERSYARATAN --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-file-earmark-check me-2"></i>Dokumen Persyaratan</h5>
                    <div class="row g-3">
                        @foreach([
                            ['label' => 'KTP', 'type' => 'ktp', 'icon' => 'bi-person-vcard', 'path' => $masyarakat?->foto_ktp],
                            ['label' => 'Kartu Keluarga', 'type' => 'kk', 'icon' => 'bi-people', 'path' => $pengajuan?->file_kk],
                            ['label' => 'Pengantar RT/RW', 'type' => 'pengantar_rt_rw', 'icon' => 'bi-file-earmark-text', 'path' => $pengajuan?->file_pengantar_rt_rw],
                        ] as $document)
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100 d-flex flex-column align-items-start">
                                    <h6><i class="bi {{ $document['icon'] }} me-1"></i>{{ $document['label'] }}</h6>
                                    @if($document['path'])
                                        @php
                                            $documentExtension = strtolower(pathinfo($document['path'], PATHINFO_EXTENSION));
                                        @endphp
                                        <span class="badge bg-success mb-3">Tersedia</span>
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary btn-sm mt-auto"
                                            data-bs-toggle="modal"
                                            data-bs-target="#lurahDocumentPreviewModal"
                                            data-document-url="{{ route('pengajuan.dokumen', ['pengajuan' => $pengajuan, 'jenis' => $document['type']]) }}"
                                            data-document-type="{{ $documentExtension === 'pdf' ? 'pdf' : 'image' }}"
                                            data-document-title="{{ $document['label'] }}">
                                            <i class="bi bi-eye me-1"></i>Lihat Dokumen
                                        </button>
                                    @else
                                        <span class="badge bg-secondary">Belum tersedia</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- =================================================
                DETAIL PENGAJUAN
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        Detail Pengajuan
                    </h5>


                    @if(count($dataPengajuan) > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <tbody>

                                @foreach($dataPengajuan as $key => $value)

                                <tr>

                                    <th
                                        style="width: 35%;"
                                        class="bg-light">

                                        {{ ucwords(
                                                    str_replace(
                                                        ['_', '-'],
                                                        ' ',
                                                        $key
                                                    )
                                                ) }}

                                    </th>

                                    <td>

                                        {{ $formatValue($value) }}

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    @else

                    <div class="alert alert-light border mb-0">

                        <i class="bi bi-info-circle me-1"></i>

                        Tidak terdapat data tambahan pada pengajuan surat.

                    </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                INFORMASI VERIFIKASI STAFF
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        Informasi Verifikasi
                    </h5>

                    <div class="timeline">


                        <div class="timeline-item">

                            <div class="timeline-icon bg-success">

                                <i class="bi bi-check-lg"></i>

                            </div>

                            <div class="timeline-content">

                                <h6>
                                    Pengajuan Diverifikasi Staff
                                </h6>

                                <p class="mb-1 text-muted">

                                    Pengajuan telah diperiksa dan diteruskan
                                    ke Lurah untuk proses tanda tangan.

                                </p>

                                @if($pengajuan?->diperiksa_at)

                                <small class="text-muted">

                                    <i class="bi bi-clock me-1"></i>

                                    {{ \Carbon\Carbon::parse($pengajuan->diperiksa_at)->translatedFormat('d F Y, H:i') }}
                                    WIB

                                </small>

                                @endif

                            </div>

                        </div>


                        <div class="timeline-item">

                            <div
                                class="timeline-icon
                                {{ $surat->status === 'ditandatangani' || $surat->status === 'selesai'
                                    ? 'bg-success'
                                    : 'bg-warning text-dark' }}">

                                <i class="bi bi-pen-fill"></i>

                            </div>

                            <div class="timeline-content">

                                <h6>
                                    Tanda Tangan Lurah
                                </h6>

                                @if(
                                $surat->status === 'ditandatangani' ||
                                $surat->status === 'selesai'
                                )

                                <p class="mb-1 text-success">

                                    Surat telah ditandatangani
                                    secara digital.

                                </p>

                                @else

                                <p class="mb-1 text-muted">

                                    Surat menunggu tanda tangan
                                    digital dari Lurah.

                                </p>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                QR VERIFIKASI
            ================================================== --}}

            @if(
            $surat->tandaTangan &&
            $surat->tandaTangan->qr_code
            )

            <div class="card mb-4">

                <div class="card-body text-center">

                    <h5 class="card-title">
                        QR Code Verifikasi Surat
                    </h5>

                    <p class="text-muted small">

                        QR Code dapat dipindai oleh masyarakat
                        untuk memverifikasi keaslian surat.

                    </p>


                    @php

                    $qrDisk =
                    \Illuminate\Support\Facades\Storage::disk('private');

                    $qrPath =
                    $surat->tandaTangan->qr_code;

                    $qrBase64 = null;


                    if ($qrDisk->exists($qrPath)) {

                    $qrContent =
                    $qrDisk->get($qrPath);

                    $extension =
                    strtolower(
                    pathinfo(
                    $qrPath,
                    PATHINFO_EXTENSION
                    )
                    );


                    if ($extension === 'svg') {

                    $qrBase64 =
                    'data:image/svg+xml;base64,' .
                    base64_encode($qrContent);

                    } else {

                    $mime =
                    $extension === 'jpg' ||
                    $extension === 'jpeg'
                    ? 'image/jpeg'
                    : 'image/png';

                    $qrBase64 =
                    'data:' .
                    $mime .
                    ';base64,' .
                    base64_encode($qrContent);
                    }

                    }

                    @endphp


                    @if($qrBase64)

                    <img
                        src="{{ $qrBase64 }}"
                        alt="QR Code Verifikasi"
                        style="
                                    width: 180px;
                                    height: 180px;
                                    object-fit: contain;
                                ">

                    <div class="mt-2">

                        <small class="text-muted">
                            Scan QR Code untuk melihat
                            informasi verifikasi surat.
                        </small>

                    </div>

                    @else

                    <div class="alert alert-warning">

                        QR Code tidak ditemukan.

                    </div>

                    @endif

                </div>

            </div>

            @endif

        </div>



        {{-- =====================================================
            KOLOM KANAN
        ====================================================== --}}

        <div class="col-lg-4">


            {{-- =================================================
                AKSI TANDA TANGAN
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        Tindakan
                    </h5>


                    @if($surat->status === 'menunggu_tanda_tangan')

                    <div class="alert alert-warning">

                        <div class="d-flex">

                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>

                            <div>

                                <strong>
                                    Menunggu Tanda Tangan
                                </strong>

                                <div class="small mt-1">

                                    Pastikan seluruh informasi
                                    surat telah diperiksa sebelum
                                    melakukan tanda tangan digital.

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">
                        Penandatangan Surat
                    </h5>


                    <div class="mb-3">

                        <label class="form-label">
                            Pilih Pejabat Penandatangan
                        </label>

                        <select
                            name="pegawai_id"
                            class="form-select"
                            required
                            form="form-tanda-tangan">

                            <option value="">
                                -- Pilih Penandatangan --
                            </option>

                            @foreach($pegawai as $item)

                            @if($item->status)

                            <option
                                value="{{ $item->id }}">
                                {{ $item->nama }}
                                -
                                {{ $item->jabatan }}

                                @if($item->nip)
                                - NIP. {{ $item->nip }}
                                @endif

                            </option>

                            @endif

                            @endforeach

                        </select>

                    </div>

                    <form
                        id="form-tanda-tangan"
                        action="{{ route(
        'lurah.tanda-tangan.sign',
        $surat
    ) }}"
                        method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary">
                            <i class="bi bi-pen"></i>
                            Tanda Tangani Surat
                        </button>

                    </form>


                    @elseif(
                    $surat->status === 'ditandatangani' ||
                    $surat->status === 'selesai'
                    )

                    <div class="alert alert-success">

                        <i class="bi bi-check-circle-fill me-1"></i>

                        Surat telah ditandatangani secara digital.

                    </div>


                    @if($surat->tandaTangan?->signed_at)

                    <div class="small text-muted mb-3">

                        Ditandatangani pada:

                        <strong>

                            {{ \Carbon\Carbon::parse(
                                        $surat->tandaTangan->signed_at
                                    )->translatedFormat('d F Y, H:i') }}

                            WIB

                        </strong>

                    </div>

                    @endif

                    <a href="{{ route('surat.pengajuan.download', $pengajuan) }}" class="btn btn-success w-100">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </a>

                    @else

                    <div class="alert alert-secondary">

                        Status surat:

                        <strong>
                            {{ $statusLabel }}
                        </strong>

                    </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                RINGKASAN PEMOHON
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        Ringkasan Pemohon
                    </h5>


                    <div class="text-center mb-3">

                        <div
                            class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                            style="
                                width: 70px;
                                height: 70px;
                            ">

                            <i
                                class="bi bi-person-fill text-primary"
                                style="font-size: 32px;"></i>

                        </div>

                    </div>


                    <div class="text-center">

                        <h6 class="mb-1">

                            {{ $masyarakat?->nama_lengkap ?? '-' }}

                        </h6>

                        <small class="text-muted">

                            NIK:
                            {{ $masyarakat?->nik ?? '-' }}

                        </small>

                    </div>


                    <hr>


                    <div class="small">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Jenis Surat
                            </span>

                            <strong class="text-end">

                                {{ $jenisSurat?->nama ?? '-' }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Status Pengajuan
                            </span>

                            <span class="badge bg-info text-dark">

                                {{ $pengajuanStatusLabel }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Nomor Surat
                            </span>

                            <strong class="text-end">

                                {{ $surat->nomor_surat ?? '-' }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                INFORMASI TANDA TANGAN
            ================================================== --}}

            @if($surat->tandaTangan)

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        Informasi Tanda Tangan
                    </h5>


                    <div class="small">

                        <div class="mb-3">

                            <span class="text-muted d-block">
                                Ditandatangani Oleh
                            </span>

                            <strong>

                                {{ $surat->tandaTangan->lurah?->name ?? '-' }}

                            </strong>

                        </div>


                        <div>

                            <span class="text-muted d-block">
                                Waktu Tanda Tangan
                            </span>

                            <strong>

                                @if($surat->tandaTangan->signed_at)

                                {{ \Carbon\Carbon::parse(
                                            $surat->tandaTangan->signed_at
                                        )->translatedFormat('d F Y, H:i') }}

                                WIB

                                @else

                                -

                                @endif

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

            @endif



            {{-- =================================================
                KEMBALI
            ================================================== --}}

            <a
                href="{{ route('lurah.tanda-tangan.index') }}"
                class="btn btn-light border w-100">

                <i class="bi bi-arrow-left me-1"></i>

                Kembali ke Daftar Surat

            </a>


        </div>

    </div>

</section>

<div class="modal fade" id="lurahDocumentPreviewModal" tabindex="-1" aria-labelledby="lurahDocumentPreviewTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lurahDocumentPreviewTitle">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body bg-light text-center">
                <img id="lurahDocumentPreviewImage" class="img-fluid d-none mx-auto" style="max-height:72vh;object-fit:contain" alt="Pratinjau dokumen">
                <iframe id="lurahDocumentPreviewPdf" class="w-100 d-none border rounded bg-white" style="height:72vh" title="Pratinjau dokumen PDF"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('lurahDocumentPreviewModal');
    if (!modal) return;

    const title = document.getElementById('lurahDocumentPreviewTitle');
    const image = document.getElementById('lurahDocumentPreviewImage');
    const pdf = document.getElementById('lurahDocumentPreviewPdf');

    modal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        if (!trigger) return;

        const url = trigger.getAttribute('data-document-url');
        title.textContent = 'Pratinjau ' + (trigger.getAttribute('data-document-title') || 'Dokumen');

        if (trigger.getAttribute('data-document-type') === 'pdf') {
            image.classList.add('d-none');
            image.removeAttribute('src');
            pdf.classList.remove('d-none');
            pdf.src = url;
        } else {
            pdf.classList.add('d-none');
            pdf.removeAttribute('src');
            image.classList.remove('d-none');
            image.src = url;
        }
    });

    modal.addEventListener('hidden.bs.modal', function () {
        image.removeAttribute('src');
        pdf.removeAttribute('src');
        image.classList.add('d-none');
        pdf.classList.add('d-none');
    });
});
</script>


{{-- =========================================================
    SWEETALERT
========================================================= --}}

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const button =
                document.getElementById(
                    'btnTandaTangan'
                );

            const form =
                document.getElementById(
                    'formTandaTangan'
                );


            if (!button || !form) {
                return;
            }


            button.addEventListener(
                'click',
                function() {

                    Swal.fire({

                        title: 'Tanda Tangani Surat?',

                        html: `
                        <p class="mb-2">
                            Anda akan menandatangani surat:
                        </p>

                        <strong>
                            {{ $surat->nomor_surat }}
                        </strong>

                        <p class="mt-3 mb-0 text-muted small">
                            Setelah ditandatangani, surat akan
                            memiliki QR Code verifikasi publik
                            dan dapat diakses oleh masyarakat.
                        </p>
                    `,

                        icon: 'question',

                        showCancelButton: true,

                        confirmButtonText: '<i class="bi bi-pen-fill me-1"></i> Ya, Tanda Tangani',

                        cancelButtonText: 'Batal',

                        confirmButtonColor: '#4154f1',

                        cancelButtonColor: '#6c757d',

                        reverseButtons: true,

                    }).then(
                        function(result) {

                            if (result.isConfirmed) {

                                Swal.fire({

                                    title: 'Memproses Tanda Tangan...',

                                    text: 'Mohon tunggu sebentar.',

                                    allowOutsideClick: false,

                                    allowEscapeKey: false,

                                    didOpen: function() {

                                        Swal.showLoading();

                                    }

                                });


                                form.submit();

                            }

                        }
                    );

                }
            );

        }
    );
</script>


{{-- =========================================================
    TAMBAHAN STYLE
========================================================= --}}

<style>
    /*
    |--------------------------------------------------------------------------
    | INFO BOX
    |--------------------------------------------------------------------------
    */

    .info-box {

        display: flex;

        align-items: center;

        gap: 12px;

        padding: 13px;

        border: 1px solid #edf0f2;

        border-radius: 10px;

        background: #fafbfc;

        height: 100%;

    }


    .info-icon {

        width: 40px;

        height: 40px;

        min-width: 40px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 9px;

        background: #f0f4ff;

        color: #4154f1;

        font-size: 18px;

    }


    .info-box small {

        display: block;

        color: #6c757d;

        font-size: 11px;

        margin-bottom: 3px;

    }


    .info-box strong {

        display: block;

        color: #212529;

        font-size: 13px;

        word-break: break-word;

    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL VALUE
    |--------------------------------------------------------------------------
    */

    .detail-value {

        padding: 10px 12px;

        border: 1px solid #e9ecef;

        border-radius: 7px;

        background: #f8f9fa;

        min-height: 42px;

        display: flex;

        align-items: center;

        word-break: break-word;

    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE
    |--------------------------------------------------------------------------
    */

    .timeline {

        position: relative;

        padding-left: 15px;

    }


    .timeline-item {

        position: relative;

        display: flex;

        gap: 15px;

        padding-bottom: 25px;

    }


    .timeline-item:last-child {

        padding-bottom: 0;

    }


    .timeline-item:not(:last-child)::before {

        content: '';

        position: absolute;

        left: 16px;

        top: 34px;

        bottom: 0;

        width: 1px;

        background: #dee2e6;

    }


    .timeline-icon {

        width: 34px;

        height: 34px;

        min-width: 34px;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        z-index: 1;

        font-size: 14px;

    }


    .timeline-content h6 {

        margin-top: 5px;

        margin-bottom: 5px;

        font-size: 14px;

    }


    .timeline-content p {

        font-size: 13px;

    }


    /*
    |--------------------------------------------------------------------------
    | CARD
    |--------------------------------------------------------------------------
    */

    .card {

        border: 1px solid #edf0f2;

        box-shadow: 0 2px 12px rgba(0,
                0,
                0,
                0.03);

    }


    .card-title {

        font-weight: 600;

    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991px) {

        .info-box {

            min-height: 70px;

        }

    }
</style>

@endsection