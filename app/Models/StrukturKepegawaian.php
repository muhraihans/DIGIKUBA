<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturKepegawaian extends Model
{
    use HasFactory;

    protected $table = 'struktur_kepegawaian';

    protected $fillable = [
        'nama',
        'jabatan',
        'nip',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}