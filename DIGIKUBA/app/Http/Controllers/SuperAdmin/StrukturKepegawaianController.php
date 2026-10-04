<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StrukturKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StrukturKepegawaianController extends Controller
{
    public function index()
    {
        $pegawai = StrukturKepegawaian::latest()
            ->paginate(10);

        return view(
            'superadmin.struktur-kepegawaian.index',
            compact('pegawai')
        );
    }


    public function create()
{
    return view(
        'superadmin.struktur-kepegawaian.create',
        [
            'jabatan' => config('master_data.jabatan', []),
        ]
    );
}


    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'jabatan' => [
                'required',
                Rule::in(
    config('master_data.jabatan', [])
),
            ],

            'nip' => [
                'nullable',
                'string',
                'max:30',
            ],

        ]);


        $pegawai =
            StrukturKepegawaian::create([
                ...$validated,
                'status' => true,
            ]);


        ActivityLog::create([

            'user_id' =>
                auth()->id(),

            'activity' =>
                'Tambah Struktur Kepegawaian',

            'description' =>
                'Menambahkan ' .
                $pegawai->nama .
                ' sebagai ' .
                $pegawai->jabatan,

            'ip_address' =>
                request()->ip(),

            'user_agent' =>
                request()->userAgent(),

        ]);


        return redirect()
            ->route(
                'superadmin.struktur-kepegawaian.index'
            )
            ->with(
                'success',
                'Data pegawai berhasil ditambahkan.'
            );
    }


    public function edit(
        StrukturKepegawaian $strukturKepegawaian
    ) {
        return view(
            'superadmin.struktur-kepegawaian.edit',
            [
                'pegawai' =>
                    $strukturKepegawaian,

                'jabatan' =>
                    config('master_data.jabatan'),
            ]
        );
    }


    public function update(
        Request $request,
        StrukturKepegawaian $strukturKepegawaian
    ) {
        $validated = $request->validate([

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'jabatan' => [
                'required',
                Rule::in(
                    config('master_data.jabatan')
                ),
            ],

            'nip' => [
                'nullable',
                'string',
                'max:30',
            ],

        ]);


        $strukturKepegawaian->update(
            $validated
        );


        return redirect()
            ->route(
                'superadmin.struktur-kepegawaian.index'
            )
            ->with(
                'success',
                'Data pegawai berhasil diperbarui.'
            );
    }


    public function destroy(
        StrukturKepegawaian $strukturKepegawaian
    ) {
        $strukturKepegawaian->update([
            'status' => false,
        ]);


        return back()
            ->with(
                'success',
                'Pegawai berhasil dinonaktifkan.'
            );
    }
}