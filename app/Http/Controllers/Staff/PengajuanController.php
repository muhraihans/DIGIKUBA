<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

use App\Models\ActivityLog;
use App\Models\PengajuanSurat;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PengajuanController extends Controller
{
    /**
     * Daftar pengajuan.
     */
    public function index(Request $request)
    {
        $query = PengajuanSurat::with([
            'masyarakat.user',
            'jenisSurat',
        ]);

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $pengajuan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'staff.pengajuan.index',
            compact('pengajuan')
        );
    }

    /**
     * Detail pengajuan.
     */
    public function show(PengajuanSurat $pengajuan)
    {
        $pengajuan->load([
            'masyarakat.user',
            'jenisSurat',
            'komentar.user',
            'surat',
        ]);

        return view(
            'staff.pengajuan.show',
            compact('pengajuan')
        );
    }

    public function dokumen(PengajuanSurat $pengajuan, $jenis)
    {
        $user = Auth::user();
        $allowedRoles = ['staff', 'superadmin', 'lurah', 'masyarakat'];

        if (!$user || !in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        if ($user->role === 'masyarakat') {
            $pengajuan->loadMissing('masyarakat');
            if (!$pengajuan->masyarakat || $pengajuan->masyarakat->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        }

        if (!in_array($jenis, ['ktp', 'kk', 'pengantar_rt_rw'], true)) {
            abort(404);
        }

        $pengajuan->loadMissing('masyarakat');

        $path = match ($jenis) {
            'ktp' => $pengajuan->masyarakat?->foto_ktp,
            'kk' => $pengajuan->file_kk,
            'pengantar_rt_rw' => $pengajuan->file_pengantar_rt_rw,
        };

        $disk = Storage::disk('private');

        if (!$path || !$disk->exists($path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return $disk->response(
            $path,
            basename($path),
            [
                'Content-Type' => $disk->mimeType($path),
                'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }
    /**
     * Proses verifikasi Staff.
     */
    public function verify(
        Request $request,
        PengajuanSurat $pengajuan
    ) {
        /*
    |--------------------------------------------------------------------------
    | CEK STATUS
    |--------------------------------------------------------------------------
    */

        if ($pengajuan->status !== 'pending') {

            return back()->with(
                'warning',
                'Pengajuan ini sudah diproses sebelumnya.'
            );
        }


        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | UPDATE PENGAJUAN
        |--------------------------------------------------------------------------
        */

            $pengajuan->update([
                'status' => 'menunggu_tanda_tangan',
                'diperiksa_oleh' => Auth::id(),
                'diperiksa_at' => now(),
            ]);


            /*
        |--------------------------------------------------------------------------
        | RELASI DATA
        |--------------------------------------------------------------------------
        */

            $pengajuan->load([
                'masyarakat.user',
                'jenisSurat',
            ]);


            /*
        |--------------------------------------------------------------------------
        | GENERATE NOMOR SURAT
        |--------------------------------------------------------------------------
        |
        | Format:
        | 470/001/KKB/2026
        |
        | 470  = kode pelayanan
        | 001  = nomor urut
        | KKB  = Kutabaru
        | 2026 = tahun
        |
        */

            $tahun = now()->year;


            /*
        | Ambil surat terakhir pada tahun berjalan.
        */

            $suratTerakhir = \App\Models\Surat::whereYear(
                'created_at',
                $tahun
            )
                ->orderByDesc('id')
                ->first();


            /*
        | Tentukan nomor urut berikutnya.
        */

            $nomorUrut = 1;


            if ($suratTerakhir) {

                /*
            | Contoh nomor:
            | 470/001/KKB/2026
            |
            | Ambil bagian "001".
            */

                $parts = explode(
                    '/',
                    $suratTerakhir->nomor_surat
                );


                if (
                    isset($parts[1]) &&
                    is_numeric($parts[1])
                ) {

                    $nomorUrut =
                        ((int) $parts[1]) + 1;
                }
            }


            /*
        | Format 3 digit:
        |
        | 1   -> 001
        | 10  -> 010
        | 100 -> 100
        */

            $nomorUrutFormatted =
                str_pad(
                    $nomorUrut,
                    3,
                    '0',
                    STR_PAD_LEFT
                );


            /*
        | Nomor surat final.
        */

            $nomorSurat =
                '470/' .
                $nomorUrutFormatted .
                '/KKB/' .
                $tahun;


            /*
        |--------------------------------------------------------------------------
        | CEK APAKAH SURAT SUDAH ADA
        |--------------------------------------------------------------------------
        */

            $suratSudahAda =
                \App\Models\Surat::where(
                    'pengajuan_id',
                    $pengajuan->id
                )->first();


            if (!$suratSudahAda) {

                /*
            |--------------------------------------------------------------------------
            | BUAT DATA SURAT
            |--------------------------------------------------------------------------
            */

                \App\Models\Surat::create([

                    'pengajuan_id' =>
                    $pengajuan->id,

                    'nomor_surat' =>
                    $nomorSurat,

                    'tanggal_surat' =>
                    now()->toDateString(),

                    'perihal' =>
                    $pengajuan
                        ->jenisSurat
                        ->nama
                        ?? 'Surat Kelurahan',

                    'status' =>
                    'menunggu_tanda_tangan',

                ]);
            }


            /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI MASYARAKAT
        |--------------------------------------------------------------------------
        */

            $masyarakat =
                $pengajuan->masyarakat;


            if (
                $masyarakat &&
                $masyarakat->user
            ) {

                if (
                    class_exists(
                        \App\Notifications\PengajuanDiterimaNotification::class
                    )
                ) {

                    $masyarakat->user->notify(
                        new \App\Notifications\PengajuanDiterimaNotification(
                            $pengajuan
                        )
                    );
                }
            }


            /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

            if (
                class_exists(
                    \App\Models\ActivityLog::class
                )
            ) {

                \App\Models\ActivityLog::create([

                    'user_id' =>
                    Auth::id(),

                    'activity' =>
                    'Verifikasi Pengajuan Surat',

                    'description' =>
                    'Memverifikasi pengajuan surat ' .
                        $pengajuan->nomor_pengajuan .
                        ' dan meneruskan ke proses tanda tangan Lurah. ' .
                        'Nomor surat: ' .
                        $nomorSurat,

                    'ip_address' =>
                    $request->ip(),

                    'user_agent' =>
                    $request->userAgent(),

                ]);
            }


            /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

            DB::commit();


            /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

            return redirect()
                ->route(
                    'staff.pengajuan.index'
                )
                ->with(
                    'success',
                    'Pengajuan berhasil diverifikasi dan diteruskan ke Lurah. Nomor surat: ' .
                        $nomorSurat
                );
        } catch (\Throwable $e) {

            /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

            DB::rollBack();


            return back()
                ->withInput()
                ->with(
                    'error',
                    'Verifikasi pengajuan gagal: ' .
                        $e->getMessage()
                );
        }
    }
}
