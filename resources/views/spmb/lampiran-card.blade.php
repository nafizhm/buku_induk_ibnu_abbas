        @php($file = $lampiran->get($jenis))
        <section class="upload-card" id="{{ $jenis }}">
            <div class="upload-heading">
                <span class="number {{ $file ? 'done' : '' }}" aria-hidden="true">{{ $file ? '✓' : $number }}</span>
                <div><h2>{{ $document['label'] }}</h2><span class="status {{ $file ? 'done' : 'pending' }}">{{ $file ? 'Sudah diunggah' : ($document['required'] ? 'Wajib' : 'Opsional · Jika ada') }}</span>
                    @if($jenis === 'pas_foto')<p>Banin / putra: baju putih berkerah.<br>Banat / putri: jilbab putih.</p>@endif
                </div>
            </div>
            @if($file)
                @php($previewUrl = route('spmb.lampiran.view', [$spmb->token, $jenis]))
                <div class="attachment-preview">
                    @if($file->mime === 'application/pdf')
                        <object data="{{ $previewUrl }}#toolbar=0&navpanes=0" type="application/pdf" aria-label="Preview {{ $document['label'] }}" tabindex="-1"><span class="pdf-placeholder">PDF<br>Ketuk untuk membuka dokumen</span></object>
                    @else
                        <img src="{{ $previewUrl }}" alt="Preview {{ $document['label'] }}" loading="lazy">
                    @endif
                    <button type="button" class="preview-trigger" data-url="{{ $previewUrl }}" data-mime="{{ $file->mime }}" data-title="{{ $document['label'] }}" aria-label="Perbesar {{ $document['label'] }}"><span>⤢ Ketuk untuk perbesar</span></button>
                </div>
                <div class="file-info"><span>{{ $file->nama_asli }} · {{ number_format($file->ukuran / 1024, 0, ',', '.') }} KB</span></div>
                <div class="attachment-tools">
                    <a href="{{ $previewUrl }}" target="_blank" rel="noopener noreferrer" class="button secondary">Buka berkas ↗</a>
                    <form class="delete-attachment" action="{{ route('spmb.lampiran.delete', [$spmb->token, $jenis]) }}" method="post" data-title="{{ $document['label'] }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="danger">Hapus berkas</button>
                    </form>
                </div>
            @endif
            <form class="upload-actions" action="{{ route('spmb.lampiran.store', $spmb->token) }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                <label class="sr-only" for="file-{{ $jenis }}">{{ $file ? 'Ganti' : 'Pilih' }} {{ $document['label'] }}</label>
                <input id="file-{{ $jenis }}" type="file" name="file" required accept="image/jpeg,image/png,image/webp{{ $jenis === 'pas_foto' ? '' : ',application/pdf' }}">
                <button type="submit">{{ $file ? 'Ganti berkas' : 'Unggah berkas' }}</button>
            </form>
            <p class="auto-upload-help muted">Berkas langsung tersimpan setelah dipilih.</p>
            <div class="upload-feedback" role="status" aria-live="polite" hidden><span class="upload-spinner" aria-hidden="true"></span><span class="upload-message"></span></div>
            <div class="selected-preview" hidden>
                <p class="muted">Preview pilihan baru · Menunggu tersimpan</p>
                <div class="attachment-preview">
                    <img alt="Preview berkas yang dipilih" hidden>
                    <span class="pdf-placeholder" hidden>PDF<br>Ketuk untuk memeriksa dokumen</span>
                    <button type="button" class="preview-trigger"><span>⤢ Periksa sebelum unggah</span></button>
                </div>
            </div>
        </section>
