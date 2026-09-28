@extends('spmb.layout')
@section('title', 'Formulir Telah Diterima')
@section('content')
    <a class="back" href="{{ route('spmb.formulir', $spmb->token) }}">← Kembali ke Registrasi</a>
    <div class="menu-card"><span class="number done" aria-hidden="true">✓</span><div class="menu-content"><h1>Formulir Telah Diterima</h1><p>Data formulir <strong>{{ $spmb->nama }}</strong> sudah berhasil dikirim. Anda dapat melanjutkan melengkapi berkas melalui menu registrasi.</p><span class="status done">Data Formulir · Selesai</span></div></div>
    <a class="button" href="{{ route('spmb.lampiran', $spmb->token) }}">Buka Berkas / Lampiran</a>
@endsection
