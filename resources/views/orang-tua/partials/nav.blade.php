{{-- BOTTOM NAV --}}
<nav class="bottom-nav">
  <button class="nav-btn" data-view="beranda" onclick="navigateView('beranda')">
    <span class="nav-inner">
      <svg viewBox="0 0 24 24" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 9-8 9 8"/><path d="M5 10v10a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V10"/></svg>
      <span>Beranda</span>
    </span>
  </button>
  <button class="nav-btn" data-view="presensi" onclick="navigateView('presensi')">
    <span class="nav-inner">
      <svg viewBox="0 0 24 24" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="15" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/><path d="m8 15 2 2 4-4"/></svg>
      <span>Presensi</span>
    </span>
  </button>
  <button class="nav-btn" data-view="kegiatan" onclick="navigateView('kegiatan')">
    <span class="nav-inner">
      <svg viewBox="0 0 24 24" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.3"/><rect x="14" y="3" width="7" height="7" rx="1.3"/><rect x="3" y="14" width="7" height="7" rx="1.3"/><path d="M14 14h3v3h-3zM19 14h2v2h-2zM14 19h2v2h-2zM19 19h2v2h-2z"/></svg>
      <span>Kegiatan</span>
    </span>
  </button>
  <button class="nav-btn" data-view="profil" onclick="navigateView('profil')">
    <span class="nav-inner">
      <svg viewBox="0 0 24 24" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
      <span>Profil</span>
    </span>
  </button>
</nav>

{{-- DRAWER MENU --}}
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="drawer" id="drawer">
  <div class="drawer-head">
    <div class="drawer-logo"><img src="{{ asset('assets/orang-tua/img/logo.jpg') }}" alt="Logo"></div>
    <div class="dh-text">
      <p class="dh-name">Rumah Qur'an Ibnu Abbas</p>
      <p class="dh-sub">Portal Orang Tua</p>
    </div>
    <button class="drawer-close" onclick="closeDrawer()" aria-label="Tutup menu">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
  </div>
  <div class="drawer-nav">
    <button class="drawer-item" data-view="beranda" onclick="goFromDrawer('beranda')">
      <span class="di-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 9-8 9 8"/><path d="M5 10v10a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V10"/></svg></span>
      Beranda
    </button>
    <button class="drawer-item" data-view="presensi" onclick="goFromDrawer('presensi')">
      <span class="di-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="15" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/><path d="m8 15 2 2 4-4"/></svg></span>
      Presensi
    </button>
    <button class="drawer-item" data-view="kegiatan" onclick="goFromDrawer('kegiatan')">
      <span class="di-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.3"/><rect x="14" y="3" width="7" height="7" rx="1.3"/><rect x="3" y="14" width="7" height="7" rx="1.3"/><path d="M14 14h3v3h-3zM19 14h2v2h-2zM14 19h2v2h-2zM19 19h2v2h-2z"/></svg></span>
      Kegiatan
    </button>
    <button class="drawer-item" data-view="hafalan" onclick="goFromDrawer('hafalan')">
      <span class="di-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg></span>
      Hafalan
      <span class="di-badge">Baru</span>
    </button>
    <button class="drawer-item" data-view="profil" onclick="goFromDrawer('profil')">
      <span class="di-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg></span>
      Profil
    </button>
  </div>
  <div class="drawer-foot">
    <form action="{{ route('orang-tua.logout') }}" method="POST" style="margin-bottom:12px;" onsubmit="document.getElementById('pageLoading').classList.add('show')">
      @csrf
      <button type="submit" class="drawer-item" style="width:100%; color:var(--danger);">Keluar</button>
    </form>
    <p>Rumah Qur'an Ibnu Abbas &copy; {{ date('Y') }}</p>
  </div>
</div>
