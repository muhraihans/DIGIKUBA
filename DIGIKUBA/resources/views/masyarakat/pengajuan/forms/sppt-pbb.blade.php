<h1>
    SURAT PENGANTAR PENERBITAN SPPT-PBB
</h1>

<div class="nomor">
    Nomor :
    {{ $surat->nomor_surat }}
</div>


<p>
    Yang bertanda tangan dibawah ini Lurah Kuta Baru
    Kecamatan Pasar Kemis Kabupaten Tangerang
    dengan ini menerangkan bahwa:
</p>


<table class="identitas">

    <tr>
        <td>Nama Wajib Pajak</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['nama_wajib_pajak'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Alamat Wajib Pajak</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['alamat_wajib_pajak'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Bukti Hak Milik</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['bukti_hak_milik'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Objek Pajak</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['objek_pajak'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Alamat Objek Pajak</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['alamat_objek_pajak'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Luas Tanah</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['luas_tanah'] ?? '-' }}
            m²
        </td>
    </tr>


    <tr>
        <td>Luas Bangunan</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['luas_bangunan'] ?? '-' }}
            m²
        </td>
    </tr>


    <tr>
        <td>SPPT-PBB / NOP</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['nop'] ?? '-' }}
        </td>
    </tr>

</table>


<p>
    <strong>Batas-batas:</strong>
</p>


<table class="identitas">

    <tr>
        <td>Sebelah Utara</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['batas_utara'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Sebelah Timur</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['batas_timur'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Sebelah Selatan</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['batas_selatan'] ?? '-' }}
        </td>
    </tr>


    <tr>
        <td>Sebelah Barat</td>
        <td>:</td>
        <td>
            {{ $dataPengajuan['batas_barat'] ?? '-' }}
        </td>
    </tr>

</table>


<p>
    Benar objek pajak tersebut diatas
    SPPT-PBB-nya belum pernah diterbitkan.
</p>


<p>
    Surat Pengantar ini dibuat untuk
    memenuhi persyaratan permohonan
    penerbitan SPPT-PBB a/n
    <strong>
        {{ $dataPengajuan['nama_wajib_pajak'] ?? '-' }}
    </strong>.
</p>


<p>
    Demikian Surat Pengantar ini dibuat
    untuk dipergunakan sebagaimana mestinya.
</p>