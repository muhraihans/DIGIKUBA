<?php

namespace Tests\Feature;

use App\Models\Masyarakat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasyarakatProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_shows_and_updates_all_registered_biodata(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Sebelum',
            'email' => 'sebelum@example.test',
            'role' => 'masyarakat',
            'status' => 'active',
        ]);

        $masyarakat = Masyarakat::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Nama Sebelum',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Tangerang',
            'tanggal_lahir' => '1990-03-15',
            'kewarganegaraan' => 'Indonesia',
            'status_perkawinan' => 'Belum Kawin',
            'agama' => 'Islam',
            'pekerjaan' => 'Lainnya',
            'pekerjaan_lainnya' => 'Penjahit',
            'alamat' => 'Alamat lama',
            'foto_ktp' => 'testing/ktp-profile.png',
            'foto_selfie' => 'testing/selfie-profile.png',
        ]);

        $this->actingAs($user)
            ->get(route('masyarakat.profile.index'))
            ->assertOk()
            ->assertSee('Jenis Kelamin')
            ->assertSee('Kewarganegaraan')
            ->assertSee('Status Perkawinan')
            ->assertSee('Agama')
            ->assertSee('Pekerjaan')
            ->assertSee('Foto Selfie');

        $response = $this->put(route('masyarakat.profile.update'), [
            'nama_lengkap' => 'Nama Baru',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-03-15',
            'kewarganegaraan' => 'Indonesia',
            'status_perkawinan' => 'Kawin',
            'agama' => 'Islam',
            'pekerjaan' => 'Lainnya',
            'pekerjaan_lainnya' => 'Wirausaha',
            'alamat' => 'Alamat baru',
            'email' => 'baru@example.test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@example.test',
        ]);
        $this->assertDatabaseHas('masyarakat', [
            'id' => $masyarakat->id,
            'nama_lengkap' => 'Nama Baru',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Jakarta',
            'kewarganegaraan' => 'Indonesia',
            'status_perkawinan' => 'Kawin',
            'agama' => 'Islam',
            'pekerjaan' => 'Lainnya',
            'pekerjaan_lainnya' => 'Wirausaha',
            'alamat' => 'Alamat baru',
        ]);
    }
}
