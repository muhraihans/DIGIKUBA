@extends('layouts.admin')

@section('title', 'Pengajuan Surat')

@section('content')

<h3 class="fw-bold mb-4">
    Pengajuan Surat
</h3>

<div class="card content-card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nomor</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($pengajuan ?? [] as $item)

                        <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $item->nomor_pengajuan }}
                            </td>

                            <td>
                                {{ $item->masyarakat->nama_lengkap }}
                            </td>

                            <td>
                                {{ $item->jenisSurat->nama }}
                            </td>

                            <td>
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('staff.pengajuan.show', $item) }}"
                                    class="btn btn-sm btn-primary">

                                    Periksa

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-4">

                                Tidak ada pengajuan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection