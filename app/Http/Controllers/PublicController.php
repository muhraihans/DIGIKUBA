<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratTandaTangan;

class PublicController extends Controller
{
    /**
     * Halaman publik hasil scan QR Code.
     *
     * Tidak membutuhkan login.
     */
    public function verifikasiSurat(string $token)
    {
        /*
         * Cari tanda tangan berdasarkan token QR.
         */
        $tandaTangan = SuratTandaTangan::with([
            'surat.pengajuan.masyarakat',
            'surat.pengajuan.jenisSurat',
            'lurah',
        ])
            ->where('token', $token)
            ->whereNotNull('signed_at')
            ->first();

        if (!$tandaTangan) {
            abort(404, 'QR Code atau surat tidak ditemukan.');
        }

        $surat = $tandaTangan->surat;

        /*
         * Pastikan surat benar-benar sudah ditandatangani.
         */
        if (
            !$surat ||
            !in_array(
                $surat->status,
                ['ditandatangani', 'selesai'],
                true
            )
        ) {
            abort(404, 'Surat belum ditandatangani atau tidak valid.');
        }

        /*
         * Ambil seluruh surat yang telah ditandatangani Lurah.
         *
         * Inilah yang akan muncul kepada publik.
         */
        $daftarSurat = Surat::with([
            'pengajuan.masyarakat',
            'pengajuan.jenisSurat',
            'tandaTangan.lurah',
        ])
            ->whereIn('status', ['ditandatangani', 'selesai'])
            ->whereHas('tandaTangan', function ($query) {
                $query->whereNotNull('signed_at');
            })
            ->latest('tanggal_surat')
            ->paginate(10);

        return view(
            'public.verifikasi-surat',
            compact(
                'surat',
                'tandaTangan',
                'daftarSurat'
            )
        );
    }
}