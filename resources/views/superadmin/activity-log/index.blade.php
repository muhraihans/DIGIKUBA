@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')

<h3 class="fw-bold mb-4">
    Activity Log
</h3>

<div class="card content-card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                        <th>Waktu</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($logs ?? [] as $log)

                        <tr data-created-at="{{ $log->created_at?->timestamp ?? '' }}">

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $log->user->name ?? 'System' }}
                            </td>

                            <td>
                                {{ $log->activity }}
                            </td>

                            <td>
                                {{ $log->description }}
                            </td>

                            <td>
                                {{ $log->ip_address }}
                            </td>

                            <td>
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center">

                                Belum ada aktivitas.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection