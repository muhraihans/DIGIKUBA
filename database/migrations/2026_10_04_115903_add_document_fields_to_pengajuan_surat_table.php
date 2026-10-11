<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_surat', 'file_kk')) {
                $table->string('file_kk')
                    ->nullable()
                    ->after('data_pengajuan');
            }

            if (!Schema::hasColumn('pengajuan_surat', 'file_pengantar_rt_rw')) {
                $table->string('file_pengantar_rt_rw')
                    ->nullable()
                    ->after('file_kk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {

            $table->dropColumn([
                'file_kk',
                'file_pengantar_rt_rw',
            ]);

        });
    }
};