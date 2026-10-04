@extends('layouts.admin')

@section('title', 'Tambah Jenis Surat')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4 class="mb-4">
            Tambah Jenis Surat
        </h4>

        <form
            action="{{ route('superadmin.jenis-surat.store') }}"
            method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Kode
                </label>

                <input
                    name="kode"
                    class="form-control"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Nama Surat
                </label>

                <input
                    name="nama"
                    class="form-control"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    class="form-control"
                    rows="4"></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Template
                </label>

                <input
                    name="template"
                    class="form-control"
                    placeholder="contoh: sktm"
                    required>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select">

                    <option value="1">
                        Aktif
                    </option>

                    <option value="0">
                        Nonaktif
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