<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Masyarakat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi masyarakat.
     */
    public function showRegistrationForm()
    {
        $negara = [
            'Indonesia', 'Afghanistan', 'Afrika Selatan', 'Albania', 'Aljazair', 'Amerika Serikat', 
            'Andorra', 'Angola', 'Antigua dan Barbuda', 'Arab Saudi', 'Argentina', 'Armenia', 
            'Australia', 'Austria', 'Azerbaijan', 'Bahama', 'Bahrain', 'Bangladesh', 'Barbados', 
            'Belanda', 'Belarus', 'Belgia', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 
            'Bosnia dan Herzegovina', 'Botswana', 'Brasil', 'Brunei Darussalam', 'Bulgaria', 
            'Burkina Faso', 'Burundi', 'Ceko', 'Chad', 'Chile', 'China', 'Denmark', 'Djibouti', 
            'Dominika', 'Ekuador', 'El Salvador', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 
            'Fiji', 'Filipina', 'Finlandia', 'Gabon', 'Gambia', 'Georgia', 'Ghana', 'Grenada', 
            'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hongaria', 
            'India', 'Inggris', 'Irak', 'Iran', 'Irlandia', 'Islandia', 'Israel', 'Italia', 
            'Jamaika', 'Jepang', 'Jerman', 'Kamboja', 'Kamerun', 'Kanada', 'Kazakhstan', 'Kenya', 
            'Kirgizstan', 'Kiribati', 'Kolombia', 'Komoro', 'Kongo', 'Korea Selatan', 'Korea Utara', 
            'Kosta Rika', 'Kroasia', 'Kuba', 'Kuwait', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 
            'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luksemburg', 'Madagaskar', 
            'Maladewa', 'Malawi', 'Malaysia', 'Mali', 'Malta', 'Maroko', 'Mauritania', 
            'Mauritius', 'Mesir', 'Meksiko', 'Moldova', 'Monako', 'Mongolia', 'Mozambik', 
            'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Niger', 'Nigeria', 'Nikaragua', 'Norwegia', 
            'Oman', 'Pakistan', 'Palestina', 'Panama', 'Pantai Gading', 'Papua Nugini', 
            'Paraguay', 'Perancis', 'Peru', 'Polandia', 'Portugal', 'Qatar', 'Republik Dominika', 
            'Rumania', 'Rusia', 'Selandia Baru', 'Senegal', 'Serbia', 'Seychelles', 'Singapura', 
            'Siprus', 'Slovakia', 'Slovenia', 'Somalia', 'Spanyol', 'Sri Lanka', 'Sudan', 
            'Sudan Selatan', 'Suriah', 'Swedia', 'Swiss', 'Taiwan', 'Tajikistan', 'Tanzania', 
            'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Tunisia', 'Turki', 'Turkmenistan', 
            'Tuvalu', 'Uganda', 'Ukraina', 'Uni Emirat Arab', 'Uruguay', 'Uzbekistan', 'Vanuatu', 
            'Vatikan', 'Venezuela', 'Vietnam', 'Yaman', 'Yordania', 'Yunani', 'Zambia', 'Zimbabwe'
        ];

        $jenisKelamin = [
            'Laki-laki',
            'Perempuan',
        ];

        $statusPerkawinan = [
            'Belum Kawin',
            'Kawin',
            'Cerai Hidup',
            'Cerai Mati',
        ];

        $agama = [
            'Islam',
            'Kristen',
            'Katolik',
            'Hindu',
            'Buddha',
            'Konghucu',
        ];

        $pekerjaan = [
            'Belum/Tidak Bekerja',
            'Mengurus Rumah Tangga',
            'Pelajar/Mahasiswa',
            'Pensiunan',
            'Pegawai Negeri Sipil (PNS)',
            'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)',
            'TNI',
            'POLRI',
            'Karyawan Swasta',
            'Karyawan BUMN',
            'Karyawan BUMD',
            'Wiraswasta',
            'Pedagang',
            'Petani',
            'Nelayan',
            'Buruh',
            'Guru',
            'Dosen',
            'Dokter',
            'Perawat',
            'Bidan',
            'Apoteker',
            'Pengacara',
            'Notaris',
            'Arsitek',
            'Teknisi',
            'Sopir',
            'Mekanik',
            'Seniman',
            'Artis',
            'Wartawan',
            'Konsultan',
            'Freelancer',
            'Pengusaha',
            'Lainnya',
        ];

        return view('auth.register', compact(
            'negara',
            'jenisKelamin',
            'statusPerkawinan',
            'agama',
            'pekerjaan'
        ));
    }

    /**
     * Proses registrasi masyarakat.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nik'               => ['required', 'digits:16', 'unique:masyarakat,nik'],
            'nama_lengkap'      => ['required', 'string', 'max:255'],
            'jenis_kelamin'     => ['required', 'in:Laki-laki,Perempuan'],
            'tempat_lahir'      => ['required', 'string', 'max:100'],
            'tanggal_lahir'     => ['required', 'date'],
            'kewarganegaraan'   => ['required', 'string', 'max:100'],
            'status_perkawinan' => ['required', 'in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati'],
            'agama'             => ['required', 'in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu'],
            'pekerjaan'         => ['required', 'string', 'max:100'],
            'pekerjaan_lainnya' => ['nullable', 'required_if:pekerjaan,Lainnya', 'string', 'max:150'],
            'alamat'            => ['required', 'string'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'foto_ktp'          => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_selfie'       => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'terms'             => ['required', 'accepted'],
        ], [
            'nik.required'               => 'NIK wajib diisi.',
            'nik.digits'                 => 'NIK harus terdiri dari 16 digit.',
            'nik.unique'                 => 'NIK sudah terdaftar.',
            'nama_lengkap.required'      => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required'     => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required'      => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required'     => 'Tanggal lahir wajib diisi.',
            'kewarganegaraan.required'   => 'Kewarganegaraan wajib dipilih.',
            'status_perkawinan.required' => 'Status perkawinan wajib dipilih.',
            'agama.required'             => 'Agama wajib dipilih.',
            'pekerjaan.required'         => 'Pekerjaan wajib dipilih.',
            'alamat.required'            => 'Alamat lengkap wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.email'                => 'Format email tidak valid.',
            'email.unique'               => 'Email sudah digunakan.',
            'password.min'               => 'Password minimal 8 karakter.',
            'password.confirmed'         => 'Konfirmasi password tidak sesuai.',
            'foto_ktp.required'          => 'Foto KTP wajib diunggah.',
            'foto_ktp.image'             => 'File KTP harus berupa gambar.',
            'foto_ktp.mimes'             => 'Format KTP harus JPG, JPEG, atau PNG.',
            'foto_ktp.max'               => 'Ukuran foto KTP maksimal 2MB.',
            'foto_selfie.required'       => 'Foto selfie wajib diunggah.',
            'foto_selfie.image'          => 'Foto selfie harus berupa gambar.',
            'foto_selfie.mimes'          => 'Format selfie harus JPG, JPEG, atau PNG.',
            'foto_selfie.max'            => 'Ukuran foto selfie maksimal 2MB.',
            'terms.required'             => 'Anda harus menyetujui pernyataan.',
            'terms.accepted'             => 'Anda harus menyetujui pernyataan.',
        ]);

        $fotoKtp = null;
        $fotoSelfie = null;

        DB::beginTransaction();

        try {
            // Simpan Berkas
            $fotoKtp = $request->file('foto_ktp')->store('masyarakat/ktp', 'private');
            $fotoSelfie = $request->file('foto_selfie')->store('masyarakat/selfie', 'private');

            // Buat Akun User
            $user = User::create([
                'name'     => $validated['nama_lengkap'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'masyarakat',
                'status'   => 'pending',
            ]);

            // Buat Profil Masyarakat
            Masyarakat::create([
                'user_id'           => $user->id,
                'nik'               => $validated['nik'],
                'nama_lengkap'      => $validated['nama_lengkap'],
                'jenis_kelamin'     => $validated['jenis_kelamin'],
                'tempat_lahir'      => $validated['tempat_lahir'],
                'tanggal_lahir'     => $validated['tanggal_lahir'],
                'kewarganegaraan'   => $validated['kewarganegaraan'],
                'status_perkawinan' => $validated['status_perkawinan'],
                'agama'             => $validated['agama'],
                'pekerjaan'         => $validated['pekerjaan'],
                'pekerjaan_lainnya' => $validated['pekerjaan_lainnya'] ?? null,
                'alamat'            => $validated['alamat'],
                'foto_ktp'          => $fotoKtp,
                'foto_selfie'       => $fotoSelfie,
            ]);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Registrasi berhasil. Akun Anda menunggu verifikasi Staff Kelurahan.');

        } catch (Throwable $e) {
            DB::rollBack();

            // Hapus file yang sudah terlanjur diunggah jika database gagal menyimpan
            if ($fotoKtp && Storage::disk('private')->exists($fotoKtp)) {
                Storage::disk('private')->delete($fotoKtp);
            }

            if ($fotoSelfie && Storage::disk('private')->exists($fotoSelfie)) {
                Storage::disk('private')->delete($fotoSelfie);
            }

            Log::error('Gagal registrasi masyarakat: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses registrasi. Silakan coba lagi.');
        }
    }
}