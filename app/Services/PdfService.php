<?php

namespace App\Services;

use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generate(Surat $surat)
    {
        $surat->load([
            'pengajuan.masyarakat.user',
            'pengajuan.jenisSurat',
            'tandaTangan.lurah',
            'tandaTangan.pegawai',
        ]);

        $pengajuan = $surat->pengajuan;
        $masyarakat = $pengajuan?->masyarakat;
        $jenisSurat = $pengajuan?->jenisSurat;
        $template = $jenisSurat?->template;

        $view = match ($template) {
            'sktm' => 'pdf.sktm',
            'sppt-pbb' => 'pdf.sppt-pbb',
            'domisili' => 'pdf.domisili',
            default => 'pdf.surat',
        };

        $rawData = $pengajuan?->data_pengajuan;
        if (!is_array($rawData)) {
            $dataPengajuan = [];
        } elseif (
            isset($rawData['data_pengajuan']) &&
            is_array($rawData['data_pengajuan'])
        ) {
            $dataPengajuan = $rawData['data_pengajuan'];
        } else {
            $dataPengajuan = $rawData;
        }

        $keperluan = $dataPengajuan['keperluan'] ?? 'keperluan administrasi';
        $keterangan = $dataPengajuan['keterangan'] ?? '';

        return Pdf::loadView(
            $view,
            [
                'surat' => $surat,
                'pengajuan' => $pengajuan,
                'masyarakat' => $masyarakat,
                'jenisSurat' => $jenisSurat,
                'dataPengajuan' => $dataPengajuan,
                'keperluan' => $keperluan,
                'keterangan' => $keterangan,
            ]
        )->setPaper('A4', 'portrait');
    }

    public function save(Surat $surat): string
    {
        $pdf = $this->generate($surat);

        $directory =
            'surat/' .
            $surat->tanggal_surat->format('Y');

        $filename =
            str_replace(
                ['/', '\\', ' '],
                '-',
                $surat->nomor_surat
            ) . '.pdf';

        $path =
            $directory . '/' . $filename;

        Storage::disk('private')->put(
            $path,
            $pdf->output()
        );

        $surat->update([
            'file_pdf' => $path,
        ]);

        return $path;
    }

    public function download(Surat $surat)
    {
        if (
            !in_array(
                $surat->status,
                [
                    'ditandatangani',
                    'selesai',
                ]
            )
        ) {
            abort(
                403,
                'Surat belum dapat diunduh.'
            );
        }

        if (
            !$surat->file_pdf ||
            !Storage::disk('private')
                ->exists($surat->file_pdf)
        ) {
            $this->save($surat);
        }

        $filename =
            basename($surat->file_pdf);

        return Storage::disk('private')
            ->download(
                $surat->file_pdf,
                $filename
            );
    }
}