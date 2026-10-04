<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanBaruNotification extends Notification
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
        $namaJenisSurat = $this->pengajuan->jenisSurat?->nama ?? 'surat';
        $namaPemohon = $this->pengajuan->masyarakat?->nama_lengkap ?? 'Masyarakat';

        return [
            'title' => 'Pengajuan Surat Baru',
            'message' => $namaPemohon . ' mengajukan ' . $namaJenisSurat .
                ' dengan nomor ' . $this->pengajuan->nomor_pengajuan .
                ' dan menunggu verifikasi.',
            'type' => 'pengajuan_baru',
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'url' => route('staff.pengajuan.show', $this->pengajuan),
        ];
    }
}