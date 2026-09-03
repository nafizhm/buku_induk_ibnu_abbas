<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller {
 public function index(Request $r){if($r->ajax())return DataTables::of(Kelas::query()->orderBy('tingkat')->orderBy('id_kelas'))->addIndexColumn()->addColumn('jumlah_siswa',fn($k)=>$k->siswa()->count())->addColumn('action',fn($k)=>'<a class="btn btn-sm btn-info text-white" href="'.route('kelas.detail',$k).'">Detail</a> <button class="btn btn-sm btn-primary btn-edit" data-id="'.$k->id_kelas.'">Edit</button> <button class="btn btn-sm btn-danger btn-delete" data-id="'.$k->id_kelas.'">Hapus</button>')->rawColumns(['action'])->make(true);return view('admin.master.kelas.index');}
 public function detail(Kelas $kela){$kela->load(['siswa'=>fn($q)=>$q->orderBy('nama_lengkap')]);return view('admin.master.kelas.detail',['kelas'=>$kela]);}
 public function show(Kelas $kela){return response()->json(['data'=>$kela]);}
 public function store(Request $r){Kelas::create($this->data($r));return response()->json(['message'=>'Kelas berhasil ditambahkan.']);}
 public function update(Request $r,Kelas $kela){$kela->update($this->data($r,$kela->id_kelas));return response()->json(['message'=>'Kelas berhasil diperbarui.']);}
 public function destroy(Kelas $kela){$kela->delete();return response()->json(['message'=>'Kelas berhasil dihapus.']);}
 private function data(Request $r,?int $id=null){return $r->validate(['nama_kelas'=>['required','max:50',Rule::unique('kelas','nama_kelas')->ignore($id,'id_kelas')],'tingkat'=>['required','max:80'],'status'=>['required',Rule::in(['aktif','non aktif'])],'jenis'=>['nullable',Rule::in(['banin','banat'])]]);}
}
