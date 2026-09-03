@extends('admin.layout')

@section('content')
<div class="page-content"><section class="section">
<div class="card"><div class="card-header d-flex justify-content-between align-items-center"><div><small class="text-uppercase text-muted">Agenda</small><h3 class="mb-0">{{ $kegiatan->nama_kegiatan }}</h3></div><a href="{{ route('kegiatan.index') }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a></div>
<div class="card-body"><div class="row g-3">
@foreach([
 'Tgl Kegiatan'=>$kegiatan->tgl_kegiatan->format('d-m-Y'),'Jumlah Undangan'=>$kegiatan->jumlah_undangan,'Zona Waktu'=>$kegiatan->zona_waktu,
 'Jumlah Hadir'=>$kegiatan->jumlah_kehadiran,'Hadir Ayah'=>$kegiatan->jumlah_hadir_ayah,'Hadir Ibu'=>$kegiatan->jumlah_hadir_ibu,
 'Hadir Keduanya'=>$kegiatan->jumlah_hadir_keduanya,'Status'=>ucfirst($kegiatan->status)] as $label=>$value)
<div class="col-md-3"><div class="border rounded p-3 h-100"><small class="text-muted">{{ $label }}</small><div class="fw-bold mt-1">{{ $value }}</div></div></div>
@endforeach
</div></div></div>

<div class="card"><div class="card-header d-flex justify-content-between align-items-center"><div><small class="text-uppercase text-muted">Peserta</small><h3 class="card-title mb-0">Peserta Kegiatan</h3></div><div class="d-flex gap-2"><button class="btn btn-light" onclick="window.print()"><i class="fas fa-print"></i> Cetak PDF</button><a href="{{ route('kegiatan.export',$kegiatan) }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Download Excel</a></div></div>
<div class="card-body"><div class="table-responsive"><table id="peserta-table" class="table table-bordered w-100"><thead><tr><th>No</th><th>NIS</th><th>Nama Siswa</th><th>Kelas</th><th>QR Code</th><th>Jenis</th><th>Jam Ayah</th><th>Jam Ibu</th><th>Status Hadir</th></tr></thead><tbody>
@foreach($peserta as $row) @php $status=$row->jam_kehadiran_ayah&&$row->jam_kehadiran_ibu?'Keduanya':($row->jam_kehadiran_ayah?'Ayah':($row->jam_kehadiran_ibu?'Ibu':($row->jam_kehadiran?'Hadir':'-'))); @endphp
<tr><td>{{ $loop->iteration }}</td><td>{{ $row->siswa?->nipd ?? '-' }}</td><td>{{ $row->siswa?->nama_lengkap ?? 'Siswa sudah dihapus' }}</td><td>{{ $row->siswa?->kelas?->nama_kelas ?? '-' }}</td><td>{{ $row->qr_code }}</td><td>{{ $row->jenis }}</td><td>{{ $row->jam_kehadiran_ayah ?? '-' }}</td><td>{{ $row->jam_kehadiran_ibu ?? '-' }}</td><td>{{ $status }}</td></tr>
@endforeach
</tbody></table></div></div></div>
</section></div>
@endsection

@push('scripts')<script>$(function(){$('#peserta-table').DataTable();});</script>@endpush
