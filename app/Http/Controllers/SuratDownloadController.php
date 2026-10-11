<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Services\PdfService;
use Illuminate\Http\Request;

class SuratDownloadController extends Controller
{
    public function __invoke(Request $request, PengajuanSurat $pengajuan, PdfService $pdfService)
    {
        $user = $request->user();
        $allowedRoles = ['masyarakat', 'staff', 'superadmin', 'lurah'];

        if (!$user || !in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh surat ini.');
        }

        if ($user->role === 'masyarakat') {
            $pengajuan->loadMissing('masyarakat');

            if (!$pengajuan->masyarakat || $pengajuan->masyarakat->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengunduh surat ini.');
            }
        }

        if ($pengajuan->status !== 'selesai') {
            return back()->with('warning', 'Surat belum selesai diproses dan belum dapat diunduh.');
        }

        $pengajuan->loadMissing('surat');
        $surat = $pengajuan->surat;

        if (!$surat) {
            return back()->with('error', 'Data surat tidak ditemukan.');
        }

        if (!in_array($surat->status, ['ditandatangani', 'selesai'], true)) {
            return back()->with('warning', 'Surat belum ditandatangani oleh Lurah.');
        }

        $nomorSurat = $surat->nomor_surat ?: $pengajuan->nomor_pengajuan;
        $safeNumber = preg_replace('/[\/\\\\]+/', '-', $nomorSurat);
        $safeNumber = preg_replace('/[^A-Za-z0-9._-]+/', '-', $safeNumber);
        $filename = 'Surat-' . trim($safeNumber, '-.') . '.pdf';

        return $pdfService->generate($surat)->download($filename);
    }
}