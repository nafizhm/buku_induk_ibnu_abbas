<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<style>
    td { mso-number-format:"\@"; padding:6px; vertical-align:top; white-space:normal; }
    .label { font-weight:bold; background:#f3f4f6; width:280px; }
    .colon { text-align:center; width:25px; }
    .value { width:500px; }
</style></head><body>
<h2>Data Pendaftar SPMB {{ $jenjang }}</h2>
<p>Jumlah pendaftar: {{ $pendaftaran->count() }} | Dicetak: {{ now()->timezone('Asia/Makassar')->format('d-m-Y H:i') }} WITA</p>
<table border="1" cellpadding="6" cellspacing="0">
@forelse($pendaftaran as $item)
    <tr><td colspan="3" style="background:#4338ca;color:#ffffff;font-weight:bold;font-size:18px">{{ $loop->iteration }}. {{ $item->nama }} — SPMB {{ $jenjang }}</td></tr>
    @foreach([
        'Token Pendaftaran' => $item->token, 'Jenjang' => $item->jenjang,
        'Nama Pendaftar Awal' => $item->nama, 'Orang Tua / Wali Pendaftar' => $item->ortu,
        'WhatsApp Pendaftar' => $item->wa, 'Status Formulir' => $item->status,
        'Tanggal Pendaftaran (WITA)' => $item->created_at?->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i'),
        'Tanggal Kirim WA (WITA)' => $item->wa_dikirim_at?->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i'),
        'Tanggal Kirim Formulir (WITA)' => $item->selesai_at?->copy()->timezone('Asia/Makassar')->format('d-m-Y H:i'),
    ] as $label => $value)
        <tr><td class="label">{{ $label }}</td><td class="colon">:</td><td class="value">{{ $value }}</td></tr>
    @endforeach
    @foreach($sections as $title => $fields)
        @php
            $color = ['Data Siswa' => '#2563eb', 'Data Ayah' => '#16a34a', 'Data Ibu' => '#db2777', 'Data Wali' => '#d97706'][$title];
        @endphp
        <tr><td colspan="3" style="background:{{ $color }};color:#ffffff;font-weight:bold;font-size:16px">{{ $title }}</td></tr>
        @foreach($fields as $label => $path)
            @php
                $value = data_get($item->siswa, $path);
                if ($value instanceof \Carbon\CarbonInterface) $value = $value->format('d-m-Y');
                if ($path === 'jenis_kelamin') $value = $value === 'L' ? 'Laki-laki' : ($value === 'P' ? 'Perempuan' : '');
            @endphp
            <tr><td class="label">{{ $label }}</td><td class="colon">:</td><td class="value">{{ $value }}</td></tr>
        @endforeach
    @endforeach
    <tr><td colspan="3" style="background:#475569;color:#ffffff;font-weight:bold;font-size:16px">Lampiran Pendaftaran</td></tr>
    @foreach(\App\Models\SpmbLampiran::DOKUMEN as $jenis => $dokumen)
        @php
            $file = $item->lampiran->firstWhere('jenis', $jenis);
        @endphp
        <tr><td class="label">{{ $dokumen['label'] }}</td><td class="colon">:</td><td class="value">{{ $file ? 'Sudah diunggah: '.$file->nama_asli : 'Belum diunggah' }}</td></tr>
    @endforeach
    <tr><td colspan="3"></td></tr>
@empty
    <tr><td colspan="3">Tidak ada pendaftar sesuai filter.</td></tr>
@endforelse
</table></body></html>
