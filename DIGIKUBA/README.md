# DIGIKUBA

DIGIKUBA adalah aplikasi layanan pengajuan dan penerbitan surat Kelurahan Kutabaru berbasis Laravel 12. Aplikasi menyediakan akses untuk masyarakat, staff, lurah, dan superadmin; pengajuan surat; dokumen persyaratan privat; tanda tangan digital; QR verifikasi; notifikasi; dan unduhan PDF.

## Kebutuhan

- PHP 8.3 atau lebih baru dan ekstensi yang dibutuhkan Laravel/PDO MySQL.
- Composer 2.
- MySQL 8 atau MariaDB yang kompatibel.
- Web server Apache/Nginx dengan document root diarahkan ke folder `public/`.

Aset aplikasi saat ini tersedia di `public/assets`; proses build Node.js hanya diperlukan jika pipeline frontend Vite mulai digunakan.

## Setup lokal

1. Pasang dependensi PHP dengan `composer install`.
2. Salin `.env.example` menjadi `.env`, isi koneksi database, lalu buat `APP_KEY` dengan `php artisan key:generate`.
3. Pastikan MySQL aktif dan database kosong/siap dipakai.
4. Jalankan migrasi dengan `php artisan migrate`.
5. Isi data referensi surat dengan `php artisan db:seed --class=JenisSuratSeeder`.
6. Buat akun admin pertama dengan `php artisan app:create-superadmin`; password diminta secara tersembunyi dan tidak disimpan di source code.
7. Jalankan tes dengan `php artisan test`.

## Database yang sebelumnya dibuat melalui phpMyAdmin

Migrasi create-table inti dibuat idempotent: jika tabel yang cocok sudah ada, migrasi akan mengadopsinya tanpa membuat ulang atau menghapus isinya. Migrasi rekonsiliasi menambahkan indeks yang kurang. Tetap lakukan backup SQL terlebih dahulu, lalu periksa `php artisan migrate:status` dan migrasikan dengan hati-hati. Jangan gunakan `migrate:fresh` atau `db:wipe` pada database berisi data.

Migrasi menyimpan struktur database, bukan salinan data pribadi produksi. Data jenis surat disediakan lewat seeder idempotent. Data masyarakat, user, dan pengajuan harus dipertahankan dari backup/impor database.

## Deployment produksi

Panduan lengkap: [DEPLOYMENT.md](DEPLOYMENT.md).

**Penting:** jangan deploy file `.env` lokal. Buat `.env` produksi di server, gunakan HTTPS, `APP_DEBUG=false`, kredensial database dengan hak minimum, direktori `storage` dan `bootstrap/cache` yang dapat ditulis, serta document root `public/`.
