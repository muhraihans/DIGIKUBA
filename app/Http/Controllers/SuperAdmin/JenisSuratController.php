<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    /**
     * Daftar jenis surat.
     */
    public function index()
    {
        $jenisSurat = JenisSurat::latest()
            ->paginate(10);

        return view(
            'superadmin.jenis-surat.index',
            compact('jenisSurat')
        );
    }

    /**
     * Form tambah.
     */
    public function create()
    {
        return view(
            'superadmin.jenis-surat.create'
        );
    }

    /**
     * Simpan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'kode' => [
                'required',
                'string',
                'max:50',
                'unique:jenis_surat,kode',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'template' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);

        JenisSurat::create($validated);

        return redirect()
            ->route(
                'superadmin.jenis-surat.index'
            )
            ->with(
                'success',
                'Jenis surat berhasil ditambahkan.'
            );
    }

    /**
     * Form edit.
     */
    public function edit(
        JenisSurat $jenisSurat
    ) {

        return view(
            'superadmin.jenis-surat.edit',
            compact('jenisSurat')
        );
    }

    /**
     * Update.
     */
    public function update(
        Request $request,
        JenisSurat $jenisSurat
    ) {

        $validated = $request->validate([

            'kode' => [
                'required',
                'string',
                'max:50',
                'unique:jenis_surat,kode,' .
                $jenisSurat->id,
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'template' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);

        $jenisSurat->update($validated);

        return redirect()
            ->route(
                'superadmin.jenis-surat.index'
            )
            ->with(
                'success',
                'Jenis surat berhasil diperbarui.'
            );
    }

    /**
     * Hapus.
     */
    public function destroy(
        JenisSurat $jenisSurat
    ) {

        if (
            $jenisSurat
                ->pengajuan()
                ->exists()
        ) {

            return back()->with(
                'error',
                'Jenis surat tidak dapat dihapus karena sudah digunakan dalam pengajuan.'
            );
        }

        $jenisSurat->delete();

        return back()->with(
            'success',
            'Jenis surat berhasil dihapus.'
        );
    }
}