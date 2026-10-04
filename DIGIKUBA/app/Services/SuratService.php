<?php

namespace App\Services;

use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\SuratTandaTangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuratService
{
    public function generateNomorPengajuan(): string
    {
        do {
            $nomor = 'PGJ-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));
        } while (
            PengajuanSurat::where(
                'nomor_pengajuan',
                $nomor
            )->exists()
        );

        return $nomor;
    }

    public function generateNomorSurat(): string
    {
        do {
            $nomor = '400/' .
                now()->format('Y') .
                '/' .
                strtoupper(Str::random(6));
        } while (
            Surat::where(
                'nomor_surat',
                $nomor
            )->exists()
        );

        return $nomor;
    }

    public function createApplication(
        int $masyarakatId,
        int $jenisSuratId,
        array $data
    ): PengajuanSurat {

        return PengajuanSurat::create([
            'nomor_pengajuan' =>
                $this->generateNomorPengajuan(),

            'masyarakat_id' =>
                $masyarakatId,

            'jenis_surat_id' =>
                $jenisSuratId,

            'data_pengajuan' =>
                $data,

            'status' =>
                'pending',
        ]);
    }

    public function verifyApplication(
        PengajuanSurat $pengajuan,
        int $staffId
    ): PengajuanSurat {

        return DB::transaction(function () use (
            $pengajuan,
            $staffId
        ) {

            $pengajuan->update([
                'status' => 'menunggu_tanda_tangan',
                'diperiksa_oleh' => $staffId,
                'diperiksa_at' => now(),
            ]);

            $surat = $pengajuan->surat;

            if (!$surat) {

                $surat = Surat::create([
                    'pengajuan_id' =>
                        $pengajuan->id,

                    'nomor_surat' =>
                        $this->generateNomorSurat(),

                    'tanggal_surat' =>
                        now()->toDateString(),

                    'perihal' =>
                        $pengajuan->jenisSurat->nama,

                    'status' =>
                        'menunggu_tanda_tangan',
                ]);
            } else {

                $surat->update([
                    'status' =>
                        'menunggu_tanda_tangan',
                ]);
            }

            return $pengajuan->fresh([
                'masyarakat.user',
                'jenisSurat',
                'surat',
            ]);
        });
    }

    public function requestRevision(
        PengajuanSurat $pengajuan,
        string $catatan,
        int $staffId
    ): PengajuanSurat {

        return DB::transaction(function () use (
            $pengajuan,
            $catatan,
            $staffId
        ) {

            $pengajuan->update([
                'status' => 'perlu_perbaikan',
                'diperiksa_oleh' => $staffId,
                'diperiksa_at' => now(),
                'catatan' => $catatan,
            ]);

            return $pengajuan->fresh();
        });
    }

    public function rejectApplication(
        PengajuanSurat $pengajuan,
        string $catatan,
        int $userId
    ): PengajuanSurat {

        return DB::transaction(function () use (
            $pengajuan,
            $catatan,
            $userId
        ) {

            $pengajuan->update([
                'status' => 'ditolak',
                'ditolak_oleh' => $userId,
                'ditolak_at' => now(),
                'catatan' => $catatan,
            ]);

            return $pengajuan->fresh();
        });
    }

    public function createSignature(
        Surat $surat,
        int $lurahId
    ): SuratTandaTangan {

        return DB::transaction(function () use (
            $surat,
            $lurahId
        ) {

            $token = Str::random(64);

            $signature = SuratTandaTangan::updateOrCreate(
                [
                    'surat_id' => $surat->id,
                ],
                [
                    'lurah_id' => $lurahId,
                    'token' => $token,
                    'signed_at' => now(),
                ]
            );

            $surat->update([
                'status' => 'ditandatangani',
            ]);

            $surat->pengajuan->update([
                'status' => 'selesai',
                'disetujui_oleh' => $lurahId,
                'disetujui_at' => now(),
            ]);

            return $signature->fresh();
        });
    }
}