<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('kelas',function(Blueprint $t){$t->increments('id_kelas');$t->string('nama_kelas',50);$t->string('tingkat',80)->default('');$t->string('wali_kelas',100)->default('');$t->string('jadwal',120)->default('');$t->enum('status',['aktif','non aktif'])->default('aktif');$t->enum('jenis',['banin','banat'])->nullable();$t->timestamps();});
  $rows=[]; for($i=1;$i<=6;$i++){foreach(['BANIN'=>'banin','BANAT'=>'banat'] as $label=>$jenis)$rows[]=['nama_kelas'=>"$i $label",'tingkat'=>'SD','jenis'=>$jenis,'status'=>'aktif'];}
  foreach([7,8,9] as $i)$rows[]=['nama_kelas'=>"KELAS $i",'tingkat'=>'SMP','jenis'=>null,'status'=>'aktif']; DB::table('kelas')->insert($rows);
  Schema::table('siswa',function(Blueprint $t){$t->unsignedInteger('kelas_id')->nullable()->after('kelas_saat_masuk');$t->foreign('kelas_id')->references('id_kelas')->on('kelas')->nullOnDelete();});
  if(Schema::hasTable('menu')){$master=DB::table('menu')->where('title','Master')->value('id');if($master){$id=DB::table('menu')->insertGetId(['id_parent'=>$master,'title'=>'Kelas','route_name'=>'kelas.index','icon'=>'bi bi-building','urutan'=>4,'lihat'=>1,'tambah'=>1,'edit'=>1,'hapus'=>1]);if(Schema::hasTable('hak_akses'))foreach(DB::table('users')->pluck('id') as $userId)DB::table('hak_akses')->insert(['id_user'=>$userId,'id_menu'=>$id,'lihat'=>1,'beranda'=>0,'tambah'=>1,'edit'=>1,'hapus'=>1]);}}
 }
 public function down(): void {if(Schema::hasTable('menu')){$ids=DB::table('menu')->where('route_name','kelas.index')->pluck('id');DB::table('hak_akses')->whereIn('id_menu',$ids)->delete();DB::table('menu')->whereIn('id',$ids)->delete();}Schema::table('siswa',function(Blueprint $t){$t->dropForeign(['kelas_id']);$t->dropColumn('kelas_id');});Schema::dropIfExists('kelas');}
};
