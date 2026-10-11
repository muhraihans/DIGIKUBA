<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Daftar notifikasi.
     */
    public function index(Request $request)
    {
        $notifications =
            $request->user()
                ->notifications()
                ->latest()
                ->paginate(15);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    /**
     * Tandai satu notifikasi sebagai telah dibaca.
     */
    public function read(
        Request $request,
        string $id
    ) {

        $notification =
            $request->user()
                ->notifications()
                ->where('id', $id)
                ->firstOrFail();

        $notification->markAsRead();

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function readAll(Request $request)
    {
        $request->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'Semua notifikasi telah dibaca.'
        );
    }
}