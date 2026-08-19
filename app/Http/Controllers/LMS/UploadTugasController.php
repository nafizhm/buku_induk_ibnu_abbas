<?php
namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UploadTugasController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();
        $auth        = Auth::user();

        if ($request->ajax()) {
            $data = Tugas::with('guru')->orderByDesc('id');

            if ($auth->id_role == 2) {
                $data->where('id_guru', $auth->id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('guru', function ($row) {
                    return $row->guru ? $row->guru->nama : '-';
                })
                ->addColumn('waktu_mulai', function ($row) {
                    $tgl = Carbon::parse($row->tgl_mulai)->translatedFormat('j F Y');
                    $jam = '<span class="badge bg-primary">' . $row->jam_mulai . '</span>';
                    return $tgl . '<br>' . $jam;
                })
                ->addColumn('waktu_akhir', function ($row) {
                    $tgl = Carbon::parse($row->tgl_akhir)->translatedFormat('j F Y');
                    $jam = '<span class="badge bg-danger">' . $row->jam_akhir . '</span>';
                    return $tgl . '<br>' . $jam;
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('upload-tugas.edit', $row->id);
                    $deleteUrl = route('upload-tugas.destroy', $row->id);

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
                ->rawColumns(['guru', 'waktu_mulai', 'waktu_akhir', 'action'])
                ->make(true);

        }

        return view('admin.lms.upload_tugas.index', compact('permissions', 'auth'));
    }

    public function edit($id)
    {
        $list = Tugas::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'nama'      => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jam_mulai' => 'required',
            'jam_akhir' => 'required',
            'file'      => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama.required'            => 'Nama tidak boleh kosong.',
            'tgl_mulai.required'       => 'Tanggal mulai wajib diisi.',
            'tgl_mulai.date'           => 'Tanggal mulai harus format tanggal yang valid.',
            'tgl_akhir.required'       => 'Tanggal akhir wajib diisi.',
            'tgl_akhir.date'           => 'Tanggal akhir harus format tanggal yang valid.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'jam_mulai.required'       => 'Jam mulai wajib diisi.',
            'jam_akhir.required'       => 'Jam akhir wajib diisi.',
            'file.mimes'               => 'File harus berformat JPG, JPEG, PNG, atau PDF.',
            'file.max'                 => 'Ukuran file maksimal 2 MB.',
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ext  = $file->getClientOriginalExtension();

                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/bahan_tugas/'), $filename);
            }

            $db = [
                'id_guru'   => Auth::id(),
                'nama'      => $request->nama,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_akhir' => $request->tgl_akhir,
                'jam_mulai' => $request->jam_mulai,
                'jam_akhir' => $request->jam_akhir,
                'file'      => $filename ?? '',
            ];

            Tugas::create($db);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = Tugas::findOrFail($id);

        $request->validate([
            'nama'      => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jam_mulai' => 'required',
            'jam_akhir' => 'required',
            'file'      => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama.required'            => 'Nama tidak boleh kosong.',
            'tgl_mulai.required'       => 'Tanggal mulai wajib diisi.',
            'tgl_mulai.date'           => 'Tanggal mulai harus format tanggal yang valid.',
            'tgl_akhir.required'       => 'Tanggal akhir wajib diisi.',
            'tgl_akhir.date'           => 'Tanggal akhir harus format tanggal yang valid.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'jam_mulai.required'       => 'Jam mulai wajib diisi.',
            'jam_akhir.required'       => 'Jam akhir wajib diisi.',
            'file.mimes'               => 'File harus berformat JPG, JPEG, PNG, atau PDF.',
            'file.max'                 => 'Ukuran file maksimal 2 MB.',
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile('file')) {
                if (! empty($data->file) && file_exists(public_path('assets/bahan_tugas/' . $data->file))) {
                    unlink(public_path('assets/bahan_tugas/' . $data->file));
                }
                $file = $request->file('file');
                $ext  = $file->getClientOriginalExtension();

                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/bahan_tugas/'), $filename);
            }

            $db = [
                'nama'      => $request->nama,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_akhir' => $request->tgl_akhir,
                'jam_mulai' => $request->jam_mulai,
                'jam_akhir' => $request->jam_akhir,
                'file'      => $filename ?? $data->file,
            ];

            $data->update($db);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $data = Tugas::findOrFail($id);
        if (! empty($data->file) && file_exists(public_path('assets/bahan_tugas/' . $data->file))) {
            unlink(public_path('assets/bahan_tugas/' . $data->file));
        }$data->delete();

        return response()->json(['status' => 'success']);
    }
}
