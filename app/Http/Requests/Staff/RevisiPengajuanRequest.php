<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class RevisiPengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && in_array(
                auth()->user()->role,
                ['staff', 'superadmin']
            );
    }

    public function rules(): array
    {
        return [
            'komentar' => [
                'required',
                'string',
                'min:5',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'komentar.required' =>
                'Catatan perbaikan wajib diisi.',

            'komentar.min' =>
                'Catatan perbaikan minimal 5 karakter.',

            'komentar.max' =>
                'Catatan perbaikan maksimal 2000 karakter.',
        ];
    }
}