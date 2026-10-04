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

class DomisiliLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_domisili_form_only_requests_the_kk_and_rt_rw_letter(): void
    {
        [$user] = $this->createMasyarakat();
        $jenisSurat = $this->createDomisiliType();

        $response = $this->actingAs($user)->get(route(
            'masyarakat.pengajuan.form',
            $jenisSurat
        ));

        $response->assertOk();
        $response->assertSee('name="file_kk"', false);
        $response->assertSee('name="file_pengantar_rt_rw"', false);
        $response->assertSee('KTP menggunakan dokumen yang sudah diunggah saat registrasi.');
        $response->assertDontSee('name="keperluan"', false);
        $response->assertDontSee('name="keterangan"', false);
    }

    public function test_domisili_application_accepts_only_document_inputs_and_stores_no_free_text(): void
    {
        Storage::fake('private');
        [$user] = $this->createMasyarakat();
        $jenisSurat = $this->createDomisiliType();

        $response = $this->actingAs($user)->post(route('masyarakat.pengajuan.store'), [
            'jenis_surat_id' => $jenisSurat->id,
            'file_kk' => UploadedFile::fake()->createWithContent('kk.pdf', "%PDF-1.4\nKK test"),
            'file_pengantar_rt_rw' => UploadedFile::fake()->createWithContent('pengantar.pdf', "%PDF-1.4\nPengantar test"),
            'keperluan' => 'Field ini tidak boleh disimpan untuk domisili.',
            'keterangan' => 'Field ini juga tidak boleh disimpan.',
        ]);

        $response->assertRedirect();

        $pengajuan = PengajuanSurat::latest('id')->firstOrFail();
        $this->assertSame([], $pengajuan->data_pengajuan);
        $this->assertNotEmpty($pengajuan->file_kk);
        $this->assertNotEmpty($pengajuan->file_pengantar_rt_rw);
        Storage::disk('private')->assertExists($pengajuan->file_kk);
        Storage::disk('private')->assertExists($pengajuan->file_pengantar_rt_rw);
    }

    public function test_domisili_letter_expiry_is_one_month_after_approval(): void
    {
        [, $masyarakat] = $this->createMasyarakat();
        $jenisSurat = $this->createDomisiliType();
        $pengajuan = PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ-DOMISILI-TEST',
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => 'selesai',
            'disetujui_at' => '2025-09-11 10:30:00',
        ]);
        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/001/KKB/2025',
            'tanggal_surat' => '2025-09-10',
            'perihal' => 'Surat Keterangan Domisili',
            'status' => 'ditandatangani',
        ]);

        $html = view('pdf.domisili', compact('surat'))->render();

        $this->assertStringContainsString('11 Oktober 2025', $html);
        $this->assertStringContainsString('Berdasarkan keterangan Ketua RT/RW setempat benar nama tersebut diatas berdomisili di wilayah', $html);
        $this->assertStringNotContainsString('11 Oktober 2025.', $html);
    }

    private function createMasyarakat(): array
    {
        $user = User::factory()->create([
            'name' => 'Domisili Test',
            'email' => 'domisili@example.test',
            'role' => 'masyarakat',
            'status' => 'active',
        ]);

        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Domisili Test',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'masyarakat/ktp/dom.pdf',
            'foto_selfie' => 'masyarakat/selfie/dom.png',
        ]);

        return [$user, $masyarakat];
    }

    private function createDomisiliType(): JenisSurat
    {
        return JenisSurat::create([
            'kode' => 'DOM',
            'nama' => 'Surat Keterangan Domisili',
            'template' => 'domisili',
            'status' => true,
        ]);
    }
}
