<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\User;
use App\Services\PdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class SharedSuratAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_superadmin_and_lurah_can_download_completed_letters(): void
    {
        [$owner, $masyarakat, $pengajuan, $surat] = $this->createCompletedSurat();
        $roles = ['staff', 'superadmin', 'lurah'];

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'role' => $role,
                'status' => 'active',
            ]);

            $pdf = Mockery::mock();
            $pdf->shouldReceive('download')
                ->once()
                ->with('Surat-470-001-KKB-2026.pdf')
                ->andReturn(response('authorized-pdf-' . $role, 200, ['Content-Type' => 'application/pdf']));

            $service = Mockery::mock(PdfService::class);
            $service->shouldReceive('generate')
                ->once()
                ->with(Mockery::on(fn (Surat $received) => $received->is($surat)))
                ->andReturn($pdf);
            $this->app->instance(PdfService::class, $service);

            $response = $this->actingAs($user)->get(route(
                'surat.pengajuan.download',
                $pengajuan
            ));

            $response->assertOk();
            $response->assertSee('authorized-pdf-' . $role);
        }
    }

    public function test_citizen_can_download_only_own_letter_and_other_citizen_is_forbidden(): void
    {
        [, , $pengajuan, $surat] = $this->createCompletedSurat();
        $owner = $surat->pengajuan->masyarakat->user;
        $otherUser = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        Masyarakat::create([
            'user_id' => $otherUser->id,
            'nik' => '1234567890123457',
            'nama_lengkap' => 'Masyarakat Lain',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-other.png',
            'foto_selfie' => 'testing/selfie-other.png',
        ]);

        $this->actingAs($otherUser)
            ->get(route('surat.pengajuan.download', $pengajuan))
            ->assertForbidden();

        $pdf = Mockery::mock();
        $pdf->shouldReceive('download')
            ->once()
            ->andReturn(response('owner-pdf', 200, ['Content-Type' => 'application/pdf']));
        $service = Mockery::mock(PdfService::class);
        $service->shouldReceive('generate')->once()->andReturn($pdf);
        $this->app->instance(PdfService::class, $service);

        $this->actingAs($owner)
            ->get(route('surat.pengajuan.download', $pengajuan))
            ->assertOk()
            ->assertSee('owner-pdf');
    }

    public function test_all_authorized_roles_can_preview_private_requirement_documents(): void
    {
        \Illuminate\Support\Facades\Storage::fake('private');
        [$owner, , $pengajuan] = $this->createCompletedSurat();
        $path = 'pengajuan/kk/access-test.pdf';
        \Illuminate\Support\Facades\Storage::disk('private')->put($path, "%PDF-1.4\nTest document\n");
        $pengajuan->update(['file_kk' => $path]);

        foreach (['masyarakat' => $owner, 'staff' => User::factory()->create(['role' => 'staff', 'status' => 'active']), 'superadmin' => User::factory()->create(['role' => 'superadmin', 'status' => 'active']), 'lurah' => User::factory()->create(['role' => 'lurah', 'status' => 'active'])] as $role => $user) {
            $response = $this->actingAs($user)->get(route(
                'pengajuan.dokumen',
                ['pengajuan' => $pengajuan, 'jenis' => 'kk']
            ));

            $response->assertOk();
            $response->assertHeader('Content-Type', 'application/pdf');
            $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition'));
        }
    }

    private function createCompletedSurat(): array
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pemilik Surat',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-owner.png',
            'foto_selfie' => 'testing/selfie-owner.png',
        ]);
        $jenisSurat = JenisSurat::create([
            'kode' => 'TST',
            'nama' => 'Surat Uji',
            'template' => 'domisili',
            'status' => true,
        ]);
        $pengajuan = PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ-SHARED-ACCESS',
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => 'selesai',
        ]);
        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/001/KKB/2026',
            'tanggal_surat' => '2026-10-04',
            'perihal' => 'Surat Keterangan',
            'status' => 'ditandatangani',
        ]);

        return [$user, $masyarakat, $pengajuan, $surat];
    }
}
