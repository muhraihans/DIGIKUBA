<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-superadmin', function () {
    if (User::where('role', 'superadmin')->exists()) {
        $this->error('Superadmin sudah tersedia. Gunakan halaman pengelolaan user untuk akun berikutnya.');
        return 1;
    }

    $name = trim((string) $this->ask('Nama superadmin'));
    $email = trim((string) $this->ask('Email superadmin'));

    $validator = Validator::make(
        ['name' => $name, 'email' => $email],
        ['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', 'unique:users,email']]
    );

    if ($validator->fails()) {
        $this->error($validator->errors()->first());
        return 1;
    }

    $password = (string) $this->secret('Password superadmin (minimal 12 karakter)');
    $confirmation = (string) $this->secret('Ulangi password');

    if (strlen($password) < 12 || !hash_equals($password, $confirmation)) {
        $this->error('Password harus minimal 12 karakter dan kedua input harus sama.');
        return 1;
    }

    User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'superadmin',
        'status' => 'active',
    ]);

    $this->info('Akun superadmin berhasil dibuat. Simpan kredensial dengan aman.');

    return 0;
})->purpose('Buat akun superadmin pertama secara interaktif dan aman');
