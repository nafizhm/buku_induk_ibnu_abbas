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
        <div class="progress-label"><span>Kelengkapan registrasi</span><strong>{{ $completed }} dari 4 bagian selesai</strong></div>
        <progress value="{{ $completed }}" max="4" aria-label="Bagian registrasi selesai">{{ $completed }} dari 4</progress>
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
    @foreach(['pernyataan' => '3. Surat Pernyataan', 'wawancara' => '4. Wawancara'] as $jenis => $judul)
        @php($done = (bool) $spmb->{$jenis.'_at'})
        <a class="menu-card" href="{{ route('spmb.'.$jenis, $spmb->token) }}">
            <span class="number {{ $done ? 'done' : '' }}" aria-hidden="true">{{ $done ? '✓' : ($jenis === 'pernyataan' ? '03' : '04') }}</span>
            <div class="menu-content"><h2>{{ $judul }}</h2><p>{{ $jenis === 'pernyataan' ? 'Baca dan setujui peraturan serta kesanggupan membayar biaya pendidikan.' : 'Isi wawancara orang tua / wali sesuai jenjang calon santri.' }}</p><span class="status {{ $done ? 'done' : '' }}">{{ $done ? 'Selesai · Tersimpan' : 'Belum diisi' }}</span></div>
            <span class="arrow" aria-hidden="true">›</span>
        </a>
    @endforeach
    <div class="info">Tanda ✓ menunjukkan bagian sudah dilengkapi. Lengkapi keempat bagian registrasi melalui tautan ini.</div>
@endsection
