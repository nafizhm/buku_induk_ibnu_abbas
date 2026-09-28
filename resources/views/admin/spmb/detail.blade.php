@extends('admin.layout')
@section('content')
<div class="page-content"><section class="section">
    <div class="card"><div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Detail Pendaftar — {{ $pendaftaran->nama }}</h3>
        <a href="{{ route($routePrefix.'.index') }}" class="btn btn-secondary">Kembali</a>
    </div><div class="card-body">
        <p>Token: <code>{{ $pendaftaran->token }}</code> · Status: {{ $pendaftaran->status }}</p>
        <h5>Lampiran</h5>
        <ul class="list-group mb-4">
        @foreach(\App\Models\SpmbLampiran::DOKUMEN as $jenis => $dokumen)
            @php($file = $pendaftaran->lampiran->firstWhere('jenis', $jenis))
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $dokumen['label'] }}
                @if($file)
                    <a href="{{ route($routePrefix.'.lampiran', [$pendaftaran, $jenis]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-primary">Lihat Lampiran</a>
                @else <span class="text-muted">Belum diunggah</span> @endif
            </li>
        @endforeach
        </ul>
        @if($siswa)
            @foreach(['Data Siswa' => $siswa, 'Data Orang Tua / Wali' => $siswa->orangTua] as $judul => $data)
                <h5>{{ $judul }}</h5>
                <div class="table-responsive"><table class="table table-bordered"><tbody>
                @foreach(($data?->getAttributes() ?? []) as $field => $value)
                    @if(!in_array($field, ['id', 'siswa_id', 'created_at', 'updated_at', 'deleted_at']) && $value !== null && $value !== '')
                        <tr><th style="width:35%">{{ \Illuminate\Support\Str::headline($field) }}</th><td>{{ $value }}</td></tr>
                    @endif
                @endforeach
                </tbody></table></div>
            @endforeach
        @else <p class="text-muted">Data formulir belum diisi.</p> @endif
    </div></div>
</section></div>
@endsection
