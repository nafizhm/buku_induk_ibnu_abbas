<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = TahunAjaran::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = $row->is_active ? 'Aktif' : 'Tidak Aktif';
                    $badgeClass = $row->is_active ? 'bg-success' : 'bg-danger';
                    $changeStatusUrl = route('tahun-ajaran.changeStatus', $row->id);

                    return '<span class="badge ' . $badgeClass . ' change-status-badge" 
                style="cursor: pointer;" 
                data-id="' . $row->id . '" 
                data-url="' . $changeStatusUrl . '">'
                        . $status .
                        '</span>';
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $showUrl = route('tahun-ajaran.show', $row->id);
                    $deleteUrl = route('tahun-ajaran.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button mx-" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' .  e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                    }
                    
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.master.tahun_ajaran.index', compact('permissions'));
    }

    public function show($id)
    {
        $data = TahunAjaran::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'tahun' => 'required|string|max:255',
        ];

        $messages = [
            'tahun.required' => 'Tahun tidak boleh kosong',
            'tahun.string' => 'Tahun harus berupa string',
            'tahun.max' => 'Tahun maksimal 255 karakter',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $isActive = $request->has('is_active') && $request->is_active;

            if ($isActive) {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            }

            $db = [
                'tahun' => $request->tahun,
                'is_active' => $isActive,
            ];

            TahunAjaran::create($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tahun ajaran berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Tahun ajaran gagal ditambahkan',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = TahunAjaran::findOrFail($id);

        $rules = [
            'tahun' => 'required|string|max:255',
        ];

        $messages = [
            'tahun.required' => 'Tahun tidak boleh kosong',
            'tahun.string' => 'Tahun harus berupa string',
            'tahun.max' => 'Tahun maksimal 255 karakter',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $isActive = $request->has('is_active') && $request->is_active;
            $wasActive = $data->is_active;

            if ($wasActive && !$isActive) {
                $jumlahAktif = TahunAjaran::where('is_active', true)->count();

                if ($jumlahAktif == 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tidak dapat menonaktifkan tahun ajaran. Minimal harus ada satu tahun ajaran yang aktif.',
                    ], 422);
                }
            }

            if ($isActive && !$wasActive) {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);
            }

            $db = [
                'tahun' => $request->tahun,
                'is_active' => $isActive,
            ];

            $data->update($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tahun ajaran berhasil diubah',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Tahun ajaran gagal diubah',
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $data = TahunAjaran::findOrFail($id);

            if ($data->is_active) {
                $jumlahAktif = TahunAjaran::where('is_active', true)->count();

                if ($jumlahAktif == 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tidak dapat menghapus tahun ajaran aktif. Minimal harus ada satu tahun ajaran yang aktif.',
                    ], 422);
                }
            }

            $data->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tahun ajaran berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Tahun ajaran gagal dihapus',
            ], 500);
        }
    }

    public function changeStatus($id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        DB::beginTransaction();

        try {
            if ($tahun->is_active) {
                $jumlahAktif = TahunAjaran::where('is_active', true)->count();

                if ($jumlahAktif == 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Minimal harus ada satu tahun ajaran yang aktif.'
                    ], 422);
                }

                $tahun->is_active = false;
                $tahun->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Tahun ajaran berhasil dinonaktifkan.'
                ]);
            } else {
                TahunAjaran::where('is_active', true)->update(['is_active' => false]);

                $tahun->is_active = true;
                $tahun->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Tahun ajaran berhasil diaktifkan.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengubah status.'
            ], 500);
        }
    }
}
