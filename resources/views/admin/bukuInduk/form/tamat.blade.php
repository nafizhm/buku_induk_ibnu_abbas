<div class="form-group">
    <label for="tahun_lulus">Tahun Lulus</label>
    <input type="number" name="tahun_lulus" id="tahun_lulus" class="form-control" value="{{ old('tahun_lulus', $lulus->tahun_lulus ?? '') }}">
</div>

<div class="form-group">
    <label for="no_ijazah">Nomor Ijazah</label>
    <input type="text" name="no_ijazah" id="no_ijazah" class="form-control" value="{{ old('no_ijazah', $lulus->no_ijazah ?? '') }}">
</div>

<div class="form-group">
    <label for="melanjutkan_ke">Melanjutkan ke</label>
    <input type="text" name="melanjutkan_ke" id="melanjutkan_ke" class="form-control"
        value="{{ old('melanjutkan_ke', $lulus->melanjutkan_ke ?? '') }}">
</div>
