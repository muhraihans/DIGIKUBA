@extends('layouts.admin')

@section('title', 'Verifikasi Akun')

@section('content')

<h3 class="fw-bold mb-4">
    Verifikasi Akun Masyarakat
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
                        <th>Tanggal Daftar</th>
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
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('staff.verifikasi-akun.show', $item) }}"
                                    class="btn btn-sm btn-primary">

                                    Periksa

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4">

                                Tidak ada akun yang menunggu verifikasi.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection