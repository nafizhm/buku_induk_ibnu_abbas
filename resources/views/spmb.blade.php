<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#3b1f78">
<title>SPMB Rumah Qur'an Ibnu Abbas 2026/2027</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --p900:#2e1766; --p800:#3b1f78; --p700:#4c2a94; --p600:#6136b8; --p500:#7b52d3;
  --p100:#ece5fa; --p50:#f6f2fd;
  --gold:#c9a24a; --gold-soft:#f3e8c8;
  --bg:#faf8fe; --card:#ffffff; --ink:#1f1a2e; --muted:#575068; --line:#e3dcf3;
  --ok:#1f8a4c; --err:#c0392b;
  box-sizing:border-box;
  padding-top:env(safe-area-inset-top,0px);
  padding-bottom:env(safe-area-inset-bottom,0px);
}
@media (prefers-color-scheme:dark){
  :root:not([data-theme="light"]){--bg:#15101f;--card:#1f1830;--ink:#efeaf9;--muted:#b5accb;--line:#332a4b;--p50:#251c3b;--p100:#2e2348;}
}
:root[data-theme="dark"]{--bg:#15101f;--card:#1f1830;--ink:#efeaf9;--muted:#b5accb;--line:#332a4b;--p50:#251c3b;--p100:#2e2348;}
html{scroll-padding-top:64px;scroll-behavior:smooth;-webkit-tap-highlight-color:transparent}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font-family:'Plus Jakarta Sans',-apple-system,sans-serif;font-size:16.5px;line-height:1.65;-webkit-font-smoothing:antialiased;padding-bottom:84px}
h1,h2,h3{font-family:'Amiri',Georgia,serif;margin:0;font-weight:700}
p{margin:0}
.wrap{padding:0 20px;max-width:640px;margin:0 auto}

/* top bar */
.top{position:sticky;top:0;z-index:40;background:var(--p900);color:#fff;padding-top:env(safe-area-inset-top,0px)}
.top-in{display:flex;align-items:center;gap:10px;padding:9px 20px;max-width:640px;margin:0 auto}
.top img{width:34px;height:34px;border-radius:9px;background:#fff;padding:2px}
.top b{font-family:'Amiri',serif;font-size:17px;letter-spacing:.2px}

/* hero */
.hero{background:radial-gradient(120% 90% at 50% 0%,var(--p600) 0%,var(--p800) 55%,var(--p900) 100%);color:#fff;padding:34px 0 40px;text-align:center;position:relative;overflow:hidden}
.hero::after{content:"";position:absolute;left:50%;bottom:-140px;width:420px;height:420px;transform:translateX(-50%);border:1px solid rgba(201,162,74,.35);border-radius:50%;box-shadow:0 0 0 26px rgba(255,255,255,.03),0 0 0 52px rgba(255,255,255,.025)}
.hero .wrap{position:relative;z-index:1}
.logo{width:92px;height:92px;border-radius:24px;background:#fff;padding:6px;margin:0 auto 18px;box-shadow:0 10px 30px rgba(0,0,0,.3)}
.badge{display:inline-block;background:rgba(201,162,74,.18);border:1px solid rgba(201,162,74,.55);color:var(--gold-soft);font-size:13.5px;font-weight:600;padding:6px 14px;border-radius:99px}
.hero h1{font-size:36px;line-height:1.15;margin:14px 0 10px}
.hero h1 span{color:var(--gold)}
.hero p.sub{color:#ddd3f5;font-size:16.5px;max-width:440px;margin:0 auto}
.cta{display:block;width:100%;max-width:420px;margin:24px auto 0;background:linear-gradient(180deg,#e0bd63,var(--gold));color:#2b1a05;border:0;border-radius:14px;padding:16px;font:700 17px 'Plus Jakarta Sans',sans-serif;text-decoration:none;text-align:center;box-shadow:0 10px 24px rgba(0,0,0,.3)}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:26px}
.stat{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:14px;padding:12px 4px}
.stat b{display:block;font-family:'Amiri',serif;font-size:26px;color:#fff;line-height:1.1}
.stat small{font-size:12.5px;color:#cfc3ee}

/* sections */
section{padding:34px 0 6px}
.eyebrow{font-size:13px;font-weight:700;color:var(--p600);letter-spacing:.04em}
h2{font-size:27px;color:var(--p800);margin:4px 0 6px}
:root[data-theme="dark"] h2{color:#fff}
.lead{color:var(--muted);font-size:15.5px;margin-bottom:16px}
.card{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:18px;margin-bottom:12px}
.prog{display:flex;gap:14px;align-items:flex-start}
.prog .n{flex:none;width:38px;height:38px;border-radius:12px;background:var(--p100);color:var(--p700);display:grid;place-items:center;font:700 18px 'Amiri',serif}
.prog h3{font:700 16.5px 'Plus Jakarta Sans',sans-serif;margin-bottom:2px}
.prog p{font-size:15px;color:var(--muted)}
.steps{counter-reset:s}
.step{display:flex;gap:14px;padding:14px 0;border-bottom:1px dashed var(--line)}
.step:last-child{border:0}
.step::before{counter-increment:s;content:counter(s);flex:none;width:30px;height:30px;border-radius:50%;background:var(--p700);color:#fff;display:grid;place-items:center;font-weight:700;font-size:14px;margin-top:2px}
.step b{display:block;font-size:16px}
.step span{font-size:14.5px;color:var(--muted)}
.row{display:flex;justify-content:space-between;gap:12px;padding:12px 0;border-bottom:1px solid var(--line);font-size:15.5px}
.row:last-child{border:0}
.row b{white-space:nowrap;color:var(--p700)}
:root[data-theme="dark"] .row b{color:#cdb9ff}
.note{font-size:14px;color:var(--muted);margin-top:8px}
.chips{display:flex;flex-wrap:wrap;gap:8px}
.chip{background:var(--p100);color:var(--p800);border-radius:99px;padding:7px 13px;font-size:14.5px;font-weight:600}
:root[data-theme="dark"] .chip{color:#dccffa}
@media (prefers-color-scheme:dark){
  :root:not([data-theme="light"]) h2{color:#fff}
  :root:not([data-theme="light"]) .row b{color:#cdb9ff}
  :root:not([data-theme="light"]) .chip{color:#dccffa}
}
.docs li{padding:9px 0;border-bottom:1px solid var(--line);list-style:none;font-size:15.5px}
.docs{margin:0;padding:0}
.docs li:last-child{border:0}
a.map{color:var(--p600);font-weight:700;text-decoration:none}

/* form */
.formbox{background:linear-gradient(180deg,var(--p50),var(--bg));border-top:1px solid var(--line);margin-top:30px;padding:30px 0 20px}
.progress{display:flex;gap:6px;margin:14px 0 4px}
.progress i{flex:1;height:6px;border-radius:9px;background:var(--line)}
.progress i.on{background:var(--p600)}
.stepname{font-size:14px;color:var(--muted);margin-bottom:12px;font-weight:600}
.pane{display:none}.pane.show{display:block}
.f{margin-bottom:16px}
.f label.l{display:block;font-weight:700;font-size:15px;margin-bottom:6px}
.f label.l em{color:var(--err);font-style:normal}
.f input[type=text],.f input[type=tel],.f input[type=date],.f textarea,.f select{width:100%;font:500 16px 'Plus Jakarta Sans',sans-serif;padding:14px;border:1.5px solid var(--line);border-radius:12px;background:var(--card);color:var(--ink);-webkit-appearance:none;appearance:none}
.f textarea{min-height:88px;resize:vertical}
.f input:focus,.f textarea:focus{outline:3px solid rgba(123,82,211,.35);border-color:var(--p500)}
.seg{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.seg label{position:relative}
.seg input{position:absolute;opacity:0}
.seg span{display:block;text-align:center;padding:13px;border:1.5px solid var(--line);border-radius:12px;font-weight:600;background:var(--card)}
.seg input:checked+span{background:var(--p700);color:#fff;border-color:var(--p700)}
.warn{display:none;background:#fdecea;color:#8a2116;border-radius:10px;padding:10px 12px;font-size:14px;margin-top:8px}
.up{display:flex;align-items:center;gap:12px;background:var(--card);border:1.5px dashed var(--p500);border-radius:14px;padding:14px;cursor:pointer}
.up input{display:none}
.up .ic{flex:none;width:42px;height:42px;border-radius:12px;background:var(--p100);color:var(--p700);display:grid;place-items:center;font-size:20px}
.up .t{min-width:0;flex:1}
.up .t b{display:block;font-size:15px}
.up .t small{display:block;color:var(--muted);font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.up.done{border-style:solid;border-color:var(--ok);background:rgba(31,138,76,.07)}
.up.done .ic{background:var(--ok);color:#fff}
.pay{background:var(--gold-soft);color:#4a3508;border-radius:14px;padding:14px;margin-bottom:16px;font-size:15px}
.pay b{font-size:19px}
.ck{display:flex;gap:12px;align-items:flex-start;margin-bottom:12px;font-size:15px}
.ck input{width:22px;height:22px;flex:none;accent-color:var(--p700);margin-top:2px}
.btns{display:flex;gap:10px;margin-top:8px}
.btn{flex:1;border:0;border-radius:14px;padding:16px;font:700 16.5px 'Plus Jakarta Sans',sans-serif;cursor:pointer}
.btn.pri{background:var(--p700);color:#fff}
.btn.sec{flex:.55;background:var(--p100);color:var(--p800)}
.btn.send{background:linear-gradient(180deg,#e0bd63,var(--gold));color:#2b1a05}
.success{display:none;text-align:center;padding:26px 8px}
.success .ok{width:70px;height:70px;border-radius:50%;background:var(--ok);color:#fff;display:grid;place-items:center;font-size:34px;margin:0 auto 14px}

footer{text-align:center;padding:26px 20px;color:var(--muted);font-size:13.5px}

/* sticky bottom CTA */
.dock{position:fixed;left:0;right:0;bottom:0;z-index:50;background:var(--card);border-top:1px solid var(--line);padding:10px 16px calc(10px + env(safe-area-inset-bottom,0px));display:flex;gap:10px;align-items:center;box-shadow:0 -6px 20px rgba(46,23,102,.12)}
.dock div{flex:1;font-size:13px;line-height:1.3;color:var(--muted)}
.dock div b{display:block;color:var(--ink);font-size:14.5px}
.dock a{background:var(--p700);color:#fff;text-decoration:none;font-weight:700;padding:13px 22px;border-radius:12px;font-size:15.5px}
@media (prefers-reduced-motion:reduce){html{scroll-behavior:auto}}
@media (min-width:700px){.wrap,.top-in{max-width:680px}}

.vid{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;opacity:.55}
.hero::before{content:"";position:absolute;inset:0;z-index:0;background:linear-gradient(180deg,rgba(46,23,102,.55),rgba(46,23,102,.82));pointer-events:none}
.hero::before{z-index:0}
.hero .wrap{z-index:2}
.hero::after{z-index:1}
.gal{display:flex;gap:12px;overflow-x:auto;scroll-snap-type:x mandatory;margin:0 -20px;padding:4px 20px 14px;-webkit-overflow-scrolling:touch;scrollbar-width:none}
.gal::-webkit-scrollbar{display:none}
.shot{flex:none;width:78%;max-width:300px;scroll-snap-align:center;border-radius:20px;overflow:hidden;background:var(--card);border:1px solid var(--line);box-shadow:0 10px 24px rgba(46,23,102,.14)}
.shot img{display:block;width:100%;aspect-ratio:4/3;object-fit:cover}
.shot span{display:block;padding:11px 14px;font-weight:700;font-size:15px}
.hint{font-size:13px;color:var(--muted)}
.rv{opacity:0;transform:translateY(18px);transition:opacity .6s ease,transform .6s ease}
.rv.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.rv{opacity:1;transform:none;transition:none}}
.mapcard{display:block;position:relative;border-radius:20px;overflow:hidden;border:1px solid var(--line);box-shadow:0 10px 24px rgba(46,23,102,.14);text-decoration:none}
.mapcard svg{display:block;width:100%;height:240px}
.mapbadge{position:absolute;left:12px;bottom:12px;background:var(--p800);color:#fff;font-weight:700;font-size:14.5px;padding:10px 16px;border-radius:99px}
</style>
</head>
<body>

<div class="top"><div class="top-in"><img src="{{ asset('assets/spmb/logo.jpg') }}" alt="Logo"><b>Rumah Qur'an Ibnu Abbas</b></div></div>

<header class="hero">
  <video class="vid" autoplay muted loop playsinline preload="auto" src="{{ asset('assets/spmb/hero-bg.mp4') }}"></video>
  <div class="wrap">
    <img class="logo" src="{{ asset('assets/spmb/logo.jpg') }}" alt="Logo Rumah Qur'an Ibnu Abbas">
    <span class="badge">Pendaftaran Online · TP 2026/2027</span>
    <h1>Penerimaan <span>Santri Baru</span></h1>
    <p class="sub">PKBM Ibnu Abbas · Paket Kesetaraan A (setara SD). Daftar dari rumah, cukup lewat HP.</p>
    <a class="cta" href="#daftar">Daftar Sekarang</a>
    <div class="stats">
      <div class="stat"><b>14</b><small>Kuota Putra (Banin)</small></div>
      <div class="stat"><b>16</b><small>Kuota Putri (Banat)</small></div>
      <div class="stat"><b>1 Okt</b><small>Dibuka</small></div>
    </div>
  </div>
</header>

<main class="wrap">
<section>
  <div class="eyebrow">VIDEO PROFIL</div>
  <h2>Kenali lebih dekat</h2>
  <p class="lead">Tonton gambaran kegiatan belajar di Rumah Qur'an Ibnu Abbas.</p>
  <div class="card" style="padding:10px">
    <video controls playsinline preload="metadata" style="width:100%;border-radius:12px;display:block;background:#1f1140" src="{{ asset('assets/spmb/video-profil.mp4') }}"></video>
  </div>
</section>

<section>
  <div class="eyebrow">SUASANA SEKOLAH</div>
  <h2>Sekilas Rumah Qur'an</h2>
  <p class="lead">Geser untuk melihat lingkungan belajar santri.</p>
  <div class="gal">
    <figure class="shot" style="margin:0"><img src="{{ asset('assets/spmb/galeri-1.svg') }}" alt="Masjid"><span>Masjid yang nyaman</span></figure>
    <figure class="shot" style="margin:0"><img src="{{ asset('assets/spmb/galeri-2.svg') }}" alt="Ruang kelas"><span>Kelas permanen ber-AC</span></figure>
    <figure class="shot" style="margin:0"><img src="{{ asset('assets/spmb/galeri-3.svg') }}" alt="Halaqah"><span>Halaqah Al-Qur'an</span></figure>
    <figure class="shot" style="margin:0"><img src="{{ asset('assets/spmb/galeri-4.svg') }}" alt="Gedung"><span>Lingkungan sekolah</span></figure>
  </div>
</section>

<section>
  <div class="eyebrow">PROGRAM UNGGULAN</div>
  <h2>Tumbuh bersama Al-Qur'an</h2>
  <p class="lead">Ditambah materi diniyah dan umum sebagai program pendukung.</p>
  <div class="card prog"><div class="n">١</div><div><h3>Al-Qiroatu Lil Athfal</h3><p>Bina baca Al-Qur'an untuk anak.</p></div></div>
  <div class="card prog"><div class="n">٢</div><div><h3>Hafalan Al-Qur'an 3 Juz</h3><p>Juz 28, 29, dan 30.</p></div></div>
  <div class="card prog"><div class="n">٣</div><div><h3>Hafalan Hadits Arbain Nawawi</h3><p>Hafalan hadits pilihan secara bertahap.</p></div></div>
</section>

<section>
  <div class="eyebrow">ALUR PENDAFTARAN</div>
  <h2>3 langkah, semua online</h2>
  <div class="card steps">
    <div class="step"><div><b>Isi form awal &amp; upload bukti transfer</b><span>Langsung di halaman ini, biaya pendaftaran Rp300.000.</span></div></div>
    <div class="step"><div><b>Admin verifikasi &amp; kirim link formulir</b><span>Link formulir lengkap dikirim lewat WhatsApp.</span></div></div>
    <div class="step"><div><b>Isi formulir &amp; upload berkas</b><span>Lengkapi data dan berkas lewat link dari WhatsApp.</span></div></div>
  </div>
</section>

<section>
  <div class="eyebrow">SYARAT</div>
  <h2>Ketentuan pendaftar</h2>
  <div class="card">
    <div class="chips"><span class="chip">Putra (Banin) &amp; Putri (Banat)</span><span class="chip">Usia min. 6,5 th (Juni 2027)</span><span class="chip">Observasi santri</span><span class="chip">Wawancara ortu</span></div>
    <p class="note">Pendaftaran otomatis ditutup saat kuota terpenuhi. Mohon maaf, belum dapat menerima calon santri berkebutuhan khusus.</p>
  </div>
</section>

<section>
  <div class="eyebrow">BIAYA</div>
  <h2>Rincian biaya</h2>
  <div class="card">
    <div class="row"><span>Sarpras Putra (Banin)</span><b>Rp12.500.000</b></div>
    <div class="row"><span>Sarpras Putri (Banat)</span><b>Rp13.000.000</b></div>
    <div class="row"><span>SPP bulanan</span><b>Rp650.000</b></div>
    <div class="row"><span>Uang tahunan (naik kelas)</span><b>Rp1.000.000</b></div>
    <div class="row"><span>Uang buku (naik kelas)</span><b>Sesuai buku</b></div>
    <p class="note">Sarpras sudah termasuk uang pangkal, pembangunan, seragam 4 stel, buku kelas 1 &amp; sampul rapor. SPP tanpa makan siang &amp; snack. Bila diterima, bayar sarpras 100% atau minimal 50% dahulu, sisanya paling lambat 2 Januari 2027. Iuran komite jika ada.</p>
  </div>
</section>

<section>
  <div class="eyebrow">JADWAL &amp; FASILITAS</div>
  <h2>Belajar Senin–Jumat</h2>
  <div class="card">
    <div class="row"><span>Kelas 1 &amp; 2</span><b>08.00–12.00 WITA</b></div>
    <div class="row"><span>Kelas 3 &amp; 4</span><b>08.00–13.00 WITA</b></div>
    <div class="row"><span>Kelas 5 &amp; 6</span><b>08.00–14.00 WITA</b></div>
    <p class="note">Sabtu &amp; Ahad libur. Fasilitas: masjid nyaman dan ruang kelas permanen ber-AC.</p>
  </div>
</section>

<section>
  <div class="eyebrow">LOKASI</div>
  <h2>Kunjungi kami</h2>
  <a class="mapcard" href="https://maps.app.goo.gl/5oeJ9Yhw5zs2K4YA6" target="_blank" rel="noopener" aria-label="Buka lokasi di Google Maps">
    <svg viewBox="0 0 600 360" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
      <rect width="600" height="360" fill="#ece5fa"/>
      <path d="M0 250 C120 210 200 290 330 250 S520 200 600 230 V360 H0Z" fill="#cfe6f5"/>
      <rect x="40" y="40" width="130" height="90" rx="14" fill="#cfe8d2"/>
      <rect x="430" y="60" width="120" height="80" rx="14" fill="#cfe8d2"/>
      <g stroke="#fff" stroke-linecap="round" fill="none"><path d="M-10 170 H610" stroke-width="16"/><path d="M300 -10 V200" stroke-width="16"/><path d="M0 60 L250 170" stroke-width="9"/><path d="M420 170 L600 100" stroke-width="9"/><path d="M110 170 V300" stroke-width="9"/></g>
      <ellipse cx="300" cy="196" rx="26" ry="8" fill="rgba(46,23,102,.25)"/>
      <path d="M300 190 C270 150 262 128 262 112 a38 38 0 0 1 76 0 c0 16 -8 38 -38 78z" fill="#4c2a94"/>
      <circle cx="300" cy="112" r="14" fill="#c9a24a"/>
    </svg>
    <span class="mapbadge">Buka petunjuk arah →</span>
  </a>
  <div class="card" style="margin-top:12px">
    <b>Rumah Qur'an Ibnu Abbas</b>
    <p style="margin-top:4px">Jalan Satu Kampung Timur, Kel. Gunung Samarinda, Kec. Balikpapan Utara, Kaltim.</p>
    <p class="note">Admin: 0819-0505-9919</p>
  </div>
</section>
</main>

<div class="formbox" id="daftar">
<div class="wrap">
  <div class="eyebrow">PENDAFTARAN AWAL</div>
  <h2>Isi form awal</h2>
  <p class="lead">Lengkapi data singkat dan upload bukti transfer. Setelah diverifikasi, admin mengirim link formulir lengkap lewat WhatsApp.</p>
  <div id="formArea">
    <form id="frm" action="{{ route('spmb.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="f"><label class="l">Nama calon siswa <em>*</em></label><input type="text" name="nama" required autocomplete="off"></div>
      <div class="f"><label class="l">Jenis kelamin <em>*</em></label>
        <div class="seg"><label><input type="radio" name="jk" value="Putra (Banin)" checked><span>Putra (Banin)</span></label><label><input type="radio" name="jk" value="Putri (Banat)"><span>Putri (Banat)</span></label></div></div>
      <div class="f"><label class="l">Nama orang tua/wali <em>*</em></label><input type="text" name="ortu" required></div>
      <div class="f"><label class="l">No. WhatsApp aktif <em>*</em></label><input type="tel" name="wa" required inputmode="tel" placeholder="08xxxxxxxxxx"></div>
      <div class="pay">Biaya pendaftaran<br><b>Rp300.000</b><br><span style="font-size:13.5px">Transfer ke rekening resmi sekolah (hubungi admin bila belum tahu nomornya), lalu upload bukti transfer di bawah.</span></div>
      <div class="f"><label class="up"><input type="file" name="bukti" accept="image/jpeg,image/png,image/webp,application/pdf"><span class="ic">🧾</span><span class="t"><b>Upload bukti transfer *</b><small>Ketuk untuk pilih foto/PDF</small></span></label></div>
      <div class="warn" id="formError" role="alert"></div>
      <div class="btns"><button type="submit" class="btn send" id="send">Kirim</button></div>
    </form>
  </div>
  <div class="success" id="ok">
    <div class="ok">✓</div>
    <h2>Alhamdulillah, data terkirim</h2>
    <p class="lead">Admin akan memverifikasi dan mengirim link formulir lengkap lewat WhatsApp.</p>
  </div>
</div>
</div>

<footer>Rumah Qur'an Ibnu Abbas · PKBM Ibnu Abbas · NPSN P2971339</footer>

<div class="dock"><div><b>Kuota terbatas</b>Banin 14 · Banat 16</div><a href="#daftar">Daftar</a></div>

<script>
const $=id=>document.getElementById(id);

document.querySelectorAll('.up input').forEach(inp=>{
  inp.addEventListener('change',()=>{
    const lab=inp.closest('.up'), f=inp.files[0];
    if(!f)return;
    if(f.size>10*1024*1024){alert('Ukuran file maksimal 10 MB.');inp.value='';return;}
    lab.classList.add('done'); lab.querySelector('.ic').textContent='✓';
    lab.querySelector('small').textContent=f.name;
  });
});

$('frm').onsubmit=async(event)=>{
  event.preventDefault();
  if($('send').disabled)return;
  const fr=$('frm');
  $('formError').style.display='none';
  for(const el of fr.querySelectorAll('input[required]')){
    if(!el.value.trim()){el.focus();el.reportValidity&&el.reportValidity();return;}
  }
  if(!fr.bukti.files.length){alert('Mohon upload bukti transfer.');return;}
  const fd=new FormData(fr);
  $('send').disabled=true; $('send').textContent='Mengirim...';
  try{
    const r=await fetch(fr.action,{method:'POST',body:fd,headers:{Accept:'application/json'}});
    const data=await r.json().catch(()=>({}));
    if(!r.ok){
      const message=r.status===422 ? Object.values(data.errors||{}).flat().join('\n')
        : r.status===419 ? 'Sesi berakhir. Muat ulang halaman sebelum mengirim kembali.'
        : r.status===413 ? 'Ukuran unggahan terlalu besar. Pilih file yang lebih kecil.'
        : r.status===429 ? 'Terlalu banyak pengiriman. Tunggu sebentar lalu coba lagi.'
        : 'Data belum dapat disimpan. Silakan coba lagi.';
      throw new Error(message);
    }
    window.location.assign(data.redirect);
  }catch(e){
    $('formError').textContent=e.message||'Pengiriman gagal. Periksa koneksi lalu coba lagi.';
    $('formError').style.display='block';
    $('send').disabled=false;$('send').textContent='Kirim';
  }
};

const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.12});
document.querySelectorAll('main section').forEach(s=>{s.classList.add('rv');io.observe(s)});
const vv=document.querySelector('.vid'); if(vv&&vv.play){vv.play().catch(()=>{});}
</script>
</body>
</html>
