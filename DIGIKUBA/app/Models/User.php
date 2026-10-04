<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel.
     */
    protected $table = 'users';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * Kolom yang disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke masyarakat.
     */
    public function masyarakat()
    {
        return $this->hasOne(
            Masyarakat::class,
            'user_id'
        );
    }

    /**
     * User sebagai Staff pemeriksa pengajuan.
     */
    public function pengajuanDiperiksa()
    {
        return $this->hasMany(
            PengajuanSurat::class,
            'diperiksa_oleh'
        );
    }

    /**
     * User sebagai pihak yang menyetujui.
     */
    public function pengajuanDisetujui()
    {
        return $this->hasMany(
            PengajuanSurat::class,
            'disetujui_oleh'
        );
    }

    /**
     * User sebagai pihak yang menolak.
     */
    public function pengajuanDitolak()
    {
        return $this->hasMany(
            PengajuanSurat::class,
            'ditolak_oleh'
        );
    }

    /**
     * User sebagai Lurah yang menandatangani.
     */
    public function tandaTangan()
    {
        return $this->hasMany(
            SuratTandaTangan::class,
            'lurah_id'
        );
    }

    /**
     * Komentar yang dibuat user.
     */
    public function komentar()
    {
        return $this->hasMany(
            SuratKomentar::class,
            'user_id'
        );
    }

    /**
     * Activity log user.
     */
    public function activityLogs()
    {
        return $this->hasMany(
            ActivityLog::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
    |--------------------------------------------------------------------------
    */

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isLurah(): bool
    {
        return $this->role === 'lurah';
    }

    public function isMasyarakat(): bool
    {
        return $this->role === 'masyarakat';
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHECK
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}