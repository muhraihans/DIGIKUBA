<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanSurat;
use App\Models\User;

class Masyarakat extends Model
{
    use HasFactory;

    protected $table = 'masyarakat';

    protected $fillable = [
    'user_id',
    'nik',
    'nama_lengkap',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'kewarganegaraan',
    'status_perkawinan',
    'agama',
    'pekerjaan',
    'pekerjaan_lainnya',
    'alamat',
    'foto_ktp',
    'foto_selfie',
    'verified_at',
    'verified_by',
];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function pengajuanSurat()
    {
        return $this->hasMany(
            PengajuanSurat::class,
            'masyarakat_id'
        );
    }

    public function getPekerjaanLabelAttribute(): string
    {
        if ($this->pekerjaan === 'Lainnya') {
            return $this->pekerjaan_lainnya ?: 'Lainnya';
        }

        return $this->pekerjaan ?: '-';
    }
}