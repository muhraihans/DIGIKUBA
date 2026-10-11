<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Masyarakat;
use App\Models\PengajuanSurat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMasyarakat = Masyarakat::count();

        $menungguVerifikasiAkun = User::where('role', 'masyarakat')
            ->where('status', 'pending')
            ->count();

        $totalPengajuan = PengajuanSurat::count();

        $menungguVerifikasiSurat = PengajuanSurat::where(
            'status',
            'pending'
        )->count();

        $pengajuanPerluPerbaikan = PengajuanSurat::where(
            'status',
            'perlu_perbaikan'
        )->count();

        $pengajuanSelesai = PengajuanSurat::where(
            'status',
            'selesai'
        )->count();

        $pengajuanDitolak = PengajuanSurat::where(
            'status',
            'ditolak'
        )->count();

        $pengajuan = PengajuanSurat::with([
            'masyarakat',
            'jenisSurat',
        ])
            ->latest()
            ->limit(8)
            ->get();

        return view('staff.dashboard', compact(
            'totalMasyarakat',
            'menungguVerifikasiAkun',
            'totalPengajuan',
            'menungguVerifikasiSurat',
            'pengajuanPerluPerbaikan',
            'pengajuanSelesai',
            'pengajuanDitolak',
            'pengajuan',
            'totalPengajuan'
        ));
    }
}