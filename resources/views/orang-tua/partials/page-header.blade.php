{{-- Header halaman dalam --}}
<div class="page-header">
  <div class="page-header-top">
    <button class="back-btn" onclick="navigateView('beranda')" aria-label="Kembali ke beranda">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg>
    </button>
    <div>
      <h1>{{ $title }}</h1>
      <p>{{ $subtitle }}</p>
    </div>
  </div>
</div>
