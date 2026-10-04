<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AccountVerifiedNotification;
use App\Notifications\AccountRejectedNotification;

class VerifikasiAkunController extends Controller
{
    /**
     * ============================================================
     * DAFTAR AKUN MASYARAKAT YANG MENUNGGU VERIFIKASI
     * ============================================================
     */
    public function index()
    {
        $masyarakat = Masyarakat::with('user')
            ->whereHas('user', function ($query) {
                $query
                    ->where('role', 'masyarakat')
                    ->where('status', 'pending');
            })
            ->latest()
            ->paginate(10);

        return view(
            'staff.verifikasi-akun.index',
            compact('masyarakat')
        );
    }


    /**
     * ============================================================
     * DETAIL AKUN MASYARAKAT
     * ============================================================
     */
    public function show(Masyarakat $masyarakat)
    {
        /*
        |--------------------------------------------------------------------------
        | Load relasi user
        |--------------------------------------------------------------------------
        */
        $masyarakat->load('user');


        /*
        |--------------------------------------------------------------------------
        | Pastikan user tersedia
        |--------------------------------------------------------------------------
        */
        if (!$masyarakat->user) {
            abort(
                404,
                'Data akun masyarakat tidak ditemukan.'
            );
        }


        return view(
            'staff.verifikasi-akun.show',
            compact('masyarakat')
        );
    }


    /**
     * ============================================================
     * MENAMPILKAN DOKUMEN PRIVATE
     * ============================================================
     *
     * $jenis:
     * - ktp
     * - selfie
     *
     * File tidak dibuat public.
     * Akses hanya melalui route yang dilindungi middleware auth
     * dan role staff/superadmin.
     */
    public function dokumen(
        Masyarakat $masyarakat,
        string $jenis
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validasi jenis dokumen
        |--------------------------------------------------------------------------
        */
        if (!in_array(
            $jenis,
            [
                'ktp',
                'selfie',
            ],
            true
        )) {
            abort(
                404,
                'Jenis dokumen tidak valid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil path berdasarkan jenis dokumen
        |--------------------------------------------------------------------------
        */
        $path = match ($jenis) {

            'ktp' => $masyarakat->foto_ktp,

            'selfie' => $masyarakat->foto_selfie,

        };


        /*
        |--------------------------------------------------------------------------
        | Pastikan database memiliki path
        |--------------------------------------------------------------------------
        */
        if (empty($path)) {

            abort(
                404,
                'Dokumen belum tersedia.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Gunakan private disk
        |--------------------------------------------------------------------------
        */
        $disk = Storage::disk('private');


        /*
        |--------------------------------------------------------------------------
        | Pastikan file benar-benar ada
        |--------------------------------------------------------------------------
        */
        if (!$disk->exists($path)) {

            abort(
                404,
                'File dokumen tidak ditemukan di server.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil absolute path
        |--------------------------------------------------------------------------
        */
        $absolutePath = $disk->path($path);


        /*
        |--------------------------------------------------------------------------
        | Pastikan file merupakan file
        |--------------------------------------------------------------------------
        */
        if (!is_file($absolutePath)) {

            abort(
                404,
                'Dokumen tidak dapat dibaca.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Tentukan MIME Type
        |--------------------------------------------------------------------------
        */
        $mimeType = mime_content_type($absolutePath);


        /*
        |--------------------------------------------------------------------------
        | MIME yang diizinkan
        |--------------------------------------------------------------------------
        */
        $allowedMimeTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
        ];


        /*
        |--------------------------------------------------------------------------
        | Tolak file dengan MIME tidak dikenal
        |--------------------------------------------------------------------------
        */
        if (!in_array(
            $mimeType,
            $allowedMimeTypes,
            true
        )) {

            abort(
                403,
                'Format dokumen tidak diperbolehkan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Tampilkan file ke browser
        |--------------------------------------------------------------------------
        */
        return response()->file(
            $absolutePath,
            [
                'Content-Type' => $mimeType,

                'Content-Disposition' =>
                    'inline; filename="' .
                    basename($absolutePath) .
                    '"',

                'Cache-Control' =>
                    'private, no-store, no-cache, must-revalidate',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]
        );
    }


    /**
     * ============================================================
     * VERIFIKASI AKUN
     * ============================================================
     */
    public function verify(
        Request $request,
        Masyarakat $masyarakat
    ) {
        /*
        |--------------------------------------------------------------------------
        | Ambil user
        |--------------------------------------------------------------------------
        */
        $user = $masyarakat->user;


        /*
        |--------------------------------------------------------------------------
        | User harus tersedia
        |--------------------------------------------------------------------------
        */
        if (!$user) {

            return back()->with(
                'error',
                'Data akun masyarakat tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan role masyarakat
        |--------------------------------------------------------------------------
        */
        if ($user->role !== 'masyarakat') {

            return back()->with(
                'error',
                'Akun yang dipilih bukan akun masyarakat.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Hanya akun pending yang dapat diverifikasi
        |--------------------------------------------------------------------------
        */
        if ($user->status !== 'pending') {

            return back()->with(
                'warning',
                'Akun ini sudah diproses sebelumnya.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan KTP tersedia
        |--------------------------------------------------------------------------
        */
        if (empty($masyarakat->foto_ktp)) {

            return back()->with(
                'error',
                'Foto KTP belum tersedia. Akun tidak dapat diverifikasi.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan selfie tersedia
        |--------------------------------------------------------------------------
        */
        if (empty($masyarakat->foto_selfie)) {

            return back()->with(
                'error',
                'Foto selfie belum tersedia. Akun tidak dapat diverifikasi.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan file KTP benar-benar ada
        |--------------------------------------------------------------------------
        */
        if (!Storage::disk('private')->exists(
            $masyarakat->foto_ktp
        )) {

            return back()->with(
                'error',
                'File KTP tidak ditemukan di server.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan file selfie benar-benar ada
        |--------------------------------------------------------------------------
        */
        if (!Storage::disk('private')->exists(
            $masyarakat->foto_selfie
        )) {

            return back()->with(
                'error',
                'File selfie tidak ditemukan di server.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update status user
        |--------------------------------------------------------------------------
        */
        $user->update([
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update data verifikasi masyarakat
        |--------------------------------------------------------------------------
        */
        $masyarakat->update([
            'verified_at' => now(),

            'verified_by' => Auth::id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        |
        | Pastikan kedua Notification class tersedia.
        |
        */

        if (
            class_exists(
                AccountVerifiedNotification::class
            )
        ) {

            $user->notify(
                new AccountVerifiedNotification()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */
        ActivityLog::create([
            'user_id' => Auth::id(),

            'activity' =>
                'Verifikasi Akun Masyarakat',

            'description' =>
                'Memverifikasi akun masyarakat: ' .
                $masyarakat->nama_lengkap .
                ' (NIK: ' .
                $masyarakat->nik .
                ')',

            'ip_address' =>
                $request->ip(),

            'user_agent' =>
                $request->userAgent(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'staff.verifikasi-akun.index'
            )
            ->with(
                'success',
                'Akun masyarakat berhasil diverifikasi.'
            );
    }


    /**
     * ============================================================
     * MENOLAK AKUN MASYARAKAT
     * ============================================================
     */
    public function reject(
        Request $request,
        Masyarakat $masyarakat
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validasi catatan
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate(
            [
                'catatan' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'catatan.required' =>
                    'Alasan penolakan wajib diisi.',

                'catatan.string' =>
                    'Alasan penolakan tidak valid.',

                'catatan.min' =>
                    'Alasan penolakan minimal 5 karakter.',

                'catatan.max' =>
                    'Alasan penolakan maksimal 1000 karakter.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Ambil user
        |--------------------------------------------------------------------------
        */
        $user = $masyarakat->user;


        /*
        |--------------------------------------------------------------------------
        | User tidak ditemukan
        |--------------------------------------------------------------------------
        */
        if (!$user) {

            return back()->with(
                'error',
                'Data akun masyarakat tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pastikan role masyarakat
        |--------------------------------------------------------------------------
        */
        if ($user->role !== 'masyarakat') {

            return back()->with(
                'error',
                'Akun yang dipilih bukan akun masyarakat.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Hanya pending yang dapat ditolak
        |--------------------------------------------------------------------------
        */
        if ($user->status !== 'pending') {

            return back()->with(
                'warning',
                'Akun ini sudah diproses sebelumnya.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update status
        |--------------------------------------------------------------------------
        */
        $user->update([
            'status' => 'rejected',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Simpan informasi verifikasi
        |--------------------------------------------------------------------------
        */
        $masyarakat->update([
            'verified_at' => null,

            'verified_by' => Auth::id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI PENOLAKAN
        |--------------------------------------------------------------------------
        */
        if (
            class_exists(
                AccountRejectedNotification::class
            )
        ) {

            $user->notify(
                new AccountRejectedNotification(
                    $validated['catatan']
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */
        ActivityLog::create([
            'user_id' => Auth::id(),

            'activity' =>
                'Menolak Akun Masyarakat',

            'description' =>
                'Menolak akun masyarakat: ' .
                $masyarakat->nama_lengkap .
                ' (NIK: ' .
                $masyarakat->nik .
                '). Alasan: ' .
                $validated['catatan'],

            'ip_address' =>
                $request->ip(),

            'user_agent' =>
                $request->userAgent(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'staff.verifikasi-akun.index'
            )
            ->with(
                'success',
                'Akun masyarakat berhasil ditolak.'
            );
    }
}