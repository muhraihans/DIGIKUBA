@extends('layouts.admin')

@section('title', 'Backup')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <div>

        <h3 class="fw-bold">
            Backup Database
        </h3>

        <p class="text-muted">
            Data backup tahunan DIGIKUBA.
        </p>

    </div>

    <form
        action="{{ route('superadmin.backup.create') }}"
        method="POST">

        @csrf

        <button class="btn btn-primary">

            <i class="bi bi-database-add me-1"></i>
            Buat Backup

        </button>

    </form>

</div>

<div class="card content-card">

    <div class="card-body">

        <table class="table table-hover">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Tahun</th>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($backups ?? [] as $backup)

                    <tr data-created-at="{{ $backup->created_at?->timestamp ?? '' }}">

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $backup->tahun }}</td>

                        <td>{{ $backup->nama_file }}</td>

                        <td>
                            {{ number_format($backup->ukuran / 1024, 2) }}
                            KB
                        </td>

                        <td>
                            {{ $backup->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td>

                            <a
                                href="{{ route('superadmin.backup.download', $backup) }}"
                                class="btn btn-sm btn-success">

                                Download

                            </a>

                            <form
                                action="{{ route('superadmin.backup.destroy', $backup) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus backup ini?')">

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center">

                            Belum ada backup.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection