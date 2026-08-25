{{-- PRESENSI --}}
<section class="view" id="view-presensi">
  @include('orang-tua.partials.page-header', ['title' => 'Presensi', 'subtitle' => 'Rekap kehadiran santri per bulan'])
  <div class="content">
    <div class="cal-card">
      <div class="cal-head">
        <button class="cal-nav" onclick="calPrev('presensi')" aria-label="Bulan sebelumnya">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg>
        </button>
        <p class="cal-title" id="presensiCalTitle">—</p>
        <button class="cal-nav" onclick="calNext('presensi')" aria-label="Bulan berikutnya">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </button>
      </div>
      <div class="cal-weekdays">
        <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
      </div>
      <div class="cal-grid" id="presensiCalGrid"></div>

      <div class="legend-row">
        <div class="legend-chip"><span class="legend-dot" style="background:var(--success);"></span>Tepat Waktu</div>
        <div class="legend-chip"><span class="legend-dot" style="background:var(--warn);"></span>Terlambat</div>
        <div class="legend-chip"><span class="legend-dot" style="background:var(--info);"></span>Izin</div>
        <div class="legend-chip"><span class="legend-dot" style="background:var(--sick);"></span>Sakit</div>
        <div class="legend-chip"><span class="legend-dot" style="background:var(--danger);"></span>Alpa</div>
      </div>
    </div>

    <div class="section-title"><h2>Rekap Bulan Ini</h2></div>
    <div class="recap-grid">
      <div class="recap-item" style="background:var(--success-bg);">
        <p class="r-num" style="color:var(--success);" id="recapOntime">0</p>
        <p class="r-label" style="color:var(--success);">Tepat Waktu</p>
      </div>
      <div class="recap-item" style="background:var(--warn-bg);">
        <p class="r-num" style="color:var(--warn);" id="recapLate">0</p>
        <p class="r-label" style="color:var(--warn);">Terlambat</p>
      </div>
      <div class="recap-item" style="background:var(--info-bg);">
        <p class="r-num" style="color:var(--info);" id="recapIzin">0</p>
        <p class="r-label" style="color:var(--info);">Izin</p>
      </div>
      <div class="recap-item" style="background:var(--sick-bg);">
        <p class="r-num" style="color:var(--sick);" id="recapSakit">0</p>
        <p class="r-label" style="color:var(--sick);">Sakit</p>
      </div>
      <div class="recap-item" style="background:var(--danger-bg);">
        <p class="r-num" style="color:var(--danger);" id="recapAlpa">0</p>
        <p class="r-label" style="color:var(--danger);">Alpa</p>
      </div>
    </div>
  </div>
</section>
