<?php
namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\DetailJawabanIsiSoal;
use App\Models\IsiSoal;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DataSoalController extends Controller
{
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        $auth        = Auth::user();

        if ($request->ajax()) {
            $data = Soal::with('guru')->orderBy('id', 'desc');

            if ($auth->id_role == 2) {
                $data->where('id_guru', $auth->id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('guru', function ($row) {
                    return $row->guru ? $row->guru->nama : '-';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('data-soal.edit', $row->id);
                    $showUrl   = route('data-soal.show', $row->id);
                    $deleteUrl = route('data-soal.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button"
                            data-id="' . e($row->id) . '"
                            data-url="' . e($editUrl) . '">Edit</button>';
                        $btn .= '<a href="' . e($showUrl) . '" class="btn btn-success btn-sm">Isi</a>';
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
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.lms.data_soal.index', compact('permissions', 'auth'));
    }

    public function edit($id)
    {
        $list = Soal::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = IsiSoal::where('id_soal', $id)->orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($row) {
                    if ($row->jenis == 1) {
                        return '<span class="badge bg-primary">Single Choice</span>';
                    } elseif ($row->jenis == 2) {
                        return '<span class="badge bg-success">Multiple Choice</span>';
                    } elseif ($row->jenis == 3) {
                        return '<span class="badge bg-warning text-white">Essay</span>';
                    } elseif ($row->jenis == 4) {
                        return '<span class="badge bg-info text-white">Select</span>';
                    } elseif ($row->jenis == 5) {
                        return '<span class="badge bg-danger">Rating</span>';
                    } else {
                        return '<span class="badge bg-secondary">Unknown</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $editUrl   = route('data-soal.isi-show', $row->id);
                    $deleteUrl = route('data-soal.isi-destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    $btn .= '<a href="' . e($editUrl) . '" class="btn btn-primary btn-sm mx-1">Edit</a>';

                    $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">
                        Hapus
                    </button>
                    </form>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'jenis'])
                ->make(true);
        }

        $data = Soal::findOrFail($id);

        return view('admin.lms.data_soal.isi.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi.',
        ]);

        DB::beginTransaction();
        try {

            $db = [
                'id_guru' => Auth::id(),
                'nama'    => $request->nama,
            ];

            Soal::create($db);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Soal berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing Soal: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan Soal.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = Soal::findOrFail($id);

        $request->validate([
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi.',
        ]);

        $db = [
            'nama' => $request->nama,
        ];

        $data->update($db);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Soal::findOrFail($id);
        $list = IsiSoal::where('id_soal', $data->id)->get();

        foreach ($list as $item) {
            if (! empty($item->foto) && file_exists(public_path('assets/bahan_soal/' . $item->foto))) {
                unlink(public_path('assets/bahan_soal/' . $item->foto));
            }
            DetailJawabanIsiSoal::where('id_isi_soal', $item->id)->delete();
            $item->delete();
        }

        $data->delete();

        return response()->json(['status' => 'success']);
    }

    public function isi_create($id)
    {
        $data = Soal::findOrFail($id);

        return view('admin.lms.data_soal.isi.create', compact('data'));
    }

    public function isi_store(Request $request, $id)
    {
        $request->validate([
            'soal'          => 'required',
            'jenis'         => 'required',
            'jawaban'       => 'required_if:jenis,1,2,4|array',
            'jawaban.*'     => 'required_if:jenis,1,2,4',
            'jumlah_rating' => 'required_if:jenis,5',
            'foto'           => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ], [
            'soal.required'             => 'Soal wajib diisi.',
            'jenis.required'            => 'Jenis jawaban wajib dipilih.',
            'jawaban.required_if'       => 'Minimal satu jawaban harus diisi.',
            'jawaban.array'             => 'Format jawaban tidak sesuai.',
            'jawaban.*.required_if'     => 'Setiap jawaban wajib diisi.',
            'jumlah_rating.required_if' => 'Jumlah rating wajib diisi.',
            'foto.mimes'              => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max'                => 'Ukuran foto maksimal 2 MB.',
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $ext  = $file->getClientOriginalExtension();
                $foto = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/bahan_soal/'), $foto);
            }

            $db = [
                'id_soal' => $id,
                'soal'    => $request->soal,
                'jenis'   => $request->jenis,
                'foto'    => $foto ?? null,
            ];

            $soal = IsiSoal::create($db);

            if ($request->jenis == 1 || $request->jenis == 2 || $request->jenis == 4) {
                foreach ($request->jawaban as $jawaban) {
                    DetailJawabanIsiSoal::create([
                        'id_isi_soal' => $soal->id,
                        'jawaban'     => $jawaban,
                    ]);
                }
            }
            if ($request->jenis == 5) {
                DetailJawabanIsiSoal::create([
                    'id_isi_soal' => $soal->id,
                    'jawaban'     => $request->jumlah_rating,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Soal berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing Isi Soal: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan Soal.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function isi_show($id)
    {
        $list = IsiSoal::findOrFail($id);
        $data = Soal::findOrFail($list->id_soal);

        $detail = collect();
        if (in_array($list->jenis, [1, 2, 4, 5])) {
            $detail = DetailJawabanIsiSoal::where('id_isi_soal', $list->id)->get();
        }

        return view('admin.lms.data_soal.isi.edit', compact('list', 'detail', 'data'));
    }

    public function isi_update(Request $request, $id)
    {
        $data = IsiSoal::findOrFail($id);

        $request->validate([
            'soal'          => 'required',
            'jenis'         => 'required',
            'jawaban'       => 'required_if:jenis,1,2,4|array',
            'jawaban.*'     => 'required_if:jenis,1,2,4',
            'jumlah_rating' => 'required_if:jenis,5',
            'foto'           => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ], [
            'soal.required'             => 'Soal wajib diisi.',
            'jenis.required'            => 'Jenis jawaban wajib dipilih.',
            'jawaban.required_if'       => 'Minimal satu jawaban harus diisi.',
            'jawaban.array'             => 'Format jawaban tidak sesuai.',
            'jawaban.*.required_if'     => 'Setiap jawaban wajib diisi.',
            'jumlah_rating.required_if' => 'Jumlah rating wajib diisi.',
            'foto.mimes'              => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max'                => 'Ukuran foto maksimal 2 MB.',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                if (! empty($data->foto) && file_exists(public_path('assets/bahan_soal/' . $data->foto))) {
                    unlink(public_path('assets/bahan_soal/' . $data->foto));
                }

                $file = $request->file('foto');
                $ext  = $file->getClientOriginalExtension();
                $foto = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/bahan_soal/'), $foto);
            }

            $db = [
                'soal'  => $request->soal,
                'jenis' => $request->jenis,
                'foto'  => $foto ?? $data->foto,
            ];

            DetailJawabanIsiSoal::where('id_isi_soal', $data->id)->delete();

            if ($request->jenis == 1 || $request->jenis == 2 || $request->jenis == 4) {
                foreach ($request->jawaban as $jawaban) {
                    DetailJawabanIsiSoal::create([
                        'id_isi_soal' => $data->id,
                        'jawaban'     => $jawaban,
                    ]);
                }
            }
            if ($request->jenis == 5) {
                DetailJawabanIsiSoal::create([
                    'id_isi_soal' => $data->id,
                    'jawaban'     => $request->jumlah_rating,
                ]);
            }

            $data->update($db);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Soal berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error editing Soal: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan Soal.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function isi_destroy($id)
    {
        $data = IsiSoal::findOrFail($id);
        if (! empty($data->foto) && file_exists(public_path('assets/bahan_soal/' . $data->foto))) {
            unlink(public_path('assets/bahan_soal/' . $data->foto));
        }
        DetailJawabanIsiSoal::where('id_isi_soal', $data->id)->delete();

        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
