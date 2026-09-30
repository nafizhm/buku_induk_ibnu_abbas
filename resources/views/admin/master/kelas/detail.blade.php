@extends('admin.layout')
@section('content')
<div class="page-content"><section class="section"><div class="card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <div><h3 class="card-title mb-1">Siswa Kelas {{ $kelas->nama_kelas }}</h3><small class="text-muted">{{ $kelas->siswa->count() }} siswa</small></div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal"><i class="fas fa-plus"></i> Tambah Siswa</button>
      <a href="{{ route('kelas.index') }}" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
  </div>
  <div class="card-body">
    @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
    <div class="table-responsive"><table class="table table-bordered table-striped w-100">
      <thead><tr><th class="text-center" style="width:45px">No</th><th>Nama Lengkap</th><th>Jenis Kelamin</th><th>Kelas Sekarang</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($kelas->siswa as $siswa)
        <tr>
          <td class="text-center">{{ $loop->iteration }}</td><td>{{ $siswa->nama_lengkap }}</td><td>{{ $siswa->jenis_kelamin==='L'?'Laki-laki':'Perempuan' }}</td><td>{{ $kelas->nama_kelas }}</td><td>{{ $siswa->status_siswa ?: '-' }}</td>
          <td><div class="d-flex flex-wrap gap-1">
            <a href="{{ route('siswa.download-one',$siswa) }}" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Download</a>
            <a href="{{ route('siswa.edit',$siswa->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form method="POST" action="{{ route('kelas.siswa.destroy', [$kelas, $siswa]) }}" class="form-keluarkan-siswa" data-nama="{{ $siswa->nama_lengkap }}">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Keluarkan</button>
            </form>
          </div></td>
        </tr>
        @empty<tr><td colspan="6" class="text-center text-muted py-4">Belum ada siswa pada kelas ini.</td></tr>@endforelse
      </tbody>
    </table></div>
  </div>
</div></section></div>
<div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaTitle" aria-hidden="true">
  <div class="modal-dialog"><form method="POST" action="{{ route('kelas.siswa.store', $kelas) }}" class="modal-content">
    @csrf
    <div class="modal-header"><h5 class="modal-title" id="tambahSiswaTitle">Tambah Siswa ke {{ $kelas->nama_kelas }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
    <div class="modal-body">
      <label for="pilihSiswa" class="form-label">Pilih siswa</label>
      <select id="pilihSiswa" name="siswa_id" class="form-select" required>
        <option value="">Cari nama atau NIPD siswa</option>
        @foreach($pilihanSiswa as $pilihan)
          <option value="{{ $pilihan->id }}" @selected(old('siswa_id') == $pilihan->id)>{{ $pilihan->nama_lengkap }} (NIPD: {{ $pilihan->nipd ?: '-' }}; ID: {{ $pilihan->id }}) — {{ $pilihan->kelas?->nama_kelas ?? 'Belum memiliki kelas' }}</option>
        @endforeach
      </select>
      <p class="text-muted mt-2 mb-0">Siswa yang sudah memiliki kelas akan dipindahkan dari kelas asal ke kelas ini.</p>
      @if($pilihanSiswa->isEmpty())<p class="text-muted mt-2">Tidak ada siswa yang dapat ditambahkan.</p>@endif
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" @disabled($pilihanSiswa->isEmpty())>Tambah Siswa</button></div>
  </form></div>
</div>
@endsection
@push('scripts')
<script>
$(function () {
    $('#pilihSiswa').select2({dropdownParent: $('#tambahSiswaModal'), width: '100%', placeholder: 'Cari nama atau NIPD siswa'});
    $('.form-keluarkan-siswa').on('submit', function (event) {
        event.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Keluarkan siswa dari kelas?',
            text: form.dataset.nama + ' akan dikeluarkan dari kelas ini. Data siswa tetap tersimpan.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, keluarkan', cancelButtonText: 'Batal'
        }).then(result => { if (result.isConfirmed) form.submit(); });
    });
    @if($errors->has('siswa_id'))
    new bootstrap.Modal(document.getElementById('tambahSiswaModal')).show();
    @endif
});
</script>
@endpush
