<?php

namespace App\Http\Requests\Masyarakat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePengajuanRequest extends FormRequest
{
    /**
     * Menentukan apakah user memiliki izin
     * untuk membuat pengajuan surat.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        return $user->isMasyarakat()
            && $user->isActive();
    }

    /**
     * Aturan validasi pengajuan surat.
     */
    public function rules(): array
    {
        return [

            'jenis_surat_id' => [
                'required',
                'integer',
                'exists:jenis_surat,id',
            ],

            'data_pengajuan' => [
                'required',
                'array',
            ],

        ];
    }

    /**
     * Pesan validasi.
     */
    public function messages(): array
    {
        return [

            'jenis_surat_id.required' =>
                'Jenis surat wajib dipilih.',

            'jenis_surat_id.integer' =>
                'Jenis surat tidak valid.',

            'jenis_surat_id.exists' =>
                'Jenis surat tidak ditemukan.',

            'data_pengajuan.required' =>
                'Data pengajuan wajib diisi.',

            'data_pengajuan.array' =>
                'Format data pengajuan tidak valid.',
        ];
    }
}