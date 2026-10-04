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

class SpptPbbApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pbb_form_matches_all_fields_printed_on_the_pbb_letter(): void
    {
        [$user, $masyarakat, $jenisSurat] = $this->fixture();

        $response = $this->actingAs($user)->get(route(
            'masyarakat.pengajuan.form',
            $jenisSurat
        ));

        $response->assertOk();
        foreach ([
            'nama_wajib_pajak',
            'alamat_wajib_pajak',
            'bukti_hak_milik',
            'objek_pajak',
            'alamat_objek_pajak',
            'luas_tanah',
            'luas_bangunan',
            'nop',
            'batas_utara',
            'batas_timur',
            'batas_selatan',
            'batas_barat',
        ] as $field) {
            $response->assertSee('name="' . $field . '"', false);
        }
        $response->assertDontSee('name="keperluan"', false);
    }

    public function test_pbb_inputs_are_saved_and_printed_in_the_pbb_letter(): void
    {
        Storage::fake('private');
        [$user, $masyarakat, $jenisSurat] = $this->fixture();

        $pbbData = [
            'nama_wajib_pajak' => 'Budi Santoso',
            'alamat_wajib_pajak' => 'Jalan Melati No. 10',
            'bukti_hak_milik' => 'Sertifikat Hak Milik Nomor 123/2020',
            'objek_pajak' => 'Tanah dan Bangunan',
            'alamat_objek_pajak' => 'Jalan Melati No. 10, Kutabaru',
            'luas_tanah' => '120',
            'luas_bangunan' => '80',
            'nop' => '36.19.050.007.001-0123.0',
            'batas_utara' => 'Rumah warga',
            'batas_timur' => 'Jalan Melati',
            'batas_selatan' => 'Kebun',
            'batas_barat' => 'Saluran air',
        ];

        $response = $this->actingAs($user)->post(route('masyarakat.pengajuan.store'), array_merge(
            $pbbData,
            [
                'jenis_surat_id' => $jenisSurat->id,
                'file_kk' => UploadedFile::fake()->createWithContent('kk.pdf', "%PDF-1.4\nKK"),
                'file_pengantar_rt_rw' => UploadedFile::fake()->createWithContent('pengantar.pdf', "%PDF-1.4\nPengantar"),
            ]
        ));

        $response->assertRedirect();
        $pengajuan = PengajuanSurat::latest('id')->firstOrFail();
        foreach ($pbbData as $field => $value) {
            $this->assertSame($value, $pengajuan->data_pengajuan[$field] ?? null, 'Saved value mismatch: ' . $field);
        }

        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/001/KKB/2026',
            'tanggal_surat' => '2026-10-04',
            'perihal' => 'Surat Pengantar Penerbitan SPPT-PBB',
            'status' => 'ditandatangani',
        ]);

        $html = view('pdf.sppt-pbb', [
            'surat' => $surat,
            'pengajuan' => $pengajuan,
            'masyarakat' => $masyarakat,
            'jenisSurat' => $jenisSurat,
            'dataPengajuan' => $pengajuan->data_pengajuan,
            'keperluan' => '',
            'keterangan' => '',
        ])->render();

        foreach ($pbbData as $value) {
            $this->assertStringContainsString($value, $html);
        }
        $this->assertStringContainsString('PEMERINTAH KABUPATEN TANGERANG', $html);
        $this->assertStringContainsString('signature-table', $html);
    }

    private function fixture(): array
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
            'status' => 'active',
        ]);
        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pemohon PBB',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-pbb.png',
            'foto_selfie' => 'testing/selfie-pbb.png',
        ]);
        $jenisSurat = JenisSurat::create([
            'kode' => 'PBB',
            'nama' => 'Surat Pengantar SPPT-PBB',
            'template' => 'sppt-pbb',
            'status' => true,
        ]);

        return [$user, $masyarakat, $jenisSurat];
    }
}
