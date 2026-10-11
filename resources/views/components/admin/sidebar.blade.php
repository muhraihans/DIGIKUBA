```blade
@php
    $user = auth()->user();
@endphp

<aside class="sidebar">

    {{-- ==========================================================
         LOGO / BRAND
    =========================================================== --}}

    <div class="sidebar-brand p-4 border-bottom">

        <a
            href="{{ url('/') }}"
            class="text-decoration-none">

            <div class="d-flex align-items-center gap-2">

                <div
                    class="brand-icon bg-primary text-white rounded-3 p-2">

                    <i class="bi bi-file-earmark-text fs-4"></i>

                </div>

                <div class="brand-text">

                    <h5 class="mb-0 fw-bold">
                        DIGIKUBA
                    </h5>

                    <small class="text-muted">
                        Kelurahan Kutabaru
                    </small>

                </div>

            </div>

        </a>

    </div>


    {{-- ==========================================================
         MENU
    =========================================================== --}}

    <div class="sidebar-menu p-3">

        <small class="menu-label text-uppercase text-muted px-3">
            Menu
        </small>


        <ul class="nav flex-column mt-2">


            {{-- ==================================================
                 SUPERADMIN
            =================================================== --}}

            @if($user && $user->isSuperadmin())


            {{-- DASHBOARD --}}

            <li class="nav-item">

                <a
                    href="{{ route('superadmin.dashboard') }}"
                    class="nav-link
                        {{ request()->routeIs('superadmin.dashboard')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-speedometer2 me-2"></i>

                    Dashboard

                </a>

            </li>


            {{-- MANAJEMEN USER --}}

            <li class="nav-item">

                <a
                    href="{{ route('superadmin.users.index') }}"
                    class="nav-link
                        {{ request()->routeIs('superadmin.users.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-people me-2"></i>

                    Manajemen User

                </a>

            </li>

            @php
            $superadminStrukturActive = request()->routeIs(
            'superadmin.struktur-kepegawaian.*'
            );
            @endphp

            <li class="nav-item">

                <a
                    class="nav-link {{ $superadminStrukturActive ? '' : 'collapsed' }}"
                    href="{{ route(
            'superadmin.struktur-kepegawaian.index'
        ) }}">

                    <i class="bi bi-diagram-3"></i>

                    <span>
                        Struktur Kepegawaian
                    </span>

                </a>

            </li>


            {{-- JENIS SURAT --}}

            <li class="nav-item">

                <a
                    href="{{ route('superadmin.jenis-surat.index') }}"
                    class="nav-link
                        {{ request()->routeIs('superadmin.jenis-surat.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-file-earmark-text me-2"></i>

                    Jenis Surat

                </a>

            </li>


            {{-- ACTIVITY LOG --}}

            <li class="nav-item">

                <a
                    href="{{ route('superadmin.activity-log.index') }}"
                    class="nav-link
                        {{ request()->routeIs('superadmin.activity-log.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-clock-history me-2"></i>

                    Activity Log

                </a>

            </li>


            {{-- BACKUP --}}

            <li class="nav-item">

                <a
                    href="{{ route('superadmin.backup.index') }}"
                    class="nav-link
                        {{ request()->routeIs('superadmin.backup.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-database me-2"></i>

                    Backup

                </a>

            </li>


            @endif



            {{-- ==================================================
                 STAFF
            =================================================== --}}

            @if($user && $user->isStaff())


            {{-- DASHBOARD --}}

            <li class="nav-item">

                <a
                    href="{{ route('staff.dashboard') }}"
                    class="nav-link
                        {{ request()->routeIs('staff.dashboard')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-speedometer2 me-2"></i>

                    Dashboard

                </a>

            </li>


            {{-- VERIFIKASI AKUN --}}

            <li class="nav-item">

                <a
                    href="{{ route('staff.verifikasi-akun.index') }}"
                    class="nav-link
                        {{ request()->routeIs('staff.verifikasi-akun.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-person-check me-2"></i>

                    Verifikasi Akun

                </a>

            </li>


            {{-- DATA MASYARAKAT --}}

            <li class="nav-item">

                <a
                    href="{{ route('staff.masyarakat.index') }}"
                    class="nav-link
                        {{ request()->routeIs('staff.masyarakat.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-people me-2"></i>

                    Data Masyarakat

                </a>

            </li>


            {{-- PENGAJUAN SURAT --}}

            <li class="nav-item">

                <a
                    href="{{ route('staff.pengajuan.index') }}"
                    class="nav-link
                        {{ request()->routeIs('staff.pengajuan.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-file-earmark-check me-2"></i>

                    Pengajuan Surat

                </a>

            </li>


            @endif



            {{-- ==================================================
                 LURAH
            =================================================== --}}

            @if($user && $user->isLurah())


            {{-- DASHBOARD --}}

            <li class="nav-item">

                <a
                    href="{{ route('lurah.dashboard') }}"
                    class="nav-link
                        {{ request()->routeIs('lurah.dashboard')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-speedometer2 me-2"></i>

                    Dashboard

                </a>

            </li>


            {{-- TANDA TANGAN --}}

            <li class="nav-item">

                <a
                    href="{{ route('lurah.tanda-tangan.index') }}"
                    class="nav-link
                        {{ request()->routeIs('lurah.tanda-tangan.index')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-pen me-2"></i>

                    Tanda Tangan

                </a>

            </li>


            {{-- SURAT DITANDATANGANI --}}

            <li class="nav-item">

                <a
                    href="{{ route('lurah.tanda-tangan.signed') }}"
                    class="nav-link
                        {{ request()->routeIs('lurah.tanda-tangan.signed')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-file-earmark-check me-2"></i>

                    Surat Ditandatangani

                </a>

            </li>


            @endif



            {{-- ==================================================
                 MASYARAKAT
            =================================================== --}}

            @if($user && $user->isMasyarakat())


            {{-- DASHBOARD --}}

            <li class="nav-item">

                <a
                    href="{{ route('masyarakat.dashboard') }}"
                    class="nav-link
                        {{ request()->routeIs('masyarakat.dashboard')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-speedometer2 me-2"></i>

                    Dashboard

                </a>

            </li>


            {{-- MENU HANYA UNTUK AKUN ACTIVE --}}

            @if($user && $user->isActive())


            {{-- PENGAJUAN SURAT --}}

            <li class="nav-item">

                <a
                    href="{{ route('masyarakat.pengajuan.index') }}"
                    class="nav-link
                            {{ request()->routeIs('masyarakat.pengajuan.*')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-file-earmark-plus me-2"></i>

                    Pengajuan Surat

                </a>

            </li>


            {{-- RIWAYAT SURAT --}}

            <li class="nav-item">

                <a
                    href="{{ route('masyarakat.riwayat.index') }}"
                    class="nav-link
                            {{ request()->routeIs('masyarakat.riwayat.*')
                                ? 'active'
                                : '' }}">

                    <i class="bi bi-clock-history me-2"></i>

                    Riwayat Surat

                </a>

            </li>


            @endif

            {{-- PROFIL --}}

            <li class="nav-item">

                <a
                    href="{{ route('masyarakat.profile.index') }}"
                    class="nav-link
                        {{ request()->routeIs('masyarakat.profile.*')
                            ? 'active'
                            : '' }}">

                    <i class="bi bi-person me-2"></i>

                    Profil

                </a>

            </li>


            @endif



            {{-- ==================================================
                 NOTIFIKASI
            =================================================== --}}

            <li class="nav-item">

                <a
                    href="{{ route('notifications.index') }}"
                    class="nav-link
                    {{ request()->routeIs('notifications.*')
                        ? 'active'
                        : '' }}">

                    <i class="bi bi-bell me-2"></i>

                    Notifikasi

                </a>

            </li>


        </ul>

    </div>

    <div class="sidebar-footer">
        <span class="sidebar-footer-icon" title="DIGIKUBA · Pelayanan Digital" aria-label="DIGIKUBA · Pelayanan Digital">
            <i class="bi bi-shield-check"></i>
        </span>
        <small class="sidebar-footer-text">DIGIKUBA · Pelayanan Digital</small>
    </div>

</aside>
```