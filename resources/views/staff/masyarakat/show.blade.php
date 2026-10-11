@extends('layouts.admin')

@section('title', 'Detail Masyarakat')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4 class="mb-4">
            Detail Masyarakat
        </h4>

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2"></i>
                    Data Masyarakat
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- NIK --}}
                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            NIK
                        </label>

                        <div class="fw-semibold">
                            {{ $masyarakat->nik }}
                        </div>

                    </div>


                    {{-- Nama --}}
                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Nama Lengkap
                        </label>

                        <div class="fw-semibold">
                            {{ $masyarakat->nama_lengkap }}
                        </div>

                    </div>


                    {{-- Tempat Lahir --}}
                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Tempat Lahir
                        </label>

                        <div>
                            {{ $masyarakat->tempat_lahir }}
                        </div>

                    </div>


                    {{-- Tanggal Lahir --}}
                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Tanggal Lahir
                        </label>

                        <div>
                            {{ \Carbon\Carbon::parse(
                            $masyarakat->tanggal_lahir
                        )->translatedFormat('d F Y') }}
                        </div>

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Email
                        </label>

                        <div>
                            {{ $masyarakat->user->email ?? '-' }}
                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label text-muted">
                            Status Akun
                        </label>

                        <div>

                            @if($masyarakat->user)

                            @switch($masyarakat->user->status)

                            @case('pending')
                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>
                            @break

                            @case('active')
                            <span class="badge bg-success">
                                Aktif
                            </span>
                            @break

                            @case('inactive')
                            <span class="badge bg-secondary">
                                Tidak Aktif
                            </span>
                            @break

                            @case('rejected')
                            <span class="badge bg-danger">
                                Ditolak
                            </span>
                            @break

                            @default
                            <span class="badge bg-secondary">
                                {{ $masyarakat->user->status }}
                            </span>

                            @endswitch

                            @else
                            -
                            @endif

                        </div>

                    </div>


                    {{-- Alamat --}}
                    <div class="col-12">

                        <label class="form-label text-muted">
                            Alamat Lengkap Sesuai KTP
                        </label>

                        <div class="border rounded p-3 bg-light">
                            {{ $masyarakat->alamat }}
                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- DOKUMEN --}}
        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-image me-2"></i>
                    Dokumen Verifikasi
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- FOTO KTP --}}
                    <div class="col-lg-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-card-image me-2"></i>
                                Foto KTP
                            </h6>

                            @if($masyarakat->foto_ktp)

                            <div class="text-center">

                                <a
                                    href="{{ route(
                                        'staff.verifikasi-akun.dokumen',
                                        [
                                            'masyarakat' => $masyarakat->id,
                                            'jenis' => 'ktp'
                                        ]
                                    ) }}"
                                    target="_blank"
                                    rel="noopener noreferrer">

                                    <img
                                        src="{{ route(
                                            'staff.verifikasi-akun.dokumen',
                                            [
                                                'masyarakat' => $masyarakat->id,
                                                'jenis' => 'ktp'
                                            ]
                                        ) }}"
                                        alt="Foto KTP {{ $masyarakat->nama_lengkap }}"
                                        class="img-fluid rounded border"
                                        style="
                                            max-height: 450px;
                                            width: auto;
                                            object-fit: contain;
                                        ">

                                </a>

                                <div class="mt-3">

                                    <a
                                        href="{{ route(
                                            'staff.verifikasi-akun.dokumen',
                                            [
                                                'masyarakat' => $masyarakat->id,
                                                'jenis' => 'ktp'
                                            ]
                                        ) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrows-fullscreen me-1"></i>
                                        Lihat KTP
                                    </a>

                                </div>

                            </div>

                            @else

                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Foto KTP belum tersedia.
                            </div>

                            @endif

                        </div>

                    </div>


                    {{-- FOTO SELFIE --}}
                    <div class="col-lg-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-camera me-2"></i>
                                Foto Selfie
                            </h6>

                            @if($masyarakat->foto_selfie)

                            <div class="text-center">

                                <a
                                    href="{{ route(
                                        'staff.verifikasi-akun.dokumen',
                                        [
                                            'masyarakat' => $masyarakat->id,
                                            'jenis' => 'selfie'
                                        ]
                                    ) }}"
                                    target="_blank"
                                    rel="noopener noreferrer">

                                    <img
                                        src="{{ route(
                                            'staff.verifikasi-akun.dokumen',
                                            [
                                                'masyarakat' => $masyarakat->id,
                                                'jenis' => 'selfie'
                                            ]
                                        ) }}"
                                        alt="Foto Selfie {{ $masyarakat->nama_lengkap }}"
                                        class="img-fluid rounded border"
                                        style="
                                            max-height: 450px;
                                            width: auto;
                                            object-fit: contain;
                                        ">

                                </a>

                                <div class="mt-3">

                                    <a
                                        href="{{ route(
                                            'staff.verifikasi-akun.dokumen',
                                            [
                                                'masyarakat' => $masyarakat->id,
                                                'jenis' => 'selfie'
                                            ]
                                        ) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrows-fullscreen me-1"></i>
                                        Lihat Selfie
                                    </a>

                                </div>

                            </div>

                            @else

                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Foto selfie belum tersedia.
                            </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection