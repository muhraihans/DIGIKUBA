<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanDitolakNotification extends Notification
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
            'title' => 'Pengajuan Surat Ditolak',
            'message' => 'Pengajuan surat ' .
                $this->pengajuan->jenisSurat->nama .
                ' telah ditolak. Silakan periksa alasan atau catatan pada detail pengajuan.',
            'type' => 'pengajuan_ditolak',
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'url' => route(
                'masyarakat.pengajuan.show',
                $this->pengajuan
            ),
        ];
    }
}