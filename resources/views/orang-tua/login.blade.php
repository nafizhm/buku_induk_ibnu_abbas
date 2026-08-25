<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
<title>Masuk — Portal Orang Tua Rumah Qur'an Ibnu Abbas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --purple-950:#241242;
    --purple-900:#331b5c;
    --purple-800:#472776;
    --purple-700:#5c34a0;
    --purple-600:#7247c4;
    --purple-500:#8f64dd;
    --purple-400:#ab86e8;
    --purple-300:#c6acf0;
    --purple-100:#ece1fb;
    --purple-50:#f7f2fd;
    --gold:#cf9f4f;
    --gold-light:#f1decf;
    --ink:#231934;
    --ink-soft:#5c5270;
    --muted:#918aa3;
    --bg:#f6f2fb;
    --card:#ffffff;
    --line:#e9e1f5;
    --danger:#cf5164;
    --danger-bg:#fbe6ea;
    --radius-lg:28px;
    --radius-md:18px;
    --radius-sm:12px;
    --shadow-sm: 0 2px 10px rgba(53,20,102,0.06);
    --shadow-md: 0 10px 30px rgba(53,20,102,0.14);
    --font-display:'Cairo', system-ui, sans-serif;
    --font-body:'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  }

  *{box-sizing:border-box; -webkit-tap-highlight-color:transparent;}
  html,body{margin:0; padding:0;}
  body{
    font-family:var(--font-body);
    background:var(--purple-950);
    color:var(--ink);
    -webkit-font-smoothing:antialiased;
    min-height:100vh;
    overflow:hidden;
  }
  img{max-width:100%; display:block;}
  button{font-family:inherit; cursor:pointer;}
  input{font-family:inherit;}
  a{color:inherit;}
  ::selection{background:var(--purple-300);}

  .shell{
    max-width:480px;
    margin:0 auto;
    min-height:100vh;height:100vh;overflow:hidden;
    display:flex;
    flex-direction:column;
    background:var(--bg);
    position:relative;
  }

  /* ---------- Hero ---------- */
  .hero{
    background:radial-gradient(130% 150% at 20% 0%, var(--purple-600) 0%, var(--purple-800) 55%, var(--purple-950) 100%);
    padding:20px 22px 24px;
    color:#fff;
    text-align:center;
    position:relative;
    overflow:hidden;
    flex-shrink:0;
  }
  .hero::before{
    content:""; position:absolute; top:-70px; right:-50px;
    width:220px; height:220px; border-radius:50%;
    background:rgba(255,255,255,0.06);
  }
  .hero::after{
    content:""; position:absolute; bottom:-30px; left:-40px;
    width:160px; height:160px; border-radius:50%;
    background:rgba(207,159,79,0.14);
  }

  /* subtle arch skyline motif in the hero background */
  .hero-arches{
    position:absolute; left:0; right:0; bottom:0; height:64px;
    opacity:0.08; z-index:0;
  }

  .hero-inner{ position:relative; z-index:1; }
  .logo-badge{
    width:58px; height:58px; margin:0 auto 10px;
    background:#fff; border-radius:24px; padding:10px;
    box-shadow:var(--shadow-md);
  }
  .logo-badge img{ width:100%; height:100%; object-fit:contain; border-radius:16px; }

  .hero-inner .eyebrow{
    font-size:11px; letter-spacing:.16em; text-transform:uppercase;
    color:var(--purple-300); font-weight:700; margin:0 0 6px;
  }
  .hero-inner h1{
    font-family:var(--font-display); font-weight:800; font-size:21px;
    margin:0 0 8px; line-height:1.25;
  }
  .hero-inner p{
    font-size:12.5px; color:var(--purple-300); margin:0;
    line-height:1.5; max-width:280px; margin:0 auto;
  }

  .arch-edge{ display:block; width:100%; height:26px; margin-top:-1px; flex-shrink:0; }
  .arch-edge path{ fill:var(--bg); }

  /* ---------- Form card ---------- */
  .form-wrap{
    flex:1;
    padding:18px 24px 20px;
    display:flex;
    flex-direction:column;
  }
  .form-head{ margin-bottom:22px; }
  .form-head h2{
    font-family:var(--font-display); font-weight:800; font-size:19px; margin:0 0 5px;
  }
  .form-head p{ font-size:12.5px; color:var(--muted); margin:0; }

  .form-group{ margin-bottom:16px; }
  .form-group label{
    display:block; font-size:12px; font-weight:700; color:var(--ink-soft); margin-bottom:7px;
  }
  .input-wrap{
    position:relative; display:flex; align-items:center;
  }
  .input-wrap .field-icon{
    position:absolute; left:14px; width:17px; height:17px; stroke:var(--muted); pointer-events:none;
  }
  .form-group input{
    width:100%; padding:13px 14px 13px 42px; border-radius:14px;
    border:1.5px solid var(--line); background:var(--purple-50);
    font-size:14px; color:var(--ink); outline:none;
    transition:border-color .15s, background .15s;
  }
  .form-group input:focus{ border-color:var(--purple-500); background:#fff; }
  .form-group.has-toggle input{ padding-right:44px; }
  .toggle-visibility{
    position:absolute; right:6px; width:34px; height:34px; border-radius:10px;
    background:transparent; border:none; display:flex; align-items:center; justify-content:center;
  }
  .toggle-visibility svg{ width:18px; height:18px; stroke:var(--muted); }

  .field-error{
    font-size:11px; color:var(--danger); margin:6px 0 0; display:none; font-weight:600;
  }
  .form-group.invalid input{ border-color:var(--danger); background:var(--danger-bg); }
  .form-group.invalid .field-error{ display:block; }

  .row-between{
    display:flex; align-items:center; justify-content:space-between;
    margin:2px 0 22px; font-size:12.5px;
  }
  .remember{ display:flex; align-items:center; gap:8px; color:var(--ink-soft); font-weight:600; }
  .remember input{
    width:17px; height:17px; accent-color:var(--purple-700); border-radius:5px; cursor:pointer;
  }
  .forgot-link{ color:var(--purple-600); font-weight:700; text-decoration:none; }

  .btn-primary{
    width:100%; padding:15px; border:none; border-radius:16px;
    background:linear-gradient(120deg, var(--purple-600), var(--purple-800));
    color:#fff; font-weight:800; font-size:14.5px; font-family:var(--font-display);
    box-shadow:0 10px 22px rgba(114,71,196,0.35);
    display:flex; align-items:center; justify-content:center; gap:8px;
    margin-bottom:22px;
  }
  .btn-primary svg{ width:17px; height:17px; stroke:#fff; }
  .btn-primary:active{ transform:translateY(1px); }

  .divider{
    display:flex; align-items:center; gap:12px; margin:0 0 22px;
    font-size:11px; color:var(--muted); font-weight:700; text-transform:uppercase; letter-spacing:.05em;
  }
  .divider::before, .divider::after{
    content:""; flex:1; height:1px; background:var(--line);
  }

  .btn-secondary{
    width:100%; padding:13px; border-radius:16px;
    background:#fff; border:1.5px solid var(--line);
    color:var(--ink); font-weight:700; font-size:13.5px;
    display:flex; align-items:center; justify-content:center; gap:10px;
    margin-bottom:12px;
  }
  .btn-secondary svg{ width:18px; height:18px; flex-shrink:0; }
  .btn-secondary:last-of-type{ margin-bottom:0; }

  .footer-note{
    margin-top:auto; padding-top:26px; text-align:center;
  }
  .footer-note p{ font-size:12.5px; color:var(--ink-soft); margin:0 0 4px; }
  .footer-note a{ color:var(--purple-700); font-weight:800; text-decoration:none; }

  .terms-note{
    text-align:center; font-size:10.5px; color:var(--muted); line-height:1.6; margin-top:16px;
  }
  .terms-note a{ color:var(--purple-600); font-weight:700; text-decoration:none; }

  /* ---------- Toast ---------- */
  .toast{
    position:fixed; left:50%; bottom:28px; transform:translate(-50%, 20px);
    background:var(--purple-950); color:#fff; font-size:13px; font-weight:600;
    padding:12px 18px; border-radius:14px; display:flex; align-items:center; gap:8px;
    box-shadow:var(--shadow-md); opacity:0; pointer-events:none; transition:.25s;
    max-width:400px; z-index:100;
  }
  .toast svg{ width:16px; height:16px; stroke:var(--gold); flex-shrink:0;}
  .toast.show{ opacity:1; transform:translate(-50%, 0); }
  .row-between,.divider,.btn-secondary,.footer-note{display:none!important}
  .form-head{margin-bottom:12px}.form-group{margin-bottom:11px}.btn-primary{margin-bottom:0;padding:12px}
</style>
</head>
<body>
<div class="shell">

  <div class="hero">
    <svg class="hero-arches" viewBox="0 0 400 64" preserveAspectRatio="none">
      <path fill="#fff" d="M0,64 L0,30 Q12,4 24,30 Q36,4 48,30 Q60,4 72,30 Q84,4 96,30 Q108,4 120,30 Q132,4 144,30 Q156,4 168,30 Q180,4 192,30 Q204,4 216,30 Q228,4 240,30 Q252,4 264,30 Q276,4 288,30 Q300,4 312,30 Q324,4 336,30 Q348,4 360,30 Q372,4 384,30 Q396,4 400,20 L400,64 Z"/>
    </svg>
    <div class="hero-inner">
      <div class="logo-badge">
        <img src="data:image/jpeg;base64,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" alt="Logo Rumah Qur'an Ibnu Abbas">
      </div>
      <p class="eyebrow">Portal Orang Tua</p>
      <h1>Rumah Qur'an Ibnu Abbas</h1>
      <p>Pantau presensi, kegiatan, dan hafalan putra-putri Anda dalam satu genggaman.</p>
    </div>
  </div>
  <svg class="arch-edge" viewBox="0 0 400 26" preserveAspectRatio="none">
    <path d="M0,26 L0,14 Q10,0 20,14 Q30,0 40,14 Q50,0 60,14 Q70,0 80,14 Q90,0 100,14 Q110,0 120,14 Q130,0 140,14 Q150,0 160,14 Q170,0 180,14 Q190,0 200,14 Q210,0 220,14 Q230,0 240,14 Q250,0 260,14 Q270,0 280,14 Q290,0 300,14 Q310,0 320,14 Q330,0 340,14 Q350,0 360,14 Q370,0 380,14 Q390,0 400,14 L400,26 Z"/>
  </svg>

  <div class="form-wrap">
    <div class="form-head">
      <h2>Masuk ke Akun</h2>
      <p>Gunakan nomor HP yang terdaftar di sekolah</p>
    </div>

    <form id="loginForm" action="{{ route('admin.loginPost') }}" method="POST">
      @csrf
      <div class="form-group" id="groupIdentity">
        <label for="no_telepon">Nomor HP / Username</label>
        <div class="input-wrap">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
          <input type="text" id="no_telepon" name="username" value="{{ old('username') }}" placeholder="Username atau nomor HP" autocomplete="username" required>
        </div>
        <p class="field-error">{{ $errors->first('username', 'Nomor HP / username wajib diisi.') }}</p>
      </div>

      <div class="form-group has-toggle" id="groupPassword">
        <label for="password">Kata Sandi</label>
        <div class="input-wrap">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2.5"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
          <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
          <button type="button" class="toggle-visibility" id="toggleVisibility" aria-label="Tampilkan kata sandi">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <p class="field-error">Kata sandi wajib diisi.</p>
      </div>

      <div class="row-between">
        <label class="remember">
          <input type="checkbox" id="remember" name="remember" value="1">
          Ingat saya
        </label>
        <a href="#" class="forgot-link">Lupa kata sandi?</a>
      </div>

      <button type="submit" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
        Masuk
      </button>
    </form>

    <div class="divider">atau masuk dengan</div>
    <button type="button" class="btn-secondary" onclick="showToast('Fitur masuk via WhatsApp segera hadir')">
      <svg viewBox="0 0 24 24" fill="#25D366"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.28-1.39a9.9 9.9 0 0 0 4.76 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.96 6.45 17.5 2 12.04 2Zm0 18.1h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.13.82.84-3.05-.2-.31a8.19 8.19 0 0 1-1.26-4.4c0-4.53 3.7-8.22 8.26-8.22 2.2 0 4.27.86 5.83 2.42a8.17 8.17 0 0 1 2.41 5.81c0 4.53-3.7 8.22-8.25 8.22Zm4.53-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.4-.12-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.04-.38-1.99-1.22-.73-.66-1.23-1.46-1.37-1.71-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.44-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.42-.14-.01-.31-.01-.48-.01-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.09s.9 2.43 1.02 2.6c.12.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.1-.23-.16-.48-.28Z"/></svg>
      Masuk dengan WhatsApp
    </button>

    <div class="footer-note">
      <p>Belum punya akun?</p>
      <a href="#" onclick="showToast('Hubungi admin sekolah untuk pendaftaran akun'); return false;">Daftar sebagai Orang Tua / Wali</a>
    </div>

    <p class="terms-note">
      Dengan masuk, Anda menyetujui <a href="#">Ketentuan Layanan</a> dan <a href="#">Kebijakan Privasi</a> Rumah Qur'an Ibnu Abbas.
    </p>
  </div>
</div>

<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><circle cx="12" cy="12" r="9"/></svg>
  <span id="toastMsg">Info</span>
</div>

<script>
  const passwordInput = document.getElementById('password');
  const toggleBtn = document.getElementById('toggleVisibility');
  toggleBtn.addEventListener('click', () => {
    const show = passwordInput.type === 'password';
    passwordInput.type = show ? 'text' : 'password';
    toggleBtn.innerHTML = show
      ? '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a19.7 19.7 0 0 1 5.06-5.94M9.9 4.24A10.4 10.4 0 0 1 12 4c7 0 11 8 11 8a19.7 19.7 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>'
      : '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
  });

  let toastTimer;
  function showToast(msg){
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2800);
  }

  document.getElementById('loginForm').addEventListener('submit', function(e){
    const identity = document.getElementById('no_telepon');
    const password = document.getElementById('password');
    const groupIdentity = document.getElementById('groupIdentity');
    const groupPassword = document.getElementById('groupPassword');

    let valid = true;
    if(identity.value.trim() === ''){
      groupIdentity.classList.add('invalid');
      valid = false;
    } else {
      groupIdentity.classList.remove('invalid');
    }
    if(password.value.trim() === ''){
      groupPassword.classList.add('invalid');
      valid = false;
    } else {
      groupPassword.classList.remove('invalid');
    }

    if(valid){
      return;
      showToast('Berhasil masuk. Mengalihkan ke beranda…');
    } else {
      e.preventDefault();
    }
  });

  @error('username')
    document.getElementById('groupIdentity').classList.add('invalid');
  @enderror
  @error('password')
    document.getElementById('groupPassword').classList.add('invalid');
  @enderror

  document.getElementById('no_telepon').addEventListener('input', function(){
    document.getElementById('groupIdentity').classList.remove('invalid');
  });
  document.getElementById('password').addEventListener('input', function(){
    document.getElementById('groupPassword').classList.remove('invalid');
  });
</script>
</body>
</html>
