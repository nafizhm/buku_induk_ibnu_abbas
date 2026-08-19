<?php

namespace App\Http\Controllers\Rombel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RombelController extends Controller
{
    public function index($tahun1, $tahun2)
    {
        $tahun = $tahun1 . '/' . $tahun2;
        $tahunAjaran = TahunAjaran::where('tahun', $tahun)->firstOrFail();
        $tahun_id = $tahunAjaran->id;

        $permissions = HakAksesController::getUserPermissions();

        if (request()->ajax()) {
            $data = Rombel::with('walas')
                ->join('jenjang_kelas', 'rombel.jenjang_kelas_id', '=', 'jenjang_kelas.id')
                ->where('rombel.tahun_ajaran_id', $tahun_id)
                ->orderBy('jenjang_kelas.jenjang', 'asc')
                ->orderBy('rombel.nama', 'asc')
                ->selectRaw('rombel.*, jenjang_kelas.jenjang')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('kelas', function ($row) {
                    return 'Kelas ' . $row->jenjang . ' ' . $row->nama;
                })
                ->addColumn('jumlah_siswa', function ($row) {
                    return $row->penempatanSiswa->count();
                })
                ->addColumn('wali_kelas', function ($row) {
                    return $row->walas->nama ?? '';
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $btn = '<a href="' . route('detailKelas', $row->id) . '" data-toggle="tooltip" data-original-title="Detail Kelas" class="btn btn-info btn-sm detailRombel mr-1">Detail</a>';

                    if ($permissions['edit']) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="btn btn-primary btn-sm editRombel mr-1">Edit</a>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteRombel">Delete</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.rombel.index', compact('tahun1', 'tahun2', 'tahun_id', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:100',
                'jenjang_kelas_id' => 'required|exists:jenjang_kelas,id',
                'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
                'walas_id' => 'required|exists:pengajar,id',
            ],
            [
                'nama.required' => 'Nama rombel harus diisi',
                'nama.max' => 'Nama rombel maksimal 100 karakter',
                'jenjang_kelas_id.required' => 'Jenjang kelas harus dipilih',
                'tahun_ajaran_id.required' => 'Tahun ajaran harus dipilih',
                'walas_id.required' => 'Wali kelas harus dipilih',
            ]
        );

        try {
            Rombel::create([
                'nama' => $request->nama,
                'jenjang_kelas_id' => $request->jenjang_kelas_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'walas_id' => $request->walas_id
            ]);

            return response()->json(['status' => 'success', 'message' => 'Rombel berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Rombel gagal ditambahkan'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'nama' => 'required|string|max:100',
                'jenjang_kelas_id' => 'required|exists:jenjang_kelas,id',
                'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
                'walas_id' => 'required|exists:pengajar,id',
            ],
            [
                'nama.required' => 'Nama rombel harus diisi',
                'nama.max' => 'Nama rombel maksimal 100 karakter',
                'jenjang_kelas_id.required' => 'Jenjang kelas harus dipilih',
                'tahun_ajaran_id.required' => 'Tahun ajaran harus dipilih',
                'walas_id.required' => 'Wali kelas harus dipilih',
            ]
        );

        try {
            Rombel::where('id', $id)->update([
                'nama' => $request->nama,
                'jenjang_kelas_id' => $request->jenjang_kelas_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'walas_id' => $request->walas_id
            ]);

            return response()->json(['status' => 'success', 'message' => 'Rombel berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Rombel gagal diupdate'], 500);
        }
    }

    public function show($id)
    {
        $rombel = Rombel::with('jenjangKelas')->findOrFail($id);

        return response()->json([
            'id' => $rombel->id,
            'nama' => $rombel->nama,
            'jenjang_kelas_id' => $rombel->jenjang_kelas_id,
            'jenjang' => $rombel->jenjangKelas->jenjang,
            'walas_id' => $rombel->walas_id,
            'nama_walas' => $rombel->walas ? $rombel->walas->nama : null
        ]);
    }

    public function destroy($id)
    {
        try {
            Rombel::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Rombel berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Rombel gagal dihapus'], 500);
        }
    }

    public function sync($tahun_id)
    {
        try {
            $rombelSebelumnya = Rombel::join('jenjang_kelas', 'rombel.jenjang_kelas_id', '=', 'jenjang_kelas.id')
                ->where('rombel.tahun_ajaran_id', $tahun_id - 1)
                ->orderBy('jenjang_kelas.jenjang', 'asc')
                ->orderBy('rombel.nama', 'asc')
                ->select('rombel.*')
                ->get();

            if ($rombelSebelumnya->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada rombel di tahun sebelumnya.',
                ], 404);
            }

            foreach ($rombelSebelumnya as $rombel) {
                Rombel::create([
                    'nama' => $rombel->nama,
                    'jenjang_kelas_id' => $rombel->jenjang_kelas_id,
                    'tahun_ajaran_id' => $tahun_id,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Rombel berhasil disinkronkan.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Rombel gagal disinkronkan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function detailKelas($rombel_id)
    {
        $rombelId = Rombel::find($rombel_id);
        $tahunAjaran = TahunAjaran::find($rombelId->tahun_ajaran_id);

        if (!$rombelId || !$tahunAjaran) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $rombel = $rombelId->id;
        $tahun = $tahunAjaran->tahun;

        $isGuru = !empty(Auth::user()->id_pengajar) && Auth::user()->id_pengajar != 0;

        if (request()->ajax()) {
            $data = Siswa::whereHas('penempatanSiswa.rombel', function ($query) use ($rombel_id) {
                $query->where('rombel.id', $rombel_id);
            })
                ->orderBy('nama_lengkap', 'asc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', function ($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->addColumn('action', function ($row) use ($isGuru, $rombel) {
                    $btn = '<a href="' . route('bukuIndukSiswa', $row->id) . '" class="btn btn-primary btn-md mr-1"><i class="fas fa-pencil-alt"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-info btn-md"><i class="fas fa-print"></i></a>';

                    if ($isGuru) {
                        $btn .= '<a href="' . route('buku-penghubung.index', ['siswaId' => $row->id, 'rombelId' => $rombel]) . '" class="btn btn-warning btn-md ml-1" title="Buku Penghubung"><i class="fas fa-book"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.rombel.detail', compact('rombel', 'tahun'));
    }
}