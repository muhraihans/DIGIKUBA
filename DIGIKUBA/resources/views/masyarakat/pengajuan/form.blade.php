@extends('layouts.admin')

@section('title', 'Form Pengajuan Surat')

@section('content')

<div class="mb-4">

    <h3 class="fw-bold">
        {{ $jenisSurat->nama }}
    </h3>

    <p class="text-muted">
        Lengkapi data pengajuan surat.
    </p>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        {{-- Pesan error umum --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Terjadi kesalahan:
                </div>

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error dari controller --}}
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <form
            action="{{ route('masyarakat.pengajuan.store', $jenisSurat) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <input
                type="hidden"
                name="jenis_surat_id"
                value="{{ $jenisSurat->id }}">

            {{-- Informasi --}}
            <div class="alert alert-info">

                <i class="bi bi-info-circle me-2"></i>

                @if($jenisSurat->template === 'domisili')
                    Unggah Kartu Keluarga dan surat pengantar RT/RW. KTP menggunakan dokumen yang sudah diunggah saat registrasi.
                @elseif($jenisSurat->template === 'sppt-pbb')
                    Isi data wajib pajak dan objek pajak sesuai dokumen pendukung. Data ini akan dicetak pada surat pengantar SPPT-PBB.
                @else
                    Pastikan seluruh data yang Anda masukkan sudah benar sebelum mengirim pengajuan.
                @endif

            </div>

            @if($jenisSurat->template === 'sktm')
            {{-- Keperluan --}}
            <div class="mb-3">

                <label
                    for="keperluan"
                    class="form-label fw-semibold">

                    Keperluan
                    <span class="text-danger">*</span>

                </label>

                <textarea
                    id="keperluan"
                    name="keperluan"
                    class="form-control @error('keperluan') is-invalid @enderror"
                    rows="4"
                    placeholder="Contoh: Saya mengajukan SKTM untuk keperluan administrasi bantuan biaya pendidikan."
                    required>{{ old('keperluan') }}</textarea>

                @error('keperluan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Keterangan --}}
            <div class="mb-3">

                <label
                    for="keterangan"
                    class="form-label fw-semibold">

                    Keterangan Tambahan

                </label>

                <textarea
                    id="keterangan"
                    name="keterangan"
                    class="form-control @error('keterangan') is-invalid @enderror"
                    rows="4"
                    placeholder="Tambahkan keterangan lain jika diperlukan.">{{ old('keterangan') }}</textarea>

                @error('keterangan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>
            @endif

            @if($jenisSurat->template === 'sppt-pbb')
                <h5 class="fw-bold mb-3">Data Wajib Pajak</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="nama_wajib_pajak" class="form-label fw-semibold">Nama Wajib Pajak <span class="text-danger">*</span></label>
                        <input id="nama_wajib_pajak" name="nama_wajib_pajak" type="text" maxlength="255" required value="{{ old('nama_wajib_pajak') }}" class="form-control @error('nama_wajib_pajak') is-invalid @enderror">
                        @error('nama_wajib_pajak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="alamat_wajib_pajak" class="form-label fw-semibold">Alamat Wajib Pajak <span class="text-danger">*</span></label>
                        <textarea id="alamat_wajib_pajak" name="alamat_wajib_pajak" rows="2" maxlength="1000" required class="form-control @error('alamat_wajib_pajak') is-invalid @enderror">{{ old('alamat_wajib_pajak') }}</textarea>
                        @error('alamat_wajib_pajak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="bukti_hak_milik" class="form-label fw-semibold">Bukti Hak Milik <span class="text-danger">*</span></label>
                        <textarea id="bukti_hak_milik" name="bukti_hak_milik" rows="2" maxlength="2000" required placeholder="Contoh: Sertifikat Hak Milik Nomor ..." class="form-control @error('bukti_hak_milik') is-invalid @enderror">{{ old('bukti_hak_milik') }}</textarea>
                        @error('bukti_hak_milik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="fw-bold mb-3">Data Objek Pajak</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="objek_pajak" class="form-label fw-semibold">Objek Pajak <span class="text-danger">*</span></label>
                        <input id="objek_pajak" name="objek_pajak" type="text" maxlength="255" required value="{{ old('objek_pajak') }}" class="form-control @error('objek_pajak') is-invalid @enderror">
                        @error('objek_pajak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="alamat_objek_pajak" class="form-label fw-semibold">Alamat Objek Pajak <span class="text-danger">*</span></label>
                        <textarea id="alamat_objek_pajak" name="alamat_objek_pajak" rows="2" maxlength="1000" required class="form-control @error('alamat_objek_pajak') is-invalid @enderror">{{ old('alamat_objek_pajak') }}</textarea>
                        @error('alamat_objek_pajak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="luas_tanah" class="form-label fw-semibold">Luas Tanah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input id="luas_tanah" name="luas_tanah" type="text" maxlength="100" required value="{{ old('luas_tanah') }}" class="form-control @error('luas_tanah') is-invalid @enderror">
                            <span class="input-group-text">m²</span>
                        </div>
                        @error('luas_tanah')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="luas_bangunan" class="form-label fw-semibold">Luas Bangunan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input id="luas_bangunan" name="luas_bangunan" type="text" maxlength="100" required value="{{ old('luas_bangunan') }}" class="form-control @error('luas_bangunan') is-invalid @enderror">
                            <span class="input-group-text">m²</span>
                        </div>
                        @error('luas_bangunan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="nop" class="form-label fw-semibold">SPPT-PBB / NOP <span class="text-danger">*</span></label>
                        <input id="nop" name="nop" type="text" maxlength="100" required value="{{ old('nop') }}" class="form-control @error('nop') is-invalid @enderror">
                        @error('nop')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="fw-bold mb-3">Batas-batas Objek Pajak</h5>
                <div class="row g-3 mb-4">
                    @foreach([
                        'batas_utara' => 'Sebelah Utara',
                        'batas_timur' => 'Sebelah Timur',
                        'batas_selatan' => 'Sebelah Selatan',
                        'batas_barat' => 'Sebelah Barat',
                    ] as $field => $label)
                        <div class="col-md-6">
                            <label for="{{ $field }}" class="form-label fw-semibold">{{ $label }} <span class="text-danger">*</span></label>
                            <input id="{{ $field }}" name="{{ $field }}" type="text" maxlength="500" required value="{{ old($field) }}" class="form-control @error($field) is-invalid @enderror">
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>
            @endif

            <hr class="my-4">

            <h5 class="mb-3">
                Dokumen Persyaratan
            </h5>

            <div class="row g-3">

                {{-- KTP --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        KTP
                    </label>

                    <div class="alert alert-success mb-0">

                        <i class="bi bi-check-circle me-1"></i>

                        KTP menggunakan dokumen
                        yang telah diupload saat registrasi.

                    </div>

                </div>

                {{-- KK --}}
                <div class="col-md-4">

                    <label
                        for="file_kk"
                        class="form-label fw-semibold">

                        Kartu Keluarga
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="file"
                        name="file_kk"
                        id="file_kk"
                        class="form-control @error('file_kk') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.pdf"
                        required>

                    <small class="text-muted">
                        Format JPG, JPEG, PNG, atau PDF.
                        Maksimal 5 MB.
                    </small>

                    @error('file_kk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Pengantar RT/RW --}}
                <div class="col-md-4">

                    <label
                        for="file_pengantar_rt_rw"
                        class="form-label fw-semibold">

                        Surat Pengantar RT/RW
                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="file"
                        name="file_pengantar_rt_rw"
                        id="file_pengantar_rt_rw"
                        class="form-control @error('file_pengantar_rt_rw') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.pdf"
                        required>

                    <small class="text-muted">
                        Format JPG, JPEG, PNG, atau PDF.
                        Maksimal 5 MB.
                    </small>

                    @error('file_pengantar_rt_rw')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <hr class="my-4">

            {{-- Tombol --}}
            <div class="d-flex gap-2">

                <a
                    href="{{ route('masyarakat.pengajuan.create') }}"
                    class="btn btn-secondary">

                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-send me-2"></i>
                    Kirim Pengajuan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection