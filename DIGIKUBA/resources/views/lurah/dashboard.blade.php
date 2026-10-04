@extends('layouts.admin')

@section('title', 'Dashboard Lurah')

@section('content')

<h3 class="fw-bold">
    Dashboard Lurah
</h3>

<p class="text-muted">
    Persetujuan dan tanda tangan surat elektronik.
</p>

<div class="row g-4 mt-2">

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Menunggu Tanda Tangan
                </span>

                <h2>
                    {{ $menungguTandaTangan ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Ditandatangani
                </span>

                <h2>
                    {{ $ditandatangani ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Selesai
                </span>

                <h2>
                    {{ $selesai ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <span class="text-muted">Total Pengajuan</span>
                <h2>{{ $totalPengajuan ?? 0 }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Pengajuan Surat Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Pengajuan</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanTerbaru as $item)
                        <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nomor_pengajuan ?? '-' }}</td>
                            <td>{{ $item->masyarakat->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jenisSurat->nama ?? '-' }}</td>
                            <td>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->status)) }}</td>
                            <td>
                                @if($item->surat)
                                    <a href="{{ route('lurah.pengajuan.show', $item) }}" class="btn btn-sm btn-outline-primary">Detail Pengajuan</a>
                                @else
                                    <span class="text-muted small">Surat belum diterbitkan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection