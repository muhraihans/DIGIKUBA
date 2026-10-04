<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;

    protected $table = 'backups';

    protected $fillable = [
        'tahun',
        'nama_file',
        'path',
        'ukuran',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'ukuran' => 'integer',
        ];
    }
}