@extends('layouts.admin')

@section('title', 'Pengajuan Surat')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold">
            Pengajuan Surat
        </h3>

        <p class="text-muted mb-0">
            Daftar pengajuan surat Anda.
        </p>

    </div>

    <a
        href="{{ route('masyarakat.pengajuan.create') }}"
        class="btn btn-primary">

        <i class="bi bi-plus-circle me-2"></i>
        Pengajuan Baru

    </a>

</div>

<div class="card table-card shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

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

                    @forelse($pengajuan ?? [] as $item)

                        <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->nomor_pengajuan }}
                            </td>

                            <td>
                                {{ $item->jenisSurat->nama }}
                            </td>

                            <td>
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>

                            <td>

                                @php

                                    $badge = match($item->status) {

                                        'pending' => 'warning',

                                        'diproses' => 'info',

                                        'perlu_perbaikan' => 'warning',

                                        'diverifikasi' => 'primary',

                                        'menunggu_tanda_tangan' => 'info',

                                        'disetujui',
                                        'selesai' => 'success',

                                        'ditolak' => 'danger',

                                        default => 'secondary',

                                    };

                                @endphp

                                <span class="badge bg-{{ $badge }}">

                                    {{ ucwords(str_replace('_', ' ', $item->status)) }}

                                </span>

                            </td>

                            <td>

                                <a
                                    href="{{ route('masyarakat.pengajuan.show', $item) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4">

                                Belum ada pengajuan surat.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection