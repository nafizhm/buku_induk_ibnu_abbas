<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CapaianKinerja\DataRaporKinerjaGuruController;
use App\Http\Controllers\CapaianKinerja\DataSupervisiAkademikController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IjazahController;
use App\Http\Controllers\InformasiAkademik\KelasController;
use App\Http\Controllers\Lms\DataSoalController;
use App\Http\Controllers\Lms\RekapNilaiController;
use App\Http\Controllers\Lms\UploadTugasController;
use App\Http\Controllers\Master\DataGuruController;
use App\Http\Controllers\Master\DataKelasController;
use App\Http\Controllers\Master\DataSiswaController;
use App\Http\Controllers\Master\HariLiburNasionalController;
use App\Http\Controllers\Master\MataPelajaranController;
use App\Http\Controllers\Master\RaporKinerjaGuruController;
use App\Http\Controllers\Master\SupervisiAkademikController;
use App\Http\Controllers\Master\TahunAjaranController;
use App\Http\Controllers\InformasiAkademik\PengajarController;
use App\Http\Controllers\Master\JenjangController;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Http\Controllers\Pengaturan\PenggunaController;
use App\Http\Controllers\Presensi\HitungAbsenController;
use App\Http\Controllers\Presensi\IzinGuruController;
use App\Http\Controllers\Presensi\IzinSiswaController;
use App\Http\Controllers\Presensi\RekapAbsenGuruController;
use App\Http\Controllers\Presensi\RekapAbsenSiswaController;
use App\Http\Controllers\Presensi\SettingAbsenController;
use App\Http\Controllers\RaporSiswaController;
use App\Http\Controllers\Rombel\RombelController;
use App\Http\Controllers\Siswa\CalonSiswaController;
use App\Http\Controllers\Siswa\PendaftaranController;
use App\Http\Controllers\Siswa\SiswaController;
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
    Route::redirect('beranda', '/admin/dashboard')->name('beranda');

    Route::prefix('master')->group(function () {
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        Route::resource('jenjang', JenjangController::class)->except(['create', 'edit']);
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::resource('hari-libur-nasional', HariLiburNasionalController::class);
        Route::resource('supervisi-akademik', SupervisiAkademikController::class);
        Route::resource('rapor-kinerja-guru', RaporKinerjaGuruController::class);

        Route::post('/change-status-tahun/{id}', [TahunAjaranController::class, 'changeStatus'])
            ->name('tahun-ajaran.changeStatus');
    });

    Route::prefix('rombongan-belajar')->group(function () {
        Route::get('/rombel/tahun/{tahun1}/{tahun2}', [RombelController::class, 'index'])->name('rombel.index');
        Route::resource('rombel', RombelController::class)->only(['show', 'store', 'update', 'destroy']);
        Route::post('/sync-rombel/{tahun}', [RombelController::class, 'sync'])->name('syncRombel');

        Route::get('/detail-kelas/{rombel_id}', [RombelController::class, 'detailKelas'])->name('detailKelas');
    });

    Route::prefix('informasi-akademik')->group(function () {
        Route::resource('pengajar', PengajarController::class)->except(['create', 'edit']);

        Route::controller(KelasController::class)->group(function () {
            Route::get('/menu-kelas', 'menu')->name('menuKelas');
            Route::get('/kenaikan-siswa', 'kenaikanKelas')->name('kenaikan-kelas.index');
            Route::get('/kenaikan-siswa/{id}/{tahun}', 'kenaikanSiswa')->name('kenaikanSiswa');
            Route::get('/get-rombel-tujuan', 'getRombelTujuan')->name('getRombelTujuan');
            Route::post('/changeKelas', 'changeKelas')->name('changeKelas');
        });
    });

    Route::prefix('siswa')->group(function () {
        Route::resource('pendaftaran', PendaftaranController::class)->only(['index', 'store']);
        
        Route::controller(SiswaController::class)->group(function () {
            Route::get('/', 'index')->name('siswa.index');
            Route::get('/list-calon', 'getCalonSiswa')->name('listCalonSiswa');
            Route::post('/add-siswa', 'addSiswa')->name('addSiswa');
            Route::delete('/{id}', 'destroy')->name('siswa.destroy');
            Route::get('/get-rombel', 'getRombel')->name('getRombel');
        });

        Route::controller(CalonSiswaController::class)->group(function () {
            Route::get('/calon-siswa', 'index')->name('calon-siswa.index');
        });
    });

    Route::prefix('presensi')->group(function () {
        Route::resource('setting-absen', SettingAbsenController::class);
        Route::resource('izin-siswa', IzinSiswaController::class);
        Route::resource('izin-guru', IzinGuruController::class);
        Route::resource('rekap-absen-siswa', RekapAbsenSiswaController::class);
        Route::resource('rekap-absen-guru', RekapAbsenGuruController::class);
        Route::resource('hitung-absen', HitungAbsenController::class);
    });

    Route::prefix('lms')->group(function () {
        Route::resource('data-soal', DataSoalController::class);

        Route::prefix('data-soal')->group(function () {
            Route::post('isi-data-soal/{id}', [DataSoalController::class, 'isi_store'])->name('data-soal.isi-store');
            Route::get('isi-data-soal-create/{id}', [DataSoalController::class, 'isi_create'])->name('data-soal.isi-create');
            Route::get('isi-data-soal/{id}', [DataSoalController::class, 'isi_show'])->name('data-soal.isi-show');
            Route::put('isi-data-soal/{id}', [DataSoalController::class, 'isi_update'])->name('data-soal.isi-update');
            Route::delete('isi-data-soal/{id}', [DataSoalController::class, 'isi_destroy'])->name('data-soal.isi-destroy');
        });

        Route::resource('upload-tugas', UploadTugasController::class);
        Route::resource('rekap-nilai', RekapNilaiController::class);
    });

    Route::prefix('capaian-kinerja')->group(function () {
        Route::resource('capaian-rapor-kinerja-guru', DataRaporKinerjaGuruController::class);
        Route::resource('capaian-supervisi-akademik', DataSupervisiAkademikController::class);
    });

    Route::prefix('pengaturan')->group(function () {
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('hak-akses', HakAksesController::class);
    });

    Route::resource('rapor-siswa', RaporSiswaController::class);
    Route::resource('ijazah', IjazahController::class);

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
