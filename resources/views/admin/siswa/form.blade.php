@php
  $orangTua = $siswa->orangTua;
  $documents = ['foto_siswa'=>'Foto Siswa','kartu_keluarga'=>'Kartu Keluarga','akta_kelahiran'=>'Akta Kelahiran','ktp_ayah'=>'KTP Ayah','ktp_ibu'=>'KTP Ibu','ktp_wali'=>'KTP Wali','ijazah_sebelumnya'=>'Ijazah Sekolah Sebelumnya','rapor_sebelumnya'=>'Rapor Sekolah Sebelumnya','surat_pindah'=>'Surat Pindah'];
@endphp

<style>
.student-form .dapodik-section{border:1px solid #e5e7eb;border-radius:10px;margin-bottom:18px;overflow:hidden}.student-form .dapodik-section-head{display:flex;align-items:center;gap:10px;padding:11px 16px;background:#f3f5ff;border-bottom:1px solid #e5e7eb}.student-form .dapodik-section-head span{display:grid;place-items:center;width:30px;height:30px;border-radius:50%;background:#435ebe;color:#fff;font-weight:700}.student-form .dapodik-section-head h2{font-size:1rem;margin:0;color:#25396f}.student-form .dapodik-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;padding:18px}.student-form .form-group{margin:0}.student-form .form-group.field-wide{grid-column:1/-1}.student-form .form-group label{display:block;margin-bottom:6px;font-weight:600;color:#495057}.student-form .form-group input,.student-form .form-group select,.student-form .form-group textarea{display:block;width:100%;padding:.5rem .75rem;border:1px solid #dce7f1;border-radius:.3rem;background:#fff;color:#607080}.student-form .form-group textarea{min-height:90px}.student-form .field-help{display:block;margin-top:5px;color:#6c757d}@media(max-width:767px){.student-form .dapodik-grid{grid-template-columns:1fr}.student-form .form-group.field-wide{grid-column:auto}}
</style>

<div class="page-content"><section class="section"><div class="card student-form">
<div class="card-header"><h3 class="card-title mb-0">{{ $title }}</h3></div>
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">@csrf @if($method === 'PUT') @method('PUT') @endif
<div class="card-body">
  @if($errors->any())<div class="alert alert-danger"><strong>Data belum dapat disimpan.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
  <ul class="nav nav-tabs mb-3" role="tablist">@foreach(['siswa'=>'Data Siswa','ayah'=>'Data Ayah','ibu'=>'Data Ibu','wali'=>'Data Wali','lampiran'=>'Lampiran'] as $tab=>$text)<li class="nav-item"><button type="button" class="nav-link {{ $loop->first?'active':'' }}" data-bs-toggle="tab" data-bs-target="#tab-{{ $tab }}">{{ $text }}</button></li>@endforeach</ul>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="tab-siswa">
      @include('orang-tua.partials.profil-siswa-dapodik', ['showSaveButton' => false])
      <section class="dapodik-section"><div class="dapodik-section-head"><span>08</span><h2>Administrasi Sekolah</h2></div><div class="dapodik-grid">
        <div class="form-group"><label for="kelas_id">Kelas Sekarang</label><select id="kelas_id" name="kelas_id"><option value="">Pilih Kelas</option>@foreach($kelas as $item)<option value="{{ $item->id_kelas }}" @selected((string)old('kelas_id',$siswa->kelas_id)===(string)$item->id_kelas)>{{ $item->nama_kelas }}</option>@endforeach</select></div>
        <div class="form-group"><label for="status_siswa">Status Siswa <span class="text-danger">*</span></label><select id="status_siswa" name="status_siswa" required>@foreach(['Aktif','Lulus','Pindah','Keluar'] as $status)<option value="{{ $status }}" @selected(old('status_siswa',$siswa->status_siswa ?: 'Aktif')===$status)>{{ $status }}</option>@endforeach</select></div>
      </div></section>
    </div>
    <div class="tab-pane fade" id="tab-ayah">@include('orang-tua.partials.profil-keluarga-dapodik', ['familySection'=>'ayah','showSaveButton'=>false])</div>
    <div class="tab-pane fade" id="tab-ibu">@include('orang-tua.partials.profil-keluarga-dapodik', ['familySection'=>'ibu','showSaveButton'=>false])</div>
    <div class="tab-pane fade" id="tab-wali"><div class="alert alert-info">Data wali bersifat opsional.</div>@include('orang-tua.partials.profil-keluarga-dapodik', ['familySection'=>'wali','showSaveButton'=>false])</div>
    <div class="tab-pane fade" id="tab-lampiran"><div class="alert alert-info">JPG, PNG, dan PDF dapat dilihat langsung. Maksimal 5 MB per dokumen.</div><div class="row g-3">@foreach($documents as $key=>$label) @php $existing=$siswa->exists?$siswa->lampiran->firstWhere('jenis_dokumen',$key):null; @endphp<div class="col-12"><div class="card border mb-0" data-attachment-row><div class="card-body"><div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2"><div><strong>{{ $label }}</strong>@if($existing)<div class="small text-success">Tersimpan: {{ $existing->nama_asli }}</div>@else<div class="small text-muted">Belum ada lampiran</div>@endif</div>@if($existing)<div class="d-flex gap-2"><button type="button" class="btn btn-sm btn-info text-white btn-preview-attachment" data-url="{{ route('siswa.lampiran.view',$existing) }}" data-title="{{ $label }}"><i class="fas fa-eye"></i> Lihat</button><button type="button" class="btn btn-sm btn-danger btn-delete-attachment" data-url="{{ route('siswa.lampiran.delete',$existing) }}"><i class="fas fa-trash"></i> Hapus</button></div>@endif</div><div class="input-group"><input id="lampiran-{{ $key }}" type="file" name="lampiran[{{ $key }}]" class="form-control attachment-input" accept=".jpg,.jpeg,.png,.pdf">@if($siswa->exists)<button type="button" class="btn btn-success btn-upload-attachment" data-kind="{{ $key }}"><i class="fas fa-upload"></i> {{ $existing ? 'Ganti' : 'Upload' }}</button>@endif</div></div></div></div>@endforeach</div></div>
  </div>
</div>
<div class="card-footer d-flex justify-content-end"><a href="{{ route('siswa.index') }}" class="btn btn-light me-2">Batal</a><button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Semua Data</button></div>
</form></div></section></div>

<div class="modal fade" id="attachmentPreviewModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="attachmentPreviewTitle">Lihat Lampiran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body p-0"><iframe id="attachmentPreviewFrame" title="Preview lampiran" style="display:block;width:100%;height:78vh;border:0"></iframe></div></div></div></div>

@push('scripts')
@if($siswa->exists)
<script>document.addEventListener('DOMContentLoaded',function(){
const modalElement=document.getElementById('attachmentPreviewModal'),frame=document.getElementById('attachmentPreviewFrame'),modal=new bootstrap.Modal(modalElement);
document.querySelectorAll('.btn-preview-attachment').forEach(button=>button.addEventListener('click',function(){document.getElementById('attachmentPreviewTitle').textContent=this.dataset.title;frame.src=this.dataset.url;modal.show();}));
modalElement.addEventListener('hidden.bs.modal',()=>frame.src='about:blank');
document.querySelectorAll('.btn-upload-attachment').forEach(button=>button.addEventListener('click',function(){const input=this.closest('[data-attachment-row]').querySelector('.attachment-input');if(!input.files.length){toastr.warning('Pilih file terlebih dahulu.');return;}const data=new FormData();data.append('_token',@json(csrf_token()));data.append('jenis_dokumen',this.dataset.kind);data.append('file',input.files[0]);this.disabled=true;fetch(@json(route('siswa.lampiran.upload',$siswa)),{method:'POST',body:data,headers:{Accept:'application/json'}}).then(async response=>{const payload=await response.json();if(!response.ok)throw payload;toastr.success(payload.message);setTimeout(()=>window.location.reload(),400);}).catch(error=>{this.disabled=false;toastr.error(error.message||Object.values(error.errors||{}).flat()[0]||'Lampiran gagal diunggah.');});}));
document.querySelectorAll('.btn-delete-attachment').forEach(button=>button.addEventListener('click',function(){Swal.fire({title:'Hapus lampiran?',text:'File yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonText:'Hapus',cancelButtonText:'Batal'}).then(result=>{if(!result.isConfirmed)return;fetch(this.dataset.url,{method:'DELETE',headers:{Accept:'application/json','X-CSRF-TOKEN':@json(csrf_token())}}).then(async response=>{const payload=await response.json();if(!response.ok)throw payload;toastr.success(payload.message);setTimeout(()=>window.location.reload(),400);}).catch(error=>toastr.error(error.message||'Lampiran gagal dihapus.'));});}));
});</script>
@endif
@if($errors->any())
@php
  $errorKeys = collect($errors->keys());
  $errorTab = $errorKeys->contains(fn($key) => str_starts_with($key, 'ayah.')) ? 'ayah'
    : ($errorKeys->contains(fn($key) => str_starts_with($key, 'ibu.')) ? 'ibu'
    : ($errorKeys->contains(fn($key) => str_starts_with($key, 'wali.')) ? 'wali'
    : ($errorKeys->contains(fn($key) => str_starts_with($key, 'lampiran.')) ? 'lampiran' : 'siswa')));
@endphp
<script>document.addEventListener('DOMContentLoaded',function(){const tab=@json($errorTab);const el=document.querySelector('[data-bs-target="#tab-'+tab+'"]');if(el&&window.bootstrap)new bootstrap.Tab(el).show();});</script>
@endif
@endpush
