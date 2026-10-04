# Deployment DIGIKUBA

## 1. Server dan document root

- PHP 8.3+, Composer 2, MySQL 8/MariaDB, dan ekstensi PHP Laravel termasuk `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `xml`, `ctype`, `curl`, `tokenizer`, `dom`, dan `gd`.
- Atur domain/web server agar document root menunjuk **hanya** ke folder `DIGIKUBA/public`.
- Aktifkan HTTPS. Jangan jadikan root proyek, `storage/`, atau `vendor/` sebagai folder publik.
- Aset CSS/JS yang dipakai aplikasi telah berada di `public/assets`; Node.js/Vite tidak diperlukan untuk deployment saat ini.

## 2. Backup dan database

1. Buat dan uji pemulihan backup SQL sebelum mengubah database.
2. Buat database MySQL dan user aplikasi dengan hak minimum yang diperlukan.
3. Jika mempertahankan basis data dari phpMyAdmin, impor/simpan data yang ada dan **jangan** jalankan `migrate:fresh` atau `db:wipe`.
4. Migrasi inti akan melewati tabel yang sudah ada; migrasi berikutnya menandai struktur tersebut dan menambah indeks yang belum ada. Periksa hasilnya dengan `php artisan migrate:status`.
5. Untuk instalasi baru, jalankan migrasi pada database kosong. Seeder `JenisSuratSeeder` menambahkan/menyelaraskan jenis surat SKTM, SPPT-PBB, dan Domisili tanpa menghapus data lainnya.

## 3. Environment produksi

Buat file `.env` langsung pada server (jangan commit/kirim file lokal). Minimal atur:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://domain-anda`
- `APP_KEY` unik yang dihasilkan sekali dengan `php artisan key:generate`
- `DB_CONNECTION=mysql` serta `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` produksi
- `SESSION_SECURE_COOKIE=true` karena situs harus memakai HTTPS
- `FILESYSTEM_DISK=local`; dokumen identitas/persyaratan tersimpan pada private disk dan jangan dipindah ke disk publik

Gunakan kredensial rahasia unik; jangan menggunakan kredensial contoh/default. Lindungi `.env` dengan permission server yang sesuai.

## 4. Instalasi/update kode

Dari root proyek di server:

1. `composer install --no-dev --prefer-dist --optimize-autoloader`
2. Pastikan `.env` sudah benar dan file `APP_KEY` tidak berubah saat update.
3. Jalankan `php artisan migrate --force` setelah backup dan pemeriksaan migration status.
4. Untuk database baru saja, jalankan `php artisan db:seed --class=JenisSuratSeeder --force`.
5. Pada instalasi baru, buat admin pertama dengan `php artisan app:create-superadmin`; command meminta password secara interaktif dan menolak berjalan bila superadmin sudah ada.
6. Pastikan `storage/` dan `bootstrap/cache/` writable oleh user PHP/web server.
7. Jalankan `php artisan optimize` setelah environment dan koneksi database tersedia.

Jangan jalankan seeder contoh/default yang membuat akun dengan password yang diketahui publik.

## 5. Setelah deployment

- Uji `/up`, halaman login, pendaftaran masyarakat, akses peran, pengajuan, notifikasi, preview dokumen, tanda tangan/QR, dan unduhan PDF.
- Pastikan storage private tidak memiliki symbolic link ke `public/`; file hanya diakses lewat controller dengan otorisasi.
- Konfigurasi mail/queue eksternal hanya jika fitur pengiriman mail/queue ditambahkan. Saat ini notifikasi disimpan melalui database dan operasi request berjalan sinkron.
- Siapkan backup database terjadwal di luar web root dan uji restore secara berkala.
- Jalankan tes di lingkungan build sebelum rilis: `php artisan view:cache` dan `php artisan test`.
