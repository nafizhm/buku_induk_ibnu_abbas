<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\HariLiburNasional;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HariLiburNasionalController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $tahun = $request->get('tahun');

            if (! $tahun) {
                return DataTables::of(collect([]))->make(true);
            }

            $data = HariLiburNasional::whereYear('tanggal', $tahun)
                ->orderBy('tanggal', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->translatedFormat('j F Y');
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('hari-libur-nasional.edit', $row->id);
                    $deleteUrl = route('hari-libur-nasional.destroy', $row->id);

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
                ->rawColumns(['action'])
                ->make(true);
        }

        $tahun = HariLiburNasional::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.master.hari_libur_nasional.index', compact('permissions', 'tahun'));
    }

    public function edit($id)
    {
        $list = HariLiburNasional::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required',
            'dalam_rangka' => 'required',
        ], [
            'tanggal.required'      => 'Tanggal harus diisi.',
            'dalam_rangka.required' => 'Keterangan harus diisi.',
        ]);

        $db = [
            'tanggal'      => $request->tanggal,
            'dalam_rangka' => $request->dalam_rangka,
        ];

        HariLiburNasional::create($db);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = HariLiburNasional::findOrFail($id);

        $request->validate([
            'tanggal'      => 'required',
            'dalam_rangka' => 'required',
        ], [
            'tanggal.required'      => 'Tanggal harus diisi.',
            'dalam_rangka.required' => 'Keterangan harus diisi.',
        ]);

        $db = [
            'tanggal'      => $request->tanggal,
            'dalam_rangka' => $request->dalam_rangka,
        ];

        $data->update($db);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = HariLiburNasional::findOrFail($id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
