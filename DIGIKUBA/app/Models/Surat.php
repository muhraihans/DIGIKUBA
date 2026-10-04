<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
    'pengajuan_id',
    'nomor_surat',
    'tanggal_surat',
    'perihal',
    'file_pdf',
    'status',
];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function tandaTangan()
{
    return $this->hasOne(
        SuratTandaTangan::class,
        'surat_id'
    );
}

public function pengajuan()
{
    return $this->belongsTo(
        PengajuanSurat::class,
        'pengajuan_id'
    );
}


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isMenungguTandaTangan(): bool
    {
        return $this->status === 'menunggu_tanda_tangan';
    }

    public function isDitandatangani(): bool
    {
        return $this->status === 'ditandatangani';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }
}