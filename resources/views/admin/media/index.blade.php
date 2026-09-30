@extends('admin.layout')
@section('content')
@php($label = ucfirst($jenis))
<div class="page-heading"><h3>Media / {{ $label }}</h3></div>
<div class="card"><div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <p class="text-muted">Kelola {{ $jenis }} yang tampil di landing page sesuai kategori SD atau SMP.</p>
    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <form method="GET" class="d-flex gap-2">
            <select name="kategori" class="form-select" aria-label="Filter kategori">
                <option value="">Semua kategori</option>
                @foreach(['SD', 'SMP'] as $kategori)<option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>{{ $kategori }}</option>@endforeach
            </select><button class="btn btn-outline-primary">Filter</button>
        </form>
        @if($permissions->tambah)<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#media-modal">Tambah {{ $label }}</button>@endif
    </div>
    <div class="table-responsive"><table class="table table-bordered align-middle">
        <thead><tr><th>Pratinjau</th><th>Judul / Keterangan</th><th>Kategori</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>
                    @if($jenis === 'foto')
                        <a href="{{ $item->foto_url }}" target="_blank" rel="noopener"><img src="{{ $item->foto_url }}" alt="{{ $item->judul }}" style="width:150px;height:95px;object-fit:cover;border-radius:8px" loading="lazy"></a>
                    @else
                        <a href="https://www.youtube.com/watch?v={{ $item->youtube_code }}" target="_blank" rel="noopener noreferrer"><img src="https://i.ytimg.com/vi/{{ $item->youtube_code }}/mqdefault.jpg" alt="{{ $item->judul }}" style="width:150px;height:95px;object-fit:cover;border-radius:8px" loading="lazy"><br>Tonton video</a>
                    @endif
                </td>
                <td>{{ $item->judul }}@if($jenis === 'video')<br><code>{{ $item->youtube_code }}</code>@endif</td>
                <td><span class="badge bg-primary">{{ $item->kategori }}</span></td>
                <td><div class="d-flex gap-2">
                    @if($permissions->edit)
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#media-modal"
                            data-item="{{ json_encode(['judul' => $item->judul, 'kategori' => $item->kategori, 'youtube_code' => $item->youtube_code, 'foto_url' => $jenis === 'foto' ? $item->foto_url : null, 'url' => route('admin.media.'.$jenis.'.update', $item)]) }}">Edit</button>
                    @endif
                    @if($permissions->hapus)
                        <form method="POST" action="{{ route('admin.media.'.$jenis.'.destroy', $item) }}" onsubmit="return confirm('Hapus media ini dari landing page?');">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    @endif
                </div></td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">Belum ada {{ $jenis }} untuk kategori ini.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    {{ $items->links() }}
</div></div>

<div class="modal fade" id="media-modal" tabindex="-1" aria-labelledby="media-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
        <form id="media-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.media.'.$jenis.'.store') }}">
            @csrf
            <input type="hidden" name="_method" id="media-method" value="POST">
            <div class="modal-header"><h5 class="modal-title" id="media-modal-title">Tambah {{ $label }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
            <div class="modal-body">
                <div id="media-errors" class="alert alert-danger d-none" role="alert" style="white-space:pre-line"></div>
                <div class="mb-3"><label for="media-judul" class="form-label">Judul / Keterangan</label><input id="media-judul" name="judul" class="form-control" required maxlength="150"></div>
                <div class="mb-3"><label for="media-kategori" class="form-label">Kategori</label><select id="media-kategori" name="kategori" class="form-select" required><option value="">Pilih kategori</option><option value="SD">SD</option><option value="SMP">SMP</option></select></div>
                @if($jenis === 'foto')
                    <div class="mb-3"><label for="media-foto" class="form-label">Foto</label><input id="media-foto" name="foto" type="file" class="form-control" accept="image/jpeg,image/png,image/webp"><div class="form-text">JPG, PNG, atau WebP, maksimal 5 MB. Saat edit, kosongkan untuk mempertahankan foto.</div></div>
                    <img id="media-preview" alt="Pratinjau foto" class="d-none img-fluid rounded" style="max-height:280px">
                @else
                    <div class="mb-3"><label for="media-code" class="form-label">Kode video YouTube</label><input id="media-code" name="youtube_code" class="form-control" required minlength="11" maxlength="11" pattern="[A-Za-z0-9_-]{11}" placeholder="5NSZyhx1s-w"><div class="form-text">Masukkan kode 11 karakter setelah v= atau youtu.be/, contoh: 5NSZyhx1s-w.</div></div>
                @endif
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button id="media-save" class="btn btn-primary" type="submit">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('media-modal');
    const form = document.getElementById('media-form');
    const errors = document.getElementById('media-errors');
    const save = document.getElementById('media-save');
    const file = document.getElementById('media-foto');
    const preview = document.getElementById('media-preview');
    let previewUrl = null;
    modal.addEventListener('show.bs.modal', function (event) {
        form.reset();
        errors.classList.add('d-none');
        const item = event.relatedTarget?.dataset.item ? JSON.parse(event.relatedTarget.dataset.item) : null;
        form.action = item ? item.url : @json(route('admin.media.'.$jenis.'.store'));
        document.getElementById('media-method').value = item ? 'PUT' : 'POST';
        document.getElementById('media-modal-title').textContent = (item ? 'Edit ' : 'Tambah ') + @json($label);
        document.getElementById('media-judul').value = item?.judul || '';
        document.getElementById('media-kategori').value = item?.kategori || @json(request('kategori', ''));
        if (file) {
            file.required = !item;
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            preview.removeAttribute('src');
            preview.classList.toggle('d-none', !item);
            if (item) preview.src = item.foto_url;
        } else document.getElementById('media-code').value = item?.youtube_code || '';
    });
    file?.addEventListener('change', function () {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        preview.classList.add('d-none');
        if (file.files[0]) {
            previewUrl = URL.createObjectURL(file.files[0]);
            preview.src = previewUrl;
            preview.classList.remove('d-none');
        }
    });
    modal.addEventListener('hide.bs.modal', event => { if (save.disabled) event.preventDefault(); });
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        save.disabled = true;
        save.textContent = 'Menyimpan...';
        errors.classList.add('d-none');
        try {
            const response = await fetch(form.action, {method: 'POST', body: new FormData(form), headers: {'Accept': 'application/json'}});
            const data = await response.json();
            if (!response.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Gagal menyimpan media.'));
            window.location.reload();
        } catch (error) {
            errors.textContent = error.message;
            errors.classList.remove('d-none');
            save.disabled = false;
            save.textContent = 'Simpan';
        }
    });
});
</script>
@endpush
