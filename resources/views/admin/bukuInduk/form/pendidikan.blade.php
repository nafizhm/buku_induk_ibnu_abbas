<!-- Murid Baru -->
<h5 class="mb-2">Murid Baru (Tingkat I)</h5>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Masuk Tingkat</label>
            <input type="text" name="masuk_tingkat" class="form-control"
                value="{{ old('masuk_tingkat', $pendidikan->masuk_tingkat ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Asal Murid</label>
            <input type="text" name="asal_murid" class="form-control"
                value="{{ old('asal_murid', $pendidikan->asal_murid ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Nama Taman Kanak-Kanak</label>
            <input type="text" name="nama_tk" class="form-control"
                value="{{ old('nama_tk', $pendidikan->nama_tk ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal STTB</label>
            <input type="date" name="tanggal_sttb" class="form-control"
                value="{{ old('tanggal_sttb', $pendidikan->tanggal_sttb ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>No. STTB</label>
            <input type="text" name="no_sttb" class="form-control"
                value="{{ old('no_sttb', $pendidikan->no_sttb ?? '') }}">
        </div>
    </div>
</div>

<!-- Divider -->
<hr class="my-4">

<!-- Pindahan -->
<h5 class="mb-2">Pindahan dari Sekolah Lain</h5>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Nama Sekolah Asal</label>
            <input type="text" name="pindahan_dari" class="form-control"
                value="{{ old('pindahan_dari', $pendidikan->pindahan_dari ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Dari Tingkat</label>
            <input type="text" name="pindahan_dari_tingkat" class="form-control"
                value="{{ old('pindahan_dari_tingkat', $pendidikan->pindahan_dari_tingkat ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Tanggal Diterima</label>
            <input type="date" name="tanggal_diterima" class="form-control"
                value="{{ old('tanggal_diterima', $pendidikan->tanggal_diterima ?? '') }}">
        </div>
    </div>
</div>
