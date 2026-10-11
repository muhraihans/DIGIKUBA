@extends('layouts.admin')

@section('title', 'Riwayat Surat')

@section('content')

<div class="mb-4">

    <h3 class="fw-bold">
        Riwayat Surat
    </h3>

    <p class="text-muted">
        Riwayat seluruh pengajuan surat Anda.
    </p>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nomor Pengajuan</th>
                        <th>Jenis</th>
                        <th>Nomor Surat</th>
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
                                {{ $item->surat->nomor_surat ?? '-' }}
                            </td>

                            <td>
                                {{ ucwords(str_replace('_', ' ', $item->status)) }}
                            </td>

                            <td>

                                @if($item->surat)

                                    <a
                                        href="{{ route('masyarakat.riwayat.download', $item) }}"
                                        class="btn btn-sm btn-success">

                                        <i class="bi bi-download"></i>

                                    </a>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4 text-muted">

                                Belum ada riwayat surat.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection