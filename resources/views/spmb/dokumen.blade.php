@extends('spmb.layout')
@section('title', $isWawancara ? 'Wawancara Orang Tua / Wali' : 'Surat Pernyataan')
@section('styles')
<style>
    .document p{margin:14px 0;line-height:1.8}.identity-list{display:grid;grid-template-columns:minmax(120px,1fr) 2fr;gap:8px 16px;margin:18px 0}.identity-list dt{font-weight:600}.identity-list dd{margin:0;overflow-wrap:anywhere}.check{display:flex;align-items:flex-start;gap:12px;background:var(--soft);padding:16px;border-radius:10px;margin:18px 0}.check input{width:20px;height:20px;flex-shrink:0;margin-top:3px}.question{display:block;font-weight:600;margin-bottom:8px}textarea{display:block;width:100%;min-height:130px;padding:12px;border:1px solid #bba9cf;border-radius:10px;font:inherit;resize:vertical}textarea:focus-visible{outline:3px solid #9665d4;outline-offset:2px}.answer{margin-bottom:24px}section{scroll-margin-top:20px}@media(max-width:520px){.identity-list{grid-template-columns:1fr;gap:3px}.identity-list dd{margin-bottom:10px}}
</style>
@endsection
@section('content')
<a class="back" href="{{ route('spmb.formulir', $spmb->token) }}">&larr; Kembali ke Registrasi</a>
<header class="intro"><div class="eyebrow">TAHUN PELAJARAN 2027/2028</div><h1>{{ $isWawancara ? 'Wawancara Orang Tua / Wali' : 'Surat Pernyataan' }}</h1><p>{{ \App\Support\SpmbDokumen::program($spmb->jenjang) }}</p></header>
@if(session('success'))<div class="success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="error" role="alert"><strong>Data belum tersimpan.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@unless($isWawancara)
<section id="pernyataan">
    <h2>3. Surat Pernyataan</h2>
    <p class="muted">Baca kedua surat berikut, lalu centang persetujuan dan simpan.</p>
    <div class="upload-card">
        <h2>Identitas orang tua / wali dan calon santri</h2>
        <dl class="identity-list">@foreach(($spmb->pernyataan_data['identitas'] ?? $identitas) as $label => $value)<dt>{{ $label }}</dt><dd>{{ $value ?: 'Belum diisi' }}</dd>@endforeach</dl>
        @unless($spmb->pernyataan_at)<p class="muted">Identitas diambil dari data pendaftaran dan Data Formulir. Lengkapi Data Formulir terlebih dahulu jika ada identitas yang belum terisi.</p>@endunless
    </div>
    <form method="POST" action="{{ route('spmb.pernyataan.store', $spmb->token) }}">
        @csrf
        @foreach($pernyataan as $key => $surat)
        <article class="upload-card document">
            <h2>Surat Pernyataan {{ $surat['judul'] }}</h2>
            @foreach($surat['paragraf'] as $paragraf)<p>{{ $paragraf }}</p>@endforeach
            @if($spmb->pernyataan_at)
                <div class="success">Disetujui oleh {{ $spmb->pernyataan_data['identitas']['Nama orang tua / wali'] }} pada {{ $spmb->pernyataan_at->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i') }} WITA.</div>
            @else
                <label class="check"><input type="checkbox" name="{{ $key }}" value="1" required @checked(old($key))><span>Saya telah membaca dan menyetujui surat pernyataan {{ strtolower($surat['judul']) }}.</span></label>
            @endif
        </article>
        @endforeach
        @unless($spmb->pernyataan_at)<button type="submit">Simpan Persetujuan</button>@endunless
    </form>
</section>
@else
<section id="wawancara">
    <h2>4. Wawancara Orang Tua / Wali Murid</h2>
    <p class="muted">Isi semua pertanyaan sesuai kondisi sebenarnya. Jika tidak ada, tuliskan “Tidak ada”.</p>
    @if($spmb->wawancara_at)<div class="success">Wawancara tersimpan pada {{ $spmb->wawancara_at->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i') }} WITA. Jawaban dapat diperbarui melalui formulir ini.</div>@endif
    <form class="upload-card" method="POST" action="{{ route('spmb.wawancara.store', $spmb->token) }}">
        @csrf
        <p><strong>{{ $identitas['Nama siswa'] }}</strong> · {{ $spmb->jk }}</p>
        <p>Ayah: {{ $identitas['Nama ayah kandung'] ?: 'Belum diisi' }}<br>Ibu: {{ $identitas['Nama ibu kandung'] ?: 'Belum diisi' }}</p>
        @foreach($pertanyaan as $key => $label)
        <div class="answer"><label class="question" for="jawaban-{{ $key }}">{{ $loop->iteration }}. {{ $label }}</label>
            <textarea id="jawaban-{{ $key }}" name="jawaban[{{ $key }}]" rows="4" maxlength="5000" required>{{ old('jawaban.'.$key, $spmb->wawancara_data['jawaban'][$key] ?? '') }}</textarea>
        </div>
        @endforeach
        <label class="check"><input type="checkbox" name="kebenaran" value="1" required @checked(old('kebenaran'))><span>Demikian, form wawancara kami isi dengan sebenar-benarnya, dan dapat dipertanggung jawabkan sebagaimana mestinya.</span></label>
        <button type="submit">Simpan Jawaban Wawancara</button>
    </form>
</section>
@endunless
@endsection
