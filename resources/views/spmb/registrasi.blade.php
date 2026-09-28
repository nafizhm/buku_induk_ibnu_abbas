@extends('spmb.layout')
@section('title', 'Registrasi Santri')
@section('content')
    <header class="intro">
        <div class="eyebrow">SELAMAT DATANG, BAPAK / IBU</div>
        <h1>Lengkapi registrasi santri</h1>
        <p class="muted">Pilih bagian di bawah untuk melengkapi data. Progres tersimpan dan dapat dilanjutkan melalui tautan yang sama.</p>
    </header>
    <section class="summary" aria-label="Progres registrasi">
        <div class="identity"><div><p>Calon santri</p><strong>{{ $spmb->nama }}</strong></div><span class="tag">{{ $spmb->jenjang }} · {{ $spmb->jk }}</span></div>
        <div class="progress-label"><span>Kelengkapan registrasi</span><strong>{{ $completed }} dari 3 bagian selesai</strong></div>
        <progress value="{{ $completed }}" max="3" aria-label="Bagian registrasi selesai">{{ $completed }} dari 3</progress>
    </section>
    <p class="section-label">DATA REGISTRASI</p>
    <a class="menu-card" href="{{ route('spmb.formulir.isian', $spmb->token) }}">
        <span class="number {{ $formComplete ? 'done' : '' }}" aria-hidden="true">{{ $formComplete ? '✓' : '01' }}</span>
        <div class="menu-content"><h2>1. Data Formulir</h2><p>Identitas santri, data Dapodik, serta informasi orang tua dan wali.</p><span class="status {{ $formComplete ? 'done' : '' }}">{{ $formComplete ? 'Selesai · Formulir terkirim' : ($spmb->siswa_id ? 'Draf tersimpan · Lanjutkan pengisian' : 'Belum diisi') }}</span></div>
        <span class="arrow" aria-hidden="true">›</span>
    </a>
    <a class="menu-card" href="{{ route('spmb.lampiran', $spmb->token) }}">
        <span class="number {{ $filesComplete ? 'done' : '' }}" aria-hidden="true">{{ $filesComplete ? '✓' : '02' }}</span>
        <div class="menu-content"><h2>2. Berkas / Lampiran</h2><p>Unggah dokumen pendukung dan pas foto. Ijazah TK bersifat opsional.</p><span class="status {{ $filesComplete ? 'done' : '' }}">{{ $filesComplete ? 'Selesai' : 'Belum lengkap' }} · {{ $uploaded }} dari 5 berkas wajib</span></div>
        <span class="arrow" aria-hidden="true">›</span>
    </a>
    <div class="menu-card" aria-disabled="true">
        <span class="number" aria-hidden="true">03</span>
        <div class="menu-content"><h2>3. Surat Pernyataan</h2><p>Formulir pernyataan akan tersedia setelah disiapkan oleh pihak sekolah.</p><span class="status pending">Segera tersedia</span></div>
    </div>
    <div class="info">Tanda ✓ menunjukkan bagian sudah dilengkapi. Silakan selesaikan Data Formulir dan Berkas terlebih dahulu sambil menunggu Surat Pernyataan tersedia.</div>
@endsection
