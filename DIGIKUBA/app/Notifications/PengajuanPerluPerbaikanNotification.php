<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanPerluPerbaikanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PengajuanSurat $pengajuan
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Perlu Perbaikan',
            'message' => 'Pengajuan surat ' .
                $this->pengajuan->jenisSurat->nama .
                ' perlu diperbaiki. Silakan periksa catatan dari Staff Kelurahan.',
            'type' => 'pengajuan_perlu_perbaikan',
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'url' => route(
                'masyarakat.pengajuan.show',
                $this->pengajuan
            ),
        ];
    }
}