<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTandaTangan extends Model
{
    use HasFactory;

    protected $table = 'surat_tanda_tangan';

    protected $fillable = [
        'surat_id',
        'lurah_id',
        'pegawai_id',
        'token',
        'qr_code',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function surat()
    {
        return $this->belongsTo(
            Surat::class,
            'surat_id'
        );
    }

    public function lurah()
    {
        return $this->belongsTo(
            User::class,
            'lurah_id'
        );
    }

    public function pegawai()
    {
        return $this->belongsTo(
            StrukturKepegawaian::class,
            'pegawai_id'
        );
    }
}