{{-- PROFIL: ringkasan kelengkapan + form per section --}}
<section class="view" id="view-profil">
  @include('orang-tua.partials.page-header', ['title' => 'Profil', 'subtitle' => 'Data santri, orang tua, wali, dan berkas'])
  <div class="content">

    @if(!$profileForm)
    <div class="profile-summary">
      <div class="profile-summary-head">
        <h2>Ringkasan Kelengkapan Profil</h2>
        <span style="font-size:10px;color:var(--muted);">{{ collect($profileSummary)->where('complete', true)->count() }}/{{ count($profileSummary) }} lengkap</span>
      </div>
      <div class="summary-list">
        @foreach($profileSummary as $summary)
        <div class="summary-row {{ $summary['complete'] ? 'ok' : 'pending' }}" data-form="{{ $summary['form'] }}">
          <span class="summary-mark">{{ $summary['complete'] ? '✓' : '!' }}</span>
          <div class="summary-text">
            <strong>{{ $summary['label'] }} @if($summary['optional'])<small style="display:inline;color:var(--muted);">(opsional)</small>@endif</strong>
            <small>{{ $summary['complete'] ? 'Data sudah lengkap' : 'Belum lengkap: '.implode(', ', $summary['missing']) }}</small>
          </div>
          <a class="summary-link" href="{{ route('orang-tua.profil', ['form' => $summary['form']]) }}"
             onclick="event.preventDefault();navigateProfileForm('{{ $summary['form'] }}')">{{ $summary['complete'] ? 'Edit' : 'Isi' }}</a>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if($profileForm === 'siswa')
    <div class="subview active" id="sub-siswa">
      <div class="photo-upload">
        <div class="photo-circle" id="siswaPhotoWrap">
          @if($siswa->foto)
            <img src="{{ asset('storage/'.$siswa->foto) }}" alt="Foto santri">
          @else
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg>
          @endif
        </div>
        <div class="photo-upload-text">
          <p class="pt-title">Foto Santri</p>
          <p class="pt-sub">JPG/PNG, maks 2MB</p>
          <button type="button" class="btn-mini" style="position:relative; overflow:hidden;">Unggah Foto
            <input type="file" accept="image/*" style="position:absolute; inset:0; opacity:0; cursor:pointer;" onchange="previewPhoto(this,'siswaPhotoWrap')">
          </button>
        </div>
      </div>

      <div class="form-group">
        <label>Nama Lengkap Santri <span class="req">*</span></label>
        <input type="text" name="nama_lengkap" placeholder="Contoh: Muhammad Zaid Al-Fauzan" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>NIS</label>
          <input type="text" name="nipd" placeholder="0231" value="{{ old('nipd', $siswa->nipd) }}">
        </div>
        <div class="form-group">
          <label>Kelas / Halaqah</label>
          <input type="text" placeholder="Halaqah" value="{{ $siswa->kelas?->nama_kelas }}" readonly>
        </div>
      </div>
      <div class="form-group">
        <label>Jenis Kelamin</label>
        <div class="radio-row">
          <label class="radio-pill {{ $siswa->jenis_kelamin === 'L' ? 'checked' : '' }}">
            <input type="radio" name="jenis_kelamin" value="L" {{ $siswa->jenis_kelamin === 'L' ? 'checked' : '' }}>Laki-laki
          </label>
          <label class="radio-pill {{ $siswa->jenis_kelamin === 'P' ? 'checked' : '' }}">
            <input type="radio" name="jenis_kelamin" value="P" {{ $siswa->jenis_kelamin === 'P' ? 'checked' : '' }}>Perempuan
          </label>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Tempat Lahir</label>
          <input type="text" name="tempat_lahir" placeholder="Malang" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
        </div>
        <div class="form-group">
          <label>Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir" value="{{ optional($siswa->tanggal_lahir)->format('Y-m-d') }}">
        </div>
      </div>
      <div class="form-group">
        <label>Alamat Domisili</label>
        <textarea name="alamat" placeholder="Jl. ..., RT/RW, Kelurahan, Kecamatan">{{ old('alamat', $siswa->alamat) }}</textarea>
      </div>

      @php
        $extraFields = [
          'nisn' => 'NISN', 'nik' => 'NIK', 'no_kk' => 'Nomor KK', 'nama_panggilan' => 'Nama Panggilan',
          'agama' => 'Agama', 'kewarganegaraan' => 'Kewarganegaraan', 'anak_ke' => 'Anak ke-',
          'jumlah_saudara_kandung' => 'Jumlah Saudara Kandung', 'jumlah_saudara_tiri' => 'Jumlah Saudara Tiri',
          'jumlah_saudara_angkat' => 'Jumlah Saudara Angkat', 'status_anak' => 'Status Anak',
          'status_dalam_keluarga' => 'Status dalam Keluarga', 'tahun_ajaran_masuk' => 'Tahun Ajaran Masuk',
          'kelas_saat_masuk' => 'Kelas Saat Masuk', 'status_siswa' => 'Status Siswa',
          'npsn_sekolah_asal' => 'NPSN Sekolah Asal', 'no_ijazah_sebelumnya' => 'Nomor Ijazah Sebelumnya',
          'no_skhun_sttb' => 'Nomor SKHUN/STTB', 'rt' => 'RT', 'rw' => 'RW', 'dusun' => 'Dusun/Kampung',
          'desa_kelurahan' => 'Desa/Kelurahan', 'kecamatan' => 'Kecamatan', 'kabupaten_kota' => 'Kabupaten/Kota',
          'provinsi' => 'Provinsi', 'kode_pos' => 'Kode Pos', 'status_tempat_tinggal' => 'Status Tempat Tinggal',
          'jarak_sekolah' => 'Jarak Rumah ke Sekolah (km)', 'moda_transportasi' => 'Moda Transportasi',
          'no_hp_darurat' => 'Nomor HP Darurat', 'golongan_darah' => 'Golongan Darah',
          'tinggi_badan' => 'Tinggi Badan (cm)', 'berat_badan' => 'Berat Badan (kg)',
          'lingkar_kepala' => 'Lingkar Kepala (cm)', 'berkebutuhan_khusus' => 'Berkebutuhan Khusus (1=ya, 0=tidak)',
          'jenis_kebutuhan_khusus' => 'Jenis Kebutuhan Khusus',
        ];
        $dateFields = ['tanggal_masuk_sekolah' => 'Tanggal Masuk Sekolah'];
      @endphp

      @foreach($dateFields as $n => $l)
      <div class="form-group"><label>{{ $l }}</label><input type="date" name="{{ $n }}" value="{{ optional(data_get($siswa,$n))->format('Y-m-d') }}"></div>
      @endforeach
      @foreach($extraFields as $n => $l)
      <div class="form-group"><label>{{ $l }}</label><input type="text" name="{{ $n }}" value="{{ old($n, data_get($siswa,$n)) }}"></div>
      @endforeach
      <div class="form-group">
        <label>Penyakit/Riwayat Kesehatan Penting</label>
        <textarea name="riwayat_kesehatan">{{ old('riwayat_kesehatan', $siswa->riwayat_kesehatan) }}</textarea>
      </div>

      <button type="button" class="btn-primary profile-save" data-section="siswa">Simpan Profil Siswa</button>
    </div>
    @endif

    @if($profileForm === 'ayah')
    <div class="subview active" id="sub-ayah">
      <div class="section-title" style="margin-top:0;"><h2>Data Ayah</h2></div>
      <div class="form-group">
        <label>Nama Ayah <span class="req">*</span></label>
        <input type="text" name="ayah[nama_ayah]" placeholder="Nama lengkap ayah" value="{{ old('ayah.nama_ayah', $orangTua?->nama_ayah) }}">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>NIK</label>
          <input type="text" name="ayah[nik_ayah]" placeholder="16 digit" value="{{ old('ayah.nik_ayah', $orangTua?->nik_ayah) }}">
        </div>
        <div class="form-group">
          <label>Tahun Lahir</label>
          <input type="number" name="ayah[tahun_lahir_ayah]" placeholder="1985" min="1900" max="2099" value="{{ old('ayah.tahun_lahir_ayah', $orangTua?->tahun_lahir_ayah) }}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Pekerjaan</label>
          <input type="text" name="ayah[pekerjaan_ayah]" placeholder="Wiraswasta" value="{{ old('ayah.pekerjaan_ayah', $orangTua?->pekerjaan_ayah) }}">
        </div>
        <div class="form-group">
          <label>No. HP / WhatsApp</label>
          <input type="tel" name="ayah[no_telp_ayah]" placeholder="0812xxxxxxx" value="{{ old('ayah.no_telp_ayah', $orangTua?->no_telp_ayah) }}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Pendidikan Terakhir</label>
          <input type="text" name="ayah[pendidikan_ayah]" placeholder="S1" value="{{ old('ayah.pendidikan_ayah', $orangTua?->pendidikan_ayah) }}">
        </div>
        <div class="form-group">
          <label>Penghasilan per Bulan</label>
          <input type="text" name="ayah[penghasilan_ayah]" placeholder="Rp 3.000.000" value="{{ old('ayah.penghasilan_ayah', $orangTua?->penghasilan_ayah) }}">
        </div>
      </div>
      <button type="button" class="btn-primary profile-save" data-section="ayah">Simpan Data Ayah</button>
    </div>
    @endif

    @if($profileForm === 'ibu')
    <div class="subview active" id="sub-ibu">
      <div class="section-title" style="margin-top:0;"><h2>Data Ibu</h2></div>
      <div class="form-group">
        <label>Nama Ibu <span class="req">*</span></label>
        <input type="text" name="ibu[nama_ibu]" placeholder="Nama lengkap ibu" value="{{ old('ibu.nama_ibu', $orangTua?->nama_ibu) }}">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>NIK</label>
          <input type="text" name="ibu[nik_ibu]" placeholder="16 digit" value="{{ old('ibu.nik_ibu', $orangTua?->nik_ibu) }}">
        </div>
        <div class="form-group">
          <label>Tahun Lahir</label>
          <input type="number" name="ibu[tahun_lahir_ibu]" placeholder="1988" min="1900" max="2099" value="{{ old('ibu.tahun_lahir_ibu', $orangTua?->tahun_lahir_ibu) }}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Pekerjaan</label>
          <input type="text" name="ibu[pekerjaan_ibu]" placeholder="Ibu Rumah Tangga" value="{{ old('ibu.pekerjaan_ibu', $orangTua?->pekerjaan_ibu) }}">
        </div>
        <div class="form-group">
          <label>No. HP / WhatsApp</label>
          <input type="tel" name="ibu[no_telp_ibu]" placeholder="0812xxxxxxx" value="{{ old('ibu.no_telp_ibu', $orangTua?->no_telp_ibu) }}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Pendidikan Terakhir</label>
          <input type="text" name="ibu[pendidikan_ibu]" placeholder="S1" value="{{ old('ibu.pendidikan_ibu', $orangTua?->pendidikan_ibu) }}">
        </div>
        <div class="form-group">
          <label>Penghasilan per Bulan</label>
          <input type="text" name="ibu[penghasilan_ibu]" placeholder="Rp 2.000.000" value="{{ old('ibu.penghasilan_ibu', $orangTua?->penghasilan_ibu) }}">
        </div>
      </div>
      <button type="button" class="btn-primary profile-save" data-section="ibu">Simpan Data Ibu</button>
    </div>
    @endif

    @if($profileForm === 'wali')
    <div class="subview active" id="sub-wali">
      <div class="toggle-row">
        <span class="tr-text">Wali sama dengan Orang Tua</span>
        <label class="switch">
          <input type="checkbox" id="waliSameToggle" checked onchange="toggleWali()">
          <span class="slider"></span>
        </label>
      </div>

      <div id="waliFields" style="display:none;">
        <div class="form-group">
          <label>Nama Wali</label>
          <input type="text" name="wali[nama_wali]" placeholder="Nama lengkap wali" value="{{ old('wali.nama_wali', $orangTua?->nama_wali) }}">
        </div>
        <div class="form-group">
          <label>Hubungan dengan Santri</label>
          <select name="wali[hubungan_wali]">
            @foreach(['Kakek','Nenek','Paman','Bibi','Kakak Kandung','Lainnya'] as $hub)
            <option value="{{ $hub }}" {{ $orangTua?->hubungan_wali === $hub ? 'selected' : '' }}>{{ $hub }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>NIK Wali</label>
            <input type="text" name="wali[nik_wali]" placeholder="16 digit" value="{{ old('wali.nik_wali', $orangTua?->nik_wali) }}">
          </div>
          <div class="form-group">
            <label>Tahun Lahir</label>
            <input type="number" name="wali[tahun_lahir_wali]" placeholder="1970" min="1900" max="2099" value="{{ old('wali.tahun_lahir_wali', $orangTua?->tahun_lahir_wali) }}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Pendidikan Terakhir</label>
            <input type="text" name="wali[pendidikan_wali]" placeholder="SMA" value="{{ old('wali.pendidikan_wali', $orangTua?->pendidikan_wali) }}">
          </div>
          <div class="form-group">
            <label>Pekerjaan</label>
            <input type="text" name="wali[pekerjaan_wali]" placeholder="Pekerjaan wali" value="{{ old('wali.pekerjaan_wali', $orangTua?->pekerjaan_wali) }}">
          </div>
        </div>
        <div class="form-group">
          <label>Penghasilan per Bulan</label>
          <input type="text" name="wali[penghasilan_wali]" placeholder="Rp 2.000.000" value="{{ old('wali.penghasilan_wali', $orangTua?->penghasilan_wali) }}">
        </div>
        <button type="button" class="btn-primary profile-save" data-section="wali">Simpan Data Wali</button>
      </div>
      <p class="empty-note" id="waliEmptyNote">Data wali mengikuti data orang tua yang sudah diisi.</p>
    </div>
    @endif

    @if($profileForm === 'berkas')
    <div class="subview active" id="sub-berkas">
      <p style="font-size:12.5px; color:var(--muted); margin:0 0 14px;">Seret file ke area di bawah atau klik untuk unggah. JPG, PNG, atau PDF (maks 5MB). Langsung tersimpan ke database tanpa tombol submit.</p>
      <div class="portal-upload-list" style="display:grid; gap:12px;">
        @php $portalDocs = ['foto_siswa' => 'Foto Siswa', 'kartu_keluarga' => 'Kartu Keluarga', 'akta_kelahiran' => 'Akta Kelahiran', 'ktp_ayah' => 'KTP Ayah', 'ktp_ibu' => 'KTP Ibu']; @endphp
        @foreach($portalDocs as $kind => $label)
          @php $file = $siswa->lampiran->firstWhere('jenis_dokumen', $kind); @endphp
          @php $isImage = $file && str_starts_with($file->mime_type ?? '', 'image/'); @endphp
        <div class="portal-file-row dropzone portal-dropzone {{ $file ? 'done' : '' }} {{ $isImage ? 'has-preview' : '' }}" data-kind="{{ $kind }}" style="position:relative; min-height:148px; flex-direction:column; justify-content:center; align-items:center; text-align:center; padding:20px 14px; overflow:hidden;">
          @if($file)
          <button type="button" class="dropzone-remove portal-file-delete" data-url="{{ route('orang-tua.lampiran.delete', $file->id) }}" aria-label="Hapus"><svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
          @if($isImage)
          <img src="{{ route('orang-tua.lampiran.view', $file->id) }}" alt="{{ $label }}" class="dropzone-preview">
          <div class="dropzone-preview-overlay"></div>
          @endif
          @endif
          <div class="dropzone-content" style="position:relative; z-index:2; display:flex; flex-direction:column; align-items:center; gap:6px; text-align:center;">
            <div class="file-icon {{ $file ? 'done' : '' }}" style="margin-bottom:2px;">{{ $file ? '✓' : '+' }}</div>
            <div class="file-info" style="text-align:center;">
              <p class="f-name" style="margin-bottom:2px;">{{ $label }}</p>
              <p class="f-status {{ $file ? 'done' : '' }}" style="justify-content:center;">{{ $file ? $file->nama_asli : 'Seret file ke sini atau klik' }}</p>
              <p class="f-hint" style="font-size:10.5px;color:var(--muted);margin:3px 0 0;">{{ $file ? 'Tersimpan · klik untuk ganti' : 'JPG, PNG, PDF · maks 5MB' }}</p>
            </div>
          </div>
          <input type="file" class="portal-file-input" hidden accept=".jpg,.jpeg,.png,.pdf">
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>
</section>
