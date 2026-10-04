@extends('layouts.admin')

@section('title', 'Detail Pengajuan Surat')

@section('content')

@php

/*
|--------------------------------------------------------------------------
| DATA PENGAJUAN
|--------------------------------------------------------------------------
*/

$rawData = $pengajuan->data_pengajuan;

/*
| Normalisasi data JSON.
|
| Data saat ini dari database:
|
| {
| "3": null,
| "data_pengajuan": {
| "keperluan": "...",
| "keterangan": "..."
| }
| }
|
*/

if (!is_array($rawData)) {

$dataPengajuan = [];

} elseif (
isset($rawData['data_pengajuan']) &&
is_array($rawData['data_pengajuan'])
) {

$dataPengajuan = $rawData['data_pengajuan'];

} else {

$dataPengajuan = $rawData;

}


/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

$statusLabel = match ($pengajuan->status) {

'pending' =>
'Menunggu Verifikasi',

'diproses' =>
'Sedang Diproses',

'perlu_perbaikan' =>
'Perlu Perbaikan',

'diverifikasi' =>
'Terverifikasi',

'menunggu_tanda_tangan' =>
'Menunggu Tanda Tangan Lurah',

'disetujui' =>
'Disetujui',

'ditolak' =>
'Ditolak',

'selesai' =>
'Selesai',

default =>
ucwords(
str_replace(
'_',
' ',
(string) $pengajuan->status
)
),

};


/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

$statusClass = match ($pengajuan->status) {

'pending' =>
'bg-warning text-dark',

'diproses' =>
'bg-info text-dark',

'perlu_perbaikan' =>
'bg-warning text-dark',

'diverifikasi' =>
'bg-primary',

'menunggu_tanda_tangan' =>
'bg-info text-dark',

'disetujui' =>
'bg-success',

'ditolak' =>
'bg-danger',

'selesai' =>
'bg-success',

default =>
'bg-secondary',

};


/*
|--------------------------------------------------------------------------
| STATUS ICON
|--------------------------------------------------------------------------
*/

$statusIcon = match ($pengajuan->status) {

'pending' =>
'bi-hourglass-split',

'diproses' =>
'bi-arrow-repeat',

'perlu_perbaikan' =>
'bi-exclamation-triangle',

'diverifikasi' =>
'bi-check-circle',

'menunggu_tanda_tangan' =>
'bi-pen',

'disetujui' =>
'bi-check-circle-fill',

'ditolak' =>
'bi-x-circle',

'selesai' =>
'bi-check-circle-fill',

default =>
'bi-info-circle',

};

$currentRole = auth()->user()->role;
$isStaffOperator = $currentRole === 'staff';
$backRoute = match ($currentRole) {
    'masyarakat' => route('masyarakat.pengajuan.index'),
    'lurah' => route('lurah.pengajuan.index'),
    default => route('staff.pengajuan.index'),
};


@endphp


{{-- ==========================================================
     PAGE TITLE
=========================================================== --}}

<div class="pagetitle mb-4">

    <h1>
        Detail Pengajuan Surat
    </h1>

    <nav>

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                <a href="{{ match($currentRole) {
                    'masyarakat' => route('masyarakat.dashboard'),
                    'lurah' => route('lurah.dashboard'),
                    'superadmin' => route('superadmin.dashboard'),
                    default => route('staff.dashboard'),
                } }}">

                    Dashboard

                </a>

            </li>

            <li class="breadcrumb-item">

                <a href="{{ $backRoute }}">

                    Pengajuan Surat

                </a>

            </li>

            <li class="breadcrumb-item active">

                Detail

            </li>

        </ol>

    </nav>

</div>



{{-- ==========================================================
     ALERT
=========================================================== --}}

@if(session('success'))

<div
    class="alert alert-success alert-dismissible fade show"
    role="alert">

    <i class="bi bi-check-circle me-2"></i>

    {{ session('success') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif


@if(session('warning'))

<div
    class="alert alert-warning alert-dismissible fade show"
    role="alert">

    <i class="bi bi-exclamation-triangle me-2"></i>

    {{ session('warning') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif


@if(session('error'))

<div
    class="alert alert-danger alert-dismissible fade show"
    role="alert">

    <i class="bi bi-x-circle me-2"></i>

    {{ session('error') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif



<div class="row g-4">


    {{-- ======================================================
         KOLOM UTAMA
    ======================================================= --}}

    <div class="col-lg-8">


        {{-- ==================================================
             INFORMASI PENGAJUAN
        =================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div
                    class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1 fw-bold">

                            <i
                                class="bi bi-file-earmark-text me-2">
                            </i>

                            Informasi Pengajuan

                        </h5>

                        <small class="text-muted">

                            Detail pengajuan surat masyarakat

                        </small>

                    </div>


                    <span
                        class="badge {{ $statusClass }} px-3 py-2">

                        {{ $statusLabel }}

                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Nomor Pengajuan

                        </small>

                        <strong>

                            {{ $pengajuan->nomor_pengajuan }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Jenis Surat

                        </small>

                        <strong>

                            {{ $pengajuan->jenisSurat->nama ?? '-' }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Tanggal Pengajuan

                        </small>

                        <strong>

                            {{ $pengajuan->created_at
                                ? $pengajuan->created_at->format(
                                    'd/m/Y H:i'
                                )
                                : '-'
                            }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Status

                        </small>

                        <strong>

                            {{ $statusLabel }}

                        </strong>

                    </div>


                </div>

            </div>

        </div>



        {{-- ==================================================
             DATA MASYARAKAT
        =================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-person me-2"></i>

                    Data Pemohon

                </h5>

            </div>


            <div class="card-body">


                @if($pengajuan->masyarakat)

                <div class="row g-4">


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Nama Lengkap

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->masyarakat
                                        ->nama_lengkap
                                    ?? '-'
                                }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            NIK

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->masyarakat
                                        ->nik
                                    ?? '-'
                                }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Jenis Kelamin

                        </small>

                        <strong>

                            {{ $pengajuan->masyarakat->jenis_kelamin ?? '-' }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Tempat Lahir

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->masyarakat
                                        ->tempat_lahir
                                    ?? '-'
                                }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">

                            Tanggal Lahir

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->masyarakat
                                        ->tanggal_lahir
                                    ? \Carbon\Carbon::parse(
                                        $pengajuan
                                            ->masyarakat
                                            ->tanggal_lahir
                                        )->translatedFormat('d F Y')
                                    : '-'
                                }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">Kewarganegaraan</small>

                        <strong>{{ $pengajuan->masyarakat->kewarganegaraan ?? '-' }}</strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">Status Perkawinan</small>

                        <strong>{{ $pengajuan->masyarakat->status_perkawinan ?? '-' }}</strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">Agama</small>

                        <strong>{{ $pengajuan->masyarakat->agama ?? '-' }}</strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">Pekerjaan</small>

                        <strong>{{ $pengajuan->masyarakat->pekerjaan_label ?? '-' }}</strong>

                    </div>


                    <div class="col-md-6">

                        <small class="text-muted d-block mb-1">Email</small>

                        <strong>{{ $pengajuan->masyarakat->user?->email ?? '-' }}</strong>

                    </div>


                    <div class="col-12">

                        <small class="text-muted d-block mb-1">

                            Alamat

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->masyarakat
                                        ->alamat
                                    ?? '-'
                                }}

                        </strong>

                    </div>


                </div>

                @else

                <div class="alert alert-warning mb-0">

                    <i
                        class="bi bi-exclamation-triangle me-2">
                    </i>

                    Data masyarakat tidak ditemukan.

                </div>

                @endif


            </div>

        </div>



        {{-- ==================================================
             DATA PENGAJUAN
        =================================================== --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i
                        class="bi bi-file-earmark-text me-2">
                    </i>

                    Data Pengajuan

                </h5>

            </div>


            <div class="card-body">


                @if(
                is_array($dataPengajuan) &&
                count($dataPengajuan) > 0
                )


                <div class="row g-4">


                    @foreach(
                    $dataPengajuan as $key => $value
                    )


                    @php

                    $label = ucwords(
                    str_replace(
                    '_',
                    ' ',
                    (string) $key
                    )
                    );

                    @endphp


                    @if(is_array($value))


                    <div class="col-12">

                        <div
                            class="border rounded-3 p-3">

                            <div
                                class="fw-semibold mb-3">

                                {{ $label }}

                            </div>


                            @foreach(
                            $value
                            as $subKey => $subValue
                            )


                            @php

                            $subLabel =
                            ucwords(
                            str_replace(
                            '_',
                            ' ',
                            (string) $subKey
                            )
                            );

                            @endphp


                            <div class="mb-3">

                                <small
                                    class="text-muted d-block mb-1">

                                    {{ $subLabel }}

                                </small>


                                @if(
                                is_array(
                                $subValue
                                )
                                )

                                <pre
                                    class="bg-light p-3 rounded small mb-0">{{ json_encode(
                                                        $subValue,
                                                        JSON_PRETTY_PRINT |
                                                        JSON_UNESCAPED_UNICODE
                                                    ) }}</pre>

                                @else

                                <div class="fw-semibold">

                                    {{
                                                            $subValue === null
                                                                ? '-'
                                                                : (string) $subValue
                                                        }}

                                </div>

                                @endif

                            </div>


                            @endforeach


                        </div>

                    </div>


                    @else


                    <div class="col-md-6">

                        <small
                            class="text-muted d-block mb-1">

                            {{ $label }}

                        </small>

                        <div class="fw-semibold">

                            {{
                                            $value === null
                                                ? '-'
                                                : (string) $value
                                        }}

                        </div>

                    </div>


                    @endif


                    @endforeach


                </div>


                @else

                <div class="alert alert-secondary mb-0">

                    <i
                        class="bi bi-info-circle me-2">
                    </i>

                    Data pengajuan belum tersedia.

                </div>

                @endif


            </div>

        </div>



        {{-- ==================================================
             CATATAN
        =================================================== --}}

        @if($pengajuan->catatan)

        <div
            class="card border-0 shadow-sm mb-4">

            <div
                class="card-header bg-white py-3">

                <h5
                    class="mb-0 fw-bold text-warning">

                    <i
                        class="bi bi-exclamation-triangle me-2">
                    </i>

                    Catatan Pemeriksaan

                </h5>

            </div>


            <div class="card-body">

                <div class="alert alert-warning mb-0">

                    {!! nl2br(
                    e($pengajuan->catatan)
                    ) !!}

                </div>

            </div>

        </div>

        @endif



        {{-- ==================================================
             KOMENTAR
        =================================================== --}}

        @if(
        $pengajuan->komentar &&
        $pengajuan->komentar->count() > 0
        )

        <div
            class="card border-0 shadow-sm mb-4">

            <div
                class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i
                        class="bi bi-chat-left-text me-2">
                    </i>

                    Riwayat Komentar

                </h5>

            </div>


            <div class="card-body">


                @foreach(
                $pengajuan->komentar as $komentar
                )

                <div
                    class="border-bottom pb-3 mb-3">

                    <div
                        class="d-flex justify-content-between">

                        <div>

                            <strong>

                                {{
                                            $komentar
                                                ->user
                                                ->name
                                            ?? 'Petugas'
                                        }}

                            </strong>

                            <div>

                                <small
                                    class="text-muted">

                                    {{
                                                $komentar->created_at
                                                ? $komentar
                                                    ->created_at
                                                    ->format(
                                                        'd/m/Y H:i'
                                                    )
                                                : '-'
                                            }}

                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="mt-2">

                        {!! nl2br(
                        e(
                        $komentar->komentar
                        )
                        ) !!}

                    </div>

                </div>

                @endforeach


            </div>

        </div>

        @endif



        {{-- ==================================================
             SURAT
        =================================================== --}}

        @if($pengajuan->surat)

        <div
            class="card border-0 shadow-sm mb-4">

            <div
                class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i
                        class="bi bi-file-earmark-pdf me-2">
                    </i>

                    Surat

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    <div class="col-md-6">

                        <small
                            class="text-muted d-block mb-1">

                            Nomor Surat

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->surat
                                        ->nomor_surat
                                    ?? '-'
                                }}

                        </strong>

                    </div>


                    <div class="col-md-6">

                        <small
                            class="text-muted d-block mb-1">

                            Tanggal Surat

                        </small>

                        <strong>

                            {{
                                    $pengajuan->surat->tanggal_surat
                                    ? \Carbon\Carbon::parse(
                                        $pengajuan
                                            ->surat
                                            ->tanggal_surat
                                    )->format('d/m/Y')
                                    : '-'
                                }}

                        </strong>

                    </div>


                    <div class="col-12">

                        <small
                            class="text-muted d-block mb-1">

                            Perihal

                        </small>

                        <strong>

                            {{
                                    $pengajuan
                                        ->surat
                                        ->perihal
                                    ?? '-'
                                }}

                        </strong>

                    </div>


                </div>


                @if(
                $pengajuan->surat->file_pdf
                )

                <div class="mt-4">

                    <span
                        class="badge bg-success">

                        <i
                            class="bi bi-check-circle me-1">
                        </i>

                        PDF Tersedia

                    </span>

                </div>

                @endif


            </div>


        </div>

        @endif

        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    <b>Dokumen Persyaratan</b>
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- KTP --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <h6>
                                <i class="bi bi-person-vcard"></i>
                                KTP
                            </h6>

                            <p class="text-muted small">
                                Dokumen dari registrasi akun.
                            </p>

                            @if($pengajuan->masyarakat?->foto_ktp)
                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#staffDocumentPreviewModal"
                                        data-document-url="{{ route('pengajuan.dokumen', ['pengajuan' => $pengajuan, 'jenis' => 'ktp']) }}"
                                    data-document-type="image"
                                    data-document-title="Foto KTP">
                                    <i class="bi bi-eye"></i>
                                    Lihat KTP
                                </button>
                            @else
                                <span class="badge bg-danger">Belum tersedia</span>
                            @endif

                        </div>

                    </div>


                    {{-- KK --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <h6>
                                <i class="bi bi-people"></i>
                                Kartu Keluarga
                            </h6>

                            @if($pengajuan->file_kk)

                            @php
                                $kkExtension = strtolower(pathinfo($pengajuan->file_kk, PATHINFO_EXTENSION));
                            @endphp
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#staffDocumentPreviewModal"
                                data-document-url="{{ route('pengajuan.dokumen', ['pengajuan' => $pengajuan, 'jenis' => 'kk']) }}"
                                data-document-type="{{ $kkExtension === 'pdf' ? 'pdf' : 'image' }}"
                                data-document-title="Kartu Keluarga">
                                <i class="bi bi-eye"></i>
                                Lihat KK
                            </button>

                            @else

                            <span class="badge bg-danger">
                                Belum tersedia
                            </span>

                            @endif

                        </div>

                    </div>


                    {{-- Pengantar --}}

                    <div class="col-md-4">

                        <div class="border rounded p-3 h-100">

                            <h6>
                                <i class="bi bi-file-earmark-text"></i>
                                Pengantar RT/RW
                            </h6>

                            @if($pengajuan->file_pengantar_rt_rw)

                            @php
                                $pengantarExtension = strtolower(pathinfo($pengajuan->file_pengantar_rt_rw, PATHINFO_EXTENSION));
                            @endphp
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#staffDocumentPreviewModal"
                                data-document-url="{{ route('pengajuan.dokumen', ['pengajuan' => $pengajuan, 'jenis' => 'pengantar_rt_rw']) }}"
                                data-document-type="{{ $pengantarExtension === 'pdf' ? 'pdf' : 'image' }}"
                                data-document-title="Pengantar RT/RW">
                                <i class="bi bi-eye"></i>
                                Lihat Pengantar
                            </button>

                            @else

                            <span class="badge bg-danger">
                                Belum tersedia
                            </span>

                            @endif

                        </div>

                    </div>


                </div>

            </div>

        </div>
    </div>






    {{-- ======================================================
         KOLOM KANAN
    ======================================================= --}}

    <div class="col-lg-4">


        {{-- ==================================================
             STATUS
        =================================================== --}}

        <div
            class="card border-0 shadow-sm mb-4">

            <div
                class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="bi bi-activity me-2"></i>

                    Status Pengajuan

                </h5>

            </div>


            <div class="card-body">


                <div
                    class="d-flex align-items-start">

                    <div class="me-3">

                        <div
                            class="rounded-circle {{ $statusClass }} text-white d-flex align-items-center justify-content-center"
                            style="
                                width: 42px;
                                height: 42px;
                            ">

                            <i
                                class="bi {{ $statusIcon }}">
                            </i>

                        </div>

                    </div>


                    <div>

                        <div class="fw-semibold">

                            {{ $statusLabel }}

                        </div>

                        <small class="text-muted">

                            Status pengajuan saat ini.

                        </small>

                    </div>

                </div>


            </div>

        </div>



        {{-- ==================================================
             AKSI STAFF
        =================================================== --}}

        <div
            class="card border-0 shadow-sm mb-4">

            <div
                class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i
                        class="bi bi-lightning me-2">
                    </i>

                    Tindakan

                </h5>

            </div>


            <div class="card-body">

                @if($isStaffOperator)


                {{-- KEMBALI --}}

                <a
                    href="{{ route(
                        'staff.pengajuan.index'
                    ) }}"
                    class="btn btn-outline-secondary w-100 mb-3">

                    <i
                        class="bi bi-arrow-left me-2">
                    </i>

                    Kembali

                </a>



                {{-- ==================================================
                     JIKA PENDING
                =================================================== --}}

                @if(
                $pengajuan->status === 'pending'
                )


                {{-- VERIFIKASI --}}

                <button
                    type="button"
                    class="btn btn-success w-100 mb-2"
                    id="btnVerify">

                    <i
                        class="bi bi-check-circle me-2">
                    </i>

                    Verifikasi Pengajuan

                </button>


                <form
                    id="verifyForm"
                    action="{{ route(
                            'staff.pengajuan.verify',
                            $pengajuan
                        ) }}"
                    method="POST"
                    class="d-none">

                    @csrf

                </form>



                {{-- PERLU PERBAIKAN --}}

                <button
                    type="button"
                    class="btn btn-warning w-100 mb-2"
                    data-bs-toggle="modal"
                    data-bs-target="#revisionModal">

                    <i
                        class="bi bi-pencil-square me-2">
                    </i>

                    Minta Perbaikan

                </button>



                {{-- TOLAK --}}

                <button
                    type="button"
                    class="btn btn-danger w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectModal">

                    <i
                        class="bi bi-x-circle me-2">
                    </i>

                    Tolak Pengajuan

                </button>


                @endif



                {{-- ==================================================
                     JIKA PERLU PERBAIKAN
                =================================================== --}}

                @if(
                $pengajuan->status ===
                'perlu_perbaikan'
                )

                <div
                    class="alert alert-warning mb-0">

                    <i
                        class="bi bi-info-circle me-2">
                    </i>

                    Menunggu masyarakat memperbaiki
                    pengajuan.

                </div>

                @endif



                {{-- ==================================================
                     JIKA SUDAH DIVERIFIKASI
                =================================================== --}}

                @if(
                in_array(
                $pengajuan->status,
                [
                'diverifikasi',
                'menunggu_tanda_tangan'
                ],
                true
                )
                )

                <div
                    class="alert alert-info mb-0">

                    <i
                        class="bi bi-info-circle me-2">
                    </i>

                    Pengajuan sudah diverifikasi
                    dan menunggu proses tanda tangan
                    Lurah.

                </div>

                @endif



                {{-- ==================================================
                     JIKA SELESAI
                =================================================== --}}

                @if(
                in_array(
                $pengajuan->status,
                [
                'disetujui',
                'selesai'
                ],
                true
                )
                )

                <div
                    class="alert alert-success mb-0">

                    <i
                        class="bi bi-check-circle me-2">
                    </i>

                    Pengajuan telah selesai diproses.

                </div>

                @if($pengajuan->surat && in_array($pengajuan->surat->status, ['ditandatangani', 'selesai'], true))
                    <a href="{{ route('surat.pengajuan.download', $pengajuan) }}" class="btn btn-success w-100 mt-3">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </a>
                @endif

                @endif



                {{-- ==================================================
                     JIKA DITOLAK
                =================================================== --}}

                @if(
                $pengajuan->status ===
                'ditolak'
                )

                <div
                    class="alert alert-danger mb-0">

                    <i
                        class="bi bi-x-circle me-2">
                    </i>

                    Pengajuan telah ditolak.

                </div>

                @endif

                @elseif($currentRole === 'masyarakat')
                    <a href="{{ $backRoute }}" class="btn btn-outline-secondary w-100 mb-3">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Pengajuan Saya
                    </a>

                    @if($pengajuan->status === 'selesai' && $pengajuan->surat && in_array($pengajuan->surat->status, ['ditandatangani', 'selesai'], true))
                        <a href="{{ route('surat.pengajuan.download', $pengajuan) }}" class="btn btn-success w-100">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </a>
                    @else
                        <div class="alert alert-info mb-0">Surat dapat diunduh setelah selesai diproses dan ditandatangani.</div>
                    @endif

                @elseif($currentRole === 'lurah')
                    <a href="{{ $backRoute }}" class="btn btn-outline-secondary w-100 mb-3">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Pengajuan
                    </a>

                    @if($pengajuan->surat && $pengajuan->status === 'menunggu_tanda_tangan')
                        <a href="{{ route('lurah.tanda-tangan.show', $pengajuan->surat) }}" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-pen me-2"></i>Buka Tindakan Tanda Tangan
                        </a>
                    @endif

                    @if($pengajuan->status === 'selesai' && $pengajuan->surat && in_array($pengajuan->surat->status, ['ditandatangani', 'selesai'], true))
                        <a href="{{ route('surat.pengajuan.download', $pengajuan) }}" class="btn btn-success w-100">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </a>
                    @else
                        <div class="alert alert-info mb-0">Surat belum selesai ditandatangani.</div>
                    @endif

                @else
                    <a href="{{ $backRoute }}" class="btn btn-outline-secondary w-100 mb-3">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    @if($pengajuan->status === 'selesai' && $pengajuan->surat && in_array($pengajuan->surat->status, ['ditandatangani', 'selesai'], true))
                        <a href="{{ route('surat.pengajuan.download', $pengajuan) }}" class="btn btn-success w-100">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </a>
                    @else
                        <div class="alert alert-info mb-0">Surat belum selesai diproses.</div>
                    @endif
                @endif


            </div>

        </div>


    </div>

</div>



{{-- ==========================================================
     MODAL PERBAIKAN
=========================================================== --}}

@if($isStaffOperator)

<div
    class="modal fade"
    id="revisionModal"
    tabindex="-1"
    aria-hidden="true">

    <div
        class="modal-dialog">

        <div
            class="modal-content">


            <div
                class="modal-header">

                <h5
                    class="modal-title">

                    <i
                        class="bi bi-pencil-square me-2">
                    </i>

                    Minta Perbaikan

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form
                action="{{ route(
                    'staff.pengajuan.revision',
                    $pengajuan
                ) }}"
                method="POST">

                @csrf


                <div
                    class="modal-body">

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Catatan Perbaikan

                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="5"
                            required
                            placeholder="Jelaskan bagian yang perlu diperbaiki oleh masyarakat..."></textarea>

                    </div>

                </div>


                <div
                    class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-warning">

                        <i
                            class="bi bi-send me-1">
                        </i>

                        Kirim Catatan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- ==========================================================
     MODAL TOLAK
=========================================================== --}}

<div
    class="modal fade"
    id="rejectModal"
    tabindex="-1"
    aria-hidden="true">

    <div
        class="modal-dialog">

        <div
            class="modal-content">


            <div
                class="modal-header">

                <h5
                    class="modal-title text-danger">

                    <i
                        class="bi bi-x-circle me-2">
                    </i>

                    Tolak Pengajuan

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form
                action="{{ route(
                    'staff.pengajuan.reject',
                    $pengajuan
                ) }}"
                method="POST">

                @csrf


                <div
                    class="modal-body">

                    <div class="alert alert-danger">

                        <i
                            class="bi bi-exclamation-triangle me-2">
                        </i>

                        Pengajuan yang ditolak akan
                        diberitahukan kepada masyarakat.

                    </div>


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Alasan Penolakan

                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="5"
                            required
                            placeholder="Masukkan alasan penolakan..."></textarea>

                    </div>

                </div>


                <div
                    class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger">

                        <i
                            class="bi bi-x-circle me-1">
                        </i>

                        Tolak Pengajuan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



@endif

{{-- ==========================================================
    MODAL PREVIEW DOKUMEN
=========================================================== --}}

<div
    class="modal fade"
    id="staffDocumentPreviewModal"
    tabindex="-1"
    aria-labelledby="staffDocumentPreviewTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffDocumentPreviewTitle">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body bg-light text-center">
                <img
                    id="staffDocumentPreviewImage"
                    class="img-fluid d-none mx-auto"
                    style="max-height: 72vh; object-fit: contain;"
                    alt="Pratinjau dokumen">
                <iframe
                    id="staffDocumentPreviewPdf"
                    class="w-100 d-none border rounded bg-white"
                    style="height: 72vh;"
                    title="Pratinjau dokumen PDF"></iframe>
            </div>
        </div>
    </div>
</div>


{{-- ==========================================================
     JAVASCRIPT
=========================================================== --}}

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const verifyButton =
                document.getElementById(
                    'btnVerify'
                );

            const verifyForm =
                document.getElementById(
                    'verifyForm'
                );


            if (
                verifyButton &&
                verifyForm
            ) {

                verifyButton.addEventListener(
                    'click',
                    function() {

                        if (
                            typeof Swal !==
                            'undefined'
                        ) {

                            Swal.fire({

                                title: 'Verifikasi Pengajuan?',

                                text: 'Pengajuan ini akan diteruskan ke proses tanda tangan Lurah.',

                                icon: 'question',

                                showCancelButton: true,

                                confirmButtonText: 'Ya, Verifikasi',

                                cancelButtonText: 'Batal',

                                reverseButtons: true

                            }).then(
                                function(result) {

                                    if (
                                        result.isConfirmed
                                    ) {

                                        verifyForm.submit();

                                    }

                                }
                            );

                        } else {

                            if (
                                confirm(
                                    'Verifikasi pengajuan ini?'
                                )
                            ) {

                                verifyForm.submit();

                            }

                        }

                    }
                );

            }

        }
    );
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('staffDocumentPreviewModal');

        if (!modal) {
            return;
        }

        const title = document.getElementById('staffDocumentPreviewTitle');
        const image = document.getElementById('staffDocumentPreviewImage');
        const pdf = document.getElementById('staffDocumentPreviewPdf');

        modal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const url = trigger.getAttribute('data-document-url');
            const type = trigger.getAttribute('data-document-type');
            title.textContent = 'Pratinjau ' + (trigger.getAttribute('data-document-title') || 'Dokumen');

            if (type === 'pdf') {
                image.classList.add('d-none');
                image.removeAttribute('src');
                pdf.classList.remove('d-none');
                pdf.src = url;
                return;
            }

            pdf.classList.add('d-none');
            pdf.removeAttribute('src');
            image.classList.remove('d-none');
            image.src = url;
        });

        modal.addEventListener('hidden.bs.modal', function () {
            image.removeAttribute('src');
            pdf.removeAttribute('src');
            image.classList.add('d-none');
            pdf.classList.add('d-none');
        });
    });
</script>

@endsection