<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  if(!Schema::hasTable('menu'))return;
  $duplicateIds=DB::table('menu')->where('title','Kelas')->where('route_name','kelas.index')->pluck('id');
  if($duplicateIds->isNotEmpty()){
   if(Schema::hasTable('hak_akses'))DB::table('hak_akses')->whereIn('id_menu',$duplicateIds)->delete();
   if(Schema::hasTable('role_menu'))DB::table('role_menu')->whereIn('menu_id',$duplicateIds)->delete();
   DB::table('menu')->whereIn('id',$duplicateIds)->delete();
  }
  DB::table('menu')->where('title','Jenjang Kelas')->update(['title'=>'Data Kelas','route_name'=>'kelas.index','icon'=>'bi bi-building','lihat'=>1,'tambah'=>1,'edit'=>1,'hapus'=>1]);
 }
 public function down(): void {DB::table('menu')->where('title','Data Kelas')->where('route_name','kelas.index')->update(['title'=>'Jenjang Kelas','route_name'=>'jenjang.index']);}
};
