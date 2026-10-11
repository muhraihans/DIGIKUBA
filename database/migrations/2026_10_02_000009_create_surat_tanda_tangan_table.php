<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('surat_tanda_tangan')) {
            Schema::create('surat_tanda_tangan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('surat_id')->unique()->constrained('surat')->cascadeOnDelete();
                $table->foreignId('lurah_id')->constrained('users')->nullOnDelete();
                $table->string('token', 64)->unique();
                $table->string('qr_code')->nullable();
                $table->timestamp('signed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tanda_tangan');
    }
};
