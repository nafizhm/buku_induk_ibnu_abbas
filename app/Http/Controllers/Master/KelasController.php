<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller {
 public function index(Request $r){
  if($r->ajax()){
   $query=Kelas::query()->withCount(['siswa','siswa as siswa_sudah_isi'=>fn($q)=>$q->whereHas('orangTua',fn($t)=>$t->where(fn($d)=>$d->where('nama_ayah','<>','')->orWhere('nama_ibu','<>','')))])->orderBy('tingkat')->orderBy('id_kelas');
   return DataTables::of($query)->skipPaging()->addIndexColumn()
    ->addColumn('jumlah_siswa',fn($k)=>$k->siswa_count)
    ->addColumn('status_data',fn($k)=>$this->statusData($k))
    ->addColumn('action',fn($k)=>'<a class="btn btn-sm btn-info text-white" href="'.route('kelas.detail',$k).'">Detail</a> <button class="btn btn-sm btn-primary btn-edit" data-id="'.$k->id_kelas.'">Edit</button> <button class="btn btn-sm btn-danger btn-delete" data-id="'.$k->id_kelas.'">Hapus</button>')
    ->rawColumns(['status_data','action'])->make(true);
  }
  return view('admin.master.kelas.index');
 }
 public function detail(Kelas $kela){
  $kela->load(['siswa'=>fn($q)=>$q->orderBy('nama_lengkap')]);
  $pilihanSiswa=Siswa::with('kelas')->where(fn($q)=>$q->whereNull('kelas_id')->orWhere('kelas_id','<>',$kela->id_kelas))->orderBy('nama_lengkap')->get();
  return view('admin.master.kelas.detail',['kelas'=>$kela,'pilihanSiswa'=>$pilihanSiswa]);
 }
 public function tambahSiswa(Request $request,Kelas $kela){
  $data=$request->validate(['siswa_id'=>['required','integer',Rule::exists('siswa','id')]]);
  Siswa::findOrFail($data['siswa_id'])->update(['kelas_id'=>$kela->id_kelas]);
  return redirect()->route('kelas.detail',$kela)->with('success','Siswa berhasil ditambahkan ke kelas.');
 }
 public function keluarkanSiswa(Kelas $kela,Siswa $siswa){
  abort_unless($kela->siswa()->whereKey($siswa->id)->update(['kelas_id'=>null]),404);
  return redirect()->route('kelas.detail',$kela)->with('success','Siswa berhasil dikeluarkan dari kelas. Data siswa tetap tersimpan.');
 }
 public function show(Kelas $kela){return response()->json(['data'=>$kela]);}
 public function store(Request $r){Kelas::create($this->data($r));return response()->json(['message'=>'Kelas berhasil ditambahkan.']);}
 public function update(Request $r,Kelas $kela){$kela->update($this->data($r,$kela->id_kelas));return response()->json(['message'=>'Kelas berhasil diperbarui.']);}
 public function destroy(Kelas $kela){$kela->delete();return response()->json(['message'=>'Kelas berhasil dihapus.']);}
 private function data(Request $r,?int $id=null){return $r->validate(['nama_kelas'=>['required','max:50',Rule::unique('kelas','nama_kelas')->ignore($id,'id_kelas')],'tingkat'=>['required','max:80'],'status'=>['required',Rule::in(['aktif','non aktif'])],'jenis'=>['nullable',Rule::in(['banin','banat'])]]);}

 /**
  * Rekap keterisian data untuk kolom "Status Data".
  * Siswa dianggap sudah mengisi data bila baris orang tua sudah ada
  * dan nama ayah atau nama ibu terisi (lihat withCount siswa_sudah_isi).
  */
 private function statusData(Kelas $kelas): string
 {
  $total=(int)$kelas->siswa_count;
  if($total===0)return '<span class="badge bg-secondary">Belum ada siswa</span>';
  $terisi=(int)$kelas->siswa_sudah_isi;
  $badge=$terisi===$total?'bg-success':($terisi===0?'bg-danger':'bg-warning text-dark');
  $judul=$terisi.' dari '.$total.' siswa sudah mengisi data';
  return '<span class="badge '.$badge.'" title="'.$judul.'">'.$terisi.' / '.$total.'</span> <small class="text-muted">sudah mengisi</small>';
 }
}
