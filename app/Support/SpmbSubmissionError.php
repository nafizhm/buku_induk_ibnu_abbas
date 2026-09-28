<?php

namespace App\Support;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SpmbSubmissionError
{
    public static function response(\Throwable $exception): \Illuminate\Http\JsonResponse
    {
        $code = 'SPMB_SERVER';
        $message = 'Terjadi kesalahan server saat memproses registrasi. Hubungi admin sebelum mengirim ulang untuk memeriksa apakah data sudah tersimpan.';
        if ($exception instanceof QueryException) {
            $driverCode = (int) ($exception->errorInfo[1] ?? 0);
            [$code, $message] = match ($driverCode) {
                1146, 1054 => ['SPMB_DB_SCHEMA', 'Struktur database registrasi belum lengkap (tabel atau kolom belum tersedia). Admin perlu menjalankan pembaruan database SPMB.'],
                1044, 1045 => ['SPMB_DB_ACCESS', 'Akses database ditolak. Admin perlu memeriksa akun dan izin database server.'],
                2002, 2003, 2006, 2013 => ['SPMB_DB_CONNECTION', 'Koneksi ke database gagal atau terputus. Hubungi admin untuk memeriksa layanan database sebelum mengirim ulang.'],
                1062 => ['SPMB_DB_DUPLICATE', 'Data registrasi bentrok dengan data yang sudah tersimpan. Hubungi admin untuk memeriksa registrasi sebelum mengirim ulang.'],
                1048, 1364, 1406, 1265 => ['SPMB_DB_DATA', 'Data tidak sesuai dengan struktur database registrasi. Admin perlu memeriksa kolom wajib, panjang kolom, dan pembaruan database.'],
                default => ['SPMB_DB_WRITE', 'Database gagal memproses registrasi. Hubungi admin dengan kode referensi di bawah.'],
            };
        } elseif ($exception instanceof \League\Flysystem\FilesystemException
            || ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                && $exception->getMessage() === 'Bukti transfer gagal disimpan. Silakan coba lagi.')) {
            $code = 'SPMB_UPLOAD_STORAGE';
            $message = 'Bukti transfer gagal disimpan di server. Admin perlu memeriksa izin folder penyimpanan dan kapasitas disk.';
        }

        $reference = 'SPMB-'.Str::upper(Str::random(10));
        Log::error('Registrasi SPMB gagal', [
            'reference' => $reference, 'error_code' => $code, 'exception' => $exception,
        ]);

        return response()->json([
            'message' => $message, 'error_code' => $code, 'reference' => $reference,
        ], 500);
    }
}
