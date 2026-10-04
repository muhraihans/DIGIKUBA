<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use App\Models\SuratTandaTangan;
use App\Models\User;
use App\Notifications\SuratDitandatanganiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TandaTanganController extends Controller
{
    /**
     * Daftar surat yang menunggu tanda tangan Lurah.
     */
    public function index()
    {
        $surat = Surat::with([
            'pengajuan.masyarakat',
            'pengajuan.jenisSurat',
        ])
            ->where('status', 'menunggu_tanda_tangan')
            ->latest()
            ->paginate(10);

        return view('lurah.tanda-tangan.index', compact('surat'));
    }

    /**
     * Detail surat sebelum ditandatangani.
     */
    public function show(Surat $surat)
{
    $surat->load([
        'pengajuan.masyarakat',
        'pengajuan.masyarakat.user',
        'pengajuan.jenisSurat',
        'tandaTangan.lurah',
        'tandaTangan.pegawai',
    ]);

    $pegawai =
        \App\Models\StrukturKepegawaian::where(
            'status',
            true
        )
        ->orderBy('jabatan')
        ->orderBy('nama')
        ->get();

    return view(
        'lurah.tanda-tangan.show',
        compact(
            'surat',
            'pegawai'
        )
    );
}
    /**
     * Proses tanda tangan digital Lurah.
     */
    public function sign(
    Request $request,
    Surat $surat
) {
    if (Auth::user()->role !== 'lurah') {

        abort(
            403,
            'Anda tidak memiliki akses untuk menandatangani surat.'
        );

    }


    if ($surat->status !== 'menunggu_tanda_tangan') {

        return back()->with(
            'warning',
            'Surat ini sudah diproses atau tidak menunggu tanda tangan.'
        );

    }


    $validated = $request->validate([

        'pegawai_id' => [
            'required',
            'exists:struktur_kepegawaian,id',
        ],

    ]);


    $pegawai =
        \App\Models\StrukturKepegawaian::where(
            'id',
            $validated['pegawai_id']
        )
        ->where('status', true)
        ->first();


    if (!$pegawai) {

        return back()->with(
            'error',
            'Pejabat penandatangan tidak aktif.'
        );

    }


    DB::beginTransaction();

    $qrPath = null;


    try {

        $surat->load([
            'pengajuan.masyarakat.user',
            'pengajuan.jenisSurat',
        ]);


        do {

            $token =
                Str::random(64);

        } while (
            SuratTandaTangan::where(
                'token',
                $token
            )->exists()
        );


        $verificationUrl =
            route(
                'verifikasi.surat',
                [
                    'token' => $token
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | QR SVG — tidak membutuhkan Imagick
        |--------------------------------------------------------------------------
        */

        $qrPath =
            'surat/qr/' .
            $token .
            '.svg';


        $qrImage =
            QrCode::format('svg')
                ->size(500)
                ->margin(2)
                ->errorCorrection('H')
                ->generate(
                    $verificationUrl
                );


        Storage::disk('private')
            ->put(
                $qrPath,
                $qrImage
            );


        SuratTandaTangan::create([

            'surat_id' =>
                $surat->id,

            'lurah_id' =>
                Auth::id(),

            'pegawai_id' =>
                $pegawai->id,

            'token' =>
                $token,

            'qr_code' =>
                $qrPath,

            'signed_at' =>
                now(),

        ]);


        $surat->update([
            'status' =>
                'ditandatangani',
        ]);


        if ($surat->pengajuan) {

            $surat->pengajuan->update([

                'status' =>
                    'selesai',

                'disetujui_oleh' =>
                    Auth::id(),

                'disetujui_at' =>
                    now(),

            ]);

        }

        $notification = new SuratDitandatanganiNotification($surat);

        $masyarakatUser = $surat->pengajuan?->masyarakat?->user;
        if ($masyarakatUser) {
            $masyarakatUser->notify($notification);
        }

        User::where('role', 'staff')
            ->where('status', 'active')
            ->get()
            ->each(function (User $staff) use ($notification) {
                $staff->notify($notification);
            });


        ActivityLog::create([

            'user_id' =>
                Auth::id(),

            'activity' =>
                'Tanda Tangan Surat',

            'description' =>
                'Menandatangani surat ' .
                $surat->nomor_surat .
                ' dengan pejabat ' .
                $pegawai->nama .
                ' (' .
                $pegawai->jabatan .
                '). QR verifikasi publik berhasil dibuat.',

            'ip_address' =>
                $request->ip(),

            'user_agent' =>
                $request->userAgent(),

        ]);


        DB::commit();


        return redirect()
            ->route(
                'lurah.tanda-tangan.signed'
            )
            ->with(
                'success',
                'Surat berhasil ditandatangani dan QR Code berhasil dibuat.'
            );


    } catch (\Throwable $e) {

        DB::rollBack();


        if ($qrPath) {

            Storage::disk('private')
                ->delete($qrPath);

        }


        return back()->with(
            'error',
            'Tanda tangan gagal: ' .
            $e->getMessage()
        );

    }
}

    /**
     * Daftar surat yang telah ditandatangani Lurah.
     */
    public function signed()
    {
        $surat = Surat::with([
            'pengajuan.masyarakat',
            'pengajuan.jenisSurat',
            'tandaTangan.lurah',
        ])
            ->where('status', 'ditandatangani')
            ->whereHas('tandaTangan')
            ->latest()
            ->paginate(10);

        return view(
            'lurah.tanda-tangan.signed',
            compact('surat')
        );
    }
}
