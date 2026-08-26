{{-- BERANDA --}}
<section class="view active" id="view-beranda">
  <div class="hero">
    <div class="hero-top">
      <button class="icon-btn" aria-label="Buka menu" onclick="openDrawer()">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
      <div class="brand">
        <div class="brand-logo"><img src="{{ asset('assets/orang-tua/img/logo.jpg') }}" alt="Logo"></div>
        <div class="brand-text">
          <p class="eyebrow">Portal Orang Tua</p>
          <p class="name">Rumah Qur'an Ibnu Abbas</p>
        </div>
      </div>
      <button class="icon-btn" aria-label="Notifikasi">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 5 2 6 2 6H4s2-1 2-6"/><path d="M10 20a2 2 0 0 0 4 0"/></svg>
        <span class="bell-dot"></span>
      </button>
    </div>

    <div class="greeting">
      <p class="hi">Assalamu'alaikum 👋</p>
    </div>

    <div class="student-card">
      <div class="student-avatar">{{ mb_substr($siswa->nama_lengkap, 0, 2) }}</div>
      <div class="student-info">
        <p class="s-name">{{ $siswa->nama_lengkap }}</p>
        <p class="s-meta">{{ $siswa->kelas?->nama_kelas ?? 'Belum ada kelas' }}</p>
      </div>
    </div>
  </div>
  <svg class="arch-edge" viewBox="0 0 400 26" preserveAspectRatio="none">
    <path d="M0,26 L0,14 Q10,0 20,14 Q30,0 40,14 Q50,0 60,14 Q70,0 80,14 Q90,0 100,14 Q110,0 120,14 Q130,0 140,14 Q150,0 160,14 Q170,0 180,14 Q190,0 200,14 Q210,0 220,14 Q230,0 240,14 Q250,0 260,14 Q270,0 280,14 Q290,0 300,14 Q310,0 320,14 Q330,0 340,14 Q350,0 360,14 Q370,0 380,14 Q390,0 400,14 L400,26 Z"/>
  </svg>

  @if($activeView === 'beranda' && collect($profileSummary)->contains(fn($item) => !$item['complete'] && empty($item['optional'])))
  <div class="profile-alert">
    <div><strong>Profil belum lengkap</strong><p>Lengkapi data siswa, orang tua, dan lampiran agar informasi santri tersimpan dengan baik.</p></div>
    <a class="alert-action" href="{{ route('orang-tua.profil') }}" onclick="event.preventDefault();navigateView('profil')">Lengkapi</a>
  </div>
  @endif

  <div class="content" style="padding-top:0;">
    <div class="stat-row">
      <div class="stat-card i-purple">
        <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M8 2v4M16 2v4M3 9h18"/><path d="m8 14 2.5 2.5L16 11"/></svg></div>
        <p class="stat-value">8/10</p>
        <p class="stat-label">Presensi bulan ini</p>
      </div>
      <div class="stat-card i-gold">
        <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="15" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/></svg></div>
        <p class="stat-value">2</p>
        <p class="stat-label">Kegiatan mendatang</p>
      </div>
    </div>

    <div class="quick-grid">
      <button class="quick-item" onclick="navigateView('presensi')">
        <div class="qi-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="15" rx="3"/><path d="M8 3v4M16 3v4M3 10h18"/><path d="m8 15 2 2 4-4"/></svg></div>
        <span>Presensi</span>
      </button>
      <button class="quick-item" onclick="navigateView('kegiatan')">
        <div class="qi-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><path d="M14 14h3v3h-3zM19 14h2v2h-2zM14 19h2v2h-2zM19 19h2v2h-2z"/></svg></div>
        <span>Kegiatan</span>
      </button>
      <button class="quick-item" onclick="navigateView('hafalan')">
        <div class="qi-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg></div>
        <span>Hafalan</span>
      </button>
      <button class="quick-item" onclick="navigateProfileForm('berkas')">
        <div class="qi-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg></div>
        <span>Berkas</span>
      </button>
    </div>

    <div class="section-title">
      <h2>Kegiatan Mendatang</h2>
      <a href="{{ route('orang-tua.kegiatan') }}" onclick="event.preventDefault(); navigateView('kegiatan');">Lihat semua
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
      </a>
    </div>
    <div class="card">
      <div class="event-preview">
        <div class="date-chip"><p class="dnum">24</p><p class="dmon">Agu</p></div>
        <div class="event-body">
          <p class="e-title">Wisuda Tahfidz Angkatan 12</p>
          <p class="e-meta">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            08.00 – Selesai · Aula Utama
          </p>
        </div>
      </div>
      <div class="event-preview">
        <div class="date-chip"><p class="dnum">29</p><p class="dmon">Agu</p></div>
        <div class="event-body">
          <p class="e-title">Pertemuan Wali Santri Triwulan</p>
          <p class="e-meta">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            09.30 – 11.30 · Ruang Serbaguna
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
