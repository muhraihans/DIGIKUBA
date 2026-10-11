<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Services\PdfService;

class RiwayatController extends Controller
{
    /**
     * Riwayat pengajuan.
     */
    public function index()
    {
        $masyarakat = auth()->user()->masyarakat;

        $pengajuan = PengajuanSurat::with([
            'jenisSurat',
            'surat',
        ])
            ->where(
                'masyarakat_id',
                $masyarakat->id
            )
            ->latest()
            ->paginate(10);

        return view(
            'masyarakat.riwayat.index',
            compact('pengajuan')
        );
    }

    /**
     * Download surat PDF.
     */
    public function download(
        PengajuanSurat $pengajuan,
        PdfService $pdfService
    )
    {
        $pengajuan->load([
            'masyarakat',
            'surat.tandaTangan.lurah',
            'jenisSurat',
        ]);

        // Pastikan hanya pemilik pengajuan yang dapat mengunduh
        if (
            !$pengajuan->masyarakat ||
            $pengajuan->masyarakat->user_id !== auth()->id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke surat ini.');
        }

        // Surat harus sudah selesai
        if ($pengajuan->status !== 'selesai') {
            return back()->with(
                'warning',
                'Surat belum selesai diproses dan belum dapat diunduh.'
            );
        }

        $surat = $pengajuan->surat;

        if (!$surat) {
            return back()->with(
                'error',
                'Data surat tidak ditemukan.'
            );
        }

        // Pastikan surat sudah ditandatangani
        if ($surat->status !== 'ditandatangani' && $surat->status !== 'selesai') {
            return back()->with(
                'warning',
                'Surat belum ditandatangani oleh Lurah.'
            );
        }

        // Nomor surat dapat mengandung "/"
        // sehingga harus dibersihkan sebelum menjadi nama file.
        $nomorSurat = $surat->nomor_surat ?: $pengajuan->nomor_pengajuan;

        $nomorSuratAman = preg_replace(
            '/[\/\\\\]+/',
            '-',
            $nomorSurat
        );

        $nomorSuratAman = preg_replace(
            '/[^A-Za-z0-9._-]+/',
            '-',
            $nomorSuratAman
        );

        $namaFile = 'Surat-' . trim($nomorSuratAman, '-.') . '.pdf';

        return $pdfService
            ->generate($surat)
            ->download($namaFile);
    }
}
