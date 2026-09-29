@extends('admin.layout')
@section('content')
<div class="page-content"><section class="section"><div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between gap-2">
        <h3 class="card-title">{{ ucfirst($jenis) }} — {{ $pendaftaran->nama }}</h3>
        <a class="btn btn-secondary" href="{{ route($routePrefix.'.index') }}">Kembali ke Pendaftar</a>
    </div>
    <div class="card-body">
        <p>{{ $pendaftaran->jenjang }} · Tahun Pelajaran {{ $data['versi'] ?? '2027/2028' }}</p>
        @if(!$tanggal)
            <div class="alert alert-warning">{{ $jenis === 'pernyataan' ? 'Surat pernyataan belum disetujui.' : 'Wawancara belum diisi.' }}</div>
        @else
            <div class="alert alert-success">{{ $jenis === 'pernyataan' ? 'Disetujui' : 'Terakhir disimpan' }} pada {{ $tanggal->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i') }} WITA.</div>
            <div class="table-responsive"><table class="table table-bordered"><tbody>
                @foreach($data['identitas'] as $label => $value)<tr><th>{{ $label }}</th><td>{{ $value ?: 'Belum diisi' }}</td></tr>@endforeach
            </tbody></table></div>
            @if($jenis === 'pernyataan')
                @foreach(['peraturan', 'biaya'] as $key)
                    @php($surat = $data['dokumen'][$key])
                    <article class="border rounded p-3 mb-3"><h4>{{ $surat['judul'] }}</h4>
                    @foreach($surat['paragraf'] as $paragraf)<p>{{ $paragraf }}</p>@endforeach
                    <span class="badge bg-success">{{ !empty($data['persetujuan'][$key]) ? 'Disetujui' : 'Belum disetujui' }}</span></article>
                @endforeach
            @else
                @foreach($data['urutan'] as $key)
                    @php($label = $data['pertanyaan'][$key])
                    <article class="border rounded p-3 mb-3"><h5>{{ $loop->iteration }}. {{ $label }}</h5><p style="white-space:pre-wrap;overflow-wrap:anywhere">{{ $data['jawaban'][$key] ?? 'Belum diisi' }}</p></article>
                @endforeach
                @if(!empty($data['kebenaran']))<p class="text-success">Orang tua / wali menyatakan jawaban diisi dengan sebenar-benarnya dan dapat dipertanggungjawabkan.</p>@endif
            @endif
        @endif
    </div>
</div></section></div>
@endsection
