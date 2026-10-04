@extends('layouts.admin')

@section('title', 'Profil')

@section('content')

<div class="pagetitle mb-4">
    <h1>Profil Saya</h1>
    <p class="text-muted mb-0">Periksa dan perbarui data yang Anda berikan saat registrasi.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-person-vcard me-2"></i>Data Pribadi</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('masyarakat.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="nik">NIK</label>
                    <input id="nik" type="text" class="form-control" value="{{ $masyarakat->nik }}" readonly>
                    <div class="form-text">NIK tidak dapat diubah.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="nama_lengkap">Nama Lengkap</label>
                    <input id="nama_lengkap" type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $masyarakat->nama_lengkap) }}" required>
                    @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">Pilih jenis kelamin</option>
                        @foreach($jenisKelamin as $option)
                            <option value="{{ $option }}" @selected(old('jenis_kelamin', $masyarakat->jenis_kelamin) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                    <input id="tempat_lahir" type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir', $masyarakat->tempat_lahir) }}" required>
                    @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                    <input id="tanggal_lahir" type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir', optional($masyarakat->tanggal_lahir)->format('Y-m-d')) }}" required>
                    @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="kewarganegaraan">Kewarganegaraan</label>
                    <input id="kewarganegaraan" type="text" name="kewarganegaraan" class="form-control @error('kewarganegaraan') is-invalid @enderror" value="{{ old('kewarganegaraan', $masyarakat->kewarganegaraan) }}" required>
                    @error('kewarganegaraan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="status_perkawinan">Status Perkawinan</label>
                    <select id="status_perkawinan" name="status_perkawinan" class="form-select @error('status_perkawinan') is-invalid @enderror" required>
                        <option value="">Pilih status perkawinan</option>
                        @foreach($statusPerkawinan as $option)
                            <option value="{{ $option }}" @selected(old('status_perkawinan', $masyarakat->status_perkawinan) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('status_perkawinan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="agama">Agama</label>
                    <select id="agama" name="agama" class="form-select @error('agama') is-invalid @enderror" required>
                        <option value="">Pilih agama</option>
                        @foreach($agama as $option)
                            <option value="{{ $option }}" @selected(old('agama', $masyarakat->agama) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @php
                    $selectedPekerjaan = old('pekerjaan', $masyarakat->pekerjaan);
                @endphp
                <div class="col-md-6">
                    <label class="form-label" for="pekerjaan">Pekerjaan</label>
                    <select id="pekerjaan" name="pekerjaan" class="form-select @error('pekerjaan') is-invalid @enderror" required>
                        <option value="">Pilih pekerjaan</option>
                        @foreach($pekerjaan as $option)
                            <option value="{{ $option }}" @selected($selectedPekerjaan === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 {{ $selectedPekerjaan === 'Lainnya' ? '' : 'd-none' }}" id="pekerjaanLainnyaWrapper">
                    <label class="form-label" for="pekerjaan_lainnya">Sebutkan Pekerjaan</label>
                    <input id="pekerjaan_lainnya" type="text" name="pekerjaan_lainnya" class="form-control @error('pekerjaan_lainnya') is-invalid @enderror" value="{{ old('pekerjaan_lainnya', $masyarakat->pekerjaan_lainnya) }}" @if($selectedPekerjaan === 'Lainnya') required @endif>
                    @error('pekerjaan_lainnya')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="alamat">Alamat Lengkap Sesuai KTP</label>
                    <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $masyarakat->alamat) }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="my-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-person me-2"></i>Dokumen Identitas</h5>
            <p class="text-muted small">Kosongkan pilihan file jika tidak ingin mengganti dokumen yang sudah tersimpan.</p>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="foto_ktp">Foto KTP</label>
                    <div class="form-text mb-2">{{ $masyarakat->foto_ktp ? 'Dokumen KTP sudah tersimpan.' : 'Dokumen KTP belum tersedia.' }}</div>
                    <input id="foto_ktp" type="file" name="foto_ktp" accept=".jpg,.jpeg,.png" class="form-control @error('foto_ktp') is-invalid @enderror">
                    @error('foto_ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="foto_selfie">Foto Selfie</label>
                    <div class="form-text mb-2">{{ $masyarakat->foto_selfie ? 'Dokumen selfie sudah tersimpan.' : 'Dokumen selfie belum tersedia.' }}</div>
                    <input id="foto_selfie" type="file" name="foto_selfie" accept=".jpg,.jpeg,.png" class="form-control @error('foto_selfie') is-invalid @enderror">
                    @error('foto_selfie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">
                <i class="bi bi-save me-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pekerjaan = document.getElementById('pekerjaan');
        const wrapper = document.getElementById('pekerjaanLainnyaWrapper');
        const pekerjaanLainnya = document.getElementById('pekerjaan_lainnya');

        if (!pekerjaan || !wrapper || !pekerjaanLainnya) {
            return;
        }

        function togglePekerjaanLainnya() {
            const isOther = pekerjaan.value === 'Lainnya';
            wrapper.classList.toggle('d-none', !isOther);
            pekerjaanLainnya.required = isOther;
            if (!isOther) {
                pekerjaanLainnya.value = '';
            }
        }

        pekerjaan.addEventListener('change', togglePekerjaanLainnya);
        togglePekerjaanLainnya();
    });
</script>
@endpush
