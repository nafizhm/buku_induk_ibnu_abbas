@extends('admin.layout')
@section('content')
<div class="page-content"><section class="section"><div class="card">
  <div class="card-header d-flex justify-content-between align-items-center"><div><h3 class="card-title mb-1">Siswa Kelas {{ $kelas->nama_kelas }}</h3><small class="text-muted">{{ $kelas->siswa->count() }} siswa</small></div><a href="{{ route('kelas.index') }}" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div>
  <div class="card-body"><div class="table-responsive"><table class="table table-bordered table-striped w-100"><thead><tr><th class="text-center" style="width:45px">No</th><th>Nama Lengkap</th><th>Jenis Kelamin</th><th>Kelas Sekarang</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($kelas->siswa as $siswa)<tr><td class="text-center">{{ $loop->iteration }}</td><td>{{ $siswa->nama_lengkap }}</td><td>{{ $siswa->jenis_kelamin==='L'?'Laki-laki':'Perempuan' }}</td><td>{{ $kelas->nama_kelas }}</td><td>{{ $siswa->status_siswa ?: '-' }}</td><td><div class="d-flex gap-1"><a href="{{ route('siswa.download-one',$siswa) }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Download</a><a href="{{ route('siswa.edit',$siswa->id) }}" class="btn btn-sm btn-primary">Edit</a></div></td></tr>@empty<tr><td colspan="6" class="text-center text-muted py-4">Belum ada siswa pada kelas ini.</td></tr>@endforelse
  </tbody></table></div></div>
</div></section></div>
@endsection
