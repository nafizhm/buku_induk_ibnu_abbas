@php
$sections = [
    'Identitas Utama' => [
        ['nipd','NIS','text','w-sm',true], ['nisn','NISN','text','w-sm'], ['nik','NIK','text','w-md'], ['no_kk','Nomor KK','text','w-md'],
        ['nama_lengkap','Nama Lengkap','text','w-lg',true], ['nama_panggilan','Nama Panggilan','text','w-md'],
        ['jenis_kelamin','Jenis Kelamin','select','w-sm',true,['L'=>'Laki-laki','P'=>'Perempuan']],
        ['tempat_lahir','Tempat Lahir','text','w-md',true], ['tanggal_lahir','Tanggal Lahir','date','w-sm',true],
        ['agama','Agama','select','w-sm',false,array_combine(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'],['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'])],
        ['kewarganegaraan','Kewarganegaraan','text','w-sm'], ['anak_ke','Anak ke-','number','w-xs'],
        ['jumlah_saudara_kandung','Jumlah Saudara Kandung','number','w-xs'], ['jumlah_saudara_tiri','Jumlah Saudara Tiri','number','w-xs'],
        ['jumlah_saudara_angkat','Jumlah Saudara Angkat','number','w-xs'],
        ['status_anak','Status Anak','select','w-sm',false,array_combine(['Kandung','Tiri','Angkat'],['Kandung','Tiri','Angkat'])],
        ['status_dalam_keluarga','Status dalam Keluarga','text','w-md'],
    ],
    'Data Sekolah' => [
        ['tahun_ajaran_masuk','Tahun Ajaran Masuk','text','w-sm'], ['tanggal_masuk_sekolah','Tanggal Masuk Sekolah','date','w-sm'],
        ['kelas_saat_masuk','Kelas Saat Masuk','text','w-sm'],
        ['kelas_id','Kelas Sekarang','select','w-sm',false,$kelas->pluck('nama_kelas','id_kelas')->all()],
        ['status_siswa','Status Siswa','select','w-sm',true,array_combine(['Aktif','Lulus','Pindah','Keluar'],['Aktif','Lulus','Pindah','Keluar'])],
    ],
    'Asal Sekolah' => [
        ['npsn_sekolah_asal','NPSN Sekolah Asal','text','w-sm'], ['no_ijazah_sebelumnya','Nomor Ijazah Sebelumnya','text','w-md'],
        ['no_skhun_sttb','Nomor SKHUN/STTB','text','w-md'],
    ],
    'Alamat Siswa' => [
        ['alamat','Alamat Lengkap','textarea','w-xl'], ['rt','RT','text','w-xs'], ['rw','RW','text','w-xs'],
        ['dusun','Dusun/Kampung','text','w-md'], ['desa_kelurahan','Desa/Kelurahan','text','w-md'], ['kecamatan','Kecamatan','text','w-md'],
        ['kabupaten_kota','Kabupaten/Kota','text','w-md'], ['provinsi','Provinsi','text','w-md'], ['kode_pos','Kode Pos','text','w-xs'],
        ['status_tempat_tinggal','Status Tempat Tinggal','select','w-md',false,array_combine(['Orang Tua','Wali','Kos','Pesantren','Lainnya'],['Orang Tua','Wali','Kos','Pesantren','Lainnya'])],
        ['jarak_sekolah','Jarak Rumah ke Sekolah (km)','number','w-xs'], ['moda_transportasi','Moda Transportasi','text','w-md'],
    ],
    'Kontak, Data Fisik & Tambahan' => [
        ['no_hp_darurat','Nomor HP Darurat','text','w-sm'],
        ['golongan_darah','Golongan Darah','select','w-xs',false,array_combine(['A','B','AB','O'],['A','B','AB','O'])],
        ['tinggi_badan','Tinggi Badan (cm)','number','w-xs'], ['berat_badan','Berat Badan (kg)','number','w-xs'], ['lingkar_kepala','Lingkar Kepala (cm)','number','w-xs'],
        ['berkebutuhan_khusus','Berkebutuhan Khusus','select','w-xs',true,['0'=>'Tidak','1'=>'Ya']],
        ['jenis_kebutuhan_khusus','Jenis Kebutuhan Khusus','text','w-lg'], ['riwayat_kesehatan','Penyakit/Riwayat Kesehatan Penting','textarea','w-xl'],
    ],
];
$parentSections = [
 'Identitas' => [
  ['nama_lengkap','Nama Lengkap','text','w-lg'],['nik','NIK','text','w-md'],['no_kk','Nomor KK','text','w-md'],
  ['tempat_lahir','Tempat Lahir','text','w-md'],['tanggal_lahir','Tanggal Lahir','date','w-sm'],
  ['agama','Agama','select','w-sm',array_combine(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'],['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'])],
  ['kewarganegaraan','Kewarganegaraan','text','w-sm'],['status_hidup','Status Hidup','select','w-sm',['Hidup'=>'Hidup','Meninggal'=>'Meninggal']],
 ],
 'Kontak' => [['no_hp','Nomor HP','text','w-sm'],['no_whatsapp','Nomor WhatsApp','text','w-sm'],['email','Email','email','w-md']],
 'Alamat' => [
  ['alamat_sama_dengan_siswa','Alamat Sama dengan Siswa','select','w-xs',['0'=>'Tidak','1'=>'Ya']],['alamat','Alamat Lengkap','textarea','w-xl'],
  ['rt','RT','text','w-xs'],['rw','RW','text','w-xs'],['desa_kelurahan','Kelurahan/Desa','text','w-md'],['kecamatan','Kecamatan','text','w-md'],
  ['kabupaten_kota','Kabupaten/Kota','text','w-md'],['provinsi','Provinsi','text','w-md'],['kode_pos','Kode Pos','text','w-xs'],
 ],
 'Pendidikan & Pekerjaan' => [
  ['pendidikan_terakhir','Pendidikan Terakhir','text','w-md'],['pekerjaan','Pekerjaan','text','w-md'],['nama_instansi','Nama Instansi/Tempat Kerja','text','w-lg'],
  ['jabatan','Jabatan','text','w-md'],['penghasilan','Penghasilan per Bulan','select','w-md',[
   'Tidak berpenghasilan'=>'Tidak berpenghasilan','< Rp1 juta'=>'< Rp1 juta','Rp1–3 juta'=>'Rp1–3 juta','Rp3–5 juta'=>'Rp3–5 juta','Rp5–10 juta'=>'Rp5–10 juta','> Rp10 juta'=>'> Rp10 juta']],
 ],
];
$waliFields = [
 ['sumber_wali','Wali Siswa','select','w-sm',['Ayah'=>'Ayah','Ibu'=>'Ibu','Orang lain'=>'Orang lain']],['nama_lengkap','Nama Wali','text','w-lg'],
 ['nik','NIK Wali','text','w-md'],['hubungan_dengan_siswa','Hubungan dengan Siswa','text','w-md'],['tempat_lahir','Tempat Lahir','text','w-md'],
 ['tanggal_lahir','Tanggal Lahir','date','w-sm'],['no_hp','Nomor HP','text','w-sm'],['no_whatsapp','Nomor WhatsApp','text','w-sm'],
 ['alamat','Alamat','textarea','w-xl'],['pendidikan_terakhir','Pendidikan','text','w-md'],['pekerjaan','Pekerjaan','text','w-md'],
 ['penghasilan','Penghasilan','select','w-md',['Tidak berpenghasilan'=>'Tidak berpenghasilan','< Rp1 juta'=>'< Rp1 juta','Rp1–3 juta'=>'Rp1–3 juta','Rp3–5 juta'=>'Rp3–5 juta','Rp5–10 juta'=>'Rp5–10 juta','> Rp10 juta'=>'> Rp10 juta']],
];
$documents=['foto_siswa'=>'Foto Siswa','kartu_keluarga'=>'Scan Kartu Keluarga','akta_kelahiran'=>'Scan Akta Kelahiran','ktp_ayah'=>'Scan KTP Ayah','ktp_ibu'=>'Scan KTP Ibu','ktp_wali'=>'Scan KTP Wali','ijazah_sebelumnya'=>'Scan Ijazah Sekolah Sebelumnya','rapor_sebelumnya'=>'Scan Rapor Sekolah Sebelumnya','surat_pindah'=>'Scan Surat Pindah'];
@endphp

<style>
.student-form .field-row{display:grid;grid-template-columns:250px minmax(0,1fr);gap:18px;align-items:start;padding:8px 0;border-bottom:1px solid #f0f0f0}
.student-form .field-label{padding-top:8px;margin:0;font-weight:600;color:#495057}.student-form .form-control{width:100%}
.student-form .w-xs{max-width:110px}.student-form .w-sm{max-width:220px}.student-form .w-md{max-width:380px}.student-form .w-lg{max-width:580px}.student-form .w-xl{max-width:760px}
.student-form .section-title{font-size:1.05rem;color:#435ebe;border-bottom:2px solid #435ebe;padding-bottom:8px;margin:26px 0 4px}
@media(max-width:767px){.student-form .field-row{grid-template-columns:1fr;gap:4px}.student-form .field-label{padding-top:0}.student-form .w-xs,.student-form .w-sm,.student-form .w-md,.student-form .w-lg,.student-form .w-xl{max-width:100%}}
</style>

<div class="page-content"><section class="section"><div class="card student-form">
<div class="card-header"><h3 class="card-title mb-0">{{ $title }}</h3></div>
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">@csrf @if($method === 'PUT') @method('PUT') @endif
<div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><strong>Data belum dapat disimpan.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <ul class="nav nav-tabs mb-3" role="tablist">
        @foreach(['siswa'=>'Data Siswa','ayah'=>'Data Ayah','ibu'=>'Data Ibu','wali'=>'Data Wali','lampiran'=>'Lampiran'] as $tab=>$text)
        <li class="nav-item" role="presentation"><button type="button" class="nav-link {{ $loop->first?'active':'' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $tab }}" role="tab" aria-controls="tab-{{ $tab }}" aria-selected="{{ $loop->first?'true':'false' }}">{{ $text }}</button></li>
        @endforeach
    </ul>
    <div class="tab-content"><div class="tab-pane fade show active" id="tab-siswa" role="tabpanel">
    @foreach($sections as $section => $fields)
        <h4 class="section-title">{{ $section }}</h4>
        @foreach($fields as $field)
            @php
                [$name,$label,$type,$width] = array_slice($field,0,4); $required=$field[4]??false; $options=$field[5]??[];
                $defaults=['kewarganegaraan'=>'Indonesia','status_siswa'=>'Aktif','berkebutuhan_khusus'=>'0'];
                $value=old($name, data_get($siswa,$name, $defaults[$name]??null));
                if(!$siswa->exists && ($value===null || $value==='')) $value=$defaults[$name]??$value;
                if($value instanceof \Carbon\CarbonInterface) $value=$value->format('Y-m-d');
            @endphp
            <div class="field-row">
                <label for="{{ $name }}" class="field-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
                <div class="{{ $width }}">
                    @if($type === 'select')
                        <select id="{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" @required($required)>
                            <option value="">Pilih</option>@foreach($options as $key=>$text)<option value="{{ $key }}" @selected((string)$value===(string)$key)>{{ $text }}</option>@endforeach
                        </select>
                    @elseif($type === 'textarea')
                        <textarea id="{{ $name }}" name="{{ $name }}" rows="3" class="form-control @error($name) is-invalid @enderror">{{ $value }}</textarea>
                    @else
                        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" class="form-control @error($name) is-invalid @enderror"
                            @if($type==='number') min="0" step="{{ in_array($name,['jarak_sekolah','tinggi_badan','berat_badan','lingkar_kepala'])?'0.01':'1' }}" @endif @required($required)>
                    @endif
                    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        @endforeach
    @endforeach
    </div>

    @foreach(['ayah'=>'Data Ayah','ibu'=>'Data Ibu'] as $parentKey=>$parentTitle)
    @php $parent=data_get($siswa,$parentKey); @endphp
    <div class="tab-pane fade" id="tab-{{ $parentKey }}" role="tabpanel">
      @foreach($parentSections as $section=>$fields)<h4 class="section-title">{{ $section }}</h4>
        @if($parentKey==='ayah' && $section==='Identitas')
        <div class="field-row"><label class="field-label">Status Hubungan dengan Siswa</label><div class="w-md"><input name="ayah[hubungan_dengan_siswa]" class="form-control" value="{{ old('ayah.hubungan_dengan_siswa',data_get($parent,'hubungan_dengan_siswa','Ayah Kandung')) }}"></div></div>
        @endif
        @foreach($fields as $field) @php [$name,$label,$type,$width]=array_slice($field,0,4);$options=$field[4]??[];$value=old($parentKey.'.'.$name,data_get($parent,$name));if($value instanceof \Carbon\CarbonInterface)$value=$value->format('Y-m-d'); @endphp
        <div class="field-row"><label class="field-label" for="{{ $parentKey }}-{{ $name }}">{{ $label }}</label><div class="{{ $width }}">
          @if($type==='select')<select id="{{ $parentKey }}-{{ $name }}" name="{{ $parentKey }}[{{ $name }}]" class="form-control parent-field" data-parent="{{ $parentKey }}" data-field="{{ $name }}"><option value="">Pilih</option>@foreach($options as $key=>$text)<option value="{{ $key }}" @selected((string)$value===(string)$key)>{{ $text }}</option>@endforeach</select>
          @elseif($type==='textarea')<textarea id="{{ $parentKey }}-{{ $name }}" name="{{ $parentKey }}[{{ $name }}]" rows="3" class="form-control parent-field" data-parent="{{ $parentKey }}" data-field="{{ $name }}">{{ $value }}</textarea>
          @else<input id="{{ $parentKey }}-{{ $name }}" name="{{ $parentKey }}[{{ $name }}]" type="{{ $type }}" value="{{ $value }}" class="form-control parent-field" data-parent="{{ $parentKey }}" data-field="{{ $name }}">@endif
          @error($parentKey.'.'.$name)<div class="text-danger small">{{ $message }}</div>@enderror
        </div></div>@endforeach
      @endforeach
    </div>@endforeach

    @php $wali=data_get($siswa,'wali'); @endphp
    <div class="tab-pane fade" id="tab-wali" role="tabpanel"><div class="alert alert-info">Data wali bersifat opsional. Pilih Ayah atau Ibu untuk menyalin datanya tanpa mengetik ulang.</div>
      <h4 class="section-title">Data Wali</h4>@foreach($waliFields as $field) @php [$name,$label,$type,$width]=array_slice($field,0,4);$options=$field[4]??[];$value=old('wali.'.$name,data_get($wali,$name));if($value instanceof \Carbon\CarbonInterface)$value=$value->format('Y-m-d'); @endphp
      <div class="field-row"><label class="field-label" for="wali-{{ $name }}">{{ $label }}</label><div class="{{ $width }}">
       @if($type==='select')<select id="wali-{{ $name }}" name="wali[{{ $name }}]" class="form-control wali-field"><option value="">Pilih</option>@foreach($options as $key=>$text)<option value="{{ $key }}" @selected((string)$value===(string)$key)>{{ $text }}</option>@endforeach</select>
       @elseif($type==='textarea')<textarea id="wali-{{ $name }}" name="wali[{{ $name }}]" rows="3" class="form-control wali-field">{{ $value }}</textarea>
       @else<input id="wali-{{ $name }}" name="wali[{{ $name }}]" type="{{ $type }}" value="{{ $value }}" class="form-control wali-field">@endif
       @error('wali.'.$name)<div class="text-danger small">{{ $message }}</div>@enderror
      </div></div>@endforeach
    </div>

    <div class="tab-pane fade" id="tab-lampiran" role="tabpanel"><div class="alert alert-info">Format: JPG, PNG, atau PDF. Maksimal 5 MB per dokumen.</div><h4 class="section-title">Upload Lampiran</h4>
      @foreach($documents as $key=>$label) @php $existing=$siswa->exists?$siswa->lampiran->firstWhere('jenis_dokumen',$key):null; @endphp
      <div class="field-row"><label class="field-label" for="lampiran-{{ $key }}">{{ $label }}</label><div class="w-lg"><input id="lampiran-{{ $key }}" type="file" name="lampiran[{{ $key }}]" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
       @if($existing)<small class="text-success">Tersimpan: {{ $existing->nama_asli }}</small>@endif @error('lampiran.'.$key)<div class="text-danger small">{{ $message }}</div>@enderror
      </div></div>@endforeach
    </div></div>
</div>
<div class="card-footer d-flex justify-content-end"><a href="{{ route('siswa.index') }}" class="btn btn-light mr-2">Batal</a><button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button></div>
</form></div></section></div>

@push('scripts')
<script>$(function(){
function toggleSpecial(){const active=$('#berkebutuhan_khusus').val()==='1';$('#jenis_kebutuhan_khusus').prop('disabled',!active);if(!active)$('#jenis_kebutuhan_khusus').val('');}$('#berkebutuhan_khusus').change(toggleSpecial);toggleSpecial();
const addressFields=['alamat','rt','rw','desa_kelurahan','kecamatan','kabupaten_kota','provinsi','kode_pos'];
function copyStudentAddress(parent){const same=$('#'+parent+'-alamat_sama_dengan_siswa').val()==='1';addressFields.forEach(f=>{const source=$('[name="'+f+'"]');const target=$('#'+parent+'-'+f);target.prop('readonly',same);if(same)target.val(source.val());});}
['ayah','ibu'].forEach(p=>{$('#'+p+'-alamat_sama_dengan_siswa').change(()=>copyStudentAddress(p));copyStudentAddress(p);});
$('#wali-sumber_wali').change(function(){const source=$(this).val();const fields=['nama_lengkap','nik','tempat_lahir','tanggal_lahir','no_hp','no_whatsapp','alamat','pendidikan_terakhir','pekerjaan','penghasilan'];if(source==='Ayah'||source==='Ibu'){const p=source.toLowerCase();fields.forEach(f=>$('#wali-'+f).val($('#'+p+'-'+f).val()).prop('readonly',true));$('#wali-hubungan_dengan_siswa').val(source).prop('readonly',true);}else{$('.wali-field').not(this).prop('readonly',false);}});
@if($errors->any())
const errorTab='{{ collect($errors->keys())->contains(fn($key)=>str_starts_with($key,'ayah.')) ? 'ayah' : (collect($errors->keys())->contains(fn($key)=>str_starts_with($key,'ibu.')) ? 'ibu' : (collect($errors->keys())->contains(fn($key)=>str_starts_with($key,'wali.')) ? 'wali' : (collect($errors->keys())->contains(fn($key)=>str_starts_with($key,'lampiran.')) ? 'lampiran' : 'siswa'))) }}';
const trigger=document.querySelector('[data-bs-target="#tab-'+errorTab+'"]');if(trigger&&window.bootstrap)new bootstrap.Tab(trigger).show();
@endif
});</script>
@endpush
