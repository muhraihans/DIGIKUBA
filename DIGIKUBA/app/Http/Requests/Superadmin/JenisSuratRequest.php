<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JenisSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isSuperadmin();
    }

    public function rules(): array
    {
        $jenisSurat = $this->route('jenis_surat');

        return [

            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'jenis_surat',
                    'kode'
                )->ignore(
                    $jenisSurat?->id
                ),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'template' => [
                'required',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'kode.required' =>
                'Kode surat wajib diisi.',

            'kode.unique' =>
                'Kode surat sudah digunakan.',

            'nama.required' =>
                'Nama jenis surat wajib diisi.',

            'deskripsi.max' =>
                'Deskripsi maksimal 2000 karakter.',

            'template.required' =>
                'Template surat wajib diisi.',

            'status.required' =>
                'Status surat wajib dipilih.',

            'status.boolean' =>
                'Status surat tidak valid.',
        ];
    }
}