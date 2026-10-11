<nav class="navbar top-navbar px-3">

    <div class="container-fluid">

        <button
            class="btn btn-light"
            id="sidebarToggle"
            data-sidebar-toggle
            aria-label="Toggle sidebar"
            aria-expanded="true">

            <i class="bi bi-list fs-4"></i>

        </button>

        <div class="ms-auto d-flex align-items-center gap-3">

            @php
                $user = auth()->user();
                $unreadNotifications = $user ? $user->unreadNotifications()->count() : 0;
            @endphp

            {{-- NOTIFICATION --}}

            @if($user)
                <div class="dropdown">

                    <button
                        class="btn btn-light position-relative"
                        data-bs-toggle="dropdown">

                        <i class="bi bi-bell fs-5"></i>

                        @if($unreadNotifications > 0)

                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                                {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}

                            </span>

                        @endif

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow">

                        <li>
                            <h6 class="dropdown-header">
                                Notifikasi
                            </h6>
                        </li>

                        @forelse(
                            $user->notifications()
                                ->latest()
                                ->take(5)
                                ->get()
                            as $notification
                        )

                        <li>

                            <a
                                href="{{ $notification->data['url'] ?? route('notifications.index') }}"
                                class="dropdown-item">

                                <strong>
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    {{ $notification->data['message'] ?? '' }}
                                </small>

                            </a>

                        </li>

                    @empty

                        <li>
                            <span class="dropdown-item text-muted">
                                Belum ada notifikasi.
                            </span>
                        </li>

                    @endforelse

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a
                                href="{{ route('notifications.index') }}"
                                class="dropdown-item text-center">

                                Lihat Semua Notifikasi

                            </a>
                        </li>

                    </ul>

                </div>
            @endif

            {{-- USER --}}

            @if($user)
                <div class="dropdown">

                    <button
                        class="btn btn-light d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown">

                        <img
                            src="{{ asset('assets/images/avatar/avatar.jpg') }}"
                            class="avatar"
                            alt="Avatar">

                        <span class="d-none d-md-inline">

                            {{ $user->name }}

                        </span>

                        <i class="bi bi-chevron-down"></i>

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow">

                        <li>
                            <span class="dropdown-item-text">

                                <strong>
                                    {{ $user->name }}
                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ ucfirst($user->role) }}

                                </small>

                            </span>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a
                                href="{{ route('password.edit') }}"
                                class="dropdown-item">

                                <i class="bi bi-key me-2"></i>
                                Ubah Password

                            </a>
                        </li>

                        <li>

                            <form
                                action="{{ route('logout') }}"
                                method="POST">

                                @csrf

                                <button
                                    class="dropdown-item text-danger">

                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>
            @endif

        </div>

    </div>

</nav>