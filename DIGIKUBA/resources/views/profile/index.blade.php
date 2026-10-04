@extends('layouts.admin')

@section('title', 'Profil')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4>
            Profil Akun
        </h4>

        <table class="table mt-4">

            <tr>

                <th width="200">
                    Nama
                </th>

                <td>
                    {{ auth()->user()->name }}
                </td>

            </tr>

            <tr>

                <th>
                    Email
                </th>

                <td>
                    {{ auth()->user()->email }}
                </td>

            </tr>

            <tr>

                <th>
                    Role
                </th>

                <td>
                    {{ ucfirst(auth()->user()->role) }}
                </td>

            </tr>

            <tr>

                <th>
                    Status
                </th>

                <td>
                    {{ ucfirst(auth()->user()->status) }}
                </td>

            </tr>

        </table>

        <a
            href="{{ route('password.edit') }}"
            class="btn btn-primary">

            Ubah Password

        </a>

    </div>

</div>

@endsection