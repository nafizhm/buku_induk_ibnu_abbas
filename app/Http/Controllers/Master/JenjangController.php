<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\JenjangKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class JenjangController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = JenjangKelas::orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $showUrl = route('jenjang.show', $row->id);
                    $deleteUrl = route('jenjang.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button mx-1" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.master.jenjang.index', compact('permissions'));
    }

    public function show($id)
    {
        $data = JenjangKelas::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'jenjang' => 'required|unique:jenjang_kelas,jenjang',
        ];

        $messages = [
            'jenjang.required' => 'Jenjang wajib diisi.',
            'jenjang.unique' => 'Jenjang sudah ada.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $db = [
                'jenjang' => $request->jenjang,
            ];

            JenjangKelas::create($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Jenjang kelas berhasil ditambahkan.'
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
        $data = JenjangKelas::findOrFail($id);

        $rules = [
            'jenjang' => 'required|unique:jenjang_kelas,jenjang,' . $data->id,
        ];

        $messages = [
            'jenjang.required' => 'Jenjang wajib diisi.',
            'jenjang.unique' => 'Jenjang sudah ada.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $db = [
                'jenjang' => $request->jenjang,
            ];

            $data->update($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Jenjang kelas berhasil diperbarui.'
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
            $data = JenjangKelas::findOrFail($id);

            $data->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Jenjang kelas berhasil dihapus.'
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