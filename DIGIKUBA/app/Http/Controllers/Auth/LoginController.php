<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password yang Anda masukkan salah.');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Akun Menunggu Verifikasi
        |--------------------------------------------------------------------------
        */

        if ($user->status === 'pending') {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun Anda masih menunggu verifikasi. Silakan tunggu konfirmasi dari pihak kelurahan.');
        }

        /*
        |--------------------------------------------------------------------------
        | Akun Ditolak
        |--------------------------------------------------------------------------
        */

        if ($user->status === 'rejected') {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun Anda ditolak. Silakan hubungi pihak kelurahan.');
        }

        /*
        |--------------------------------------------------------------------------
        | Akun Tidak Aktif
        |--------------------------------------------------------------------------
        */

        if ($user->status === 'inactive') {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun Anda sedang tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect Berdasarkan Role
        |--------------------------------------------------------------------------
        */

        return $this->redirectByRole($user);
    }

    /**
     * Redirect berdasarkan role.
     */
    private function redirectByRole($user)
    {
        return match ($user->role) {

            'superadmin' => redirect()
                ->route('superadmin.dashboard'),

            'staff' => redirect()
                ->route('staff.dashboard'),

            'lurah' => redirect()
                ->route('lurah.dashboard'),

            'masyarakat' => redirect()
                ->route('masyarakat.dashboard'),

            default => redirect()
                ->route('login')
                ->with('error', 'Role pengguna tidak dikenali.')
        };
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}