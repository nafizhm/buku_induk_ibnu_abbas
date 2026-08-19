<div class="form-group">
    <label for="nisn">NISN</label>
    <input type="text" name="nisn" id="nisn" class="form-control" value="{{ $siswa->nisn }}" readonly>
</div>

<div class="form-group">
    <label for="nama_lengkap">Nama Lengkap</label>
    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="{{ $siswa->nama_lengkap }}"
        readonly>
</div>

<div class="form-group">
    <label for="nfc_uid">NFC UID</label>
    <input type="text" name="nfc_uid" id="nfc_uid" class="form-control" value="{{ $siswa->nfc_uid }}">
</div>

<div class="form-group">
    <label for="pin_rfid">Pin</label>
    <div class="input-group">
        <input type="password" name="pin_rfid" id="pin_rfid" class="form-control" value="{{ $siswa->pin_rfid }}">
        <button type="button" class="btn btn-outline-secondary" id="togglePin">
            <i class="fas fa-eye"></i>
        </button>
    </div>
</div>

<div class="form-group">
    <label for="password">Password Baru</label>
    <input type="password" name="password" id="password" class="form-control"
        placeholder="Masukkan password baru (minimal 6 karakter)" minlength="6" maxlength="255">
    <small class="form-text text-muted">Password minimal 6 karakter</small>
</div>

<div class="form-group">
    <label for="password_confirmation">Konfirmasi Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
        placeholder="Konfirmasi password baru" minlength="6" maxlength="255">
    <small class="form-text text-muted">Masukkan ulang password untuk konfirmasi</small>
</div>
