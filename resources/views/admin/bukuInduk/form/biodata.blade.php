<div class="form-group"><label>NISN <span class="text-danger">*</span></label><input name="nisn" class="form-control"
        value="{{ $siswa->nisn }}" required></div>
<div class="form-group"><label>NIPD <span class="text-danger">*</span></label><input name="nipd" class="form-control"
        value="{{ $siswa->nipd }}" required></div>
<div class="form-group"><label>Nama Lengkap <span class="text-danger">*</span></label><input name="nama_lengkap"
        class="form-control" value="{{ $siswa->nama_lengkap }}" required></div>
<div class="form-group"><label>Nama Panggilan <span class="text-danger">*</span></label><input name="nama_panggilan"
        class="form-control" value="{{ $siswa->nama_panggilan }}" required></div>
<div class="form-group">
    <label>Jenis Kelamin <span class="text-danger">*</span></label>
    <select name="jenis_kelamin" class="form-control" required>
        <option value="" selected disabled>Pilih Jenis Kelamin</option>
        <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
    </select>
</div>
<label>Kelahiran <span class="text-danger">*</span></label>
<div class="form-group input-group">
    <div class="input-group-prepend">
        <span class="input-group-text">Tempat & Tanggal</span>
    </div>
    <input name="tempat_lahir" class="form-control" value="{{ $siswa->tempat_lahir }}" required>
    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $siswa->tanggal_lahir }}" required>
</div>
<div class="form-row">
    <div class="col">
        <div class="form-group">
            <label>Agama <span class="text-danger">*</span></label>
            <select name="agama" class="form-control" required>
                <option value="" selected disabled>-</option>
                <option value="Islam" {{ $siswa->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                <option value="Kristen" {{ $siswa->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                <option value="Katholik" {{ $siswa->agama == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                <option value="Hindu" {{ $siswa->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                <option value="Buddha" {{ $siswa->agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                <option value="Khonghucu" {{ $siswa->agama == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
            </select>
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <label>Kewarganegaraan <span class="text-danger">*</span></label>
            <select name="kewarganegaraan" id="kewarganegaraan" class="form-control" required>
                <option value="Indonesia" {{ $siswa->kewarganegaraan == 'Indonesia' ? 'selected' : '' }}>Indonesia
                </option>
                <option value="Malaysia" {{ $siswa->kewarganegaraan == 'Malaysia' ? 'selected' : '' }}>Malaysia
                </option>
                <option value="Singapura" {{ $siswa->kewarganegaraan == 'Singapura' ? 'selected' : '' }}>Singapura
                </option>
                <option value="Thailand" {{ $siswa->kewarganegaraan == 'Thailand' ? 'selected' : '' }}>Thailand
                </option>
                <option value="Vietnam" {{ $siswa->kewarganegaraan == 'Vietnam' ? 'selected' : '' }}>Vietnam
                </option>
                <option value="Jepang" {{ $siswa->kewarganegaraan == 'Jepang' ? 'selected' : '' }}>Jepang</option>
                <option value="Korea Selatan" {{ $siswa->kewarganegaraan == 'Korea Selatan' ? 'selected' : '' }}>
                    Korea Selatan</option>
                <option value="Amerika Serikat" {{ $siswa->kewarganegaraan == 'Amerika Serikat' ? 'selected' : '' }}>
                    Amerika Serikat</option>
                <option value="Inggris" {{ $siswa->kewarganegaraan == 'Inggris' ? 'selected' : '' }}>Inggris
                </option>
                <option value="Jerman" {{ $siswa->kewarganegaraan == 'Jerman' ? 'selected' : '' }}>Jerman</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <label>Jumlah Saudara</label>
    <input type="number" name="jumlah_saudara" class="form-control" value="{{ $siswa->jumlah_saudara }}">
</div>

<div class="form-group">
    <label>Bahasa Rumah</label>
    <input type="text" name="bahasa_rumah" class="form-control" value="{{ $siswa->bahasa_rumah }}">
</div>

<div class="form-group">
    <label>Golongan Darah</label>
    <select name="golongan_darah" class="form-control">
        <option value="A" {{ $siswa->golongan_darah == 'A' ? 'selected' : '' }}>A</option>
        <option value="B" {{ $siswa->golongan_darah == 'B' ? 'selected' : '' }}>B</option>
        <option value="AB" {{ $siswa->golongan_darah == 'AB' ? 'selected' : '' }}>AB</option>
        <option value="O" {{ $siswa->golongan_darah == 'O' ? 'selected' : '' }}>O</option>
    </select>
</div>

<div class="form-row">
    <div class="col">
        <div class="form-group">
            <label>Alamat Rumah <span class="text-danger">*</span></label>
            <input type="text" name="alamat" class="form-control" value="{{ $siswa->alamat }}" required>
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <label>No Telp Rumah <span class="text-danger">*</span></label>
            <input type="text" name="no_telepon_rumah" class="form-control" value="{{ $siswa->no_telepon_rumah }}"
                required>
        </div>
    </div>
</div>

<div class="form-group">
    <label>Tinggal dengan</label>
    <input type="text" name="tinggal_dengan" class="form-control" value="{{ $siswa->tinggal_dengan }}">
</div>

<div class="form-group">
    <label>Jarak ke Sekolah (M) <span class="text-danger">*</span></label>

    <input type="text" id="jarak_sekolah_display" class="form-control"
        value="{{ number_format($siswa->jarak_sekolah, 0, ',', '.') }}" required>
    <input type="hidden" name="jarak_sekolah" id="jarak_sekolah" value="{{ $siswa->jarak_sekolah }}">
</div>

<div class="form-group">
    <label for="kesanggupan_spp">Kesanggupan SPP (Rp)</label>
    <input type="text" name="kesanggupan_spp" id="kesanggupan_spp" class="form-control"
        value="{{ number_format($siswa->kesanggupan_spp ?? 0, 0, ',', '.') }}"
        placeholder="Masukkan kesanggupan SPP">
    <small class="form-text text-muted">Masukkan nominal kesanggupan SPP per bulan</small>
</div>

<div class="form-group">
    <label for="foto">Foto</label>
    <div class="mb-3">
        <img id="preview-foto"
            src="{{ $siswa->foto ? asset('assets/images/siswa/' . $siswa->foto) : asset('assets/images/placeholder_profile.png') }}"
            alt="Foto Siswa" class="img-fluid rounded shadow"
            style="max-width: 200px; aspect-ratio: 3 / 4; object-fit: cover;">
    </div>
    <input type="file" name="foto" class="form-control" style="width: 250px;" accept="image/*"
        onchange="previewFoto(event)">
    <small class="form-text text-muted">Upload foto wajah ukuran 3x4. Format JPG/PNG.
        Max 500KB.</small>
</div>
