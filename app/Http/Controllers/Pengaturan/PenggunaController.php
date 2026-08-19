<?php
namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\HakAkses;
use App\Models\Menu;
use App\Models\Pengguna;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Pengguna::orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    if (! $row->role) {
                        return '<span class="badge bg-secondary">Tidak Ada</span>';
                    }

                    $roleId   = $row->role->id;
                    $roleName = $row->role->role;

                    $badgeColors = [
                        1 => 'bg-indigo',
                        2 => 'bg-maroon',
                        3 => 'bg-primary',
                        4 => 'bg-info',
                        5 => 'bg-success',
                    ];

                    $color = $badgeColors[$roleId] ?? 'bg-secondary';

                    return '<span class="badge ' . $color . '">' . e($roleName) . '</span>';
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Aktif</span>';
                    } elseif ($row->status == 2) {
                        return '<span class="badge bg-danger">Blokir</span>';
                    } else {
                        return '<span class="badge bg-secondary">Unknown</span>';
                    }
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('pengguna.edit', $row->id);
                    $deleteUrl = route('pengguna.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button"
                            data-id="' . e($row->id) . '"
                            data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">
                        Hapus
                    </button>
                    </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'role'])
                ->make(true);
        }

        $roles = Role::all();

        return view('admin.pengaturan.pengguna.index', compact('permissions', 'roles'));
    }

    public function edit($id)
    {
        $list = Pengguna::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required',
            'status'   => 'required',
        ], [
            'nama.required'     => 'Nama tidak boleh kosong.',

            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',

            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',

            'role.required'     => 'Role harus dipilih.',
            'status.required'   => 'Status harus dipilih.',
        ]);

        $db = [
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email'    => $request->email,
            'role'     => $request->role,
            'status'   => $request->status,
        ];

        $user = Pengguna::create($db);

        $menus = Menu::all();

        foreach ($menus as $menu) {
            $akses = [
                'id_user' => $user->id,
                'id_menu' => $menu->id,
            ];

            $akses['lihat']  = 1;
            $akses['tambah'] = 0;
            $akses['edit']   = 0;
            $akses['hapus']  = 0;

            HakAkses::create($akses);
        }

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = Pengguna::findOrFail($id);

        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:users,username,' . $data->id . ',id',
            'password' => 'min:6',
            'email'    => 'required|email|unique:users,email,' . $data->id . ',id',
            'role'     => 'required',
            'status'   => 'required',
        ], [
            'nama.required'     => 'Nama tidak boleh kosong.',

            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',

            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',

            'role.required'     => 'Role harus dipilih.',
            'status.required'   => 'Status harus dipilih.',
        ]);

        $db = [
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email'    => $request->email,
            'role'     => $request->role,
            'status'   => $request->status,
        ];

        $data->update($db);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Pengguna::findOrFail($id);
        HakAkses::where('id_user', $data->id)->delete();
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
