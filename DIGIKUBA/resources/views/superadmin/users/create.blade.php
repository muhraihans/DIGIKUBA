@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4 class="mb-4">
            Tambah User
        </h4>

        <form
            action="{{ route('superadmin.users.store') }}"
            method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Nama
                </label>

                <input
                    name="name"
                    class="form-control"
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
                    required>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Role
                </label>

                <select
                    name="role"
                    class="form-select"
                    required>

                    <option value="superadmin">
                        Superadmin
                    </option>

                    <option value="staff">
                        Staff
                    </option>

                    <option value="lurah">
                        Lurah
                    </option>

                    <option value="masyarakat">
                        Masyarakat
                    </option>

                </select>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select"
                    required>

                    <option value="active">
                        Active
                    </option>

                    <option value="pending">
                        Pending
                    </option>

                    <option value="inactive">
                        Inactive
                    </option>

                    <option value="rejected">
                        Rejected
                    </option>

                </select>

            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>

</div>

@endsection