<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = MataPelajaran::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = $row->is_active ? 'Aktif' : 'Tidak Aktif';
                    $badge = $row->is_active ? 'badge-success' : 'badge-danger';
                    return '<span class="badge ' . $badge . '">' . $status . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('mata-pelajaran.show', $row->id);
                    $deleteUrl = route('mata-pelajaran.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                    $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('content.mata_pelajaran.index');
    }

    public function show($id)
    {
        $data = MataPelajaran::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|unique:mata_pelajaran,nama_mapel',
            'singkatan' => 'required|unique:mata_pelajaran,singkatan',
            'durasi' => 'required|numeric|min:1',
            'kkm' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|in:0,1',
        ];

        $messages = [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique' => 'Nama mata pelajaran sudah digunakan.',
            'singkatan.required' => 'Singkatan wajib diisi.',
            'singkatan.unique' => 'Singkatan sudah digunakan.',
            'is_active.required' => 'Status wajib diisi.',
            'is_active.boolean' => 'Status harus berupa boolean.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $db = [
                'kode_mapel' => $request->kode_mapel,
                'nama_mapel' => $request->nama_mapel,
                'singkatan' => $request->singkatan,
                'durasi' => $request->durasi,
                'kkm' => $request->kkm,
                'is_active' => $request->is_active == '1',
            ];

            MataPelajaran::create($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = MataPelajaran::findOrFail($id);

        $rules = [
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel,' . $data->id . ',id',
            'nama_mapel' => 'required|unique:mata_pelajaran,nama_mapel,' . $data->id . ',id',
            'singkatan' => 'required|unique:mata_pelajaran,singkatan,' . $data->id . ',id',
            'durasi' => 'required|numeric|min:1',
            'kkm' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|in:0,1',
        ];

        $messages = [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique' => 'Nama mata pelajaran sudah digunakan.',
            'singkatan.required' => 'Singkatan wajib diisi.',
            'singkatan.unique' => 'Singkatan sudah digunakan.',
            'is_active.required' => 'Status wajib diisi.',
            'is_active.boolean' => 'Status harus berupa boolean.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $db = [
                'kode_mapel' => $request->kode_mapel,
                'nama_mapel' => $request->nama_mapel,
                'singkatan' => $request->singkatan,
                'durasi' => $request->durasi,
                'kkm' => $request->kkm,
                'is_active' => $request->is_active == '1',
            ];

            $data->update($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil diubah'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = MataPelajaran::findOrFail($id);

            $data->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Mata pelajaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
