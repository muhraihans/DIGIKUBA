@extends('layouts.admin')

@section('title', 'Detail Pengajuan Surat')

@section('content')

@php
    $rawData = $pengajuan->data_pengajuan;

    if (!is_array($rawData)) {
        $dataPengajuan = [];
    } elseif (isset($rawData['data_pengajuan']) && is_array($rawData['data_pengajuan'])) {
        $dataPengajuan = $rawData['data_pengajuan'];
    } else {
        $dataPengajuan = $rawData;
    }

    $documents = [
        [
            'label' => 'KTP',
            'icon' => 'bi-person-vcard',
            'type' => 'ktp',
            'path' => $pengajuan->masyarakat?->foto_ktp,
        ],
        [
            'label' => 'Kartu Keluarga',
            'icon' => 'bi-people',
            'type' => 'kk',
            'path' => $pengajuan->file_kk,
        ],
        [
            'label' => 'Pengantar RT/RW',
            'icon' => 'bi-file-earmark-text',
            'type' => 'pengantar_rt_rw',
            'path' => $pengajuan->file_pengantar_rt_rw,
        ],
    ];

    $statusLabel = match ($pengajuan->status) {
        'pending' => 'Menunggu Verifikasi',
        'diproses' => 'Sedang Diproses',
        'perlu_perbaikan' => 'Perlu Perbaikan',
        'diverifikasi' => 'Terverifikasi',
        'menunggu_tanda_tangan' => 'Menunggu Tanda Tangan Lurah',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'selesai' => 'Selesai',
        default => ucwords(str_replace('_', ' ', (string) $pengajuan->status)),
    };

    $statusClass = match ($pengajuan->status) {
        'pending', 'perlu_perbaikan' => 'bg-warning text-dark',
        'diproses', 'menunggu_tanda_tangan' => 'bg-info text-dark',
        'diverifikasi' => 'bg-primary',
        'disetujui', 'selesai' => 'bg-success',
        'ditolak' => 'bg-danger',
        default => 'bg-secondary',
    };

    $statusIcon = match ($pengajuan->status) {
        'pending' => 'bi-hourglass-split',
        'diproses' => 'bi-arrow-repeat',
        'perlu_perbaikan' => 'bi-exclamation-triangle',
        'diverifikasi' => 'bi-check-circle',
        'menunggu_tanda_tangan' => 'bi-pen',
        'disetujui', 'selesai' => 'bi-check-circle-fill',
        'ditolak' => 'bi-x-circle',
        default => 'bi-info-circle',
    };

    $suratSiapDiunduh = $pengajuan->status === 'selesai'
        && $pengajuan->surat
        && in_array($pengajuan->surat->status, ['ditandatangani', 'selesai'], true);
@endphp

<div class="pagetitle mb-4">
    <h1>Detail Pengajuan Surat</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('masyarakat.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('masyarakat.pengajuan.index') }}">Pengajuan Surat</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Informasi Pengajuan</h5>
                        <small class="text-muted">Detail pengajuan surat Anda</small>
                    </div>
                    <span class="badge {{ $statusClass }} px-3 py-2">{{ $statusLabel }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Nomor Pengajuan</small>
                        <strong>{{ $pengajuan->nomor_pengajuan }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Jenis Surat</small>
                        <strong>{{ $pengajuan->jenisSurat->nama ?? '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Tanggal Pengajuan</small>
                        <strong>{{ $pengajuan->created_at?->format('d/m/Y H:i') ?? '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Status</small>
                        <strong>{{ $statusLabel }}</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Data Pemohon</h5>
            </div>
            <div class="card-body">
                @if($pengajuan->masyarakat)
                    <div class="row g-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Nama Lengkap</small>
                            <strong>{{ $pengajuan->masyarakat->nama_lengkap ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">NIK</small>
                            <strong>{{ $pengajuan->masyarakat->nik ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                            <strong>{{ $pengajuan->masyarakat->jenis_kelamin ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tempat Lahir</small>
                            <strong>{{ $pengajuan->masyarakat->tempat_lahir ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                            <strong>{{ $pengajuan->masyarakat->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Kewarganegaraan</small>
                            <strong>{{ $pengajuan->masyarakat->kewarganegaraan ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Status Perkawinan</small>
                            <strong>{{ $pengajuan->masyarakat->status_perkawinan ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Agama</small>
                            <strong>{{ $pengajuan->masyarakat->agama ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Pekerjaan</small>
                            <strong>{{ $pengajuan->masyarakat->pekerjaan_label ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Email</small>
                            <strong>{{ $pengajuan->masyarakat->user?->email ?? '-' }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Alamat</small>
                            <strong>{{ $pengajuan->masyarakat->alamat ?? '-' }}</strong>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">Data pemohon tidak tersedia.</div>
                @endif
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-card-list me-2"></i>Data Pengajuan</h5>
            </div>
            <div class="card-body">
                @if(count($dataPengajuan) > 0)
                    <div class="row g-4">
                        @foreach($dataPengajuan as $key => $value)
                            @php
                                $label = ucwords(str_replace('_', ' ', (string) $key));
                            @endphp

                            @if(is_array($value))
                                <div class="col-12">
                                    <div class="border rounded-3 p-3">
                                        <div class="fw-semibold mb-3">{{ $label }}</div>
                                        @foreach($value as $subKey => $subValue)
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">{{ ucwords(str_replace('_', ' ', (string) $subKey)) }}</small>
                                                @if(is_array($subValue))
                                                    <pre class="bg-light p-3 rounded small mb-0">{{ json_encode($subValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                @else
                                                    <div class="fw-semibold">{{ $subValue === null || $subValue === '' ? '-' : (string) $subValue }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">{{ $label }}</small>
                                    <div class="fw-semibold">{{ $value === null || $value === '' ? '-' : (string) $value }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-secondary mb-0"><i class="bi bi-info-circle me-2"></i>Data pengajuan belum tersedia.</div>
                @endif
            </div>
        </section>

        @if($pengajuan->catatan)
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Catatan Pemeriksaan</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">{!! nl2br(e($pengajuan->catatan)) !!}</div>
                </div>
            </section>
        @endif

        @if($pengajuan->komentar && $pengajuan->komentar->isNotEmpty())
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-chat-left-text me-2"></i>Riwayat Komentar</h5>
                </div>
                <div class="card-body">
                    @foreach($pengajuan->komentar as $komentar)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $komentar->user->name ?? 'Petugas' }}</strong>
                                    <div><small class="text-muted">{{ $komentar->created_at?->format('d/m/Y H:i') ?? '-' }}</small></div>
                                </div>
                            </div>
                            <div class="mt-2">{!! nl2br(e($komentar->komentar)) !!}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($pengajuan->surat)
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-pdf me-2"></i>Informasi Surat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Nomor Surat</small>
                            <strong>{{ $pengajuan->surat->nomor_surat ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Tanggal Surat</small>
                            <strong>{{ $pengajuan->surat->tanggal_surat ? \Illuminate\Support\Carbon::parse($pengajuan->surat->tanggal_surat)->format('d/m/Y') : '-' }}</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Perihal</small>
                            <strong>{{ $pengajuan->surat->perihal ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-check me-2"></i>Dokumen Persyaratan</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($documents as $document)
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 d-flex flex-column align-items-start">
                                <h6><i class="bi {{ $document['icon'] }} me-1"></i>{{ $document['label'] }}</h6>

                                @if($document['path'])
                                    @php
                                        $extension = strtolower(pathinfo($document['path'], PATHINFO_EXTENSION));
                                    @endphp

                                    <span class="badge bg-success mb-3">Tersedia</span>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm mt-auto"
                                        data-bs-toggle="modal"
                                        data-bs-target="#documentPreviewModal"
                                        data-document-url="{{ route('masyarakat.pengajuan.dokumen', ['pengajuan' => $pengajuan, 'jenis' => $document['type']]) }}"
                                        data-document-type="{{ $extension === 'pdf' ? 'pdf' : 'image' }}"
                                        data-document-title="{{ $document['label'] }}">
                                        <i class="bi bi-eye me-1"></i>Lihat Dokumen
                                    </button>
                                @else
                                    <span class="badge bg-secondary">Belum tersedia</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <aside class="col-lg-4">
        <section class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-activity me-2"></i>Status Pengajuan</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="rounded-circle {{ $statusClass }} text-white d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="bi {{ $statusIcon }}"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $statusLabel }}</div>
                        <small class="text-muted">Status pengajuan saat ini.</small>
                    </div>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-download me-2"></i>Unduh Surat</h5>
            </div>
            <div class="card-body">
                @if($suratSiapDiunduh)
                    <a href="{{ route('masyarakat.riwayat.download', $pengajuan) }}" class="btn btn-success w-100">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </a>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>Surat dapat diunduh setelah selesai diproses dan ditandatangani.
                    </div>
                @endif
            </div>
        </section>
    </aside>
</div>

<div
    class="modal fade"
    id="documentPreviewModal"
    tabindex="-1"
    aria-labelledby="documentPreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentPreviewModalLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body bg-light text-center">
                <img
                    id="documentPreviewImage"
                    class="img-fluid d-none mx-auto"
                    style="max-height: 72vh; object-fit: contain;"
                    alt="Pratinjau dokumen">
                <iframe
                    id="documentPreviewPdf"
                    class="w-100 d-none border rounded bg-white"
                    style="height: 72vh;"
                    title="Pratinjau PDF"></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('documentPreviewModal');

        if (!modal) {
            return;
        }

        const title = document.getElementById('documentPreviewModalLabel');
        const image = document.getElementById('documentPreviewImage');
        const pdf = document.getElementById('documentPreviewPdf');

        modal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            const url = trigger.getAttribute('data-document-url');
            const type = trigger.getAttribute('data-document-type');

            title.textContent = 'Pratinjau ' + (trigger.getAttribute('data-document-title') || 'Dokumen');

            if (type === 'pdf') {
                image.classList.add('d-none');
                image.removeAttribute('src');
                pdf.classList.remove('d-none');
                pdf.src = url;
                return;
            }

            pdf.classList.add('d-none');
            pdf.removeAttribute('src');
            image.classList.remove('d-none');
            image.src = url;
        });

        modal.addEventListener('hidden.bs.modal', function () {
            image.removeAttribute('src');
            pdf.removeAttribute('src');
            image.classList.add('d-none');
            pdf.classList.add('d-none');
        });
    });
</script>
@endpush

@endsection
