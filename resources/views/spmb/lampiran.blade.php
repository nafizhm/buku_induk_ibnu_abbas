@extends('spmb.layout')
@section('title', 'Berkas dan Lampiran')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/spmb/lampiran.css') }}?v={{ filemtime(public_path('assets/spmb/lampiran.css')) }}">
@endsection
@section('content')
    <a class="back" href="{{ route('spmb.formulir', $spmb->token) }}">← Kembali ke Registrasi</a>
    <header class="intro"><div class="eyebrow">BAGIAN 02 · DOKUMEN PENDUKUNG</div><h1>Berkas / Lampiran</h1><p class="muted">Dokumen untuk <strong>{{ $spmb->nama }}</strong>. Unggah satu per satu dengan tulisan dan foto yang terlihat jelas.</p></header>
    <div class="info">Format JPG, PNG, WebP, atau PDF, maksimal 5 MB per berkas. Pas foto harus berupa gambar. Lima berkas wajib akan memberi centang pada bagian ini; ijazah TK diunggah jika ada.</div>
    @if(session('success'))<div class="success" role="status">✓ {{ session('success') }}</div>@endif
    @if($errors->any())<div class="error" role="alert"><strong>Berkas belum tersimpan.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @foreach($dokumen as $jenis => $document)
        @include('spmb.lampiran-card', ['number' => $loop->iteration])
    @endforeach
    <a class="button" href="{{ route('spmb.formulir', $spmb->token) }}">Kembali ke Registrasi</a>
    <dialog id="preview-dialog" aria-labelledby="preview-title">
        <div class="preview-header"><h2 id="preview-title">Preview berkas</h2><button type="button" id="preview-close" class="secondary" autofocus>Tutup ✕</button></div>
        <div class="preview-toolbar">
            <div id="image-zoom"><button type="button" id="zoom-out" aria-label="Perkecil gambar">−</button><output id="zoom-level" aria-live="polite">100%</output><button type="button" id="zoom-in" aria-label="Perbesar gambar">+</button><button type="button" id="zoom-reset">Reset</button></div>
            <a id="preview-open" target="_blank" rel="noopener noreferrer">Buka penuh ↗</a>
        </div>
        <p id="preview-hint" class="preview-hint"></p>
        <div id="preview-stage" tabindex="0" aria-label="Area preview, geser untuk melihat gambar yang diperbesar"></div>
    </dialog>
@endsection
@section('scripts')
<script src="{{ asset('assets/spmb/lampiran.js') }}?v={{ filemtime(public_path('assets/spmb/lampiran.js')) }}" defer></script>
@endsection
