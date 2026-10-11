<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

// AUTH
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;


// NOTIFICATION
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SuratDownloadController;

// MASYARAKAT
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Masyarakat\PengajuanSuratController;
use App\Http\Controllers\Masyarakat\RiwayatController as MasyarakatRiwayatController;
use App\Http\Controllers\Masyarakat\ProfileController as MasyarakatProfileController;

// STAFF
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\VerifikasiAkunController;
use App\Http\Controllers\Staff\PengajuanController as StaffPengajuanController;
use App\Http\Controllers\Staff\MasyarakatController as StaffMasyarakatController;
use App\Http\Controllers\Staff\PengajuanController;

// LURAH
use App\Http\Controllers\Lurah\DashboardController as LurahDashboardController;
use App\Http\Controllers\Lurah\PengajuanController as LurahPengajuanController;
use App\Http\Controllers\Lurah\TandaTanganController;

// SUPERADMIN
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboardController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use App\Http\Controllers\Superadmin\JenisSuratController as SuperadminJenisSuratController;
use App\Http\Controllers\Superadmin\ActivityLogController;
use App\Http\Controllers\Superadmin\BackupController;
use App\Http\Controllers\Superadmin\StrukturKepegawaianController;

// PUBLIC
use App\Http\Controllers\PublicController;


/*
|--------------------------------------------------------------------------
| HOME / ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    /*
    |--------------------------------------------------------------------------
    | Jika belum login
    |--------------------------------------------------------------------------
    */

    if (!Auth::check()) {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Ambil user login
    |--------------------------------------------------------------------------
    */

    $user = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | Redirect berdasarkan role
    |--------------------------------------------------------------------------
    */

    if ($user->role === 'superadmin') {
        return redirect()->route(
            'superadmin.dashboard'
        );
    }


    if ($user->role === 'staff') {
        return redirect()->route(
            'staff.dashboard'
        );
    }


    if ($user->role === 'lurah') {
        return redirect()->route(
            'lurah.dashboard'
        );
    }


    if ($user->role === 'masyarakat') {
        return redirect()->route(
            'masyarakat.dashboard'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Role tidak dikenali
    |--------------------------------------------------------------------------
    */

    abort(
        403,
        'Role pengguna tidak dikenali.'
    );

})->name('home');


/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
|
| Hanya dapat diakses ketika belum login.
|
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/login',
        [LoginController::class, 'showLoginForm']
    )->name('login');


    Route::post(
        '/login',
        [LoginController::class, 'login']
    )->name('login.process');


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/register',
        [RegisterController::class, 'showRegistrationForm']
    )->name('register');


    Route::post(
        '/register',
        [RegisterController::class, 'register']
    )->name('register.process');

});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
|
| Semua route di bawah membutuhkan login.
|
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [LoginController::class, 'logout']
    )->name('logout');


    /*
    |--------------------------------------------------------------------------
    | UBAH PASSWORD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/ubah-password',
        [PasswordController::class, 'edit']
    )->name('password.edit');


    Route::put(
        '/ubah-password',
        [PasswordController::class, 'update']
    )->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifikasi',
        [NotificationController::class, 'index']
    )->name('notifications.index');


    Route::post(
        '/notifikasi/{id}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');


    Route::post(
        '/notifikasi/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.read-all');

});


/*
|--------------------------------------------------------------------------
| MASYARAKAT
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:masyarakat'
])
->prefix('masyarakat')
->name('masyarakat.')
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [MasyarakatDashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    |
    | Profile tetap dapat diakses walaupun akun masih pending.
    |
    */

    Route::get(
        '/profile',
        [MasyarakatProfileController::class, 'index']
    )->name('profile.index');


    Route::put(
        '/profile',
        [MasyarakatProfileController::class, 'update']
    )->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN SURAT
    |--------------------------------------------------------------------------
    |
    | Hanya masyarakat yang sudah active.
    |
    */

    Route::middleware('verified.account')->group(function () {


        /*
        |--------------------------------------------------------------------------
        | DAFTAR JENIS SURAT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengajuan',
            [PengajuanSuratController::class, 'index']
        )->name('pengajuan.index');


        
        /*
        |--------------------------------------------------------------------------
        | FORM PENGAJUAN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengajuan/create',
            [PengajuanSuratController::class, 'create']
        )->name('pengajuan.create');


        /*
        |--------------------------------------------------------------------------
        | SIMPAN PENGAJUAN
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pengajuan',
            [PengajuanSuratController::class, 'store']
        )->name('pengajuan.store');


        /*
        |--------------------------------------------------------------------------
        | FORM BERDASARKAN JENIS SURAT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengajuan/surat/{jenisSurat}',
            [PengajuanSuratController::class, 'form']
        )->name('pengajuan.form');

        Route::get(
            '/pengajuan/{pengajuan}/dokumen/{jenis}',
            [PengajuanSuratController::class, 'dokumen']
        )->name('pengajuan.dokumen');


        /*
        |--------------------------------------------------------------------------
        | DETAIL PENGAJUAN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengajuan/{pengajuan}',
            [PengajuanSuratController::class, 'show']
        )->name('pengajuan.show');


        /*
        |--------------------------------------------------------------------------
        | UPDATE PENGAJUAN
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/pengajuan/{pengajuan}',
            [PengajuanSuratController::class, 'update']
        )->name('pengajuan.update');


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT SURAT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat',
            [MasyarakatRiwayatController::class, 'index']
        )->name('riwayat.index');


        /*
        |--------------------------------------------------------------------------
        | DETAIL RIWAYAT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat/{pengajuan}',
            [MasyarakatRiwayatController::class, 'show']
        )->name('riwayat.show');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD PDF
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat/{pengajuan}/download',
            [MasyarakatRiwayatController::class, 'download']
        )->name('riwayat.download');

    });

});


/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
|
| Staff dan Superadmin dapat mengakses route Staff.
|
*/
Route::middleware('auth')->group(function () {
    Route::get(
        '/pengajuan/{pengajuan}/dokumen/{jenis}',
        [StaffPengajuanController::class, 'dokumen']
    )->name('pengajuan.dokumen');

    Route::get(
        '/surat/pengajuan/{pengajuan}/download',
        SuratDownloadController::class
    )->name('surat.pengajuan.download');
});

Route::middleware([
    'auth',
    'role:staff,superadmin'
])
->prefix('staff')
->name('staff.')
->group(function () {

    // DASHBOARD
    Route::get(
        '/dashboard',
        [StaffDashboardController::class, 'index']
    )->name('dashboard');


    // VERIFIKASI AKUN MASYARAKAT
    Route::get(
        '/verifikasi-akun',
        [VerifikasiAkunController::class, 'index']
    )->name('verifikasi-akun.index');

    Route::get(
        '/verifikasi-akun/{masyarakat}/dokumen/{jenis}',
        [VerifikasiAkunController::class, 'dokumen']
    )->name('verifikasi-akun.dokumen');

    Route::get(
        '/verifikasi-akun/{masyarakat}',
        [VerifikasiAkunController::class, 'show']
    )->name('verifikasi-akun.show');

    Route::post(
        '/verifikasi-akun/{masyarakat}/verify',
        [VerifikasiAkunController::class, 'verify']
    )->name('verifikasi-akun.verify');

    Route::post(
        '/verifikasi-akun/{masyarakat}/reject',
        [VerifikasiAkunController::class, 'reject']
    )->name('verifikasi-akun.reject');


    // DATA MASYARAKAT
    Route::get(
        '/masyarakat',
        [StaffMasyarakatController::class, 'index']
    )->name('masyarakat.index');

    Route::get(
        '/masyarakat/{masyarakat}',
        [StaffMasyarakatController::class, 'show']
    )->name('masyarakat.show');


    // PENGAJUAN SURAT
    Route::get(
        '/pengajuan',
        [StaffPengajuanController::class, 'index']
    )->name('pengajuan.index');

    Route::get(
        '/pengajuan/{pengajuan}',
        [StaffPengajuanController::class, 'show']
    )->name('pengajuan.show');

    // DOKUMEN PENGAJUAN
    Route::get(
        '/pengajuan/{pengajuan}/dokumen/{jenis}',
        [StaffPengajuanController::class, 'dokumen']
    )->name('pengajuan.dokumen');

    // VERIFIKASI PENGAJUAN
    Route::post(
        '/pengajuan/{pengajuan}/verify',
        [StaffPengajuanController::class, 'verify']
    )->name('pengajuan.verify');

    // MINTA PERBAIKAN
    Route::post(
        '/pengajuan/{pengajuan}/revision',
        [StaffPengajuanController::class, 'revision']
    )->name('pengajuan.revision');

    // TOLAK PENGAJUAN
    Route::post(
        '/pengajuan/{pengajuan}/reject',
        [StaffPengajuanController::class, 'reject']
    )->name('pengajuan.reject');

    // KOMENTAR
    Route::post(
        '/pengajuan/{pengajuan}/komentar',
        [StaffPengajuanController::class, 'comment']
    )->name('pengajuan.comment');
});


/*
|--------------------------------------------------------------------------
| LURAH
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:lurah,superadmin'
])
->prefix('lurah')
->name('lurah.')
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [LurahDashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN TERINVERIFIKASI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pengajuan',
        [LurahPengajuanController::class, 'index']
    )->name('pengajuan.index');


    /*
    |--------------------------------------------------------------------------
    | DETAIL PENGAJUAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pengajuan/{pengajuan}',
        [LurahPengajuanController::class, 'show']
    )->name('pengajuan.show');


    /*
    |--------------------------------------------------------------------------
    | TANDA TANGAN
    |--------------------------------------------------------------------------
    |
    | Route riwayat HARUS diletakkan sebelum
    | /tanda-tangan/{surat}.
    |
    */

    Route::get(
        '/tanda-tangan/riwayat/signed',
        [TandaTanganController::class, 'signed']
    )->name('tanda-tangan.signed');


    /*
    |--------------------------------------------------------------------------
    | DAFTAR SURAT YANG MENUNGGU TANDA TANGAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/tanda-tangan',
        [TandaTanganController::class, 'index']
    )->name('tanda-tangan.index');


    /*
    |--------------------------------------------------------------------------
    | DETAIL SURAT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/tanda-tangan/{surat}',
        [TandaTanganController::class, 'show']
    )->name('tanda-tangan.show');


    /*
    |--------------------------------------------------------------------------
    | PROSES TANDA TANGAN QR
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/tanda-tangan/{surat}',
        [TandaTanganController::class, 'sign']
    )->name('tanda-tangan.sign');

});


/*
|--------------------------------------------------------------------------
| SUPERADMIN
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
->group(function () {

Route::get(
    '/struktur-kepegawaian',
    [StrukturKepegawaianController::class, 'index']
)->name('struktur-kepegawaian.index');


Route::get(
    '/struktur-kepegawaian/create',
    [StrukturKepegawaianController::class, 'create']
)->name('struktur-kepegawaian.create');


Route::post(
    '/struktur-kepegawaian',
    [StrukturKepegawaianController::class, 'store']
)->name('struktur-kepegawaian.store');


Route::get(
    '/struktur-kepegawaian/{strukturKepegawaian}/edit',
    [StrukturKepegawaianController::class, 'edit']
)->name('struktur-kepegawaian.edit');


Route::put(
    '/struktur-kepegawaian/{strukturKepegawaian}',
    [StrukturKepegawaianController::class, 'update']
)->name('struktur-kepegawaian.update');


Route::delete(
    '/struktur-kepegawaian/{strukturKepegawaian}',
    [StrukturKepegawaianController::class, 'destroy']
)->name('struktur-kepegawaian.destroy');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [SuperadminDashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/users',
        [SuperadminUserController::class, 'index']
    )->name('users.index');


    Route::get(
        '/users/create',
        [SuperadminUserController::class, 'create']
    )->name('users.create');


    Route::post(
        '/users',
        [SuperadminUserController::class, 'store']
    )->name('users.store');


    Route::get(
        '/users/{user}',
        [SuperadminUserController::class, 'show']
    )->name('users.show');


    Route::get(
        '/users/{user}/edit',
        [SuperadminUserController::class, 'edit']
    )->name('users.edit');


    Route::put(
        '/users/{user}',
        [SuperadminUserController::class, 'update']
    )->name('users.update');


    Route::delete(
        '/users/{user}',
        [SuperadminUserController::class, 'destroy']
    )->name('users.destroy');


    /*
    |--------------------------------------------------------------------------
    | JENIS SURAT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/jenis-surat',
        [SuperadminJenisSuratController::class, 'index']
    )->name('jenis-surat.index');


    Route::get(
        '/jenis-surat/create',
        [SuperadminJenisSuratController::class, 'create']
    )->name('jenis-surat.create');


    Route::post(
        '/jenis-surat',
        [SuperadminJenisSuratController::class, 'store']
    )->name('jenis-surat.store');


    Route::get(
        '/jenis-surat/{jenisSurat}',
        [SuperadminJenisSuratController::class, 'show']
    )->name('jenis-surat.show');


    Route::get(
        '/jenis-surat/{jenisSurat}/edit',
        [SuperadminJenisSuratController::class, 'edit']
    )->name('jenis-surat.edit');


    Route::put(
        '/jenis-surat/{jenisSurat}',
        [SuperadminJenisSuratController::class, 'update']
    )->name('jenis-surat.update');


    Route::delete(
        '/jenis-surat/{jenisSurat}',
        [SuperadminJenisSuratController::class, 'destroy']
    )->name('jenis-surat.destroy');


    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/activity-log',
        [ActivityLogController::class, 'index']
    )->name('activity-log.index');


    Route::get(
        '/activity-log/{activityLog}',
        [ActivityLogController::class, 'show']
    )->name('activity-log.show');


    /*
    |--------------------------------------------------------------------------
    | BACKUP
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/backup',
        [BackupController::class, 'index']
    )->name('backup.index');


    Route::post(
        '/backup/create',
        [BackupController::class, 'create']
    )->name('backup.create');


    Route::get(
        '/backup/{backup}/download',
        [BackupController::class, 'download']
    )->name('backup.download');


    Route::delete(
        '/backup/{backup}',
        [BackupController::class, 'destroy']
    )->name('backup.destroy');

});


/*
|--------------------------------------------------------------------------
| PUBLIC - VERIFIKASI SURAT
|--------------------------------------------------------------------------
| Tidak membutuhkan login.
*/

Route::get(
    '/verifikasi-surat/{token}',
    [PublicController::class, 'verifikasiSurat']
)->name('verifikasi.surat');


/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
|
| Jika URL tidak ditemukan.
|
*/

Route::fallback(function () {

    abort(
        404,
        'Halaman yang Anda cari tidak ditemukan.'
    );

});