<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\DataGuruController;
use App\Http\Controllers\Master\DataKelasController;
use App\Http\Controllers\Master\DataSiswaController;
use App\Http\Controllers\Master\TahunAjaranController;
use App\Http\Controllers\Master\KelasController as MasterKelasController;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Http\Controllers\Pengaturan\PenggunaController;
use App\Http\Controllers\RaporSiswaController;
use App\Http\Controllers\Siswa\CalonSiswaController;
use App\Http\Controllers\Siswa\PendaftaranController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Mobile\RegisterController;
use App\Http\Controllers\Mobile\SiswaController as MobileSiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('admin/login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('admin/post-login', [AuthController::class, 'postLogin'])->name('admin.loginPost');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('master')->group(function () {
        Route::get('jenjang', [MasterKelasController::class, 'index'])->name('jenjang.index');
        Route::resource('kelas', MasterKelasController::class)->except(['create', 'edit']);
        Route::resource('tahun-ajaran', TahunAjaranController::class);

        Route::post('/change-status-tahun/{id}', [TahunAjaranController::class, 'changeStatus'])
            ->name('tahun-ajaran.changeStatus');
    });

    Route::prefix('siswa')->group(function () {
        Route::resource('pendaftaran', PendaftaranController::class)->only(['index', 'store']);

        Route::controller(SiswaController::class)->group(function () {
            Route::get('/', 'index')->name('siswa.index');
            Route::get('/create', 'create')->name('siswa.create');
            Route::post('/download', 'download')->name('siswa.download');
            Route::post('/', 'store')->name('siswa.store');
            Route::get('/{id}', 'show')->whereNumber('id')->name('siswa.show');
            Route::get('/{id}/edit', 'edit')->whereNumber('id')->name('siswa.edit');
            Route::put('/{id}', 'update')->whereNumber('id')->name('siswa.update');
            Route::delete('/{id}', 'destroy')->name('siswa.destroy');
        });

        Route::controller(CalonSiswaController::class)->group(function () {
            Route::get('/calon-siswa', 'index')->name('calon-siswa.index');
        });
    });

    Route::prefix('pengaturan')->group(function () {
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('hak-akses', HakAksesController::class);
    });

    Route::resource('rapor-siswa', RaporSiswaController::class);
    Route::get('get-hak-akses', [HakAksesController::class, 'getHakAkses'])->name('admin.getHakAkses');
    Route::put('updateHakAkses', [HakAksesController::class, 'updateHakAkses'])->name('admin.updateHakAkses');

    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
});

Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
})->name('refresh.csrf');

Route::get('/paksa-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Anda telah logout.');
});


Route::prefix('mobile')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', function () {
            return view('mobile.login');
        })->name('mobile.login');

        Route::controller(RegisterController::class)->group(function () {
            Route::get('register', 'show')->name('mobile.register');
            Route::post('register', 'store')->name('mobile.register.store');
            Route::get('register/siswa/{kelas}', 'getSiswa')->name('mobile.register.siswa');
        });
    });

    // Di luar guest: user baru otomatis login saat diarahkan ke halaman ini
    Route::get('register/success', [RegisterController::class, 'success'])
        ->name('mobile.register.success');

    Route::get('siswa/daftar', [MobileSiswaController::class, 'create'])->name('siswa.daftar.create');

    Route::post('siswa', [MobileSiswaController::class, 'store'])->name('siswa.daftar.store');

    Route::get('siswa/{siswa}', [MobileSiswaController::class, 'show'])->whereNumber('siswa')->name('siswa.daftar.show');

    Route::put('siswa/{siswa}', [MobileSiswaController::class, 'update'])->whereNumber('siswa')->name('siswa.daftar.update');
    Route::post('siswa/{siswa}', [MobileSiswaController::class, 'update'])->whereNumber('siswa');
});

// Alias tanpa prefix mobile untuk kompatibilitas JS lama yang fetch ke /siswa
Route::post('siswa', [MobileSiswaController::class, 'store']);
Route::get('siswa/{siswa}', [MobileSiswaController::class, 'show'])->whereNumber('siswa');
Route::put('siswa/{siswa}', [MobileSiswaController::class, 'update'])->whereNumber('siswa');
Route::post('siswa/{siswa}', [MobileSiswaController::class, 'update'])->whereNumber('siswa');
