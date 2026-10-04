@extends('layouts.admin')

@section('title', 'Pengajuan Surat')

@section('content')
<div class="pagetitle mb-4">
    <h1>Pengajuan Surat</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('lurah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pengajuan Surat</li>
        </ol>
    </nav>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                            <td>{{ $pengajuan->firstItem() + $loop->index }}</td>
                            <td>{{ $item->nomor_pengajuan ?? '-' }}</td>
                            <td>{{ $item->masyarakat->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->jenisSurat->nama ?? '-' }}</td>
                            <td>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->status)) }}</td>
                            <td>
                                <a href="{{ route('lurah.pengajuan.show', $item) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan surat yang menunggu atau telah diproses.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $pengajuan->links() }}</div>
    </div>
</div>
@endsection
