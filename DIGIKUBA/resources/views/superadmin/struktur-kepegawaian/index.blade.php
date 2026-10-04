@extends('layouts.admin')

@section('title', 'Struktur Kepegawaian')

@section('content')

<div class="pagetitle">

    <h1>
        Struktur Kepegawaian
    </h1>

    <nav>
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                Superadmin
            </li>

            <li class="breadcrumb-item active">
                Struktur Kepegawaian
            </li>

        </ol>
    </nav>

</div>


<section class="section">

<div class="card">

<div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h5 class="card-title mb-0">
            Daftar Struktur Kepegawaian
        </h5>

        <a
            href="{{ route(
                'superadmin.struktur-kepegawaian.create'
            ) }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle"></i>
            Tambah Pegawai
        </a>

    </div>


    <div class="table-responsive">

        <table class="table table-hover">

            <thead>

                <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">

                    <th>
                        #
                    </th>

                    <th>
                        Nama
                    </th>

                    <th>
                        Jabatan
                    </th>

                    <th>
                        NIP
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

            @forelse($pegawai as $item)

                <tr>

                    <td>
                        {{ $pegawai->firstItem() + $loop->index }}
                    </td>

                    <td>
                        {{ $item->nama }}
                    </td>

                    <td>
                        {{ $item->jabatan }}
                    </td>

                    <td>
                        {{ $item->nip ?: '-' }}
                    </td>

                    <td>

                        @if($item->status)

                            <span class="badge bg-success">
                                Aktif
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Tidak Aktif
                            </span>

                        @endif

                    </td>

                    <td>

                        <a
                            href="{{ route(
                                'superadmin.struktur-kepegawaian.edit',
                                $item
                            ) }}"
                            class="btn btn-sm btn-warning"
                        >
                            <i class="bi bi-pencil"></i>
                        </a>


                        @if($item->status)

                            <form
                                action="{{ route(
                                    'superadmin.struktur-kepegawaian.destroy',
                                    $item
                                ) }}"
                                method="POST"
                                class="d-inline"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm(
                                        'Nonaktifkan pegawai ini?'
                                    )"
                                >
                                    <i class="bi bi-person-x"></i>
                                </button>

                            </form>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="text-center py-4"
                    >
                        Belum ada data pegawai.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{ $pegawai->links() }}

</div>

</div>

</section>

@endsection