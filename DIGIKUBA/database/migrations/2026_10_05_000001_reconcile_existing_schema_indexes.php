<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure common lookup indexes exist on both fresh installs and databases
     * that were initially created through phpMyAdmin.
     */
    public function up(): void
    {
        $indexes = [
            'users' => [
                ['role'],
                ['status'],
            ],
            'activity_logs' => [
                ['user_id'],
                ['created_at'],
            ],
            'pengajuan_surat' => [
                ['masyarakat_id'],
                ['jenis_surat_id'],
                ['status'],
                ['created_at'],
            ],
            'surat' => [
                ['status'],
                ['tanggal_surat'],
            ],
            'surat_tanda_tangan' => [
                ['lurah_id'],
                ['pegawai_id'],
            ],
            'notifications' => [
                ['notifiable_type', 'notifiable_id'],
                ['read_at'],
            ],
        ];

        foreach ($indexes as $table => $columnsList) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($columnsList as $columns) {
                if (Schema::hasIndex($table, $columns)) {
                    continue;
                }

                Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                    $blueprint->index($columns);
                });
            }
        }
    }

    /**
     * Leave these indexes in place on rollback because they may have existed
     * before the migration was run on an adopted phpMyAdmin database.
     */
    public function down(): void
    {
        // Intentionally non-destructive.
    }
};
