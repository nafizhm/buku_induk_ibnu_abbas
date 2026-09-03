@extends('admin.layout')

@section('content')
<div class="page-content"><section class="section"><div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div><small class="text-uppercase text-muted">Agenda</small><h3 class="card-title mb-0">Data Kegiatan</h3></div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kegiatanModal" data-mode="create"><i class="fas fa-plus"></i> Tambah Kegiatan</button>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <div class="table-responsive"><table id="kegiatan-table" class="table table-bordered table-striped w-100">
            <thead><tr><th>No</th><th>Tgl Kegiatan</th><th>Nama Kegiatan</th><th>Jumlah Undangan</th><th>Hadir Ayah</th><th>Hadir Ibu</th><th>Hadir Keduanya</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>@foreach($kegiatan as $item)<tr>
                <td>{{ $loop->iteration }}</td><td>{{ $item->tgl_kegiatan->format('d-m-Y') }}</td><td>{{ $item->nama_kegiatan }}</td>
                <td>{{ $item->jumlah_undangan }}</td><td>{{ $item->jumlah_hadir_ayah }}</td><td>{{ $item->jumlah_hadir_ibu }}</td><td>{{ $item->jumlah_hadir_keduanya }}</td>
                <td><span class="badge {{ $item->status === 'aktif' ? 'bg-success' : 'bg-warning' }}">{{ $item->status }}</span></td>
                <td><div class="d-flex gap-1">
                    <a href="{{ route('kegiatan.show', $item) }}" class="btn btn-sm btn-info text-white">Detail</a>
                    <button class="btn btn-sm btn-primary btn-edit" data-bs-toggle="modal" data-bs-target="#kegiatanModal" data-mode="edit" data-id="{{ $item->id }}" data-tanggal="{{ $item->tgl_kegiatan->format('Y-m-d') }}" data-nama="{{ $item->nama_kegiatan }}" data-zona="{{ $item->zona_waktu }}" data-status="{{ $item->status }}">Edit</button>
                    <form action="{{ route('kegiatan.destroy', $item) }}" method="POST" class="form-delete">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
                </div></td>
            </tr>@endforeach</tbody>
        </table></div>
    </div>
</div></section></div>

<div class="modal fade" id="kegiatanModal" tabindex="-1"><div class="modal-dialog"><form id="kegiatanForm" method="POST" class="modal-content">
    @csrf <input type="hidden" name="_method" id="form-method" value="POST">
    <div class="modal-header"><h5 class="modal-title">Tambah Kegiatan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Tgl Kegiatan</label><input type="date" name="tgl_kegiatan" id="form-tanggal" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Nama Kegiatan</label><input type="text" name="nama_kegiatan" id="form-nama" class="form-control" maxlength="150" required></div>
        <div class="mb-3"><label class="form-label">Zona Waktu</label><select name="zona_waktu" id="form-zona" class="form-control" required><option>WIB</option><option>WITA</option><option>WIT</option></select></div>
        <div><label class="form-label">Status</label><select name="status" id="form-status" class="form-control" required><option value="aktif">Aktif</option><option value="non aktif">Non Aktif</option></select></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div>
</form></div></div>
@endsection

@push('scripts')
<script>$(function(){
$('#kegiatan-table').DataTable();
$('#kegiatanModal').on('show.bs.modal',function(e){const b=$(e.relatedTarget),edit=b.data('mode')==='edit',id=b.data('id');$('.modal-title',this).text(edit?'Edit Kegiatan':'Tambah Kegiatan');$('#kegiatanForm').attr('action',edit?'{{ url('admin/kegiatan') }}/'+id:'{{ route('kegiatan.store') }}');$('#form-method').val(edit?'PUT':'POST');$('#form-tanggal').val(edit?b.data('tanggal'):'');$('#form-nama').val(edit?b.data('nama'):'');$('#form-zona').val(edit?b.data('zona'):'WIB');$('#form-status').val(edit?b.data('status'):'aktif');});
$('.form-delete').on('submit',function(e){e.preventDefault();const form=this;Swal.fire({title:'Hapus Kegiatan',text:'Data kegiatan ini akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(r=>{if(r.isConfirmed)form.submit();});});
@if($errors->any()) new bootstrap.Modal(document.getElementById('kegiatanModal')).show(); @endif
});</script>
@endpush
