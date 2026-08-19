<?php

namespace App\Http\Controllers\InformasiAkademik;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\pengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PengajarController extends Controller
{
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = Pengajar::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', function ($row) {
                    if (!$row->jenis_kelamin)
                        return '-';
                    return $row->jenis_kelamin == 'L' ? 'Laki - laki' : 'Perempuan';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->is_active ? 'Aktif' : 'Tidak Aktif';
                    $badge = $row->is_active ? 'badge bg-success text-white' : 'badge bg-danger text-white';
                    return '<span class="' . $badge . '">' . $status . '</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $showUrl = route('pengajar.show', $row->id);
                    $deleteUrl = route('pengajar.destroy', $row->id);

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
                ->rawColumns(['jenis_kelamin', 'status', 'action'])
                ->make(true);
        }

        return view('admin.informasi_akademik.pengajar.index', compact('permissions'));
    }

    public function show($id)
    {
        $data = Pengajar::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_pengajar' => 'required|unique:pengajar,kode_pengajar',
            'nip' => 'nullable|unique:pengajar,nip',
            'nama' => 'required',
            'password' => 'required|min:8',
            'jenis_kelamin' => 'required',
            'jabatan' => 'required',
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'pendidikan_terakhir' => 'required',
            'no_telepon' => 'required',
            'is_active' => 'required',
        ];

        $messages = [
            'kode_pengajar.required' => 'Kode pengajar wajib diisi.',
            'kode_pengajar.unique' => 'Kode pengajar sudah digunakan.',
            'nip.unique' => 'NIP sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi.',
            'no_telepon.required' => 'No telepon wajib diisi.',
            'is_active.required' => 'Status aktif wajib diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $db = [
                'kode_pengajar' => $request->kode_pengajar,
                'nip' => $request->nip,
                'nama' => $request->nama,
                'password' => Hash::make($request->password),
                'jenis_kelamin' => $request->jenis_kelamin,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'no_telepon' => $request->no_telepon,
                'is_active' => $request->is_active == '1' || $request->is_active == true,
            ];

            Pengajar::create($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengajar berhasil ditambahkan'
            ], 200);
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
        $data = Pengajar::findOrFail($id);

        Log::debug($id, $request->all());

        $rules = [
            'kode_pengajar' => 'required|unique:pengajar,kode_pengajar,' . $data->id . ',id',
            'nip' => 'nullable|unique:pengajar,nip,' . $data->id . ',id',
            'nama' => 'required',
            'password' => 'nullable|min:8',
            'jenis_kelamin' => 'required',
            'jabatan' => 'required',
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'pendidikan_terakhir' => 'required',
            'no_telepon' => 'required',
            'is_active' => 'required',
        ];

        $messages = [
            'kode_pengajar.required' => 'Kode pengajar wajib diisi.',
            'kode_pengajar.unique' => 'Kode pengajar sudah digunakan.',
            'nip.unique' => 'NIP sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi.',
            'no_telepon.required' => 'No telepon wajib diisi.',
            'is_active.required' => 'Status aktif wajib diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $db = [
                'kode_pengajar' => $request->kode_pengajar,
                'nip' => $request->nip,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'no_telepon' => $request->no_telepon,
                'is_active' => $request->is_active == '1' || $request->is_active == true,
            ];

            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $db['password'] = Hash::make($request->password);
            }

            $data->update($db);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengajar berhasil diubah'
            ], 200);
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
            $data = Pengajar::findOrFail($id);

            $data->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengajar berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
