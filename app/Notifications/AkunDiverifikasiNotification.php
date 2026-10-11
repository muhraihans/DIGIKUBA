<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AkunDiverifikasiNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Akun Berhasil Diverifikasi',
            'message' => 'Akun Anda telah diverifikasi oleh Staff Kelurahan. Anda sekarang dapat menggunakan seluruh layanan DIGIKUBA.',
            'type' => 'akun_diverifikasi',
            'url' => route('masyarakat.dashboard'),
        ];
    }
}