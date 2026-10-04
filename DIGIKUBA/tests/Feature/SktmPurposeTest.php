<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SktmPurposeTest extends TestCase
{
    use RefreshDatabase;

    public function test_sktm_submission_saves_the_purpose_and_pdf_renders_it_with_the_shared_format(): void
    {
        Storage::fake('private');

        $user = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pemohon SKTM',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-02-03',
            'kewarganegaraan' => 'Indonesia',
            'status_perkawinan' => 'Belum Kawin',
            'agama' => 'Islam',
            'pekerjaan' => 'Karyawan Swasta',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-sktm.png',
            'foto_selfie' => 'testing/selfie-sktm.png',
        ]);
        $jenisSurat = JenisSurat::create([
            'kode' => 'SKTM',
            'nama' => 'Surat Keterangan Tidak Mampu',
            'template' => 'sktm',
            'status' => true,
        ]);

        $response = $this->actingAs($user)->post(route('masyarakat.pengajuan.store'), [
            'jenis_surat_id' => $jenisSurat->id,
            'keperluan' => 'Permohonan bantuan biaya pendidikan',
            'keterangan' => 'Untuk melengkapi persyaratan sekolah',
            'file_kk' => UploadedFile::fake()->createWithContent('kk.pdf', "%PDF-1.4\nKartu Keluarga"),
            'file_pengantar_rt_rw' => UploadedFile::fake()->createWithContent('pengantar.pdf', "%PDF-1.4\nPengantar"),
        ]);

        $response->assertRedirect();
        $pengajuan = PengajuanSurat::latest('id')->firstOrFail();
        $this->assertSame('Permohonan bantuan biaya pendidikan', $pengajuan->data_pengajuan['keperluan']);
        $this->assertSame('Untuk melengkapi persyaratan sekolah', $pengajuan->data_pengajuan['keterangan']);

        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/001/KKB/2026',
            'tanggal_surat' => '2026-10-04',
            'perihal' => $jenisSurat->nama,
            'status' => 'ditandatangani',
        ]);

        $html = view('pdf.sktm', [
            'surat' => $surat,
            'pengajuan' => $pengajuan,
            'masyarakat' => $masyarakat,
            'jenisSurat' => $jenisSurat,
            'dataPengajuan' => $pengajuan->data_pengajuan,
            'keperluan' => $pengajuan->data_pengajuan['keperluan'],
            'keterangan' => $pengajuan->data_pengajuan['keterangan'],
        ])->render();

        $this->assertStringContainsString('Permohonan bantuan biaya pendidikan', $html);
        $this->assertStringContainsString('Untuk melengkapi persyaratan sekolah', $html);
        $this->assertStringContainsString('PEMERINTAH KABUPATEN TANGERANG', $html);
        $this->assertStringContainsString('Jenis Kelamin', $html);
        $this->assertStringContainsString('signature-table', $html);
    }
}
