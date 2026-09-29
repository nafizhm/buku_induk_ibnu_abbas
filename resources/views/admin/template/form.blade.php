@extends('admin.layout')
@section('content')
<div class="page-heading"><h3>{{ $template->exists ? 'Edit Template' : 'Tambah Template' }}</h3></div>
<div class="card"><div class="card-body">
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ $template->exists ? route('admin.template.update', $template) : route('admin.template.store') }}">
        @csrf
        @if($template->exists) @method('PUT') @endif
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Template</label>
            <input id="nama" name="nama" class="form-control" value="{{ old('nama', $template->nama) }}" required maxlength="255">
        </div>
        <div class="mb-3">
            <label for="penggunaan" class="form-label">Penggunaan</label>
            <select id="penggunaan" name="penggunaan" class="form-select">
                <option value="" @selected(old('penggunaan', $template->penggunaan) === null || old('penggunaan', $template->penggunaan) === '')>Disimpan untuk penggunaan lain</option>
                <option value="formulir_awal" @selected(old('penggunaan', $template->penggunaan) === 'formulir_awal')>Formulir awal SPMB (SD & SMP)</option>
            </select>
            <div class="form-text">Satu template formulir awal digunakan saat admin menekan Kirim WA pada menu Formulir.</div>
        </div>
        <div class="mb-3">
            <label for="isi" class="form-label">Isi Pesan</label>
            <textarea id="isi" name="isi" class="form-control" rows="12" required maxlength="10000" aria-describedby="kode-template">{{ old('isi', $template->isi) }}</textarea>
            <div class="form-text" id="kode-template">
                <code>[[link]]</code>: tautan pengisian formulir (wajib untuk formulir awal).<br>
                <code>[[nama]]</code>: nama calon siswa, <code>[[ortu]]</code>: nama orang tua/wali, <code>[[jenjang]]</code>: SD atau SMP.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.template.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection
