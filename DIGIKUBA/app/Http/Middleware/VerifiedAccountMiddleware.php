<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedAccountMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Middleware ini khusus untuk masyarakat
        if ($user->role === 'masyarakat') {

            // Akun masyarakat harus berstatus active
            if ($user->status !== 'active') {

                return redirect()
                    ->route('masyarakat.dashboard')
                    ->with(
                        'warning',
                        'Akun Anda belum diverifikasi oleh Staff Kelurahan.'
                    );
            }
        }

        return $next($request);
    }
}