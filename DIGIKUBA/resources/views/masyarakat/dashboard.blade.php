@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">

    <h3 class="fw-bold">
        Dashboard
    </h3>

    <p class="text-muted">
        Selamat datang di DIGIKUBA,
        {{ auth()->user()->name }}.
    </p>

</div>

@if(auth()->user()->status !== 'active')

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4 text-center">

            <i class="bi bi-hourglass-split fs-1 text-warning"></i>

            <h4 class="mt-3">
                Akun Sedang Menunggu Verifikasi
            </h4>

            <p class="text-muted mb-0">

                Akun Anda telah berhasil dibuat.
                Silakan menunggu proses verifikasi
                oleh Staff Kelurahan.

            </p>

        </div>

    </div>

@else

    <div class="row g-4">

        <div class="col-md-4">

            <div class="card stat-card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Pengajuan
                            </small>

                            <h3 class="fw-bold">
                                {{ $totalPengajuan ?? 0 }}
                            </h3>

                        </div>

                        <i class="bi bi-file-earmark-text fs-1 text-primary"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card stat-card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Disetujui
                    </small>

                    <h3 class="fw-bold text-success">
                        {{ $pengajuanDisetujui ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card stat-card shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Perlu Perbaikan
                    </small>

                    <h3 class="fw-bold text-warning">
                        {{ $pengajuanPerbaikan ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Pengajuan Surat Terbaru</h5>
            <a href="{{ route('masyarakat.pengajuan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Pengajuan</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $item)
                            <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nomor_pengajuan ?? '-' }}</td>
                                <td>{{ $item->jenisSurat->nama ?? '-' }}</td>
                                <td>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $item->status)) }}</td>
                                <td>
                                    <a href="{{ route('masyarakat.pengajuan.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan surat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <h5 class="fw-bold">
                Layanan Surat
            </h5>

            <p class="text-muted">
                Ajukan surat secara online melalui DIGIKUBA.
            </p>

            <a
                href="{{ route('masyarakat.pengajuan.create') }}"
                class="btn btn-primary">

                <i class="bi bi-plus-circle me-2"></i>
                Ajukan Surat

            </a>

        </div>

    </div>

@endif

@endsection