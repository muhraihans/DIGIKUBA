<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('surat')) {
            Schema::create('surat', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pengajuan_id')->unique()->constrained('pengajuan_surat')->cascadeOnDelete();
                $table->string('nomor_surat', 100);
                $table->date('tanggal_surat')->nullable();
                $table->string('perihal');
                $table->string('file_pdf')->nullable();
                $table->enum('status', ['draft', 'menunggu_tanda_tangan', 'ditandatangani', 'selesai'])->default('draft');
                $table->timestamps();
                $table->index('status', 'idx_surat_status');
                $table->index('tanggal_surat', 'idx_surat_tanggal');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
