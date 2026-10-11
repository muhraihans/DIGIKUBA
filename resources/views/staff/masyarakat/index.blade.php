@extends('layouts.admin')

@section('title', 'Data Masyarakat')

@section('content')

<h3 class="fw-bold mb-4">
    Data Masyarakat
</h3>

<div class="card content-card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($masyarakat ?? [] as $item)

                        <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">

                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nik }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->user->email }}</td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $item->user->status }}
                                </span>
                            </td>

                            <td>

                                <a
                                    href="{{ route('staff.masyarakat.show', $item) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center">

                                Belum ada data.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection