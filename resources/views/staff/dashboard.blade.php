@extends('layouts.admin')

@section('title', 'Dashboard Staff')

@section('content')

<div class="mb-4">

    <h3 class="fw-bold">
        Dashboard Staff
    </h3>

    <p class="text-muted">
        Monitoring administrasi DIGIKUBA.
    </p>

</div>

<div class="row g-4">

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">
                    Akun Menunggu Verifikasi
                </small>

                <h2 class="fw-bold">
                    {{ $menungguVerifikasiAkun ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">
                    Menunggu Verifikasi
                </small>

                <h2 class="fw-bold">
                    {{ $menungguVerifikasiSurat ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">
                    Perlu Perbaikan
                </small>

                <h2 class="fw-bold text-warning">
                    {{ $pengajuanPerluPerbaikan ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">
                    Selesai
                </small>

                <h2 class="fw-bold text-success">
                    {{ $pengajuanSelesai ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">Pengajuan Surat Terbaru</h5>
        <a href="{{ route('staff.pengajuan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
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
                    @forelse($pengajuan as $item)
                        <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nomor_pengajuan ?? '-' }}</td>
                            <td>{{ $item->masyarakat->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jenisSurat->nama ?? '-' }}</td>
                            <td>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->status)) }}</td>
                            <td>
                                <a href="{{ route('staff.pengajuan.show', $item) }}" class="btn btn-sm btn-outline-primary">Detail</a>
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