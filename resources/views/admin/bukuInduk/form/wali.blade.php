<!-- Orang Tua -->
<h5 class="mb-2">Data Orang Tua</h5>
<div class="row">
    <!-- Ayah -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Ayah</label>
            <input type="text" name="nama_ayah" class="form-control"
                value="{{ old('nama_ayah', $wali->nama_ayah ?? '') }}">
        </div>
        <div class="form-group">
            <label>No. Telp Ayah</label>
            <input type="text" name="no_telepon_ayah" class="form-control"
                value="{{ old('no_telepon_ayah', $wali->no_telepon_ayah ?? '') }}">
        </div>
        <div class="form-group">
            <label>Pendidikan Ayah</label>
            <select name="pendidikan_ayah" class="form-control">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" {{ ($wali->pendidikan_ayah ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>
                    Tidak Sekolah</option>
                <option value="SD" {{ ($wali->pendidikan_ayah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                <option value="SMP" {{ ($wali->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                <option value="SMA" {{ ($wali->pendidikan_ayah ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                <option value="D1" {{ ($wali->pendidikan_ayah ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                <option value="D2" {{ ($wali->pendidikan_ayah ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                <option value="D3" {{ ($wali->pendidikan_ayah ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="D4/S1" {{ ($wali->pendidikan_ayah ?? '') == 'D4/S1' ? 'selected' : '' }}>D4/S1</option>
                <option value="S2" {{ ($wali->pendidikan_ayah ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                <option value="S3" {{ ($wali->pendidikan_ayah ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
            </select>
        </div>
        <div class="form-group">
            <label>Pekerjaan Ayah</label>
            <input type="text" name="pekerjaan_ayah" class="form-control"
                value="{{ old('pekerjaan_ayah', $wali->pekerjaan_ayah ?? '') }}">
        </div>
    </div>

    <!-- Ibu -->
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Ibu</label>
            <input type="text" name="nama_ibu" class="form-control"
                value="{{ old('nama_ibu', $wali->nama_ibu ?? '') }}">
        </div>
        <div class="form-group">
            <label>No. Telp Ibu</label>
            <input type="text" name="no_telepon_ibu" class="form-control"
                value="{{ old('no_telepon_ibu', $wali->no_telepon_ibu ?? '') }}">
        </div>
        <div class="form-group">
            <label>Pendidikan Ibu</label>
            <select name="pendidikan_ibu" class="form-control">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" {{ ($wali->pendidikan_ibu ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>
                    Tidak Sekolah</option>
                <option value="SD" {{ ($wali->pendidikan_ibu ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                <option value="SMP" {{ ($wali->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                <option value="SMA" {{ ($wali->pendidikan_ibu ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                <option value="D1" {{ ($wali->pendidikan_ibu ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                <option value="D2" {{ ($wali->pendidikan_ibu ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                <option value="D3" {{ ($wali->pendidikan_ibu ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="D4/S1" {{ ($wali->pendidikan_ibu ?? '') == 'D4/S1' ? 'selected' : '' }}>D4/S1</option>
                <option value="S2" {{ ($wali->pendidikan_ibu ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                <option value="S3" {{ ($wali->pendidikan_ibu ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
            </select>
        </div>
        <div class="form-group">
            <label>Pekerjaan Ibu</label>
            <input type="text" name="pekerjaan_ibu" class="form-control"
                value="{{ old('pekerjaan_ibu', $wali->pekerjaan_ibu ?? '') }}">
        </div>
    </div>
</div>

<!-- Wali -->
<hr class="my-4">
<h5 class="mb-2">Data Wali Murid (Jika Ada)</h5>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Nama Wali</label>
            <input type="text" name="nama_wali" class="form-control"
                value="{{ old('nama_wali', $wali->nama_wali ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Hubungan Keluarga</label>
            <select name="hubungan_wali" class="form-control">
                <option value="">Pilih Hubungan</option>
                <option value="Kakek" {{ ($wali->hubungan_wali ?? '') == 'Kakek' ? 'selected' : '' }}>Kakek</option>
                <option value="Nenek" {{ ($wali->hubungan_wali ?? '') == 'Nenek' ? 'selected' : '' }}>Nenek</option>
                <option value="Paman" {{ ($wali->hubungan_wali ?? '') == 'Paman' ? 'selected' : '' }}>Paman</option>
                <option value="Bibi" {{ ($wali->hubungan_wali ?? '') == 'Bibi' ? 'selected' : '' }}>Bibi</option>
                <option value="Kakak" {{ ($wali->hubungan_wali ?? '') == 'Kakak' ? 'selected' : '' }}>Kakak</option>
                <option value="Adik" {{ ($wali->hubungan_wali ?? '') == 'Adik' ? 'selected' : '' }}>Adik</option>
                <option value="Lainnya" {{ ($wali->hubungan_wali ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Pendidikan Wali</label>
            <select name="pendidikan_wali" class="form-control">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah"
                    {{ ($wali->pendidikan_wali ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                <option value="SD" {{ ($wali->pendidikan_wali ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                <option value="SMP" {{ ($wali->pendidikan_wali ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                <option value="SMA" {{ ($wali->pendidikan_wali ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                <option value="D1" {{ ($wali->pendidikan_wali ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                <option value="D2" {{ ($wali->pendidikan_wali ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                <option value="D3" {{ ($wali->pendidikan_wali ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                <option value="D4/S1" {{ ($wali->pendidikan_wali ?? '') == 'D4/S1' ? 'selected' : '' }}>D4/S1</option>
                <option value="S2" {{ ($wali->pendidikan_wali ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                <option value="S3" {{ ($wali->pendidikan_wali ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Pekerjaan Wali</label>
            <input type="text" name="pekerjaan_wali" class="form-control"
                value="{{ old('pekerjaan_wali', $wali->pekerjaan_wali ?? '') }}">
        </div>
    </div>
</div>
