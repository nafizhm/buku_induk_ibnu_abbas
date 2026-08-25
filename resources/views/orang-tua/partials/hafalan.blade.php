{{-- HAFALAN --}}
<section class="view" id="view-hafalan">
  @include('orang-tua.partials.page-header', ['title' => 'Hafalan', 'subtitle' => "Catatan hafalan Al-Qur'an & hadits harian"])
  <div class="content">
    <div class="cal-card">
      <div class="cal-head">
        <button class="cal-nav" onclick="calPrev('hafalan')" aria-label="Bulan sebelumnya">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg>
        </button>
        <p class="cal-title" id="hafalanCalTitle">—</p>
        <button class="cal-nav" onclick="calNext('hafalan')" aria-label="Bulan berikutnya">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </button>
      </div>
      <div class="cal-weekdays">
        <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
      </div>
      <div class="cal-grid" id="hafalanCalGrid"></div>
      <p class="empty-note" style="padding-top:12px; padding-bottom:0;">Tanggal bertanda titik memiliki catatan hafalan. Ketuk tanggal untuk melihat detail.</p>
    </div>

    <div class="detail-panel" id="hafalanDetail">
      <p class="empty-note" style="padding:4px 0;">Pilih tanggal pada kalender untuk melihat detail hafalan.</p>
    </div>
  </div>
</section>
