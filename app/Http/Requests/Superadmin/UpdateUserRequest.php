<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isSuperadmin();
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:superadmin,staff,lurah,masyarakat',
            ],

            'status' => [
                'required',
                'in:pending,active,inactive,rejected',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'name.required' =>
                'Nama wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan.',

            'password.min' =>
                'Password minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',

            'role.required' =>
                'Role wajib dipilih.',

            'role.in' =>
                'Role tidak valid.',

            'status.required' =>
                'Status wajib dipilih.',

            'status.in' =>
                'Status tidak valid.',
        ];
    }
}