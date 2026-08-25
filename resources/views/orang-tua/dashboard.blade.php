<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
<title>Portal Orang Tua — Rumah Qur'an Ibnu Abbas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<link rel="stylesheet" href="{{ asset('assets/orang-tua/css/portal.css') }}">
</head>
<body>
<div class="app">

  @include('orang-tua.partials.beranda')
  @include('orang-tua.partials.presensi')
  @include('orang-tua.partials.kegiatan')
  @include('orang-tua.partials.hafalan')
  @include('orang-tua.partials.profil')

  @include('orang-tua.partials.nav')

  <div class="toast" id="toast">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
    <span id="toastMsg">Data berhasil disimpan</span>
  </div>
  <div class="page-loading" id="pageLoading" aria-live="polite"><div class="page-loading-card"><div class="loading-ring"></div><span>Memuat halaman...</span></div></div>
  <div class="modal fade" id="portalFileModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="portalFileTitle">Preview Lampiran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body p-0"><iframe id="portalFileFrame" style="width:100%;height:75vh;border:0"></iframe></div></div></div></div>

</div>

<script>
  window.PORTAL = {
    csrf: '{{ csrf_token() }}',
    activeView: @json($activeView),
    profileForm: @json($profileForm),
    qrText: @json('RQIA-PRESENSI|ORTU:'.$account->id.'|SANTRI:'.$siswa->nipd.'|'.($orangTua?->nama_lengkap ?? $siswa->nama_lengkap)),
    presensiData: @json($presensiData),
    hafalanData: @json($hafalanData),
    routes: {
      beranda: @json(route('orang-tua.beranda')),
      presensi: @json(route('orang-tua.presensi')),
      kegiatan: @json(route('orang-tua.kegiatan')),
      hafalan: @json(route('orang-tua.hafalan')),
      profil: @json(route('orang-tua.profil')),
      profilUpdate: @json(route('orang-tua.profil.update', '__SECTION__')),
      lampiranUpload: @json(route('orang-tua.lampiran.upload'))
    }
  };
</script>
<script src="{{ asset('assets/orang-tua/js/portal.js') }}"></script>
</body>
</html>
