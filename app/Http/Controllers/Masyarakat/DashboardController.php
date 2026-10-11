<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Jika belum diverifikasi
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'active') {

            return view('masyarakat.dashboard', [
                'user' => $user,
                'totalPengajuan' => 0,
                'pengajuanDisetujui' => 0,
                'pengajuanPerbaikan' => 0,
                'pengajuanDiproses' => 0,
                'pengajuan' => collect(),
            ]);
        }

        $masyarakat = $user->masyarakat;

        if (!$masyarakat) {
            return view('masyarakat.dashboard', [
                'user' => $user,
                'totalPengajuan' => 0,
                'pengajuanDisetujui' => 0,
                'pengajuanPerbaikan' => 0,
                'pengajuanDiproses' => 0,
                'pengajuan' => collect(),
            ]);
        }

        $query = PengajuanSurat::where('masyarakat_id', $masyarakat->id);

        $totalPengajuan = (clone $query)->count();
        $pengajuanDisetujui = (clone $query)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->count();
        $pengajuanPerbaikan = (clone $query)
            ->where('status', 'perlu_perbaikan')
            ->count();
        $pengajuanDiproses = (clone $query)
            ->whereIn('status', [
                'pending',
                'diproses',
                'diverifikasi',
                'menunggu_tanda_tangan',
            ])
            ->count();
        $pengajuan = (clone $query)
            ->with('jenisSurat')
            ->latest()
            ->limit(6)
            ->get();

        return view('masyarakat.dashboard', compact(
            'user',
            'totalPengajuan',
            'pengajuanDisetujui',
            'pengajuanPerbaikan',
            'pengajuanDiproses',
            'pengajuan'
        ));
    }
}