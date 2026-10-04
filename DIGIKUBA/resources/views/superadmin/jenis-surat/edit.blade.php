@extends('layouts.admin')

@section('title', 'Edit Jenis Surat')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4 class="mb-4">
            Edit Jenis Surat
        </h4>

        <form
            action="{{ route('superadmin.jenis-surat.update', $jenisSurat) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">
                    Kode
                </label>

                <input
                    name="kode"
                    class="form-control"
                    value="{{ old('kode', $jenisSurat->kode) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Nama
                </label>

                <input
                    name="nama"
                    class="form-control"
                    value="{{ old('nama', $jenisSurat->nama) }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    class="form-control"
                    rows="4">{{ old('deskripsi', $jenisSurat->deskripsi) }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Template
                </label>

                <input
                    name="template"
                    class="form-control"
                    value="{{ old('template', $jenisSurat->template) }}"
                    required>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Status
                </label>

                <select
                    name="status"
                    class="form-select">

                    <option
                        value="1"
                        @selected($jenisSurat->status)>
                        Aktif
                    </option>

                    <option
                        value="0"
                        @selected(!$jenisSurat->status)>
                        Nonaktif
                    </option>

                </select>

            </div>

            <button class="btn btn-primary">
                Simpan Perubahan
            </button>

        </form>

    </div>

</div>

@endsection