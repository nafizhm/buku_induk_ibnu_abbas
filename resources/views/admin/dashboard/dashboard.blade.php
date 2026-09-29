@extends('admin.layout')
@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        @forelse($spmbStatistik as $jenjang => $statistik)
            <section class="mb-4" aria-labelledby="spmb-{{ strtolower($jenjang) }}">
                <h4 id="spmb-{{ strtolower($jenjang) }}">SPMB {{ $jenjang }}</h4>
                <div class="row g-3">
                    @foreach($statistik as $filter => $item)
                        @php
                            $warna = ['semua' => 'primary', 'wa' => 'info', 'proses' => 'warning', 'lengkap' => 'success'][$filter];
                            $ikon = ['semua' => 'file-earmark-text', 'wa' => 'whatsapp', 'proses' => 'pencil-square', 'lengkap' => 'check-circle'][$filter];
                        @endphp
                        <div class="col-12 col-sm-6 col-xl-3">
                            <a href="{{ $item['url'] }}" class="card h-100 mb-0 border border-{{ $warna }} text-decoration-none spmb-statistik"
                               aria-label="{{ $item['label'] }} {{ $jenjang }}: {{ $item['jumlah'] }}. Lihat daftar">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <h6>{{ $item['label'] }}</h6>
                                        <i class="bi bi-{{ $ikon }} text-{{ $warna }} fs-4" aria-hidden="true"></i>
                                    </div>
                                    <div class="fs-1 fw-bold text-{{ $warna }}">{{ number_format($item['jumlah'], 0, ',', '.') }}</div>
                                    <span class="text-muted">Lihat daftar <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="alert alert-light">Statistik SPMB tersedia bagi pengguna yang memiliki akses menu SPMB.</div>
        @endforelse
        @if(count($spmbStatistik))
            <div class="card"><div class="card-body small text-muted">
                <p class="mb-2">Total formulir mencakup seluruh pendaftaran pada setiap jenjang. Sudah Dikirim WA dihitung dari catatan tombol Kirim WA; pengiriman pesan tetap dilakukan di WhatsApp.</p>
                <p class="mb-0">Proses Isi Formulir mencakup pendaftaran berstatus isi formulir yang belum mengirim data akhir. Data &amp; Lampiran Lengkap berarti formulir sudah dikirim dan semua lampiran wajib sudah diunggah. Angka WA dapat mencakup pendaftaran yang sudah lengkap.</p>
            </div></div>
        @endif
    </div>
@endsection
@push('scripts')
@endpush
