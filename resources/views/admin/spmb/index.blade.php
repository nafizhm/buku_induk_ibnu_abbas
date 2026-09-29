@extends('admin.layout')

@section('content')
@php($isPendaftar = $isPendaftar ?? false)
<div class="page-content"><section class="section"><div class="card">
    <div class="card-header"><h3 class="card-title">SPMB {{ $jenjang }} - {{ $isPendaftar ? 'Pendaftar' : 'Formulir' }}</h3></div>
    <div class="card-body">
        <form method="GET" action="{{ route($routePrefix.'.index') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3">
            <div>
                <label for="filter-statistik" class="form-label">Filter statistik</label>
                <select name="filter" id="filter-statistik" class="form-select">
                    @foreach(\App\Models\SpmbPendaftaran::FILTERS as $value => $label)
                        <option value="{{ $value }}" @selected($filter === $value)>{{ $value === 'semua' && $isPendaftar ? 'Semua Pendaftar' : $label }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Tampilkan</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            @if($isPendaftar)<a href="{{ route($routePrefix.'.download', ['filter' => $filter]) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Download Excel (Daftar Ini)</a>@endif
            <span class="text-muted pb-2">{{ $pendaftaran->count() }} data</span>
        </form>
        @if($isPendaftar)<p class="text-muted">Pendaftar yang sudah mengirim data formulir.</p>@else
        <p class="text-muted">Kirim WA membuka pesan siap kirim dan menandai status isi formulir. Tekan Kirim di WhatsApp untuk mengirim pesannya.</p>@endif
        <div id="waError" class="alert alert-danger d-none" role="alert"></div>
        <div class="table-responsive">
        <table id="spmb-table" class="table table-bordered table-striped w-100">
            <thead><tr><th>No</th><th>Tanggal (WITA) / Token</th><th>Calon Siswa</th><th>Jenjang</th><th>Jenis Kelamin</th><th>Orang Tua/Wali</th><th>No. HP / WhatsApp</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($pendaftaran as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td data-order="{{ $item->created_at->timestamp }}">{{ $item->created_at->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i') }} WITA<br><small>Token: <code>{{ $item->token }}</code></small></td>
                        <td>{{ $item->nama }}</td><td>{{ $item->jenjang }}</td><td>{{ $item->jk }}</td><td>{{ $item->ortu }}</td><td>{{ $item->wa }}</td>
                        <td class="spmb-status"><span class="badge {{ $item->status === 'selesai' ? 'bg-success' : ($item->status === 'isi formulir' ? 'bg-info' : 'bg-secondary') }}">{{ $item->status }}</span></td>
                        <td><div class="d-flex flex-wrap gap-1">                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#buktiModal" data-url="{{ route($routePrefix.'.bukti', $item) }}" data-mime="{{ $item->bukti_mime }}" data-nama="{{ $item->nama }}">Lihat Bukti</button>
@if($isPendaftar)<a class="btn btn-sm btn-info" href="{{ route($routePrefix.'.detail', $item) }}">Detail / Lampiran</a>@endif
@if($isPendaftar)
<a class="btn btn-sm {{ $item->pernyataan_at ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route($routePrefix.'.pernyataan', $item) }}">Pernyataan{{ $item->pernyataan_at ? ' ✓' : ' (Belum)' }}</a>
<a class="btn btn-sm {{ $item->wawancara_at ? 'btn-success' : 'btn-outline-secondary' }}" href="{{ route($routePrefix.'.wawancara', $item) }}">Wawancara{{ $item->wawancara_at ? ' ✓' : ' (Belum)' }}</a>
@endif
@if($isPendaftar)<a class="btn btn-sm btn-success" href="{{ route($routePrefix.'.download-one', $item) }}"><i class="bi bi-file-earmark-excel"></i> Download Excel</a>@endif
@if(!$isPendaftar)<button type="button" class="btn btn-sm btn-success kirim-wa" data-url="{{ route($routePrefix.'.kirim-wa', $item) }}">Kirim WA</button>@endif</div></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div></div>
</div></section></div>
<div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title" id="buktiTitle">Bukti Transfer</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
        <div class="modal-body text-center" id="buktiPreview"></div>
        <div class="modal-footer"><a id="buktiOpen" class="btn btn-outline-primary" target="_blank" rel="noopener">Buka di tab baru</a><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        const table = $('#spmb-table').DataTable({order: [], language: {emptyTable: 'Belum ada pendaftaran SPMB.'}});
        $('#spmb-table').on('click', '.kirim-wa', async function () {
            const button = this;
            const error = document.getElementById('waError');
            error.classList.add('d-none');
            const tab = window.open('about:blank', '_blank');
            if (!tab) {
                error.textContent = 'Izinkan pop-up untuk membuka WhatsApp, lalu klik Kirim WA kembali.';
                error.classList.remove('d-none');
                return;
            }
            tab.opener = null;
            button.disabled = true;
            try {
                const response = await fetch(button.dataset.url, {
                    method: 'POST', headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token())}
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Tidak dapat menyiapkan pesan WhatsApp. Muat ulang halaman dan coba lagi.');
                tab.location.href = data.url;
                const badge = button.closest('tr').querySelector('.spmb-status .badge');
                badge.textContent = data.status;
                badge.className = 'badge ' + (data.status === 'selesai' ? 'bg-success' : 'bg-info');
                table.row(button.closest('tr')).invalidate('dom');
            } catch (e) {
                tab.close();
                error.textContent = e.message;
                error.classList.remove('d-none');
            } finally { button.disabled = false; }
        });
        const modal = document.getElementById('buktiModal');
        const preview = document.getElementById('buktiPreview');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            preview.replaceChildren();
            document.getElementById('buktiTitle').textContent = 'Bukti Transfer — ' + button.dataset.nama;
            document.getElementById('buktiOpen').href = button.dataset.url;
            const isPdf = button.dataset.mime === 'application/pdf';
            const media = document.createElement(isPdf ? 'iframe' : 'img');
            media.src = button.dataset.url;
            if (isPdf) {
                media.title = 'Bukti transfer PDF';
                media.style.cssText = 'width:100%;height:70vh;border:0';
            } else {
                media.alt = 'Bukti transfer';
                media.style.cssText = 'max-width:100%;max-height:70vh;object-fit:contain';
                media.onerror = function () { preview.textContent = 'Bukti tidak dapat ditampilkan. Gunakan tombol Buka di tab baru.'; };
            }
            preview.appendChild(media);
        });
        modal.addEventListener('hidden.bs.modal', function () { preview.replaceChildren(); });
    });
</script>
@endpush
