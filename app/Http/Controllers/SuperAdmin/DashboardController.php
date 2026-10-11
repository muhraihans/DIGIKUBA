<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $totalMasyarakat = Masyarakat::count();

        $totalStaff = User::where(
            'role',
            'staff'
        )->count();

        $totalLurah = User::where(
            'role',
            'lurah'
        )->count();

        $pendingAkun = User::where(
            'role',
            'masyarakat'
        )
            ->where(
                'status',
                'pending'
            )
            ->count();

        $totalPengajuan = PengajuanSurat::count();

        $pengajuanSelesai = PengajuanSurat::where(
            'status',
            'selesai'
        )->count();

        $totalSurat = Surat::count();

        $pengajuanTerbaru = PengajuanSurat::with([
            'masyarakat',
            'jenisSurat',
        ])
            ->latest()
            ->limit(8)
            ->get();

        return view(
            'superadmin.dashboard',
            compact(
                'totalUsers',
                'totalMasyarakat',
                'totalStaff',
                'totalLurah',
                'pendingAkun',
                'totalPengajuan',
                'pengajuanSelesai',
                'totalSurat',
                'pengajuanTerbaru'
            )
        );
    }
}