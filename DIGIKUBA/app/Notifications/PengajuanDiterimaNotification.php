<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanDiterimaNotification extends Notification
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
            'title' => 'Pengajuan Surat Diverifikasi',
            'message' => 'Pengajuan surat ' .
                $this->pengajuan->jenisSurat->nama .
                ' telah diverifikasi dan menunggu proses tanda tangan Lurah.',
            'type' => 'pengajuan_diterima',
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'url' => route(
                'masyarakat.pengajuan.show',
                $this->pengajuan
            ),
        ];
    }
}