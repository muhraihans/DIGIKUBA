<div class="row g-3">

    <div class="col-md-6">

        <label class="form-label">
            Nama Lengkap
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->nama_lengkap }}"
            readonly
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            NIK
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->nik }}"
            readonly
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Jenis Kelamin
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->jenis_kelamin }}"
            readonly
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Kewarganegaraan
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->kewarganegaraan }}"
            readonly
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Status Perkawinan
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->status_perkawinan }}"
            readonly
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Agama
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->agama }}"
            readonly
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Pekerjaan
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $masyarakat->pekerjaan_label }}"
            readonly
        >

    </div>


    <div class="col-12">

        <label class="form-label">
            Keperluan
        </label>

        <input
            type="text"
            name="keperluan"
            class="form-control"
            value="{{ old('keperluan', $data['keperluan'] ?? '') }}"
            required
        >

    </div>


    <div class="col-12">

        <label class="form-label">
            Keterangan
        </label>

        <textarea
            name="keterangan"
            class="form-control"
            rows="4"
        >{{ old('keterangan', $data['keterangan'] ?? '') }}</textarea>

    </div>


</div>