<h5 class="mb-2">Pindah Sekolah</h5>
<div class="form-group">
    <label for="asal_tingkat">Asal Tingkat</label>
    <input type="text" name="asal_tingkat" id="asal_tingkat" class="form-control" value="{{ old('asal_tingkat', $pindah->asal_tingkat ?? '') }}">
</div>

<div class="form-group">
    <label for="ke_sekolah">Pindah ke Sekolah</label>
    <input type="text" name="ke_sekolah" id="ke_sekolah" class="form-control" value="{{ old('ke_sekolah', $pindah->ke_sekolah ?? '') }}">
</div>

<div class="form-group">
    <label for="ke_tingkat">Ke Tingkat</label>
    <input type="text" name="ke_tingkat" id="ke_tingkat" class="form-control" value="{{ old('ke_tingkat', $pindah->ke_tingkat ?? '') }}">
</div>

<div class="form-group">
    <label for="tanggal_pindah">Tanggal Pindah</label>
    <input type="date" name="tanggal_pindah" id="tanggal_pindah" class="form-control"
        value="{{ old('tanggal_pindah', $pindah->tanggal_pindah ?? '') }}">
</div>

<hr class="my-4">

<h5 class="mb-2">Keluar Sekolah</h5>
<div class="form-group">
    <label for="tanggal_keluar">Tanggal Keluar</label>
    <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control"
        value="{{ old('tanggal_keluar') }}">
</div>

<div class="form-group">
    <label for="alasan_keluar">Alasan Keluar</label>
    <textarea name="alasan_keluar" id="alasan_keluar" class="form-control" rows="3">{{ old('alasan_keluar') }}</textarea>
</div>
