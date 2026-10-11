<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';
protected $fillable = [
    'nomor_pengajuan',
    'masyarakat_id',
    'jenis_surat_id',
    'data_pengajuan',

    'file_kk',
    'file_pengantar_rt_rw',

    'status',
    'diperiksa_oleh',
    'diperiksa_at',
    'disetujui_oleh',
    'disetujui_at',
    'ditolak_oleh',
    'ditolak_at',
    'catatan',
];
    protected $casts = [
    'data_pengajuan' => 'array',
    'diperiksa_at' => 'datetime',
    'disetujui_at' => 'datetime',
    'ditolak_at' => 'datetime',
];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Masyarakat yang mengajukan.
     */
    public function masyarakat()
    {
        return $this->belongsTo(
            Masyarakat::class,
            'masyarakat_id'
        );
    }

    /**
     * Jenis surat.
     */
    public function jenisSurat()
    {
        return $this->belongsTo(
            JenisSurat::class,
            'jenis_surat_id'
        );
    }

    /**
     * Surat hasil pengajuan.
     */
    public function surat()
    {
        return $this->hasOne(
            Surat::class,
            'pengajuan_id'
        );
    }

    /**
     * Semua komentar.
     */
    public function komentar()
    {
        return $this->hasMany(
            SuratKomentar::class,
            'pengajuan_id'
        );
    }

    /**
     * Staff yang memeriksa.
     */
    public function pemeriksa()
    {
        return $this->belongsTo(
            User::class,
            'diperiksa_oleh'
        );
    }

    /**
     * Pihak yang menyetujui.
     */
    public function penyetuju()
    {
        return $this->belongsTo(
            User::class,
            'disetujui_oleh'
        );
    }

    /**
     * Pihak yang menolak.
     */
    public function penolak()
    {
        return $this->belongsTo(
            User::class,
            'ditolak_oleh'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDiproses(): bool
    {
        return $this->status === 'diproses';
    }

    public function isPerluPerbaikan(): bool
    {
        return $this->status === 'perlu_perbaikan';
    }

    public function isDiverifikasi(): bool
    {
        return $this->status === 'diverifikasi';
    }

    public function isMenungguTandaTangan(): bool
    {
        return $this->status === 'menunggu_tanda_tangan';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }
}