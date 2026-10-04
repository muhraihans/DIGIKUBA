<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Semua user.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where(
                'role',
                $request->role
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );

            });
        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'superadmin.users.index',
            compact('users')
        );
    }

    /**
     * Form tambah user.
     */
    public function create()
    {
        return view(
            'superadmin.users.create'
        );
    }

    /**
     * Simpan user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],

            'role' => [
                'required',
                'in:superadmin,staff,lurah,masyarakat',
            ],

            'status' => [
                'required',
                'in:pending,active,inactive,rejected',
            ],

        ]);

        User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'role' => $validated['role'],

            'status' => $validated['status'],
        ]);

        return redirect()
            ->route(
                'superadmin.users.index'
            )
            ->with(
                'success',
                'User berhasil ditambahkan.'
            );
    }

    /**
     * Detail user.
     */
    public function show(User $user)
    {
        $user->load('masyarakat');

        return view(
            'superadmin.users.show',
            compact('user')
        );
    }

    /**
     * Form edit.
     */
    public function edit(User $user)
    {
        return view(
            'superadmin.users.edit',
            compact('user')
        );
    }

    /**
     * Update user.
     */
    public function update(
        Request $request,
        User $user
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email,' . $user->id,
            ],

            'role' => [
                'required',
                'in:superadmin,staff,lurah,masyarakat',
            ],

            'status' => [
                'required',
                'in:pending,active,inactive,rejected',
            ],

        ]);

        $user->update($validated);

        return redirect()
            ->route(
                'superadmin.users.index'
            )
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        /*
        | Jangan izinkan superadmin menghapus dirinya sendiri.
        */

        if ($user->id === auth()->id()) {

            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun sendiri.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }

    /**
     * Reset password user.
     */
    public function resetPassword(
        Request $request,
        User $user
    ) {

        $validated = $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $user->update([
            'password' =>
                Hash::make(
                    $validated['password']
                ),
        ]);

        return back()->with(
            'success',
            'Password user berhasil direset.'
        );
    }
}