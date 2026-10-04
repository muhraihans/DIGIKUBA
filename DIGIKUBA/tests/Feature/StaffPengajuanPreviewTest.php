<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StaffPengajuanPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_open_rt_rw_letter_inline_for_modal_preview(): void
    {
        Storage::fake('private');
        [$staff, $pengajuan] = $this->createFixture();
        $path = 'pengajuan/pengantar-rt-rw/pengantar.pdf';
        Storage::disk('private')->put($path, "%PDF-1.4\nPengantar RT RW\n");
        $pengajuan->update(['file_pengantar_rt_rw' => $path]);

        $response = $this->actingAs($staff)->get(route(
            'staff.pengajuan.dokumen',
            ['pengajuan' => $pengajuan, 'jenis' => 'pengantar_rt_rw']
        ));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString(
            'inline',
            $response->headers->get('Content-Disposition')
        );
        $this->assertStringContainsString(
            'private',
            $response->headers->get('Cache-Control')
        );
    }

    public function test_staff_pengajuan_detail_opens_ktp_kk_and_pengantar_in_one_modal(): void
    {
        [$staff, $pengajuan] = $this->createFixture([
            'file_kk' => 'pengajuan/kk/kk.pdf',
            'file_pengantar_rt_rw' => 'pengajuan/pengantar-rt-rw/pengantar.pdf',
        ]);

        $response = $this->actingAs($staff)->get(route(
            'staff.pengajuan.show',
            $pengajuan
        ));

        $response->assertOk();
        $response->assertSee('id="staffDocumentPreviewModal"', false);
        $response->assertSee('data-document-title="Foto KTP"', false);
        $response->assertSee('data-document-title="Kartu Keluarga"', false);
        $response->assertSee('data-document-title="Pengantar RT/RW"', false);
        $response->assertSee('dokumen/pengantar_rt_rw', false);
    }

    private function createFixture(array $pengajuanOverrides = []): array
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'status' => 'active',
        ]);
        $communityUser = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        $masyarakat = Masyarakat::create([
            'user_id' => $communityUser->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pemohon Uji',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'masyarakat/ktp/ktp.png',
            'foto_selfie' => 'masyarakat/selfie/selfie.png',
        ]);
        $jenisSurat = JenisSurat::create([
            'kode' => 'STP',
            'nama' => 'Surat Test',
            'template' => 'domisili',
            'status' => true,
        ]);
        $pengajuan = PengajuanSurat::create(array_merge([
            'nomor_pengajuan' => 'PGJ-STAFF-PREVIEW',
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => 'pending',
        ], $pengajuanOverrides));

        return [$staff, $pengajuan];
    }
}
