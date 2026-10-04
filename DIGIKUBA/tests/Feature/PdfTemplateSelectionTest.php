<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\User;
use App\Services\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PdfTemplateSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_service_uses_the_selected_letter_template(): void
    {
        [$user, $masyarakat] = $this->createMasyarakat();
        $templates = [
            'domisili' => 'pdf.domisili',
            'sktm' => 'pdf.sktm',
            'sppt-pbb' => 'pdf.sppt-pbb',
            'other' => 'pdf.surat',
        ];
        $surats = [];

        foreach ($templates as $template => $expectedView) {
            $jenisSurat = JenisSurat::create([
                'kode' => strtoupper($template),
                'nama' => 'Surat ' . $template,
                'template' => $template === 'other' ? 'template-lain' : $template,
                'status' => true,
            ]);
            $pengajuan = PengajuanSurat::create([
                'nomor_pengajuan' => 'PGJ-' . strtoupper($template),
                'masyarakat_id' => $masyarakat->id,
                'jenis_surat_id' => $jenisSurat->id,
                'data_pengajuan' => ['keperluan' => 'Uji template'],
                'status' => 'selesai',
            ]);
            $surats[$template] = Surat::create([
                'pengajuan_id' => $pengajuan->id,
                'nomor_surat' => '470/' . count($surats) . '/KKB/2026',
                'perihal' => 'Surat ' . $template,
                'status' => 'ditandatangani',
            ]);
        }

        $renderer = Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $renderer->shouldReceive('setPaper')
            ->times(count($templates))
            ->with('A4', 'portrait')
            ->andReturnSelf();

        $actualViews = [];
        Pdf::shouldReceive('loadView')
            ->times(count($templates))
            ->andReturnUsing(function ($view, $data) use (&$actualViews, $renderer) {
                $actualViews[] = $view;
                $this->assertArrayHasKey('surat', $data);
                $this->assertArrayHasKey('pengajuan', $data);
                $this->assertArrayHasKey('masyarakat', $data);
                $this->assertArrayHasKey('dataPengajuan', $data);

                return $renderer;
            });

        $service = app(PdfService::class);
        foreach ($surats as $surat) {
            $service->generate($surat);
        }

        $this->assertSame(array_values($templates), $actualViews);
    }

    public function test_community_download_uses_the_pdf_service_and_keeps_a_safe_filename(): void
    {
        [$user, $masyarakat] = $this->createMasyarakat();
        $jenisSurat = JenisSurat::create([
            'kode' => 'DOM',
            'nama' => 'Surat Domisili',
            'template' => 'domisili',
            'status' => true,
        ]);
        $pengajuan = PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ-DOM-01',
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => 'selesai',
        ]);
        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/001/KKB/2026',
            'perihal' => 'Surat Domisili',
            'status' => 'ditandatangani',
        ]);

        $pdf = Mockery::mock();
        $pdf->shouldReceive('download')
            ->once()
            ->with('Surat-470-001-KKB-2026.pdf')
            ->andReturn(response('generated-pdf', 200, ['Content-Type' => 'application/pdf']));

        $pdfService = Mockery::mock(PdfService::class);
        $pdfService->shouldReceive('generate')
            ->once()
            ->with(Mockery::on(fn (Surat $received) => $received->is($surat)))
            ->andReturn($pdf);
        $this->app->instance(PdfService::class, $pdfService);

        $response = $this->actingAs($user)->get(route(
            'masyarakat.riwayat.download',
            $pengajuan
        ));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertSee('generated-pdf');
    }

    private function createMasyarakat(): array
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pemohon PDF',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-pdf.png',
            'foto_selfie' => 'testing/selfie-pdf.png',
        ]);

        return [$user, $masyarakat];
    }
}
