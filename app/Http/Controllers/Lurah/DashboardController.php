<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\Surat;

class DashboardController extends Controller
{
    public function index()
    {
        $menungguTandaTangan = Surat::where(
            'status',
            'menunggu_tanda_tangan'
        )->count();

        $ditandatangani = Surat::where(
            'status',
            'ditandatangani'
        )->count();

        $totalPengajuan = PengajuanSurat::count();

        $selesai = PengajuanSurat::where(
            'status',
            'selesai'
        )->count();

        $pengajuanTerbaru = PengajuanSurat::with([
            'masyarakat',
            'jenisSurat',
            'surat',
        ])
            ->latest()
            ->limit(8)
            ->get();

        return view(
            'lurah.dashboard',
            compact(
                'menungguTandaTangan',
                'ditandatangani',
                'totalPengajuan',
                'selesai',
                'pengajuanTerbaru'
            )
        );
    }
}