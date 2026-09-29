@extends('admin.layout')
@section('content')
<div class="page-heading"><h3>Detail Template</h3></div>
<div class="card"><div class="card-body">
    <h4>{{ $template->nama }}</h4>
    <p class="text-muted">{{ $template->penggunaan === 'formulir_awal' ? 'Formulir awal SPMB (SD & SMP)' : 'Disimpan untuk penggunaan lain' }}</p>
    <div class="border rounded p-3 mb-3" style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $template->isi }}</div>
    @if($permissions->edit)<a href="{{ route('admin.template.edit', $template) }}" class="btn btn-primary">Edit</a>@endif
    <a href="{{ route('admin.template.index') }}" class="btn btn-secondary">Kembali</a>
</div></div>
@endsection
