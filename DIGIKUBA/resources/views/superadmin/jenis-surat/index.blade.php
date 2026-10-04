@extends('layouts.admin')

@section('title', 'Jenis Surat')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h3 class="fw-bold">
        Jenis Surat
    </h3>

    <a
        href="{{ route('superadmin.jenis-surat.create') }}"
        class="btn btn-primary">

        Tambah Jenis Surat

    </a>

</div>

<div class="card content-card">

    <div class="card-body">

        <table class="table table-hover">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($jenisSurat ?? [] as $jenis)

                    <tr data-created-at="{{ $jenis->created_at?->timestamp ?? '' }}">

                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jenis->kode }}</td>
                        <td>{{ $jenis->nama }}</td>
                        <td>{{ $jenis->template }}</td>

                        <td>

                            @if($jenis->status)

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Nonaktif
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('superadmin.jenis-surat.edit', $jenis) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center">

                            Belum ada jenis surat.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection