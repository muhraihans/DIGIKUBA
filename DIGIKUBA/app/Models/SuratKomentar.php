<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKomentar extends Model
{
    use HasFactory;

    protected $table = 'surat_komentar';

    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'komentar',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Pengajuan surat.
     */
    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanSurat::class,
            'pengajuan_id'
        );
    }

    /**
     * User yang memberikan komentar.
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}