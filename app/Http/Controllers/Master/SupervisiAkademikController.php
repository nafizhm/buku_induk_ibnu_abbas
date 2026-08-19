<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\DetailJawabanSA;
use App\Models\SoalSupervisiAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SupervisiAkademikController extends Controller
{
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = SoalSupervisiAkademik::orderBy('id', 'asc');

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
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('supervisi-akademik.show', $row->id);
                    $deleteUrl = route('supervisi-akademik.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<a href="' . e($editUrl) . '" class="btn btn-primary btn-sm mx-1">Edit</a>';
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
                ->rawColumns(['action', 'jenis'])
                ->make(true);
        }

        return view('admin.master.supervisi_akademik.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.master.supervisi_akademik.create');
    }
    public function show($id)
    {
        $list = SoalSupervisiAkademik::findOrFail($id);

        $detail = collect();
        if (in_array($list->jenis, [1, 2, 4, 5])) {
            $detail = DetailJawabanSA::where('id_soal', $list->id)->get();
        }

        return view('admin.master.supervisi_akademik.edit', compact('list', 'detail'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'soal'          => 'required',
            'jenis'         => 'required',
            'jawaban'       => 'required_if:jenis,1,2,4|array',
            'jawaban.*'     => 'required_if:jenis,1,2,4',
            'jumlah_rating' => 'required_if:jenis,5',
        ], [
            'soal.required'             => 'Soal wajib diisi.',
            'jenis.required'            => 'Jenis jawaban wajib dipilih.',
            'jawaban.required_if'       => 'Minimal satu jawaban harus diisi.',
            'jawaban.array'             => 'Format jawaban tidak sesuai.',
            'jawaban.*.required_if'     => 'Setiap jawaban wajib diisi.',
            'jumlah_rating.required_if' => 'Jumlah rating wajib diisi.',
        ]);

        DB::beginTransaction();
        try {

            $db = [
                'soal'  => $request->soal,
                'jenis' => $request->jenis,
            ];

            $soal = SoalSupervisiAkademik::create($db);

            if ($request->jenis == 1 || $request->jenis == 2 || $request->jenis == 4) {
                foreach ($request->jawaban as $jawaban) {
                    DetailJawabanSA::create([
                        'id_soal' => $soal->id,
                        'jawaban' => $jawaban,
                    ]);
                }
            }
            if ($request->jenis == 5) {
                DetailJawabanSA::create([
                    'id_soal' => $soal->id,
                    'jawaban' => $request->jumlah_rating,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Soal berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing Soal Supervisi Akademik: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan Soal.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = SoalSupervisiAkademik::findOrFail($id);

        $request->validate([
            'soal'          => 'required',
            'jenis'         => 'required',
            'jawaban'       => 'required_if:jenis,1,2,4|array',
            'jawaban.*'     => 'required_if:jenis,1,2,4',
            'jumlah_rating' => 'required_if:jenis,5',
        ], [
            'soal.required'             => 'Soal wajib diisi.',
            'jenis.required'            => 'Jenis jawaban wajib dipilih.',
            'jawaban.required_if'       => 'Minimal satu jawaban harus diisi.',
            'jawaban.array'             => 'Format jawaban tidak sesuai.',
            'jawaban.*.required_if'     => 'Setiap jawaban wajib diisi.',
            'jumlah_rating.required_if' => 'Jumlah rating wajib diisi.',
        ]);

        DB::beginTransaction();
        try {

            $db = [
                'soal'  => $request->soal,
                'jenis' => $request->jenis,
            ];

            DetailJawabanSA::where('id_soal', $data->id)->delete();

            if ($request->jenis == 1 || $request->jenis == 2 || $request->jenis == 4) {
                foreach ($request->jawaban as $jawaban) {
                    DetailJawabanSA::create([
                        'id_soal' => $data->id,
                        'jawaban' => $jawaban,
                    ]);
                }
            }
            if ($request->jenis == 5) {
                DetailJawabanSA::create([
                    'id_soal' => $data->id,
                    'jawaban' => $request->jumlah_rating,
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
            Log::error('Error editing Soal Supervisi Akademik: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan Soal.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $data = SoalSupervisiAkademik::findOrFail($id);
        DetailJawabanSA::where('id_soal', $data->id)->delete();

        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
