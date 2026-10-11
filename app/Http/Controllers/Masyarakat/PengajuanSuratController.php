<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanSuratController extends Controller
{
    /**
     * Menampilkan daftar pengajuan surat masyarakat.
     */
    public function index()
    {
        $masyarakat = Auth::user()->masyarakat;

        if (!$masyarakat) {
            abort(
                404,
                'Data masyarakat tidak ditemukan.'
            );
        }

        $pengajuan = PengajuanSurat::with('jenisSurat')
            ->where('masyarakat_id', $masyarakat->id)
            ->latest()
            ->paginate(10);

        return view(
            'masyarakat.pengajuan.index',
            compact('pengajuan')
        );
    }

    /**
     * Menampilkan halaman pilihan jenis surat.
     */
    public function create()
    {
        $jenisSurat = JenisSurat::where(
            'status',
            true
        )
            ->orderBy('nama')
            ->get();

        return view(
            'masyarakat.pengajuan.create',
            compact('jenisSurat')
        );
    }

    /**
     * Menampilkan form berdasarkan jenis surat.
     */
    public function form(JenisSurat $jenisSurat)
    {
        if (!$jenisSurat->status) {
            abort(
                404,
                'Jenis surat tidak tersedia.'
            );
        }

        return view(
            'masyarakat.pengajuan.form',
            compact('jenisSurat')
        );
    }

    /**
     * Menyimpan pengajuan surat.
     */
    public function store(Request $request)
{
    $user = auth()->user();

    $masyarakat = $user->masyarakat;

    if (!$masyarakat) {
        return back()->with(
            'error',
            'Data masyarakat tidak ditemukan.'
        );
    }

    $jenisSurat = \App\Models\JenisSurat::findOrFail(
        $request->jenis_surat_id
    );


    $rules = [

        'jenis_surat_id' => [
            'required',
            'exists:jenis_surat,id',
        ],

        'file_kk' => [
            'required',
            'file',
            'mimes:jpg,jpeg,png,pdf',
            'max:5120',
        ],

        'file_pengantar_rt_rw' => [
            'required',
            'file',
            'mimes:jpg,jpeg,png,pdf',
            'max:5120',
        ],

    ];


    /*
    |--------------------------------------------------------------------------
    | Validasi berdasarkan jenis surat
    |--------------------------------------------------------------------------
    */

    if ($jenisSurat->template === 'sktm') {

        $rules = array_merge($rules, [

            'keperluan' => [
                'required',
                'string',
                'max:500',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);

    }


    if ($jenisSurat->template === 'sppt-pbb') {

        $rules = array_merge($rules, [

            'nama_wajib_pajak' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat_wajib_pajak' => [
                'required',
                'string',
                'max:1000',
            ],

            'bukti_hak_milik' => [
                'required',
                'string',
                'max:2000',
            ],

            'objek_pajak' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat_objek_pajak' => [
                'required',
                'string',
                'max:1000',
            ],

            'luas_tanah' => [
                'required',
                'string',
                'max:100',
            ],

            'luas_bangunan' => [
                'required',
                'string',
                'max:100',
            ],

            'nop' => [
                'required',
                'string',
                'max:100',
            ],

            'batas_utara' => [
                'required',
                'string',
                'max:500',
            ],

            'batas_timur' => [
                'required',
                'string',
                'max:500',
            ],

            'batas_selatan' => [
                'required',
                'string',
                'max:500',
            ],

            'batas_barat' => [
                'required',
                'string',
                'max:500',
            ],

        ]);

    }


    $validated = $request->validate($rules);


    DB::beginTransaction();

    $fileKk = null;
    $filePengantar = null;

    try {

        $fileKk = $request
            ->file('file_kk')
            ->store(
                'pengajuan/kk',
                'private'
            );


        $filePengantar = $request
            ->file('file_pengantar_rt_rw')
            ->store(
                'pengajuan/pengantar-rt-rw',
                'private'
            );


        $dataPengajuan = $jenisSurat->template === 'domisili'
            ? []
            : $request->except([
                '_token',
                'jenis_surat_id',
                'file_kk',
                'file_pengantar_rt_rw',
            ]);


        $nomorPengajuan =
            'PGJ-' .
            now()->format('YmdHis') .
            '-' .
            $masyarakat->id;


        $pengajuan = \App\Models\PengajuanSurat::create([

            'nomor_pengajuan' =>
                $nomorPengajuan,

            'masyarakat_id' =>
                $masyarakat->id,

            'jenis_surat_id' =>
                $jenisSurat->id,

            'data_pengajuan' =>
                $dataPengajuan,

            'file_kk' =>
                $fileKk,

            'file_pengantar_rt_rw' =>
                $filePengantar,

            'status' =>
                'pending',

        ]);

        $pengajuan->load([
            'masyarakat',
            'jenisSurat',
        ]);

        User::where('role', 'staff')
            ->where('status', 'active')
            ->get()
            ->each(function (User $staff) use ($pengajuan) {
                $staff->notify(
                    new PengajuanBaruNotification($pengajuan)
                );
            });


        DB::commit();


        return redirect()
            ->route(
                'masyarakat.pengajuan.show',
                $pengajuan
            )
            ->with(
                'success',
                'Pengajuan surat berhasil dikirim.'
            );

    } catch (\Throwable $e) {

        DB::rollBack();


        if ($fileKk) {
            Storage::disk('private')
                ->delete($fileKk);
        }


        if ($filePengantar) {
            Storage::disk('private')
                ->delete($filePengantar);
        }


        return back()
            ->withInput()
            ->with(
                'error',
                'Pengajuan gagal: ' .
                $e->getMessage()
            );
    }
}

    /**
     * Menampilkan detail pengajuan.
     */
    public function show(PengajuanSurat $pengajuan)
    {
        $masyarakat = Auth::user()->masyarakat;

        if (
            !$masyarakat ||
            $pengajuan->masyarakat_id !== $masyarakat->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke pengajuan ini.'
            );
        }

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

    /**
     * Menampilkan dokumen persyaratan milik pengajuan masyarakat.
     */
    public function dokumen(PengajuanSurat $pengajuan, string $jenis)
    {
        $pengajuan->loadMissing('masyarakat');

        if (
            !$pengajuan->masyarakat ||
            $pengajuan->masyarakat->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        if (!in_array($jenis, ['ktp', 'kk', 'pengantar_rt_rw'], true)) {
            abort(404, 'Jenis dokumen tidak valid.');
        }

        $path = match ($jenis) {
            'ktp' => $pengajuan->masyarakat->foto_ktp,
            'kk' => $pengajuan->file_kk,
            'pengantar_rt_rw' => $pengajuan->file_pengantar_rt_rw,
        };

        $disk = Storage::disk('private');

        if (!$path || !$disk->exists($path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $absolutePath = $disk->path($path);

        if (!is_file($absolutePath)) {
            abort(404, 'Dokumen tidak dapat dibaca.');
        }

        $mimeType = mime_content_type($absolutePath);
        $allowedMimeTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf',
        ];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            abort(403, 'Format dokumen tidak diperbolehkan.');
        }

        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes(basename($absolutePath)) . '"',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Memperbarui pengajuan yang perlu diperbaiki.
     */
    public function update(
        Request $request,
        PengajuanSurat $pengajuan
    ) {
        $masyarakat = Auth::user()->masyarakat;

        if (
            !$masyarakat ||
            $pengajuan->masyarakat_id !== $masyarakat->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke pengajuan ini.'
            );
        }

        if (
            $pengajuan->status !== 'perlu_perbaikan'
        ) {
            return back()->with(
                'warning',
                'Pengajuan ini tidak dapat diperbarui.'
            );
        }

        $data = $request->except([
            '_token',
            '_method',
        ]);

        $pengajuan->update([
            'data_pengajuan' => $data,
            'status' => 'pending',
        ]);

        return redirect()
            ->route(
                'masyarakat.pengajuan.show',
                $pengajuan
            )
            ->with(
                'success',
                'Pengajuan berhasil diperbarui dan dikirim kembali.'
            );
    }
}
