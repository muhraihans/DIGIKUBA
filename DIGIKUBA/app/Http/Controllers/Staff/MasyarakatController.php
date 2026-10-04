<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Masyarakat;
use Illuminate\Http\Request;

class MasyarakatController extends Controller
{
    /**
     * Daftar masyarakat.
     */
    public function index(Request $request)
    {
        $query = Masyarakat::with('user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $masyarakat = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'staff.masyarakat.index',
            compact('masyarakat')
        );
    }

    /**
     * Detail masyarakat.
     */
    public function show(Masyarakat $masyarakat)
    {
        $masyarakat->load([
            'user',
            'pengajuanSurat.jenisSurat',
        ]);

        return view(
            'staff.masyarakat.show',
            compact('masyarakat')
        );
    }
}