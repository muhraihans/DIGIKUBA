@extends('layouts.admin')

@section('title', 'Dashboard Superadmin')

@section('content')

<h3 class="fw-bold">
    Dashboard Superadmin
</h3>

<p class="text-muted">
    Monitoring dan pengelolaan DIGIKUBA.
</p>

<div class="row g-4 mt-2">

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Total User
                </span>

                <h2>
                    {{ $totalUsers ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Masyarakat
                </span>

                <h2>
                    {{ $totalMasyarakat ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Staff
                </span>

                <h2>
                    {{ $totalStaff ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card stat-card">

            <div class="card-body">

                <span class="text-muted">
                    Lurah
                </span>

                <h2>
                    {{ $totalLurah ?? 0 }}
                </h2>

            </div>

        </div>

    </div>

</div>

<div class="row g-4 mt-1">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <span class="text-muted">Akun Menunggu Verifikasi</span>
                <h2>{{ $pendingAkun ?? 0 }}</h2>
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
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <span class="text-muted">Pengajuan Selesai</span>
                <h2>{{ $pengajuanSelesai ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <span class="text-muted">Total Surat</span>
                <h2>{{ $totalSurat ?? 0 }}</h2>
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
                    @forelse($pengajuanTerbaru as $item)
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