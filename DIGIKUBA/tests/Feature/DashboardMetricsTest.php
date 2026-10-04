<?php

namespace Tests\Feature;

use App\Models\JenisSurat;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_metrics_match_workflow_statuses_and_show_recent_applications(): void
    {
        $jenisSurat = JenisSurat::create([
            'kode' => 'DBT',
            'nama' => 'Surat Contoh',
            'status' => true,
        ]);

        [$activeUser, $activeMasyarakat] = $this->createMasyarakat('active', 1);
        [, $pendingMasyarakat] = $this->createMasyarakat('pending', 2);

        $pending = $this->createPengajuan($activeMasyarakat, $jenisSurat, 'pending', 'PGJ-PENDING');
        $revision = $this->createPengajuan($activeMasyarakat, $jenisSurat, 'perlu_perbaikan', 'PGJ-REVISION');
        $completed = $this->createPengajuan($activeMasyarakat, $jenisSurat, 'selesai', 'PGJ-COMPLETED');
        $rejected = $this->createPengajuan($pendingMasyarakat, $jenisSurat, 'ditolak', 'PGJ-REJECTED');

        $this->actingAs($activeUser)
            ->get(route('masyarakat.dashboard'))
            ->assertOk()
            ->assertViewHas('totalPengajuan', 3)
            ->assertViewHas('pengajuanDisetujui', 1)
            ->assertViewHas('pengajuanPerbaikan', 1)
            ->assertViewHas('pengajuan', function ($rows) use ($pending, $revision, $completed) {
                return $rows->count() === 3
                    && $rows->pluck('id')->contains($pending->id)
                    && $rows->pluck('id')->contains($revision->id)
                    && $rows->pluck('id')->contains($completed->id);
            });

        $staff = User::factory()->create([
            'role' => 'staff',
            'status' => 'active',
        ]);

        $this->actingAs($staff)
            ->get(route('staff.dashboard'))
            ->assertOk()
            ->assertViewHas('totalMasyarakat', 2)
            ->assertViewHas('menungguVerifikasiAkun', 1)
            ->assertViewHas('totalPengajuan', 4)
            ->assertViewHas('menungguVerifikasiSurat', 1)
            ->assertViewHas('pengajuanPerluPerbaikan', 1)
            ->assertViewHas('pengajuanDitolak', 1)
            ->assertViewHas('pengajuanSelesai', 1)
            ->assertViewHas('pengajuan', function ($rows) use ($pending, $rejected) {
                return $rows->count() === 4
                    && $rows->pluck('id')->contains($pending->id)
                    && $rows->pluck('id')->contains($rejected->id);
            });

        $waitingSurat = Surat::create([
            'pengajuan_id' => $pending->id,
            'nomor_surat' => '400/001/2026',
            'perihal' => 'Surat Menunggu Tanda Tangan',
            'status' => 'menunggu_tanda_tangan',
        ]);
        Surat::create([
            'pengajuan_id' => $completed->id,
            'nomor_surat' => '400/002/2026',
            'perihal' => 'Surat Selesai',
            'status' => 'ditandatangani',
        ]);

        $lurah = User::factory()->create([
            'role' => 'lurah',
            'status' => 'active',
        ]);

        $this->actingAs($lurah)
            ->get(route('lurah.dashboard'))
            ->assertOk()
            ->assertViewHas('menungguTandaTangan', 1)
            ->assertViewHas('ditandatangani', 1)
            ->assertViewHas('selesai', 1)
            ->assertViewHas('totalPengajuan', 4)
            ->assertViewHas('pengajuanTerbaru', function ($rows) use ($waitingSurat) {
                return $rows->count() === 4
                    && $rows->firstWhere('id', $waitingSurat->pengajuan_id) !== null;
            });

        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        $this->actingAs($superadmin)
            ->get(route('superadmin.dashboard'))
            ->assertOk()
            ->assertViewHas('totalUsers', 5)
            ->assertViewHas('totalMasyarakat', 2)
            ->assertViewHas('totalStaff', 1)
            ->assertViewHas('totalLurah', 1)
            ->assertViewHas('pendingAkun', 1)
            ->assertViewHas('totalPengajuan', 4)
            ->assertViewHas('pengajuanSelesai', 1)
            ->assertViewHas('totalSurat', 2)
            ->assertViewHas('pengajuanTerbaru', function ($rows) use ($pending) {
                return $rows->count() === 4
                    && $rows->pluck('id')->contains($pending->id);
            });
    }

    private function createMasyarakat(string $status, int $sequence): array
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
            'status' => $status,
        ]);

        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => str_pad((string) $sequence, 16, '0', STR_PAD_LEFT),
            'nama_lengkap' => 'Pemohon ' . $sequence,
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Kelurahan Kutabaru',
            'foto_ktp' => 'testing/ktp-' . $sequence . '.png',
            'foto_selfie' => 'testing/selfie-' . $sequence . '.png',
        ]);

        return [$user, $masyarakat];
    }

    private function createPengajuan(
        Masyarakat $masyarakat,
        JenisSurat $jenisSurat,
        string $status,
        string $nomor
    ): PengajuanSurat {
        return PengajuanSurat::create([
            'nomor_pengajuan' => $nomor,
            'masyarakat_id' => $masyarakat->id,
            'jenis_surat_id' => $jenisSurat->id,
            'data_pengajuan' => [],
            'status' => $status,
        ]);
    }
}
