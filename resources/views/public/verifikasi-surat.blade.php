<!DOCTYPE html>
<html lang="id" data-table-tools-only>

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Verifikasi Surat - DIGIKUBA
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        .header {
            background: #4154f1;
            color: white;
            padding: 35px 20px;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            margin-bottom: 0;
            opacity: .9;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
        }

        .verification-card {
            margin-top: -30px;
        }

        .verified-icon {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 15px;
        }

        .table th {
            width: 35%;
            color: #6c757d;
            font-weight: 600;
        }

        .table-sort-button {
            color: inherit;
            white-space: nowrap;
        }

        .table-sort-icon {
            color: #6c757d;
            font-size: .8em;
        }

        .badge-signed {
            background: #198754;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
        }

        .section-title {
            font-weight: 700;
            color: #212529;
        }

        .footer {
            color: #6c757d;
            font-size: 13px;
            padding: 30px 0;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="container">

            <h1>
                DIGIKUBA
            </h1>

            <p>
                Layanan Digital Kelurahan Kutabaru
            </p>

        </div>
    </div>


    <div class="container pb-5">

        {{-- SURAT YANG DI-SCAN --}}
        <div class="card verification-card mb-4">

            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">

                    <div class="verified-icon">
                        ✓
                    </div>

                    <h4 class="fw-bold">
                        Surat Terverifikasi
                    </h4>

                    <p class="text-muted mb-0">
                        QR Code berhasil diverifikasi.
                    </p>

                    <div class="mt-3">
                        <span class="badge-signed">
                            ✓ Ditandatangani Lurah
                        </span>
                    </div>

                </div>


                <h5 class="section-title mb-3">
                    Informasi Surat
                </h5>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <tr>
                            <th>
                                Nomor Surat
                            </th>

                            <td>
                                <strong>
                                    {{ $surat->nomor_surat }}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Perihal
                            </th>

                            <td>
                                {{ $surat->perihal }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Yang Mengajukan
                            </th>

                            <td>
                                {{ $surat->pengajuan->masyarakat->nama_lengkap ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Tanggal Pengajuan
                            </th>

                            <td>
                                {{ optional($surat->pengajuan->created_at)->format('d F Y') }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Tanggal Ditandatangani
                            </th>

                            <td>
                                {{ optional($tandaTangan->signed_at)->format('d F Y H:i') }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Penandatangan
                            </th>

                            <td>
                                {{ $tandaTangan->lurah->name ?? 'Lurah' }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>


        {{-- LIST SURAT --}}
        <div class="card">

            <div class="card-body p-4 p-md-5">

                <div class="mb-4">

                    <h4 class="section-title mb-1">
                        Daftar Surat Telah Ditandatangani
                    </h4>

                    <p class="text-muted mb-0">
                        Daftar surat yang telah ditandatangani secara digital oleh Lurah Kelurahan Kutabaru.
                    </p>

                </div>


                @if($daftarSurat->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Nomor Surat
                                    </th>

                                    <th>
                                        Jenis Surat
                                    </th>

                                    <th>
                                        Yang Mengajukan
                                    </th>

                                    <th>
                                        Tanggal Surat
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($daftarSurat as $item)

                                    <tr data-created-at="{{ $item->created_at?->timestamp ?? '' }}">

                                        <td>
                                            {{ $daftarSurat->firstItem() + $loop->index }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $item->nomor_surat }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $item->perihal }}
                                        </td>

                                        <td>
                                            {{ $item->pengajuan->masyarakat->nama_lengkap ?? '-' }}
                                        </td>

                                        <td>
                                            {{ optional($item->tanggal_surat)->format('d/m/Y') }}
                                        </td>

                                        <td>

                                            <span class="badge bg-success">
                                                Ditandatangani
                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}
                    <div class="mt-4">

                        {{ $daftarSurat->links() }}

                    </div>

                @else

                    <div class="alert alert-info mb-0">

                        Belum terdapat surat yang telah ditandatangani.

                    </div>

                @endif

            </div>

        </div>


        <div class="footer text-center">

            <div>
                DIGIKUBA — Layanan Digital Kelurahan Kutabaru
            </div>

            <div class="mt-1">
                Halaman verifikasi surat publik
            </div>

        </div>

    </div>

    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>