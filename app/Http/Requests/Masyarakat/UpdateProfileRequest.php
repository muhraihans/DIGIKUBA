<?php

namespace App\Http\Requests\Masyarakat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMasyarakat();
    }

    public function rules(): array
    {
        $user = auth()->user();

        return [

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'jenis_kelamin' => [
                'required',
                'in:Laki-laki,Perempuan',
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

            'kewarganegaraan' => [
                'required',
                'string',
                'max:100',
            ],

            'status_perkawinan' => [
                'required',
                'in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            ],

            'agama' => [
                'required',
                'in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            ],

            'pekerjaan' => [
                'required',
                'string',
                'max:100',
            ],

            'pekerjaan_lainnya' => [
                'nullable',
                'required_if:pekerjaan,Lainnya',
                'string',
                'max:150',
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
                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'foto_ktp' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'foto_selfie' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'nama_lengkap.required' =>
                'Nama lengkap wajib diisi.',

            'jenis_kelamin.required' =>
                'Jenis kelamin wajib dipilih.',

            'tempat_lahir.required' =>
                'Tempat lahir wajib diisi.',

            'tanggal_lahir.required' =>
                'Tanggal lahir wajib diisi.',

            'tanggal_lahir.before' =>
                'Tanggal lahir tidak valid.',

            'kewarganegaraan.required' =>
                'Kewarganegaraan wajib diisi.',

            'status_perkawinan.required' =>
                'Status perkawinan wajib dipilih.',

            'agama.required' =>
                'Agama wajib dipilih.',

            'pekerjaan.required' =>
                'Pekerjaan wajib diisi.',

            'pekerjaan_lainnya.required_if' =>
                'Pekerjaan lainnya wajib diisi.',

            'alamat.required' =>
                'Alamat wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan.',

            'foto_ktp.image' =>
                'Foto KTP harus berupa gambar.',

            'foto_ktp.mimes' =>
                'Foto KTP harus JPG, JPEG, atau PNG.',

            'foto_ktp.max' =>
                'Foto KTP maksimal 2 MB.',

            'foto_selfie.image' =>
                'Foto selfie harus berupa gambar.',

            'foto_selfie.mimes' =>
                'Foto selfie harus JPG, JPEG, atau PNG.',

            'foto_selfie.max' =>
                'Foto selfie maksimal 2 MB.',
        ];
    }
}