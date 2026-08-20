@extends('admin.layout')

@section('content')
<div class="page-content"><section class="section"><div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Data Anak / Siswa</h3>
        <div class="d-flex gap-2"><button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#downloadModal">Download</button><a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm">Tambah Siswa</a></div>
    </div>
    <div class="card-body"><div class="table-responsive"><table id="table" class="table table-bordered w-100">
        <thead><tr><th>No</th><th>NISN</th><th>Nama Lengkap</th><th>Jenis Kelamin</th><th>Kelas Sekarang</th><th>Status</th><th>Aksi</th></tr></thead>
    </table></div></div>
</div></section></div>
<div class="modal fade" id="downloadModal" tabindex="-1"><div class="modal-dialog"><form action="{{ route('siswa.download') }}" method="POST" class="modal-content">@csrf<div class="modal-header bg-success"><h5 class="modal-title text-white">Download Data Siswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><label class="form-label">Pilih Kelas</label><select name="kelas_id" class="form-control" required><option value="">Pilih kelas</option>@foreach($kelas as $item)<option value="{{ $item->id_kelas }}">{{ $item->nama_kelas }}</option>@endforeach</select><small class="text-muted">Data siswa dari kelas terpilih akan diunduh dalam format Excel.</small></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-success">Download Excel</button></div></form></div></div>
@endsection

@push('scripts')
<script>
$(function(){
    const table=$('#table').DataTable({processing:true,serverSide:true,ajax:'{{ route('siswa.index') }}',columns:[
        {data:'DT_RowIndex',orderable:false,searchable:false},{data:'nisn'},{data:'nama_lengkap'},
        {data:'jenis_kelamin'},{data:'kelas_sekarang',name:'kelas.nama_kelas',orderable:false},{data:'status_siswa'},{data:'action',orderable:false,searchable:false}
    ]});
    $(document).on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus data siswa?',text:'Data akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(r=>{if(r.isConfirmed)$.ajax({url:'{{ route('siswa.destroy', ':id') }}'.replace(':id',id),type:'DELETE',data:{_token:'{{ csrf_token() }}'},success:function(res){table.ajax.reload(null,false);toastr.success(res.message);}});});});
});
</script>
@endpush
