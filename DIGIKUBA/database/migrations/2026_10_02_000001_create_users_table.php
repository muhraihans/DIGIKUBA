<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['superadmin', 'staff', 'lurah', 'masyarakat'])->default('masyarakat');
                $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
                $table->rememberToken();
                $table->timestamps();
                $table->index('role', 'idx_users_role');
                $table->index('status', 'idx_users_status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
