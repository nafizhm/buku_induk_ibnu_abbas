{{-- KEGIATAN: QR presensi dan jadwal dari database --}}
<section class="view" id="view-kegiatan">
  @include('orang-tua.partials.page-header', ['title' => 'Kegiatan', 'subtitle' => 'Jadwal dan presensi kegiatan'])
  <div class="content">
    @if($qrKegiatan)
    <div class="qr-card">
      <p class="qr-eyebrow">QR Presensi Kegiatan</p>
      <h3>{{ $qrKegiatan->kegiatan->nama_kegiatan }}</h3>
      <p class="qr-note">{{ $qrKegiatan->kegiatan->tgl_kegiatan->locale('id')->translatedFormat('l, d F Y') }} · {{ $qrKegiatan->kegiatan->zona_waktu }}</p>
      <div class="qr-box" id="qrcode"></div>
      <p class="qr-note">Tunjukkan kode ini kepada petugas saat check-in kegiatan. Jangan bagikan kode ke orang lain.</p>
      <div class="qr-id">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;stroke:currentColor;"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
        ID Ortu: RQ-{{ str_pad($account->id, 4, '0', STR_PAD_LEFT) }}
      </div>
    </div>
    @endif

    <div class="section-title"><h2>Daftar Kegiatan</h2></div>
    <div class="filter-chips">
      <button class="chip active" data-filter="semua" onclick="filterKegiatan(this,'semua')">Semua</button>
      <button class="chip" data-filter="mendatang" onclick="filterKegiatan(this,'mendatang')">Akan Datang</button>
      <button class="chip" data-filter="selesai" onclick="filterKegiatan(this,'selesai')">Selesai</button>
    </div>

    <div style="margin-top:16px;">
      @forelse($daftarKegiatan as $item)
      @php
        $statusKegiatan = $item->status === 'aktif' && $item->tgl_kegiatan->gte(today())
          ? 'mendatang' : 'selesai';
      @endphp
      <div class="card kegiatan-item" data-status="{{ $statusKegiatan }}" style="{{ !$loop->first ? 'margin-top:10px;' : '' }}">
        <div class="activity-card">
          <div class="date-chip" style="{{ $statusKegiatan === 'selesai' ? 'opacity:.6;' : '' }}">
            <p class="dnum">{{ $item->tgl_kegiatan->format('d') }}</p>
            <p class="dmon">{{ $item->tgl_kegiatan->locale('id')->translatedFormat('M') }}</p>
          </div>
          <div class="a-body">
            <p class="a-title">{{ $item->nama_kegiatan }}</p>
            <p class="a-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>{{ $item->tgl_kegiatan->locale('id')->translatedFormat('l, d F Y') }}</p>
            <p class="a-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6-7-11a7 7 0 0 1 14 0c0 5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $item->zona_waktu }}</p>
            <div class="a-tags">
              @if($statusKegiatan === 'mendatang')
                <span class="badge pending">Akan Datang</span>
              @else
                <span class="badge ok">Selesai</span>
              @endif
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="card"><p style="padding:18px;text-align:center;color:var(--muted);">Belum ada data kegiatan.</p></div>
      @endforelse
    </div>
  </div>
</section>
