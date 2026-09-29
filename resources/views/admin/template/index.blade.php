@extends('admin.layout')
@section('content')
<div class="page-heading"><h3>Template</h3></div>
<div class="card"><div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <p class="text-muted mb-0">Kelola isi pesan WhatsApp untuk pendaftar.</p>
        @if($permissions->tambah)<a href="{{ route('admin.template.create') }}" class="btn btn-primary">Tambah Template</a>@endif
    </div>
    <div class="table-responsive"><table class="table table-bordered align-middle">
        <thead><tr><th>Nama Template</th><th>Penggunaan</th><th>Isi Pesan</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($templates as $template)
            <tr>
                <td>{{ $template->nama }}</td>
                <td>{{ $template->penggunaan === 'formulir_awal' ? 'Formulir awal SPMB (SD & SMP)' : 'Disimpan untuk penggunaan lain' }}</td>
                <td style="max-width:400px;white-space:pre-wrap;overflow-wrap:anywhere">{{ \Illuminate\Support\Str::limit($template->isi, 150) }}</td>
                <td><div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-sm btn-info" href="{{ route('admin.template.show', $template) }}">Lihat</a>
                    @if($permissions->edit)<a class="btn btn-sm btn-primary" href="{{ route('admin.template.edit', $template) }}">Edit</a>@endif
                    @if($permissions->hapus)
                        <form method="POST" action="{{ route('admin.template.destroy', $template) }}" onsubmit="return confirm('Hapus template ini? Jika digunakan untuk formulir awal, Kirim WA tidak tersedia sampai template pengganti dibuat.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    @endif
                </div></td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Belum ada template.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    {{ $templates->links() }}
</div></div>
@endsection
