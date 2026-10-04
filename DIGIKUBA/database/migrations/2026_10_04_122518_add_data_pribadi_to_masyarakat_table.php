<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('masyarakat', function (Blueprint $table) {
            if (!Schema::hasColumn('masyarakat', 'jenis_kelamin')) {
                $table->enum(
                    'jenis_kelamin',
                    [
                        'Laki-laki',
                        'Perempuan'
                    ]
                )->after('nama_lengkap');
            }

            if (!Schema::hasColumn('masyarakat', 'kewarganegaraan')) {
                $table->string(
                    'kewarganegaraan',
                    100
                )->after('tanggal_lahir');
            }

            if (!Schema::hasColumn('masyarakat', 'status_perkawinan')) {
                $table->enum(
                    'status_perkawinan',
                    [
                        'Belum Kawin',
                        'Kawin',
                        'Cerai Hidup',
                        'Cerai Mati'
                    ]
                )->after('kewarganegaraan');
            }

            if (!Schema::hasColumn('masyarakat', 'agama')) {
                $table->enum(
                    'agama',
                    [
                        'Islam',
                        'Kristen',
                        'Katolik',
                        'Hindu',
                        'Buddha',
                        'Konghucu'
                    ]
                )->after('status_perkawinan');
            }

            if (!Schema::hasColumn('masyarakat', 'pekerjaan')) {
                $table->string(
                    'pekerjaan',
                    100
                )->after('agama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('masyarakat', function (Blueprint $table) {

            $table->dropColumn([
                'jenis_kelamin',
                'kewarganegaraan',
                'status_perkawinan',
                'agama',
                'pekerjaan',
            ]);
        });
    }
};