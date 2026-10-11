@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h3 class="fw-bold">
        Notifikasi
    </h3>

    <form
        action="{{ route('notifications.read-all') }}"
        method="POST">

        @csrf

        <button class="btn btn-outline-primary">

            Tandai Semua Dibaca

        </button>

    </form>

</div>

<div class="card content-card">

    <div class="card-body">

        @forelse(auth()->user()->notifications as $notification)

            <div
                class="p-3 border-bottom
                {{ is_null($notification->read_at) ? 'bg-light' : '' }}">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="fw-bold">

                            {{ $notification->data['title'] ?? 'Notifikasi' }}

                        </h6>

                        <p class="mb-1">

                            {{ $notification->data['message'] ?? '' }}

                        </p>

                        <small class="text-muted">

                            {{ $notification->created_at->diffForHumans() }}

                        </small>

                    </div>

                    @if(is_null($notification->read_at))

                        <form
                            action="{{ route('notifications.read', $notification->id) }}"
                            method="POST">

                            @csrf

                            <button
                                class="btn btn-sm btn-outline-primary">

                                Tandai Dibaca

                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @empty

            <div class="text-center text-muted py-5">

                <i class="bi bi-bell-slash fs-1"></i>

                <p class="mt-3">
                    Belum ada notifikasi.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection