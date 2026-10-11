@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h3 class="fw-bold">
        Manajemen User
    </h3>

    <a
        href="{{ route('superadmin.users.create') }}"
        class="btn btn-primary">

        <i class="bi bi-person-plus me-1"></i>
        Tambah User

    </a>

</div>

<div class="card content-card">

    <div class="card-body">

        <table class="table table-hover">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($users ?? [] as $user)

                    <tr data-created-at="{{ $user->created_at?->timestamp ?? '' }}">

                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ ucfirst($user->status) }}</td>

                        <td>

                            <a
                                href="{{ route('superadmin.users.show', $user) }}"
                                class="btn btn-sm btn-info">

                                Detail

                            </a>

                            <a
                                href="{{ route('superadmin.users.edit', $user) }}"
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

                            Belum ada user.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection