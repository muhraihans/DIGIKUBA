<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Registrasi - DIGIKUBA</title>

    <meta content="Aplikasi Digital Kelurahan Kutabaru" name="description">

    <!-- Bootstrap -->
    <link
        href="{{ asset('assets/css/bootstrap.min.css') }}"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link
        href="{{ asset('assets/css/style.css') }}"
        rel="stylesheet">

    <style>
        body {
            background: #f6f9ff;
        }

        .register-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        }

        .register-logo {
            font-size: 28px;
            font-weight: 700;
            color: #012970;
            text-decoration: none;
        }

        .register-logo span {
            color: #4154f1;
        }

        .form-label {
            font-weight: 600;
            color: #444;
        }

        .required {
            color: #dc3545;
        }

        .section-title {
            color: #012970;
            font-weight: 700;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* =========================
           CAMERA
        ========================= */

        .camera-container {
            width: 100%;
            background: #111;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        #camera {
            display: block;
            width: 100%;
            height: 350px;
            object-fit: cover;
            background: #111;
        }

        .camera-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #fff;
            background: #222;
            z-index: 2;
            text-align: center;
            padding: 20px;
        }

        .camera-placeholder i {
            font-size: 60px;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .camera-guide {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 180px;
            height: 230px;
            transform: translate(-50%, -50%);
            border: 2px solid rgba(255, 255, 255, 0.85);
            border-radius: 50%;
            pointer-events: none;
            z-index: 3;
        }

        .camera-guide-text {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            color: #fff;
            font-size: 13px;
            text-shadow: 0 1px 3px #000;
            pointer-events: none;
            z-index: 4;
        }

        .camera-status {
            font-size: 13px;
            margin-top: 8px;
        }

        /* =========================
           SELFIE PREVIEW
        ========================= */

        #photoPreview {
            display: none;
            margin-top: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        #selfiePreview {
            display: block;
            width: 100%;
            max-height: 350px;
            object-fit: contain;
            background: #111;
            border-radius: 8px;
        }

        /* =========================
           CAMERA BUTTON
        ========================= */

        .camera-buttons {
            margin-top: 12px;
        }

        /* =========================
           PASSWORD
        ========================= */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            border: 0;
            background: transparent;
            color: #6c757d;
            cursor: pointer;
        }

        /* =========================
           TERMS
        ========================= */

        .terms-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px 15px;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 768px) {

            #camera {
                height: 300px;
            }

            .camera-guide {
                width: 150px;
                height: 200px;
            }

            .register-card .card-body {
                padding: 25px 18px !important;
            }
        }
    </style>
</head>

<body>

    <main>

        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">

                <!-- =========================
                 LOGO
            ========================== -->

                <div class="d-flex justify-content-center py-4">

                    <a
                        href="{{ url('/') }}"
                        class="register-logo d-flex align-items-center">

                        <i class="bi bi-building me-2"></i>

                        DIGI<span>KUBA</span>

                    </a>

                </div>


                <!-- =========================
                 REGISTER CARD
            ========================== -->

                <div class="col-lg-9 col-md-11">

                    <div class="card register-card">

                        <div class="card-body p-4 p-md-5">

                            <!-- HEADER -->

                            <div class="text-center mb-4">

                                <h3 class="fw-bold">
                                    Registrasi Akun
                                </h3>

                                <p class="text-muted mb-0">
                                    Silakan lengkapi data diri Anda
                                    untuk membuat akun DIGIKUBA.
                                </p>

                            </div>


                            <!-- =========================
                             SUCCESS ALERT
                        ========================== -->

                            @if(session('success'))

                            <div
                                class="alert alert-success alert-dismissible fade show"
                                role="alert">

                                <i class="bi bi-check-circle me-2"></i>

                                {{ session('success') }}

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert">
                                </button>

                            </div>

                            @endif


                            <!-- =========================
                             ERROR ALERT
                        ========================== -->

                            @if(session('error'))

                            <div
                                class="alert alert-danger alert-dismissible fade show"
                                role="alert">

                                <i class="bi bi-exclamation-triangle me-2"></i>

                                {{ session('error') }}

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert">
                                </button>

                            </div>

                            @endif


                            <!-- =========================
                             VALIDATION ERROR
                        ========================== -->

                            @if($errors->any())

                            <div
                                class="alert alert-danger"
                                role="alert">

                                <div class="fw-bold mb-2">
                                    Terdapat kesalahan pada data:
                                </div>

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                    @endforeach

                                </ul>

                            </div>

                            @endif


                            <!-- =========================
                             REGISTER FORM
                        ========================== -->

                            <form
                                id="registerForm"
                                action="{{ route('register.process') }}"
                                method="POST"
                                enctype="multipart/form-data">

                                @csrf


                                <div class="row g-3">


                                    <!-- =========================
                                     DATA DIRI
                                ========================== -->

                                    <div class="col-12">

                                        <h5 class="section-title">
                                            <i class="bi bi-person-vcard me-2"></i>
                                            Data Diri
                                        </h5>

                                    </div>


                                    <!-- NIK -->

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            NIK
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="nik"
                                            class="form-control @error('nik') is-invalid @enderror"
                                            value="{{ old('nik') }}"
                                            maxlength="16"
                                            minlength="16"
                                            inputmode="numeric"
                                            pattern="[0-9]{16}"
                                            required>

                                        @error('nik')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                        <small class="text-muted">
                                            NIK harus terdiri dari 16 digit.
                                        </small>

                                    </div>


                                    <!-- NAMA -->

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Nama Lengkap
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="nama_lengkap"
                                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                                            value="{{ old('nama_lengkap') }}"
                                            required>

                                        @error('nama_lengkap')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- JENIS KELAMIN -->

                                    <div class="col-md-6">

                                        <label
                                            for="jenis_kelamin"
                                            class="form-label">

                                            Jenis Kelamin
                                            <span class="required">*</span>

                                        </label>

                                        <select
                                            name="jenis_kelamin"
                                            id="jenis_kelamin"
                                            class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                            required>

                                            <option value="">
                                                -- Pilih Jenis Kelamin --
                                            </option>

                                            @foreach(($jenisKelamin ?? []) as $item)

                                            <option
                                                value="{{ $item }}"
                                                {{ old('jenis_kelamin') == $item ? 'selected' : '' }}>

                                                {{ $item }}

                                            </option>

                                            @endforeach

                                        </select>

                                        @error('jenis_kelamin')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- TEMPAT LAHIR -->

                                    <div class="col-md-3">

                                        <label class="form-label">

                                            Tempat Lahir
                                            <span class="required">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="tempat_lahir"
                                            class="form-control @error('tempat_lahir') is-invalid @enderror"
                                            value="{{ old('tempat_lahir') }}"
                                            required>

                                        @error('tempat_lahir')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- TANGGAL LAHIR -->

                                    <div class="col-md-3">

                                        <label class="form-label">

                                            Tanggal Lahir
                                            <span class="required">*</span>

                                        </label>

                                        <input
                                            type="date"
                                            name="tanggal_lahir"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            value="{{ old('tanggal_lahir') }}"
                                            required>

                                        @error('tanggal_lahir')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- KEWARGANEGARAAN -->

                                    <div class="col-md-6">

                                        <label
                                            for="kewarganegaraan"
                                            class="form-label">

                                            Kewarganegaraan
                                            <span class="required">*</span>

                                        </label>

                                        <select
                                            name="kewarganegaraan"
                                            id="kewarganegaraan"
                                            class="form-select @error('kewarganegaraan') is-invalid @enderror"
                                            required>

                                            <option value="">
                                                -- Pilih Kewarganegaraan --
                                            </option>

                                            @foreach(($negara ?? []) as $item)

                                            <option
                                                value="{{ $item }}"
                                                {{ old('kewarganegaraan') == $item ? 'selected' : '' }}>

                                                {{ $item }}

                                            </option>

                                            @endforeach

                                        </select>

                                        @error('kewarganegaraan')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- STATUS PERKAWINAN -->

                                    <div class="col-md-6">

                                        <label
                                            for="status_perkawinan"
                                            class="form-label">

                                            Status Perkawinan
                                            <span class="required">*</span>

                                        </label>

                                        <select
                                            name="status_perkawinan"
                                            id="status_perkawinan"
                                            class="form-select @error('status_perkawinan') is-invalid @enderror"
                                            required>

                                            <option value="">
                                                -- Pilih Status Perkawinan --
                                            </option>

                                            @foreach(($statusPerkawinan ?? []) as $item)

                                            <option
                                                value="{{ $item }}"
                                                {{ old('status_perkawinan') == $item ? 'selected' : '' }}>

                                                {{ $item }}

                                            </option>

                                            @endforeach

                                        </select>

                                        @error('status_perkawinan')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- AGAMA -->

                                    <div class="col-md-6">

                                        <label
                                            for="agama"
                                            class="form-label">

                                            Agama
                                            <span class="required">*</span>

                                        </label>

                                        <select
                                            name="agama"
                                            id="agama"
                                            class="form-select @error('agama') is-invalid @enderror"
                                            required>

                                            <option value="">
                                                -- Pilih Agama --
                                            </option>

                                            @foreach(($agama ?? []) as $item)

                                            <option
                                                value="{{ $item }}"
                                                {{ old('agama') == $item ? 'selected' : '' }}>

                                                {{ $item }}

                                            </option>

                                            @endforeach

                                        </select>

                                        @error('agama')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- PEKERJAAN -->

                                    <div class="col-md-6">

                                        <label
                                            for="pekerjaan"
                                            class="form-label">

                                            Pekerjaan
                                            <span class="required">*</span>

                                        </label>

                                        <select
                                            name="pekerjaan"
                                            id="pekerjaan"
                                            class="form-select @error('pekerjaan') is-invalid @enderror"
                                            required>

                                            <option value="">
                                                -- Pilih Pekerjaan --
                                            </option>

                                            @foreach(($pekerjaan ?? []) as $item)

                                            <option
                                                value="{{ $item }}"
                                                {{ old('pekerjaan') == $item ? 'selected' : '' }}>

                                                {{ $item }}

                                            </option>

                                            @endforeach

                                        </select>

                                        @error('pekerjaan')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- PEKERJAAN LAINNYA -->

                                    <div
                                        class="col-md-6"
                                        id="pekerjaan-lainnya-wrapper"
                                        style="display: none;">

                                        <label class="form-label">

                                            Sebutkan Pekerjaan

                                            <span class="required">*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="pekerjaan_lainnya"
                                            id="pekerjaan_lainnya"
                                            class="form-control"
                                            value="{{ old('pekerjaan_lainnya') }}">

                                    </div>


                                    <!-- ALAMAT -->

                                    <div class="col-12">

                                        <label class="form-label">

                                            Alamat Lengkap Sesuai KTP

                                            <span class="required">*</span>

                                        </label>

                                        <textarea
                                            name="alamat"
                                            class="form-control @error('alamat') is-invalid @enderror"
                                            rows="3"
                                            required>{{ old('alamat') }}</textarea>

                                        @error('alamat')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- =========================
                                     AKUN
                                ========================== -->

                                    <div class="col-12 mt-4">

                                        <h5 class="section-title">
                                            <i class="bi bi-shield-lock me-2"></i>
                                            Keamanan Akun
                                        </h5>

                                    </div>




                                    <!-- PASSWORD -->

                                    <div class="col-md-6">

                                        <label class="form-label">

                                            Password

                                            <span class="required">*</span>

                                        </label>

                                        <div class="password-wrapper">

                                            <input
                                                type="password"
                                                name="password"
                                                id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required>

                                            <button
                                                type="button"
                                                class="toggle-password"
                                                data-target="password">

                                                <i class="bi bi-eye"></i>

                                            </button>

                                        </div>

                                        @error('password')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- KONFIRMASI -->

                                    <div class="col-md-6">

                                        <label class="form-label">

                                            Konfirmasi Password

                                            <span class="required">*</span>

                                        </label>

                                        <div class="password-wrapper">

                                            <input
                                                type="password"
                                                name="password_confirmation"
                                                id="password_confirmation"
                                                class="form-control"
                                                required>

                                            <button
                                                type="button"
                                                class="toggle-password"
                                                data-target="password_confirmation">

                                                <i class="bi bi-eye"></i>

                                            </button>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Email
                                            <span class="required">*</span>
                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}"
                                            placeholder="nama@email.com"
                                            autocomplete="email"
                                            required>

                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                    </div>

                                    <!-- =========================
                                     DOKUMEN
                                ========================== -->

                                    <div class="col-12 mt-4">

                                        <h5 class="section-title">
                                            <i class="bi bi-file-earmark-person me-2"></i>
                                            Dokumen Identitas
                                        </h5>

                                    </div>


                                    <!-- FOTO KTP -->

                                    <div class="col-md-6">

                                        <label class="form-label">

                                            Foto KTP

                                            <span class="required">*</span>

                                        </label>

                                        <input
                                            type="file"
                                            name="foto_ktp"
                                            id="foto_ktp"
                                            class="form-control @error('foto_ktp') is-invalid @enderror"
                                            accept="image/jpeg,image/png"
                                            required>

                                        @error('foto_ktp')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                        <small class="text-muted">
                                            Format JPG/JPEG/PNG, maksimal 2 MB.
                                        </small>

                                    </div>


                                    <!-- =========================
                                     SELFIE LIVE CAMERA
                                ========================== -->

                                    <div class="col-12 mt-3">

                                        <label class="form-label">

                                            Foto Selfie

                                            <span class="required">*</span>

                                        </label>

                                        <div class="alert alert-info py-2">

                                            <i class="bi bi-info-circle me-2"></i>

                                            Foto selfie harus diambil langsung
                                            menggunakan kamera perangkat.
                                            Upload foto dari galeri tidak tersedia.

                                        </div>


                                        <!-- CAMERA -->

                                        <div class="camera-container">

                                            <video
                                                id="camera"
                                                autoplay
                                                playsinline
                                                muted>
                                            </video>


                                            <!-- PLACEHOLDER -->

                                            <div
                                                id="cameraPlaceholder"
                                                class="camera-placeholder">

                                                <i class="bi bi-camera"></i>

                                                <div class="fw-semibold">
                                                    Kamera belum diaktifkan
                                                </div>

                                                <small>
                                                    Klik tombol "Aktifkan Kamera"
                                                </small>

                                            </div>


                                            <!-- GUIDE -->

                                            <div
                                                id="cameraGuide"
                                                class="camera-guide"
                                                style="display: none;">
                                            </div>


                                            <div
                                                id="cameraGuideText"
                                                class="camera-guide-text"
                                                style="display: none;">

                                                Posisikan wajah di dalam lingkaran

                                            </div>

                                        </div>


                                        <!-- CAMERA STATUS -->

                                        <div
                                            id="cameraStatus"
                                            class="camera-status text-muted">

                                            <i class="bi bi-camera-video-off me-1"></i>

                                            Kamera belum aktif.

                                        </div>


                                        <!-- CAMERA BUTTONS -->

                                        <div class="camera-buttons d-flex gap-2 flex-wrap">

                                            <button
                                                type="button"
                                                id="startCamera"
                                                class="btn btn-primary">

                                                <i class="bi bi-camera-video me-1"></i>

                                                Aktifkan Kamera

                                            </button>


                                            <button
                                                type="button"
                                                id="capturePhoto"
                                                class="btn btn-success"
                                                style="display: none;">

                                                <i class="bi bi-camera-fill me-1"></i>

                                                Ambil Foto Selfie

                                            </button>


                                            <button
                                                type="button"
                                                id="retakePhoto"
                                                class="btn btn-warning"
                                                style="display: none;">

                                                <i class="bi bi-arrow-repeat me-1"></i>

                                                Ambil Ulang

                                            </button>

                                        </div>


                                        <!-- HIDDEN CANVAS -->

                                        <canvas
                                            id="photoCanvas"
                                            style="display: none;">
                                        </canvas>


                                        <!-- HIDDEN FILE -->

                                        <input
                                            type="file"
                                            name="foto_selfie"
                                            id="foto_selfie"
                                            accept="image/jpeg"
                                            hidden>


                                        <!-- PREVIEW -->

                                        <div id="photoPreview">

                                            <div class="fw-semibold mb-2">

                                                <i class="bi bi-check-circle text-success me-1"></i>

                                                Foto Selfie Berhasil Diambil

                                            </div>

                                            <img
                                                id="selfiePreview"
                                                src=""
                                                alt="Preview Foto Selfie">

                                            <div class="small text-muted mt-2">

                                                Pastikan wajah terlihat jelas sebelum
                                                melanjutkan registrasi.

                                            </div>

                                        </div>


                                        <!-- SELFIE ERROR -->

                                        @error('foto_selfie')

                                        <div class="text-danger small mt-2">
                                            {{ $message }}
                                        </div>

                                        @enderror

                                    </div>


                                    <!-- =========================
                                     TERMS
                                ========================== -->

                                    <div class="col-12 mt-4">

                                        <div class="terms-box">

                                            <div class="form-check">

                                                <input
                                                    type="checkbox"
                                                    name="terms"
                                                    value="1"
                                                    class="form-check-input"
                                                    id="terms"
                                                    {{ old('terms') ? 'checked' : '' }}
                                                    required>

                                                <label
                                                    class="form-check-label"
                                                    for="terms">

                                                    Saya menyatakan bahwa seluruh data
                                                    yang saya masukkan adalah benar.

                                                </label>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- =========================
                                     SUBMIT
                                ========================== -->

                                    <div class="col-12 mt-4">

                                        <button
                                            type="submit"
                                            id="submitButton"
                                            class="btn btn-primary w-100">

                                            <i class="bi bi-person-plus me-1"></i>

                                            Daftar

                                        </button>

                                    </div>


                                    <!-- LOGIN -->

                                    <div class="col-12 text-center mt-3">

                                        <span class="text-muted">
                                            Sudah memiliki akun?
                                        </span>

                                        <a
                                            href="{{ route('login') }}"
                                            class="text-decoration-none">

                                            Login di sini

                                        </a>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </main>


    <!-- Bootstrap JS -->

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* =====================================================
               PEKERJAAN
            ===================================================== */

            const pekerjaan = document.getElementById('pekerjaan');
            const pekerjaanWrapper =
                document.getElementById('pekerjaan-lainnya-wrapper');
            const pekerjaanInput =
                document.getElementById('pekerjaan_lainnya');


            function togglePekerjaan() {

                if (!pekerjaan) {
                    return;
                }

                if (pekerjaan.value === 'Lainnya') {

                    pekerjaanWrapper.style.display = 'block';

                    pekerjaanInput.required = true;

                } else {

                    pekerjaanWrapper.style.display = 'none';

                    pekerjaanInput.required = false;

                    pekerjaanInput.value = '';

                }

            }


            if (pekerjaan) {

                pekerjaan.addEventListener(
                    'change',
                    togglePekerjaan
                );

                togglePekerjaan();

            }


            /* =====================================================
               PASSWORD TOGGLE
            ===================================================== */

            document
                .querySelectorAll('.toggle-password')
                .forEach(function(button) {

                    button.addEventListener('click', function() {

                        const targetId =
                            this.getAttribute('data-target');

                        const input =
                            document.getElementById(targetId);

                        const icon =
                            this.querySelector('i');


                        if (input.type === 'password') {

                            input.type = 'text';

                            icon.classList.remove('bi-eye');

                            icon.classList.add('bi-eye-slash');

                        } else {

                            input.type = 'password';

                            icon.classList.remove('bi-eye-slash');

                            icon.classList.add('bi-eye');

                        }

                    });

                });


            /* =====================================================
               LIVE CAMERA
            ===================================================== */

            const video =
                document.getElementById('camera');

            const canvas =
                document.getElementById('photoCanvas');

            const selfieInput =
                document.getElementById('foto_selfie');

            const selfiePreview =
                document.getElementById('selfiePreview');

            const photoPreview =
                document.getElementById('photoPreview');

            const cameraPlaceholder =
                document.getElementById('cameraPlaceholder');

            const cameraGuide =
                document.getElementById('cameraGuide');

            const cameraGuideText =
                document.getElementById('cameraGuideText');

            const cameraStatus =
                document.getElementById('cameraStatus');

            const startCamera =
                document.getElementById('startCamera');

            const capturePhoto =
                document.getElementById('capturePhoto');

            const retakePhoto =
                document.getElementById('retakePhoto');

            const registerForm =
                document.getElementById('registerForm');


            let cameraStream = null;


            /* =====================================================
               UPDATE STATUS
            ===================================================== */

            function updateCameraStatus(
                message,
                type = 'muted',
                icon = 'bi-camera-video'
            ) {

                cameraStatus.className =
                    'camera-status text-' + type;

                cameraStatus.innerHTML =
                    '<i class="bi ' + icon + ' me-1"></i>' +
                    message;

            }


            /* =====================================================
               START CAMERA
            ===================================================== */

            async function startCameraFunction() {

                try {

                    if (!navigator.mediaDevices ||
                        !navigator.mediaDevices.getUserMedia) {

                        updateCameraStatus(
                            'Browser tidak mendukung akses kamera.',
                            'danger',
                            'bi-camera-video-off'
                        );

                        return;
                    }


                    updateCameraStatus(
                        'Meminta izin kamera...',
                        'warning',
                        'bi-camera-video'
                    );


                    cameraStream =
                        await navigator.mediaDevices.getUserMedia({

                            video: {

                                facingMode: {
                                    ideal: 'user'
                                },

                                width: {
                                    ideal: 1280
                                },

                                height: {
                                    ideal: 720
                                }

                            },

                            audio: false

                        });


                    video.srcObject =
                        cameraStream;


                    await video.play();


                    cameraPlaceholder.style.display =
                        'none';

                    cameraGuide.style.display =
                        'block';

                    cameraGuideText.style.display =
                        'block';


                    capturePhoto.style.display =
                        'inline-block';

                    startCamera.style.display =
                        'none';

                    retakePhoto.style.display =
                        'none';


                    updateCameraStatus(
                        'Kamera aktif. Silakan posisikan wajah Anda di dalam lingkaran.',
                        'success',
                        'bi-camera-video-fill'
                    );


                } catch (error) {

                    console.error(
                        'Camera Error:',
                        error
                    );


                    let message =
                        'Kamera tidak dapat diakses.';


                    if (error.name === 'NotAllowedError') {

                        message =
                            'Izin kamera ditolak. Silakan izinkan kamera pada browser.';

                    } else if (error.name === 'NotFoundError') {

                        message =
                            'Kamera tidak ditemukan pada perangkat.';

                    } else if (error.name === 'NotReadableError') {

                        message =
                            'Kamera sedang digunakan oleh aplikasi lain.';

                    } else if (error.name === 'SecurityError') {

                        message =
                            'Akses kamera diblokir karena alasan keamanan. Gunakan HTTPS atau localhost.';

                    }


                    updateCameraStatus(
                        message,
                        'danger',
                        'bi-camera-video-off'
                    );

                }

            }


            /* =====================================================
               CAPTURE PHOTO
            ===================================================== */

            function capturePhotoFunction() {

                if (!cameraStream) {

                    updateCameraStatus(
                        'Aktifkan kamera terlebih dahulu.',
                        'danger',
                        'bi-exclamation-triangle'
                    );

                    return;

                }


                if (!video.videoWidth ||
                    !video.videoHeight) {

                    updateCameraStatus(
                        'Kamera belum siap. Silakan tunggu sebentar.',
                        'warning',
                        'bi-hourglass-split'
                    );

                    return;

                }


                /*

                    Gunakan resolusi asli kamera.

                */

                canvas.width =
                    video.videoWidth;

                canvas.height =
                    video.videoHeight;


                const context =
                    canvas.getContext('2d');


                /*

                    Mirror selfie agar seperti kamera depan.

                */

                context.save();

                context.translate(
                    canvas.width,
                    0
                );

                context.scale(
                    -1,
                    1
                );


                context.drawImage(
                    video,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );


                context.restore();


                /*

                    Convert canvas menjadi JPEG.

                */

                canvas.toBlob(
                    function(blob) {

                        if (!blob) {

                            updateCameraStatus(
                                'Gagal mengambil foto. Silakan coba lagi.',
                                'danger',
                                'bi-exclamation-triangle'
                            );

                            return;

                        }


                        const file =
                            new File(
                                [blob],
                                'selfie-' +
                                Date.now() +
                                '.jpg', {
                                    type: 'image/jpeg'
                                }
                            );


                        /*
                            Masukkan hasil kamera ke
                            input file secara programatis.
                        */

                        const dataTransfer =
                            new DataTransfer();

                        dataTransfer.items.add(file);

                        selfieInput.files =
                            dataTransfer.files;


                        /*
                            Preview
                        */

                        const imageURL =
                            URL.createObjectURL(blob);

                        selfiePreview.src =
                            imageURL;

                        photoPreview.style.display =
                            'block';


                        /*
                            Tombol
                        */

                        capturePhoto.style.display =
                            'none';

                        retakePhoto.style.display =
                            'inline-block';


                        updateCameraStatus(
                            'Foto selfie berhasil diambil.',
                            'success',
                            'bi-check-circle'
                        );


                        /*
                            Matikan kamera setelah foto
                            berhasil diambil.

                            Foto sudah tersimpan pada
                            input foto_selfie.
                        */

                        stopCamera();


                    },
                    'image/jpeg',
                    0.90
                );

            }


            /* =====================================================
               STOP CAMERA
            ===================================================== */

            function stopCamera() {

                if (cameraStream) {

                    cameraStream
                        .getTracks()
                        .forEach(function(track) {

                            track.stop();

                        });

                    cameraStream = null;

                }

                video.srcObject = null;

            }


            /* =====================================================
               RETAKE PHOTO
            ===================================================== */

            function retakePhotoFunction() {

                selfieInput.value = '';

                selfiePreview.src = '';

                photoPreview.style.display =
                    'none';

                retakePhoto.style.display =
                    'none';


                startCamera.style.display =
                    'none';

                capturePhoto.style.display =
                    'inline-block';


                startCameraFunction();

            }


            /* =====================================================
               BUTTON EVENTS
            ===================================================== */

            startCamera.addEventListener(
                'click',
                startCameraFunction
            );


            capturePhoto.addEventListener(
                'click',
                capturePhotoFunction
            );


            retakePhoto.addEventListener(
                'click',
                retakePhotoFunction
            );


            /* =====================================================
               FORM SUBMIT VALIDATION
            ===================================================== */

            registerForm.addEventListener(
                'submit',
                function(event) {

                    /*
                        Pastikan selfie sudah diambil
                        dari kamera.
                    */

                    if (
                        !selfieInput.files ||
                        selfieInput.files.length === 0
                    ) {

                        event.preventDefault();


                        alert(
                            'Silakan ambil foto selfie menggunakan kamera terlebih dahulu.'
                        );


                        document
                            .getElementById('startCamera')
                            .scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });


                        return;

                    }


                    /*
                        Pastikan file benar-benar JPEG.
                    */

                    const selfieFile =
                        selfieInput.files[0];


                    if (
                        selfieFile.type !== 'image/jpeg'
                    ) {

                        event.preventDefault();


                        alert(
                            'Foto selfie harus berformat JPEG.'
                        );


                        return;

                    }

                }
            );


            /* =====================================================
               CLEANUP CAMERA
            ===================================================== */

            window.addEventListener(
                'beforeunload',
                function() {

                    stopCamera();

                }
            );

        });
    </script>

</body>

</html>