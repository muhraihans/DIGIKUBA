@extends('layouts.admin')

@section('title', 'Detail Verifikasi Akun')

@section('content')

<div class="pagetitle">
    <h1>Verifikasi Akun Masyarakat</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('staff.dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('staff.verifikasi-akun.index') }}">
                    Verifikasi Akun
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail
            </li>
        </ol>
    </nav>
</div>

<section class="section">

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('warning') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
    @endif


    {{-- Informasi Status --}}
    <div class="card mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h5 class="card-title mb-0">
                        Detail Akun Masyarakat
                    </h5>

                    <small class="text-muted">
                        Pemeriksaan data registrasi masyarakat
                    </small>
                </div>

                @if($masyarakat->user)

                @if($masyarakat->user->status === 'pending')

                <span class="badge bg-warning text-dark">
                    <i class="bi bi-clock me-1"></i>
                    Menunggu Verifikasi
                </span>

                @elseif($masyarakat->user->status === 'active')

                <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Aktif
                </span>

                @elseif($masyarakat->user->status === 'rejected')

                <span class="badge bg-danger">
                    <i class="bi bi-x-circle me-1"></i>
                    Ditolak
                </span>

                @else

                <span class="badge bg-secondary">
                    {{ ucfirst($masyarakat->user->status) }}
                </span>

                @endif

                @endif

            </div>

        </div>
    </div>


    {{-- Data Masyarakat --}}
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


    {{-- INFORMASI VERIFIKASI --}}
    @if($masyarakat->verified_at)

    <div class="card mb-4">

        <div class="card-body">

            <div class="alert alert-success mb-0">

                <div class="d-flex">

                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>

                    <div>

                        <strong>
                            Akun telah diverifikasi.
                        </strong>

                        <br>

                        <small>
                            Diverifikasi pada
                            {{ \Carbon\Carbon::parse(
                                    $masyarakat->verified_at
                                )->translatedFormat('d F Y H:i') }}
                            WIB
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @endif


    {{-- ACTION --}}
    @if(
    $masyarakat->user &&
    $masyarakat->user->status === 'pending'
    )

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-shield-check me-2"></i>
                Tindakan Verifikasi
            </h5>
        </div>

        <div class="card-body">

            <div class="alert alert-info">

                <i class="bi bi-info-circle me-2"></i>

                Pastikan data masyarakat, foto KTP,
                dan foto selfie telah diperiksa
                sebelum melakukan verifikasi.

            </div>

            <div class="d-flex flex-wrap gap-2">

                {{-- KEMBALI --}}
                <a
                    href="{{ route(
                            'staff.verifikasi-akun.index'
                        ) }}"
                    class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>


                {{-- TOLAK --}}
                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#modalReject">
                    <i class="bi bi-x-circle me-1"></i>
                    Tolak
                </button>


                {{-- VERIFIKASI --}}
                <button
                    type="button"
                    class="btn btn-success"
                    id="btnVerify">
                    <i class="bi bi-check-circle me-1"></i>
                    Verifikasi Akun
                </button>

            </div>

        </div>

    </div>

    @else

    <div class="d-flex">

        <a
            href="{{ route(
                    'staff.verifikasi-akun.index'
                ) }}"
            class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    @endif

</section>


{{-- MODAL TOLAK --}}
@if(
$masyarakat->user &&
$masyarakat->user->status === 'pending'
)

<div
    class="modal fade"
    id="modalReject"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route(
                    'staff.verifikasi-akun.reject',
                    $masyarakat->id
                ) }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="bi bi-x-circle text-danger me-2"></i>
                        Tolak Akun
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        Akun masyarakat akan ditandai sebagai
                        <strong>ditolak</strong>.

                    </div>

                    <div class="mb-3">

                        <label
                            for="catatan"
                            class="form-label">
                            Alasan Penolakan
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="catatan"
                            id="catatan"
                            class="form-control"
                            rows="5"
                            placeholder="Masukkan alasan penolakan..."
                            required></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>
                        Tolak Akun
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif


{{-- FORM VERIFIKASI TERSEMBUNYI --}}
@if(
$masyarakat->user &&
$masyarakat->user->status === 'pending'
)

<form
    id="formVerify"
    method="POST"
    action="{{ route(
        'staff.verifikasi-akun.verify',
        $masyarakat->id
    ) }}"
    class="d-none">
    @csrf
</form>

@endif


@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const verifyButton =
            document.getElementById('btnVerify');

        const verifyForm =
            document.getElementById('formVerify');


        if (verifyButton && verifyForm) {

            verifyButton.addEventListener(
                'click',
                function() {

                    Swal.fire({
                        title: 'Verifikasi Akun?',
                        text: 'Pastikan data, KTP, dan selfie masyarakat sudah diperiksa.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Verifikasi',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {

                        if (result.isConfirmed) {

                            verifyForm.submit();

                        }

                    });

                }
            );

        }

    });
</script>

@endpush

@endsection