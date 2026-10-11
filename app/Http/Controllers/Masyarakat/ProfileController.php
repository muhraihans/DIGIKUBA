<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Masyarakat\UpdateProfileRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Menampilkan profile.
     */
    public function index()
    {
        $user = auth()->user();

        $masyarakat = $user->masyarakat;

        $jenisKelamin = ['Laki-laki', 'Perempuan'];
        $statusPerkawinan = [
            'Belum Kawin',
            'Kawin',
            'Cerai Hidup',
            'Cerai Mati',
        ];
        $agama = [
            'Islam',
            'Kristen',
            'Katolik',
            'Hindu',
            'Buddha',
            'Konghucu',
        ];
        $pekerjaan = [
            'Belum/Tidak Bekerja',
            'Mengurus Rumah Tangga',
            'Pelajar/Mahasiswa',
            'Pensiunan',
            'Pegawai Negeri Sipil (PNS)',
            'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)',
            'TNI',
            'POLRI',
            'Karyawan Swasta',
            'Karyawan BUMN',
            'Karyawan BUMD',
            'Wiraswasta',
            'Pedagang',
            'Petani',
            'Nelayan',
            'Buruh',
            'Guru',
            'Dosen',
            'Dokter',
            'Perawat',
            'Bidan',
            'Apoteker',
            'Pengacara',
            'Notaris',
            'Arsitek',
            'Teknisi',
            'Sopir',
            'Mekanik',
            'Seniman',
            'Artis',
            'Wartawan',
            'Konsultan',
            'Freelancer',
            'Pengusaha',
            'Lainnya',
        ];

        return view(
            'masyarakat.profile.index',
            compact(
                'user',
                'masyarakat',
                'jenisKelamin',
                'statusPerkawinan',
                'agama',
                'pekerjaan'
            )
        );
    }

    /**
     * Update data yang diperbolehkan.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $masyarakat = $user->masyarakat;

        if (!$masyarakat) {
            abort(404, 'Data masyarakat tidak ditemukan.');
        }

        $validated = $request->validated();
        $profileData = Arr::only($validated, [
            'nama_lengkap',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'kewarganegaraan',
            'status_perkawinan',
            'agama',
            'pekerjaan',
            'pekerjaan_lainnya',
            'alamat',
        ]);

        if ($validated['pekerjaan'] !== 'Lainnya') {
            $profileData['pekerjaan_lainnya'] = null;
        }

        $storedFiles = [];
        $oldFiles = [];

        try {
            foreach ([
                'foto_ktp' => 'masyarakat/ktp',
                'foto_selfie' => 'masyarakat/selfie',
            ] as $field => $directory) {
                if (!$request->hasFile($field)) {
                    continue;
                }

                $storedFiles[$field] = $request->file($field)->store(
                    $directory,
                    'private'
                );
                $oldFiles[$field] = $masyarakat->{$field};
            }

            DB::transaction(function () use ($user, $masyarakat, $validated, $profileData, $storedFiles) {
                $user->update([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ]);

                $masyarakat->update(array_merge(
                    $profileData,
                    $storedFiles
                ));
            });
        } catch (Throwable $exception) {
            foreach ($storedFiles as $path) {
                Storage::disk('private')->delete($path);
            }

            throw $exception;
        }

        foreach ($oldFiles as $path) {
            if ($path) {
                Storage::disk('private')->delete($path);
            }
        }

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }
}