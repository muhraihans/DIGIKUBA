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
                $table->string('jenis_kelamin', 20)
                    ->nullable()
                    ->after('nama_lengkap');
            }

            if (!Schema::hasColumn('masyarakat', 'kewarganegaraan')) {
                $table->string('kewarganegaraan', 100)
                    ->nullable()
                    ->after('tanggal_lahir');
            }

            if (!Schema::hasColumn('masyarakat', 'status_perkawinan')) {
                $table->string('status_perkawinan', 30)
                    ->nullable()
                    ->after('kewarganegaraan');
            }

            if (!Schema::hasColumn('masyarakat', 'agama')) {
                $table->string('agama', 50)
                    ->nullable()
                    ->after('status_perkawinan');
            }

            if (!Schema::hasColumn('masyarakat', 'pekerjaan')) {
                $table->string('pekerjaan', 100)
                    ->nullable()
                    ->after('agama');
            }

            if (!Schema::hasColumn('masyarakat', 'pekerjaan_lainnya')) {
                $table->string('pekerjaan_lainnya', 150)
                    ->nullable()
                    ->after('pekerjaan');
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
                'pekerjaan_lainnya',
            ]);

        });
    }
};