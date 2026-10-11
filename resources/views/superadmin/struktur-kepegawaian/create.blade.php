@extends('layouts.admin')

@section('title', 'Struktur Kepegawaian')

@section('content')

<div class="pagetitle">

    <h1>
        Tambah Struktur Kepegawaian
    </h1>

</div>


<section class="section">

<div class="card">

<div class="card-body">

    <h5 class="card-title">
        Data Pegawai
    </h5>


    <form
        action="{{ route(
            'superadmin.struktur-kepegawaian.store'
        ) }}"
        method="POST"
    >

        @csrf


        <div class="mb-3">

            <label class="form-label">
                Nama Lengkap
            </label>

            <input
                type="text"
                name="nama"
                class="form-control"
                value="{{ old('nama') }}"
                required
            >

        </div>


        <div class="mb-3">

            <label class="form-label">
                Jabatan
            </label>

            <select
                name="jabatan"
                class="form-select"
                required
            >

                <option value="">
                    Pilih Jabatan
                </option>

                @foreach($jabatan as $item)

                    <option
                        value="{{ $item }}"
                    >
                        {{ $item }}
                    </option>

                @endforeach

            </select>

        </div>


        <div class="mb-3">

            <label class="form-label">
                NIP
            </label>

            <input
                type="text"
                name="nip"
                class="form-control"
                value="{{ old('nip') }}"
            >

        </div>


        <button
            class="btn btn-primary"
        >
            <i class="bi bi-save"></i>
            Simpan
        </button>


        <a
            href="{{ route(
                'superadmin.struktur-kepegawaian.index'
            ) }}"
            class="btn btn-secondary"
        >
            Kembali
        </a>

    </form>

</div>

</div>

</section>

@endsection