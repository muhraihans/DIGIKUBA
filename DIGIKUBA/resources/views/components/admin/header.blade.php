<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

<meta
    name="csrf-token"
    content="{{ csrf_token() }}">

<title>
    @yield('title', 'Dashboard') - DIGIKUBA
</title>

<meta
    name="description"
    content="DIGIKUBA - Aplikasi Penerbitan Surat Kelurahan Kutabaru Berbasis Digital">

<link
    rel="icon"
    href="{{ asset('assets/images/logo.png') }}">

<link
    rel="stylesheet"
    href="{{ asset('assets/css/bootstrap.min.css') }}">

<link
    rel="stylesheet"
    href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

<link
    rel="stylesheet"
    href="{{ asset('assets/css/style.css') }}">

<style>

    body {
        background: #f6f8fb;
    }

    .main-content {
        margin-left: 260px;
        padding-bottom: 76px;
        min-height: 100vh;
        transition: margin-left 0.25s ease;
    }

    .sidebar {
        width: 260px;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: #fff;
        border-right: 1px solid #e9ecef;
        transition: width 0.25s ease;
    }

    .sidebar-menu {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
    }

    .sidebar-footer {
        display: flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 0.6rem;
        margin: 0;
        min-height: 56px;
        padding: 0.75rem 1rem;
        color: #6c757d;
        background: #fff;
        border-top: 1px solid #e9ecef;
    }

    .sidebar-footer-icon {
        display: inline-grid;
        flex: 0 0 28px;
        width: 28px;
        height: 28px;
        place-items: center;
        color: #198754;
        background: #e9f7ef;
        border-radius: 50%;
    }

    .sidebar-footer-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    body.sidebar-mini .sidebar {
        width: 88px;
    }

    body.sidebar-mini .sidebar-brand {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        display: flex;
        justify-content: center;
    }

    body.sidebar-mini .sidebar .brand-icon {
        margin-right: 0 !important;
    }

    body.sidebar-mini .sidebar .brand-text,
    body.sidebar-mini .sidebar .menu-label {
        display: none !important;
    }

    body.sidebar-mini .sidebar-footer {
        justify-content: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    body.sidebar-mini .sidebar-footer-text {
        display: none;
    }

    body.sidebar-mini .main-content {
        margin-left: 88px;
    }

    body.sidebar-mini .admin-footer {
        left: 88px;
    }

    body.sidebar-mini .sidebar .nav-link {
        overflow: hidden;
        white-space: nowrap;
        font-size: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    body.sidebar-mini .sidebar .nav-link i {
        margin-right: 0 !important;
        font-size: 1rem;
    }

    body.sidebar-mini .top-navbar {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    body.sidebar-mini .top-navbar .btn {
        min-width: 2.75rem;
    }

    .sidebar .nav-link {
        color: #495057;
        padding: 10px 15px;
        margin: 3px 10px;
        border-radius: 8px;
        transition: padding 0.25s ease, margin 0.25s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background: #f0f4ff;
    }

    .top-navbar {
        position: sticky;
        top: 0;
        z-index: 999;
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        min-height: 70px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .table-sort-button {
        color: inherit;
        white-space: nowrap;
    }

    .table-sort-button:hover,
    .table-sort-button:focus-visible {
        color: #0d6efd;
    }

    .table-sort-icon {
        color: #6c757d;
        font-size: 0.8em;
    }

    .admin-footer {
        position: fixed;
        right: 0;
        bottom: 0;
        left: 260px;
        z-index: 1100;
        display: flex;
        align-items: center;
        min-height: 68px;
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
        background: #fff;
        box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06);
        transition: left 0.25s ease;
    }

    .stat-card {
        border: 0;
        border-radius: 14px;
    }

    .table-card {
        border: 0;
        border-radius: 14px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    @media (max-width: 991px) {

        .main-content {
            margin-left: 0;
        }

        .admin-footer {
            left: 0;
        }

        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }
    }

</style>

@stack('styles')