<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasyarakatPengajuanDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_open_a_private_requirement_document_inline(): void
    {
        Storage::fake('private');
        Storage::disk('private')->put(
            'pengajuan/kk/preview.pdf',
            "%PDF-1.4\n% DIGIKUBA document preview\n"
        );

        [$user, $pengajuan] = $this->createMasyarakatPengajuan([
            'file_kk' => 'pengajuan/kk/preview.pdf',
        ]);

        $response = $this->actingAs($user)->get(route(
            'masyarakat.pengajuan.dokumen',
            ['pengajuan' => $pengajuan, 'jenis' => 'kk']
        ));

        $response->assertOk();
        $this->assertStringContainsString(
            'inline',
            $response->headers->get('Content-Disposition')
        );
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_other_masyarakat_cannot_open_another_users_document(): void
    {
        Storage::fake('private');
        [$owner, $pengajuan] = $this->createMasyarakatPengajuan([
            'file_kk' => 'pengajuan/kk/private.pdf',
        ]);
        $otherUser = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        Masyarakat::create([
            'user_id' => $otherUser->id,
            'nik' => '1234567890123457',
            'nama_lengkap' => 'Pengguna Lain',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-other.png',
            'foto_selfie' => 'testing/selfie-other.png',
        ]);

        $response = $this->actingAs($otherUser)->get(route(
            'masyarakat.pengajuan.dokumen',
            ['pengajuan' => $pengajuan, 'jenis' => 'kk']
        ));

        $response->assertForbidden();
    }

    public function test_detail_page_has_modal_preview_buttons_for_uploaded_documents(): void
    {
        [$user, $pengajuan] = $this->createMasyarakatPengajuan([
            'file_kk' => 'pengajuan/kk/preview.pdf',
            'file_pengantar_rt_rw' => 'pengajuan/pengantar-rt-rw/preview.jpg',
        ]);

        $response = $this->actingAs($user)->get(route(
            'masyarakat.pengajuan.show',
            $pengajuan
        ));

        $response->assertOk();
        $response->assertSee('id="staffDocumentPreviewModal"', false);
        $response->assertSee('data-document-title="Kartu Keluarga"', false);
        $response->assertSee('data-document-title="Pengantar RT/RW"', false);
        $response->assertSee('Jenis Kelamin');
        $response->assertSee('Kewarganegaraan');
        $response->assertSee('Tangerang');
        $response->assertSee('pemohon@example.test');

        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        $staffDetail = $this->actingAs($superadmin)->get(route(
            'staff.pengajuan.show',
            $pengajuan
        ));

        $staffDetail->assertOk();
        $staffDetail->assertSee('Kewarganegaraan');
        $staffDetail->assertSee('Status Perkawinan');
        $staffDetail->assertSee('pemohon@example.test');
        $staffDetail->assertSee('Dokumen Persyaratan');

        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/001/KKB/2026',
            'tanggal_surat' => '2026-10-04',
            'perihal' => 'Surat Uji',
            'status' => 'menunggu_tanda_tangan',
        ]);
        $lurah = User::factory()->create([
            'role' => 'lurah',
            'status' => 'active',
        ]);

        $lurahDetail = $this->actingAs($lurah)->get(route(
            'lurah.tanda-tangan.show',
            $surat
        ));

        $lurahDetail->assertOk();
        $lurahDetail->assertSee('Jenis Kelamin');
        $lurahDetail->assertSee('Kewarganegaraan');
        $lurahDetail->assertSee('pemohon@example.test');
        $lurahDetail->assertSee('Dokumen Persyaratan');
        $lurahDetail->assertSee('id="lurahDocumentPreviewModal"', false);

        $pengajuan->update(['status' => 'menunggu_tanda_tangan']);

        $lurahApplicationDetail = $this->actingAs($lurah)->get(route(
            'lurah.pengajuan.show',
            $pengajuan
        ));

        $lurahApplicationDetail->assertOk();
        $lurahApplicationDetail->assertSee('id="staffDocumentPreviewModal"', false);
        $lurahApplicationDetail->assertSee('Dokumen Persyaratan');
        $lurahApplicationDetail->assertSee('Buka Tindakan Tanda Tangan');
    }

    private function createMasyarakatPengajuan(array $overrides = []): array
    {
        $user = User::factory()->create([
            'email' => 'pemohon@example.test',
            'role' => 'masyarakat',
            'status' => 'active',
        ]);

        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pemohon Uji',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-03-15',
            'kewarganegaraan' => 'Indonesia',
            'status_perkawinan' => 'Kawin',
            'agama' => 'Islam',
            'pekerjaan' => 'Karyawan Swasta',
            'alamat' => 'Jalan Contoh No. 1',
            'foto_ktp' => 'masyarakat/ktp/pemohon.png',
            'foto_selfie' => 'masyarakat/selfie/pemohon.png',
        ]);

        $jenisSurat = JenisSurat::create([
            'kode' => 'TEST',
            'nama' => 'Surat Uji',
            'status' => true,
        ]);

        $pengajuan = PengajuanSurat::create(array_merge([
            'nomor_pengajuan' => 'PGJ-TEST-' . $user->id,
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => 'pending',
        ], $overrides));

        return [$user, $pengajuan];
    }
}
