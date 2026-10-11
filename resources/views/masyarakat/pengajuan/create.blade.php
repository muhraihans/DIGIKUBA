@extends('layouts.admin')

@section('title', 'Ajukan Surat')

@section('content')

<div class="mb-4">

    <h3 class="fw-bold">
        Ajukan Surat
    </h3>

    <p class="text-muted">
        Pilih jenis surat yang ingin Anda ajukan.
    </p>

</div>

<div class="row g-4">

    @forelse($jenisSurat ?? [] as $jenis)

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="mb-3">

                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">

                            <i class="bi bi-file-earmark-text fs-3 text-primary"></i>

                        </div>

                    </div>

                    <h5 class="fw-bold">
                        {{ $jenis->nama }}
                    </h5>

                    <p class="text-muted">

                        {{ $jenis->deskripsi }}

                    </p>

                    <a
                        href="{{ route('masyarakat.pengajuan.form', $jenis) }}"
                        class="btn btn-primary">

                        Ajukan Surat
                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div class="alert alert-info">
                Belum tersedia jenis surat.
            </div>

        </div>

    @endforelse

</div>

@endsection