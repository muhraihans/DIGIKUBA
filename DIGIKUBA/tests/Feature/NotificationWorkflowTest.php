<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\StrukturKepegawaian;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotificationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_application_notifies_all_active_staff(): void
    {
        Storage::fake('private');
        [$applicant] = $this->createMasyarakat('active', 'applicant@example.test');
        $staff = User::factory()->create([
            'role' => 'staff',
            'status' => 'active',
        ]);
        $otherStaff = User::factory()->create([
            'role' => 'staff',
            'status' => 'active',
        ]);
        $inactiveStaff = User::factory()->create([
            'role' => 'staff',
            'status' => 'inactive',
        ]);
        $jenisSurat = $this->createJenisSurat('domisili');

        $response = $this->actingAs($applicant)->post(route('masyarakat.pengajuan.store'), [
            'jenis_surat_id' => $jenisSurat->id,
            'file_kk' => UploadedFile::fake()->createWithContent('kk.pdf', "%PDF-1.4\nKK"),
            'file_pengantar_rt_rw' => UploadedFile::fake()->createWithContent('pengantar.pdf', "%PDF-1.4\nPengantar"),
        ]);

        $response->assertRedirect();
        $pengajuan = PengajuanSurat::latest('id')->firstOrFail();

        foreach ([$staff, $otherStaff] as $recipient) {
            $notification = $recipient->fresh()->notifications()->first();
            $this->assertNotNull($notification);
            $this->assertSame('pengajuan_baru', $notification->data['type']);
            $this->assertSame($pengajuan->id, $notification->data['pengajuan_id']);
            $this->assertSame(route('staff.pengajuan.show', $pengajuan), $notification->data['url']);
            $this->assertSame(1, $recipient->fresh()->unreadNotifications()->count());
        }

        $this->assertSame(0, $inactiveStaff->fresh()->notifications()->count());
    }

    public function test_lurah_signature_notifies_staff_and_applicant_with_role_correct_links(): void
    {
        Storage::fake('private');
        [$applicant, $masyarakat] = $this->createMasyarakat('active', 'owner@example.test');
        $staff = User::factory()->create([
            'role' => 'staff',
            'status' => 'active',
        ]);
        $lurah = User::factory()->create([
            'role' => 'lurah',
            'status' => 'active',
        ]);
        $pegawai = StrukturKepegawaian::create([
            'nama' => 'Pejabat Contoh',
            'jabatan' => 'Sekretaris Kelurahan',
            'status' => true,
        ]);
        $jenisSurat = $this->createJenisSurat('sktm');
        $pengajuan = PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ-SIGN-NOTIF',
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => 'menunggu_tanda_tangan',
        ]);
        $surat = Surat::create([
            'pengajuan_id' => $pengajuan->id,
            'nomor_surat' => '470/005/KKB/2026',
            'tanggal_surat' => now()->toDateString(),
            'perihal' => 'Surat Keterangan',
            'status' => 'menunggu_tanda_tangan',
        ]);

        $response = $this->actingAs($lurah)->post(route(
            'lurah.tanda-tangan.sign',
            $surat
        ), [
            'pegawai_id' => $pegawai->id,
        ]);

        $response->assertRedirect(route('lurah.tanda-tangan.signed'));
        $this->assertDatabaseHas('pengajuan_surat', [
            'id' => $pengajuan->id,
            'status' => 'selesai',
        ]);

        $staffNotification = $staff->fresh()->notifications()->first();
        $applicantNotification = $applicant->fresh()->notifications()->first();

        $this->assertNotNull($staffNotification);
        $this->assertNotNull($applicantNotification);
        $this->assertSame('surat_ditandatangani', $staffNotification->data['type']);
        $this->assertSame('surat_ditandatangani', $applicantNotification->data['type']);
        $this->assertSame(route('staff.pengajuan.show', $pengajuan), $staffNotification->data['url']);
        $this->assertSame(route('masyarakat.pengajuan.show', $pengajuan), $applicantNotification->data['url']);
        $this->assertSame(1, $staff->fresh()->unreadNotifications()->count());
        $this->assertSame(1, $applicant->fresh()->unreadNotifications()->count());
    }

    private function createMasyarakat(string $status, string $email): array
    {
        $user = User::factory()->create([
            'email' => $email,
            'role' => 'masyarakat',
            'status' => $status,
        ]);
        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => str_pad((string) $user->id, 16, '0', STR_PAD_LEFT),
            'nama_lengkap' => 'Pemohon ' . $user->id,
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-' . $user->id . '.png',
            'foto_selfie' => 'testing/selfie-' . $user->id . '.png',
        ]);

        return [$user, $masyarakat];
    }

    private function createJenisSurat(string $template): JenisSurat
    {
        return JenisSurat::create([
            'kode' => strtoupper($template),
            'nama' => 'Surat ' . strtoupper($template),
            'template' => $template,
            'status' => true,
        ]);
    }
}
