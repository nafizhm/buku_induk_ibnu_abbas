<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UtilityController;

Route::post('/login', [AuthController::class, 'login']);

// Utility WEB
Route::get('/get-jenjang', [UtilityController::class, 'getJenjang'])->name('get-jenjang');
Route::get('/get-roles', [UtilityController::class, 'getRoles'])->name('get-roles');
Route::get('/get-rombel', [UtilityController::class, 'getRombel'])->name('get-rombel');
Route::get('/get-kelas/{id}', [UtilityController::class, 'getKelas'])->name('get-kelas');

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);

//     Route::get('/siswa', [SiswaController::class, 'show']);
//     Route::put('/siswa', [SiswaController::class, 'update']);

//     Route::get('/get-metode-pembayaran', [TransactionController::class, 'getMetodePembayaran']);
//     Route::get('/get-tagihan', [TransactionController::class, 'getTagihan']);
//     Route::get('/get-pembayaran', [TransactionController::class, 'getPembayaran']);
//     Route::get('/get-all-pembayaran', [TransactionController::class, 'getAllPembayaran']);
//     Route::post('/create-pembayaran', [TransactionController::class, 'createPembayaran']);

//     Route::post('/absen', [AbsensiController::class, 'absen']);
//     Route::post('/izin', [AbsensiController::class, 'izin']);
//     Route::get('/get-absensi', [AbsensiController::class, 'getAbsensi']);
//     Route::get('/get-perizinan', [AbsensiController::class, 'getPerizinan']);

//     Route::get('/get-kalender-akademik', [KalenderAkademikController::class, 'getKalenderAkademik']);

//     Route::get('/get-buku-penghubung', [BukuPenghubungController::class, 'getBukuPenghubung']);
//     Route::get('/read-buku-penghubung/{id}', [BukuPenghubungController::class, 'markAsRead']);

//     Route::get('/get-jadwal-pelajaran', [JadwalPelajaranController::class, 'getJadwalPelajaran']);

//     Route::get('/get-pengumuman', [PengumumanController::class, 'getPengumuman']);

//     Route::get('/get-today-notification', [NotificationController::class, 'getTodayNotification']);
// });

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
