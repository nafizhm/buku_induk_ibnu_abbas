@extends('admin.layout')
@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @if(count($quotaAvailability))
            <div class="card">
                <div class="card-header"><h4>Pengaturan Kuota Pendaftaran</h4></div>
                <div class="card-body">
                    <p class="text-muted">Sudah mendaftar mencakup semua formulir masuk. Kuota 0 menutup pendaftaran kategori tersebut. Jika jumlah pendaftar mencapai kuota, pendaftaran baru otomatis ditutup.</p>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Jenjang</th><th>Kategori</th><th>Kuota</th><th>Sudah mendaftar</th><th>Sisa kuota</th></tr></thead>
                            <tbody>
                                @foreach($quotaAvailability as $jenjang => $categories)
                                    @foreach($categories as $jk => $quota)
                                        <tr>
                                            <td>{{ $jenjang }}</td><td>{{ $jk }}</td>
                                            <td>
                                                @if($quotaEditable[$jenjang])
                                                    <form method="POST" action="{{ route('admin.kuota.update') }}" class="d-flex gap-2">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="jenjang" value="{{ $jenjang }}">
                                                        <input type="hidden" name="jk" value="{{ $jk }}">
                                                        <input type="number" name="kuota" value="{{ old('jenjang') === $jenjang && old('jk') === $jk ? old('kuota') : $quota['limit'] }}" min="0" max="1000000" required class="form-control" style="width: 110px" aria-label="Kuota {{ $jenjang }} {{ $jk }}">
                                                        <button class="btn btn-primary" type="submit">Simpan Kuota</button>
                                                    </form>
                                                @else
                                                    {{ $quota['limit'] }}
                                                    <small class="d-block text-muted">Perlu hak Edit pada SPMB {{ $jenjang }} → Formulir.</small>
                                                @endif
                                            </td>
                                            <td><strong>{{ $quota['registered'] }}</strong> pendaftar</td>
                                            <td>{{ $quota['remaining'] }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
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
