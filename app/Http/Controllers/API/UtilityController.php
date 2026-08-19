<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pengajar;
use App\Models\JenjangKelas;
use App\Models\Role;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Exception;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function getJenjang()
    {
        try {
            $jenjang = JenjangKelas::all();
            return response()->json(
                [
                    "success" => true,
                    'message' => 'Data jenjang berhasil diambil',
                    "data" => $jenjang
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    'message' => 'Data jenjang gagal diambil',
                    'data' => []
                ],
                500
            );
        }
    }

    public function getRoles()
    {
        try {
            $roles = Role::all();
            return response()->json(
                [
                    "success" => true,
                    'message' => 'Data role berhasil diambil',
                    "data" => $roles
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    'message' => 'Data role gagal diambil',
                    'data' => []
                ],
                500
            );
        }
    }

    public function getRombel()
    {
        try {
            $tahunAktifId = TahunAjaran::where('is_active', 1)->value('id');

            $rombel = Rombel::with('jenjangKelas')
                ->join('jenjang_kelas', 'rombel.jenjang_kelas_id', '=', 'jenjang_kelas.id')
                ->where('rombel.tahun_ajaran_id', $tahunAktifId)
                ->select('rombel.id', 'rombel.jenjang_kelas_id', 'rombel.nama', 'jenjang_kelas.jenjang')
                ->orderBy('jenjang_kelas.jenjang', 'asc')
                ->orderBy('rombel.nama', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kelas' => $item->jenjang . ' ' . $item->nama
                    ];
                });

            return response()->json(
                [
                    "success" => true,
                    'message' => 'Data rombel berhasil diambil',
                    "data" => $rombel
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    'message' => 'Data rombel gagal diambil',
                    'data' => []
                ],
                500
            );
        }
    }

    public function getPengajar()
    {
        try {
            $pengajar = Pengajar::isActive()->where('jabatan', 'Wali Kelas')->get();

            return response()->json(
                [
                    "success" => true,
                    'message' => 'Data pengajar berhasil diambil',
                    "data" => $pengajar
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    'message' => 'Data pengajar gagal diambil',
                    'data' => []
                ],
                500
            );
        }
    }

    public function getPengajarMapel()
    {
        try {
            $pengajar = Pengajar::isActive()->where('jabatan', 'Pengajar Mapel')->get();
            return response()->json(
                [
                    "success" => true,
                    'message' => 'Data pengajar mapel berhasil diambil',
                    "data" => $pengajar
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    'message' => 'Data pengajar mapel gagal diambil',
                    'data' => []
                ],
                500
            );
        }
    }

    public function getKelas($id)
    {
        try {
            $siswa = Siswa::find($id);

            if (!$siswa) {
                return response()->json([
                    "success" => false,
                    "message" => "Siswa tidak ditemukan",
                    "data" => []
                ], 404);
            }

            $rombel = $siswa->getCurrentRombel();

            if (!$rombel) {
                return response()->json([
                    "success" => false,
                    "message" => "Siswa belum ditempatkan di rombel tahun ajaran aktif",
                    "data" => []
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "Data kelas berhasil diambil",
                "data" => [
                    'kelas' => $siswa->kelas,
                    'semester' => $siswa->semester,
                    'target' => $siswa->target
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 500);
        }
    }
}