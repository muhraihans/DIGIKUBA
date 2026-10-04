<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengajuan_surat')) {
            Schema::create('pengajuan_surat', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_pengajuan', 100)->unique();
                $table->foreignId('masyarakat_id')->constrained('masyarakat')->cascadeOnDelete();
                $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->nullOnDelete();
                $table->json('data_pengajuan');
                $table->string('file_kk')->nullable();
                $table->string('file_pengantar_rt_rw')->nullable();
                $table->enum('status', ['pending', 'diproses', 'perlu_perbaikan', 'diverifikasi', 'menunggu_tanda_tangan', 'disetujui', 'ditolak', 'selesai'])->default('pending');
                $table->foreignId('diperiksa_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('diperiksa_at')->nullable();
                $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('disetujui_at')->nullable();
                $table->foreignId('ditolak_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('ditolak_at')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->index('status', 'idx_pengajuan_status');
                $table->index('created_at', 'idx_pengajuan_created');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
