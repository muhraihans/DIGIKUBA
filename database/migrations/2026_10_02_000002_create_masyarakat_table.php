<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('masyarakat')) {
            Schema::create('masyarakat', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('nik', 16)->unique();
                $table->string('nama_lengkap');
                $table->string('jenis_kelamin', 20)->nullable();
                $table->string('tempat_lahir', 100);
                $table->date('tanggal_lahir');
                $table->string('kewarganegaraan', 100)->nullable();
                $table->string('status_perkawinan', 30)->nullable();
                $table->string('agama', 50)->nullable();
                $table->string('pekerjaan', 100)->nullable();
                $table->string('pekerjaan_lainnya', 150)->nullable();
                $table->text('alamat');
                $table->string('foto_ktp');
                $table->string('foto_selfie');
                $table->timestamp('verified_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('masyarakat');
    }
};
