<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_tanda_tangan', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_tanda_tangan', 'pegawai_id')) {
                $table->foreignId('pegawai_id')
                    ->nullable()
                    ->after('surat_id')
                    ->constrained(
                        'struktur_kepegawaian'
                    )
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_tanda_tangan', function (Blueprint $table) {

            $table->dropForeign([
                'pegawai_id'
            ]);

            $table->dropColumn(
                'pegawai_id'
            );

        });
    }
};