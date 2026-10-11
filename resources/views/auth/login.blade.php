<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Login DIGIKUBA - Layanan Surat Online Kelurahan Kutabaru"
    >

    <title>Masuk | DIGIKUBA</title>

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/bootstrap.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/style.css') }}"
    >
</head>

<body class="auth-body">

    {{-- Theme Toggle --}}
    <button
        class="icon-button theme-toggle auth-theme-toggle"
        type="button"
        data-theme-toggle
        aria-label="Ganti tema"
        title="Ganti tema"
    >
        <i
            class="bi bi-moon-stars"
            data-theme-icon
            aria-hidden="true"
        ></i>
    </button>


    <main class="auth-page">

        <section class="auth-card">


            {{-- ========================= --}}
            {{-- BRAND --}}
            {{-- ========================= --}}

            <a
                class="auth-brand"
                href="{{ route('login') }}"
            >

                <span class="brand-icon">

                    <i
                        class="bi bi-building"
                        aria-hidden="true"
                    ></i>

                </span>

                <span>

                    <strong>
                        DIGIKUBA
                    </strong>

                    <small>
                        Layanan Surat Online Kelurahan Kutabaru.
                    </small>

                </span>

            </a>


            {{-- ========================= --}}
            {{-- VISUAL --}}
            {{-- ========================= --}}

            <div class="auth-visual">

                <img
                    src="{{ asset('assets/images/png/dasher-ui-bootstrap-5.jpg') }}"
                    alt="DIGIKUBA - Layanan Surat Online Kelurahan Kutabaru"
                >

            </div>


            {{-- ========================= --}}
            {{-- LOGIN FORM --}}
            {{-- ========================= --}}

            <form
                class="needs-validation"
                action="{{ route('login.process') }}"
                method="POST"
                novalidate
            >

                @csrf


                {{-- Heading --}}

                <div class="mb-4">

                    <p class="eyebrow mb-1">
                        Akses Layanan
                    </p>

                    <h1 class="h3 mb-1">
                        Login
                    </h1>

                    <p class="text-muted mb-0">
                        Masuk ke akun DIGIKUBA Anda.
                    </p>

                </div>


                {{-- ========================= --}}
                {{-- SUCCESS MESSAGE --}}
                {{-- ========================= --}}

                @if (session('success'))

                    <div
                        class="alert alert-success d-flex align-items-start"
                        role="alert"
                    >

                        <i
                            class="bi bi-check-circle me-2"
                            aria-hidden="true"
                        ></i>

                        <div>
                            {{ session('success') }}
                        </div>

                    </div>

                @endif


                {{-- ========================= --}}
                {{-- WARNING MESSAGE --}}
                {{-- ========================= --}}

                @if (session('warning'))

                    <div
                        class="alert alert-warning d-flex align-items-start"
                        role="alert"
                    >

                        <i
                            class="bi bi-exclamation-triangle me-2"
                            aria-hidden="true"
                        ></i>

                        <div>
                            {{ session('warning') }}
                        </div>

                    </div>

                @endif


                {{-- ========================= --}}
                {{-- ERROR MESSAGE --}}
                {{-- ========================= --}}

                @if (session('error'))

                    <div
                        class="alert alert-danger d-flex align-items-start"
                        role="alert"
                    >

                        <i
                            class="bi bi-exclamation-circle me-2"
                            aria-hidden="true"
                        ></i>

                        <div>
                            {{ session('error') }}
                        </div>

                    </div>

                @endif


                @if ($errors->any())

                    <div
                        class="alert alert-danger d-flex align-items-start"
                        role="alert"
                    >

                        <i
                            class="bi bi-exclamation-circle me-2"
                            aria-hidden="true"
                        ></i>

                        <div>

                            <strong>
                                Login gagal.
                            </strong>

                            <ul class="mb-0 mt-1 ps-3">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                @endif


                {{-- ========================= --}}
                {{-- EMAIL --}}
                {{-- ========================= --}}

                <div class="mb-3">

                    <label
                        class="form-label"
                        for="loginEmail"
                    >
                        Email address
                    </label>

                    <input
                        class="form-control @error('email') is-invalid @enderror"
                        id="loginEmail"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        placeholder="nama@email.com"
                        required
                    >

                    @error('email')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @else

                        <div class="invalid-feedback">
                            Masukkan alamat email yang valid.
                        </div>

                    @enderror

                </div>


                {{-- ========================= --}}
                {{-- PASSWORD --}}
                {{-- ========================= --}}

                <div class="mb-3">

                    <div class="d-flex justify-content-between">

                        <label
                            class="form-label"
                            for="loginPassword"
                        >
                            Password
                        </label>

                        {{-- Untuk sementara belum menggunakan
                             fitur lupa password melalui email --}}
                        <a
                            class="small fw-semibold"
                            href="{{ route('password.edit') }}"
                        >
                            Ubah Password
                        </a>

                    </div>


                    <div class="position-relative">

                        <input
                            class="form-control @error('password') is-invalid @enderror"
                            id="loginPassword"
                            name="password"
                            type="password"
                            minlength="8"
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            required
                        >

                    </div>


                    @error('password')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @else

                        <div class="invalid-feedback">
                            Password wajib diisi.
                        </div>

                    @enderror

                </div>


                {{-- ========================= --}}
                {{-- REMEMBER ME --}}
                {{-- ========================= --}}

                <div class="form-check mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="rememberMe"
                        name="remember"
                        value="1"
                        {{ old('remember') ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="rememberMe"
                    >
                        Ingat saya
                    </label>

                </div>


                {{-- ========================= --}}
                {{-- LOGIN BUTTON --}}
                {{-- ========================= --}}

                <button
                    class="btn btn-primary w-100"
                    type="submit"
                >

                    <i
                        class="bi bi-box-arrow-in-right"
                        aria-hidden="true"
                    ></i>

                    Masuk

                </button>

            </form>


            {{-- ========================= --}}
            {{-- REGISTER --}}
            {{-- ========================= --}}

            <div class="auth-footer">

                Belum memiliki akun?

                <a href="{{ route('register') }}">
                    Daftar sebagai Masyarakat
                </a>

            </div>

        </section>

    </main>


    {{-- ========================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================= --}}

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>


    {{-- Bootstrap Form Validation --}}

    <script>

        (() => {

            'use strict';

            const forms =
                document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(form => {

                form.addEventListener(
                    'submit',
                    event => {

                        if (!form.checkValidity()) {

                            event.preventDefault();
                            event.stopPropagation();

                        }

                        form.classList.add('was-validated');

                    },
                    false
                );

            });

        })();

    </script>

</body>

</html>