<?php

namespace App\Http\Requests\Lurah;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TandaTanganRequest extends FormRequest
{
    /**
     * Menentukan apakah user memiliki izin
     * untuk melakukan tanda tangan surat.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        return in_array(
            $user->role,
            [
                'lurah',
                'superadmin',
            ],
            true
        );
    }

    /**
     * Aturan validasi.
     */
    public function rules(): array
    {
        return [
            'konfirmasi' => [
                'required',
                'accepted',
            ],
        ];
    }

    /**
     * Pesan validasi.
     */
    public function messages(): array
    {
        return [
            'konfirmasi.required' =>
                'Konfirmasi tanda tangan wajib dilakukan.',

            'konfirmasi.accepted' =>
                'Anda harus mengonfirmasi bahwa surat telah diperiksa dan disetujui untuk ditandatangani.',
        ];
    }
}