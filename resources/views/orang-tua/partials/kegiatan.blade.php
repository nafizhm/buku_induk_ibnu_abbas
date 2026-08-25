{{-- KEGIATAN: QR presensi + daftar kegiatan (contoh statis) --}}
<section class="view" id="view-kegiatan">
  @include('orang-tua.partials.page-header', ['title' => 'Kegiatan', 'subtitle' => 'Jadwal dan presensi kegiatan'])
  <div class="content">
    <div class="qr-card">
      <p class="qr-eyebrow">QR Presensi Kegiatan</p>
      <h3>{{ $orangTua?->nama_ayah ?? $orangTua?->nama_ibu ?? $siswa->nama_lengkap }} — Wali dari {{ $siswa->nama_lengkap }}</h3>
      <div class="qr-box" id="qrcode"></div>
      <p class="qr-note">Tunjukkan kode ini kepada petugas saat check-in kegiatan. Jangan bagikan kode ke orang lain.</p>
      <div class="qr-id">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;stroke:currentColor;"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
        ID Ortu: RQ-{{ str_pad($account->id, 4, '0', STR_PAD_LEFT) }}
      </div>
    </div>

    <div class="section-title"><h2>Daftar Kegiatan</h2></div>
    <div class="filter-chips">
      <button class="chip active" data-filter="semua" onclick="filterKegiatan(this,'semua')">Semua</button>
      <button class="chip" data-filter="mendatang" onclick="filterKegiatan(this,'mendatang')">Akan Datang</button>
      <button class="chip" data-filter="selesai" onclick="filterKegiatan(this,'selesai')">Selesai</button>
    </div>

    <div style="margin-top:16px;">
      @php
        $kegiatan = [
          ['d' => '24', 'm' => 'Agu', 'judul' => 'Wisuda Tahfidz Angkatan 12', 'jam' => '08.00 – Selesai', 'lokasi' => 'Aula Utama', 'status' => 'mendatang'],
          ['d' => '29', 'm' => 'Agu', 'judul' => 'Pertemuan Wali Santri Triwulan', 'jam' => '09.30 – 11.30', 'lokasi' => 'Ruang Serbaguna', 'status' => 'mendatang'],
          ['d' => '02', 'm' => 'Agu', 'judul' => 'Kajian Parenting Bulanan', 'jam' => '09.00 – 10.30', 'lokasi' => 'Masjid Ibnu Abbas', 'status' => 'selesai'],
          ['d' => '19', 'm' => 'Jul', 'judul' => 'Pembagian Rapor Semester', 'jam' => '08.00 – 12.00', 'lokasi' => 'Ruang Kelas Masing-masing', 'status' => 'selesai'],
        ];
      @endphp
      @foreach($kegiatan as $k)
      <div class="card kegiatan-item" data-status="{{ $k['status'] }}" style="{{ !$loop->first ? 'margin-top:10px;' : '' }}">
        <div class="activity-card">
          <div class="date-chip" style="{{ $k['status'] === 'selesai' ? 'opacity:.6;' : '' }}"><p class="dnum">{{ $k['d'] }}</p><p class="dmon">{{ $k['m'] }}</p></div>
          <div class="a-body">
            <p class="a-title">{{ $k['judul'] }}</p>
            <p class="a-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>{{ $k['jam'] }}</p>
            <p class="a-meta-row"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6-7-11a7 7 0 0 1 14 0c0 5-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $k['lokasi'] }}</p>
            <div class="a-tags">
              @if($k['status'] === 'mendatang')
                <span class="badge pending">Akan Datang</span>
              @else
                <span class="badge ok">Selesai</span>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
