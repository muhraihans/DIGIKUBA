<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiAkunRequest extends FormRequest
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
            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.max' =>
                'Catatan maksimal 1000 karakter.',
        ];
    }
}