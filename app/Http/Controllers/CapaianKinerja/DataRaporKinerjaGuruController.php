<?php
namespace App\Http\Controllers\CapaianKinerja;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\CapaianRKG;
use App\Models\DetailCapaianRKG;
use App\Models\SoalRaporKinerjaGuru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DataRaporKinerjaGuruController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $permissions = HakAksesController::getUserPermissions();
        $auth        = Auth::user();

        if ($request->ajax()) {
            $data = CapaianRKG::with('guru')->orderBy('id', 'desc');

            if ($auth->id_role == 2) {
                $data->where('id_guru', $auth->id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->translatedFormat('j F Y');
                })
                ->addColumn('guru', function ($row) {
                    return $row->guru ? $row->guru->nama : '-';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('capaian-rapor-kinerja-guru.show', $row->id);
                    $deleteUrl = route('capaian-rapor-kinerja-guru.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    $btn .= '<a href="' . e($editUrl) . '" class="btn btn-primary btn-sm mx-1">Hasil</a>';

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

        return view('admin.capaian_kinerja.rapor_kinerja_guru.index', compact('permissions', 'auth'));
    }

    public function create()
    {
        $soals = SoalRaporKinerjaGuru::with('detailJawaban')->get();

        return view('admin.capaian_kinerja.rapor_kinerja_guru.create', compact('soals'));
    }
    public function show($id)
    {
        $soals = SoalRaporKinerjaGuru::with('detailJawaban')->get();

        $jawaban = DetailCapaianRKG::where('id_capaian', $id)
            ->get()
            ->groupBy('id_soal');

        return view('admin.capaian_kinerja.rapor_kinerja_guru.show', compact('soals', 'jawaban'));
    }

    public function store(Request $request)
    {
        Carbon::setLocale('id');

        $soalIds = SoalRaporKinerjaGuru::pluck('id')->toArray();

        $rules = [];
        foreach ($soalIds as $id) {
            $rules["jawaban.$id"] = 'required';
        }

        $messages = [
            'required' => 'Jawaban wajib diisi.',
        ];

        $validated = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $capaian = CapaianRKG::create([
                'tanggal' => Carbon::now(),
                'id_guru' => Auth::id(),
            ]);

            foreach ($validated['jawaban'] as $idSoal => $jawaban) {
                $soal = SoalRaporKinerjaGuru::findOrFail($idSoal);

                if ($soal->jenis == 3) {
                    DetailCapaianRKG::create([
                        'id_capaian'    => $capaian->id,
                        'id_soal'       => $idSoal,
                        'id_jawaban'    => 0,
                        'jawaban_essay' => $jawaban,
                    ]);
                } else {
                    if (is_array($jawaban)) {
                        foreach ($jawaban as $idJawaban) {
                            DetailCapaianRKG::create([
                                'id_capaian'    => $capaian->id,
                                'id_soal'       => $idSoal,
                                'id_jawaban'    => $idJawaban,
                                'jawaban_essay' => null,
                            ]);
                        }
                    } else {
                        DetailCapaianRKG::create([
                            'id_capaian'    => $capaian->id,
                            'id_soal'       => $idSoal,
                            'id_jawaban'    => $jawaban,
                            'jawaban_essay' => null,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Capaian berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing Capaian RKG: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan Capaian.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $data = CapaianRKG::findOrFail($id);
        DetailCapaianRKG::where('id_capaian', $data->id)->delete();

        $data->delete();

        return response()->json(['status' => 'success']);
    }

}
