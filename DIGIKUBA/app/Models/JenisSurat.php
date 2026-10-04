<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'template',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Semua pengajuan dari jenis surat ini.
     */
    public function pengajuan()
    {
        return $this->hasMany(
            PengajuanSurat::class,
            'jenis_surat_id'
        );
    }

    /**
     * Cek apakah jenis surat aktif.
     */
    public function isActive(): bool
    {
        return $this->status === true;
    }
}