@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')

<div class="card content-card">

    <div class="card-body">

        <h4 class="mb-4">
            Detail User
        </h4>

        <table class="table">

            <tr>
                <th width="200">Nama</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>

            <tr>
                <th>Role</th>
                <td>{{ ucfirst($user->role) }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>{{ ucfirst($user->status) }}</td>
            </tr>

            <tr>
                <th>Dibuat</th>
                <td>{{ $user->created_at->format('d F Y H:i') }}</td>
            </tr>

        </table>

    </div>

</div>

@endsection