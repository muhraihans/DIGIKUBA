@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4 class="mb-4">
            Edit User
        </h4>

        <form
            action="{{ route('superadmin.users.update', $user) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">
                    Nama
                </label>

                <input
                    name="name"
                    class="form-control"
                    value="{{ old('name', $user->name) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $user->email) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti password.
                </small>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Role
                </label>

                <select
                    name="role"
                    class="form-select">

                    @foreach(['superadmin','staff','lurah','masyarakat'] as $role)

                        <option
                            value="{{ $role }}"
                            @selected($user->role === $role)>

                            {{ ucfirst($role) }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select">

                    @foreach(['pending','active','inactive','rejected'] as $status)

                        <option
                            value="{{ $status }}"
                            @selected($user->status === $status)>

                            {{ ucfirst($status) }}

                        </option>

                    @endforeach

                </select>

            </div>

            <button class="btn btn-primary">
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

@endsection