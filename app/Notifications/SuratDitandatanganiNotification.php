<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratDitandatanganiNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Surat $surat
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $detailUrl = $notifiable->role === 'masyarakat'
            ? route('masyarakat.pengajuan.show', $this->surat->pengajuan_id)
            : route('staff.pengajuan.show', $this->surat->pengajuan_id);

        return [
            'title' => 'Surat Telah Ditandatangani',
            'message' => 'Surat dengan nomor ' .
                $this->surat->nomor_surat .
                ' telah ditandatangani oleh Lurah.',
            'type' => 'surat_ditandatangani',
            'surat_id' => $this->surat->id,
            'nomor_surat' => $this->surat->nomor_surat,
            'url' => $detailUrl,
        ];
    }
}