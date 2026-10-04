<?php

namespace App\Notifications;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratSiapDownloadNotification extends Notification
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
        return [
            'title' => 'Surat Siap Diunduh',
            'message' => 'Surat ' .
                $this->surat->perihal .
                ' dengan nomor ' .
                $this->surat->nomor_surat .
                ' sudah tersedia dan dapat diunduh.',
            'type' => 'surat_siap_download',
            'surat_id' => $this->surat->id,
            'nomor_surat' => $this->surat->nomor_surat,
            'url' => route(
                'masyarakat.riwayat.download',
                $this->surat->pengajuan_id
            ),
        ];
    }
}