<?php

namespace App\Http\Controllers\InformasiAkademik;

use App\Http\Controllers\Controller;
use App\Models\PenempatanSiswa;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function kenaikanKelas()
    {
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();

        if (request()->ajax()) {
            $data = Rombel::with('walas')
                ->filterRombelAktif()
                ->join('jenjang_kelas', 'rombel.jenjang_kelas_id', '=', 'jenjang_kelas.id')
                ->selectRaw('rombel.*, jenjang_kelas.jenjang')
                ->where('jenjang', '<', function ($query) {
                    $query->selectRaw('MAX(jenjang)')
                        ->from('jenjang_kelas');
                })
                ->orderBy('jenjang_kelas.jenjang')
                ->orderBy('rombel.nama')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('kelas', function ($row) {
                    return $row ? 'Kelas ' . $row->jenjangKelas->jenjang . ' ' . $row->nama : '-';
                })
                ->addColumn('nipd', function ($row) {
                    return $row->nipd ?? '';
                })
                ->addColumn('wali_kelas', function ($row) {
                    return $row->walas->nama ?? '';
                })
                ->addColumn('jumlah_siswa', function ($row) {
                    return $row->penempatanSiswa->count();
                })
                ->addColumn('action', function ($row) use ($tahunAjaran) {
                    $btn = '<a href="' . route('kenaikanSiswa', [$row->id, $tahunAjaran->id]) . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="btn btn-primary btn-sm">Detail</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('content.KenaikanKelas.index');
    }

    public function kenaikanSiswa($rombel_id, $tahun_ajaran_id)
    {
        $rombelId = Rombel::find($rombel_id);
        $tahunAjaranId = TahunAjaran::find($tahun_ajaran_id);

        if (!$rombelId || !$tahunAjaranId) {
            return redirect()->route('kenaikan-kelas.index')->with('error', 'Data tidak ditemukan.');
        }

        $rombel = $rombel_id;
        $tahunAjaran = $tahun_ajaran_id;

        if (request()->ajax()) {
            $data = Siswa::whereHas('penempatanSiswa.rombel', function ($query) use ($rombel_id, $tahun_ajaran_id) {
                $query->where('rombel.id', $rombel_id)
                    ->where('rombel.tahun_ajaran_id', $tahun_ajaran_id);
            })
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_siswa', function ($row) {
                    return $row->nama_lengkap ?? '';
                })
                ->make(true);
        }

        return view('content.KenaikanKelas.detail', compact('rombel', 'tahunAjaran'));
    }

    public function getRombelTujuan(Request $request)
    {
        try {
            $rombelId = $request->input('rombel_id');

            if (!$rombelId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter rombel_id wajib diisi.',
                    'data' => []
                ], 400);
            }

            $tahunAktif = TahunAjaran::where('is_active', 1)->first();
            if (!$tahunAktif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tahun ajaran aktif tidak ditemukan.',
                    'data' => []
                ], 404);
            }

            $tahunAktifId = $tahunAktif->id;

            $tahunDepanId = TahunAjaran::where('id', '>', $tahunAktifId)
                ->orderBy('id')
                ->value('id');

            if (!$tahunDepanId) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tahun ajaran berikutnya tidak ditemukan.',
                    'data' => [
                        'naik' => [],
                        'tinggal' => []
                    ]
                ], 200);
            }

            $rombelAsal = Rombel::with('jenjangKelas')->find($rombelId);

            if (!$rombelAsal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rombel tidak ditemukan.',
                    'data' => []
                ], 404);
            }

            $jenjangAsal = $rombelAsal->jenjangKelas->jenjang;

            $rombelTahunDepan = Rombel::join('jenjang_kelas', 'rombel.jenjang_kelas_id', '=', 'jenjang_kelas.id')
                ->where('rombel.tahun_ajaran_id', $tahunDepanId)
                ->select('rombel.id', 'rombel.nama', 'jenjang_kelas.jenjang')
                ->get();

            if ($rombelTahunDepan->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Rombel tahun depan tidak ditemukan.',
                    'data' => [
                        'naik' => [],
                        'tinggal' => []
                    ]
                ], 200);
            }

            $naik = $rombelTahunDepan
                ->where('jenjang', $jenjangAsal + 1)
                ->values()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kelas' => $item->jenjang . ' ' . $item->nama
                    ];
                });

            $tinggal = $rombelTahunDepan
                ->where('jenjang', $jenjangAsal)
                ->values()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kelas' => $item->jenjang . ' ' . $item->nama
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Data rombel tujuan berhasil diambil.',
                'data' => [
                    'naik' => $naik,
                    'tinggal' => $tinggal
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function changeKelas(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'rombel_id' => 'required|exists:rombel,id',
        ]);

        try {
            PenempatanSiswa::create([
                'siswa_id' => $request->siswa_id,
                'rombel_id' => $request->rombel_id
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data'
            ], 500);
        }
    }

    public function menu()
    {        
        $tahunAktifId = TahunAjaran::where('is_active', 1)->value('id');

        $menu = Rombel::with('jenjangKelas')
            ->join('jenjang_kelas', 'rombel.jenjang_kelas_id', '=', 'jenjang_kelas.id')
            ->where('rombel.tahun_ajaran_id', $tahunAktifId)
            ->select('rombel.id', 'rombel.jenjang_kelas_id', 'rombel.nama', 'jenjang_kelas.jenjang')
            ->orderBy('jenjang_kelas.jenjang', 'asc')
            ->orderBy('rombel.nama', 'asc')
            ->get();

        return view('admin.informasi_akademik.kelas.menu', compact('menu'));
    }
}
