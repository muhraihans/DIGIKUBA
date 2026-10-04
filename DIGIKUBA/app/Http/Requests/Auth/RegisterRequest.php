<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nik' => [
                'required',
                'digits:16',
                'unique:masyarakat,nik',
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'tempat_lahir' => [
                'required',
                'string',
                'max:100',
            ],

            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
            ],

            'alamat' => [
                'required',
                'string',
                'max:1000',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'foto_ktp' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'foto_selfie' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'terms' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'nik.required' =>
                'NIK wajib diisi.',

            'nik.digits' =>
                'NIK harus terdiri dari 16 digit.',

            'nik.unique' =>
                'NIK sudah terdaftar.',

            'nama_lengkap.required' =>
                'Nama lengkap wajib diisi.',

            'tempat_lahir.required' =>
                'Tempat lahir wajib diisi.',

            'tanggal_lahir.required' =>
                'Tanggal lahir wajib diisi.',

            'tanggal_lahir.date' =>
                'Format tanggal lahir tidak valid.',

            'tanggal_lahir.before' =>
                'Tanggal lahir harus merupakan tanggal sebelum hari ini.',

            'alamat.required' =>
                'Alamat wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan.',

            'password.required' =>
                'Password wajib diisi.',

            'password.min' =>
                'Password minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',

            'foto_ktp.required' =>
                'Foto KTP wajib diunggah.',

            'foto_ktp.image' =>
                'File KTP harus berupa gambar.',

            'foto_ktp.mimes' =>
                'Foto KTP harus berformat JPG, JPEG, atau PNG.',

            'foto_ktp.max' =>
                'Ukuran foto KTP maksimal 2 MB.',

            'foto_selfie.required' =>
                'Foto selfie wajib diunggah.',

            'foto_selfie.image' =>
                'File selfie harus berupa gambar.',

            'foto_selfie.mimes' =>
                'Foto selfie harus berformat JPG, JPEG, atau PNG.',

            'foto_selfie.max' =>
                'Ukuran foto selfie maksimal 2 MB.',
        ];
    }
}