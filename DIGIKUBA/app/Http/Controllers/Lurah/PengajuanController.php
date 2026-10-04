<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanSurat::with([
            'masyarakat.user',
            'jenisSurat',
            'surat',
        ])
            ->whereIn('status', ['diverifikasi', 'menunggu_tanda_tangan', 'selesai'])
            ->latest()
            ->paginate(10);

        return view('lurah.pengajuan.index', compact('pengajuan'));
    }

    public function show(PengajuanSurat $pengajuan)
    {
        $pengajuan->load([
            'masyarakat.user',
            'jenisSurat',
            'komentar.user',
            'surat.tandaTangan.lurah',
        ]);

        return view('staff.pengajuan.show', compact('pengajuan'));
    }
}