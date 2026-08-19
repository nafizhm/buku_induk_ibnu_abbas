@extends('admin.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <style>
        .parent-preview {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-auto-rows: auto;
            gap: 8px;
        }

        .helper {
            grid-row: span 4 / span 4;
        }

        .biodata {
            grid-column: span 3 / span 3;
        }

        .wali-murid {
            grid-column: span 3 / span 3;
            grid-column-start: 2;
            grid-row-start: 2;
        }

        .akademik-murid {
            grid-column: span 3 / span 3;
            grid-column-start: 2;
            grid-row-start: 3;
        }

        .tambahan {
            grid-column: span 3 / span 3;
            grid-column-start: 2;
            grid-row-start: 4;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="container-fluid">
            <div class="card bg-light">
                <div class="card-body">
                    <div style="text-align: center;">
                        <h2 class="page-title text-truncate text-dark font-weight-bold mb-1">Form Pendaftaran Calon Murid
                        </h2>
                    </div>
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step" data-target="#step-1">
                                <button type="button" class="step-trigger" role="tab" id="step-1-trigger">
                                    <span class="bs-stepper-circle">1</span>Biodata
                                    <span class="bs-stepper-label"></span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#step-2">
                                <button type="button" class="step-trigger" role="tab" id="step-2-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Data Wali</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#step-3">
                                <button type="button" class="step-trigger" role="tab" id="step-3-trigger">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">Akademik Murid</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#step-4">
                                <button type="button" class="step-trigger" role="tab" id="step-4-trigger">
                                    <span class="bs-stepper-circle">4</span>
                                    <span class="bs-stepper-label">Tambahan</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#step-5">
                                <button type="button" class="step-trigger" role="tab" id="step-5-trigger">
                                    <span class="bs-stepper-circle">5</span>
                                    <span class="bs-stepper-label">Simpan</span>
                                </button>
                            </div>
                        </div>

                        <form id="form-calon">
                            @csrf
                            <div class="bs-stepper-content">
                                <!-- Step 1 -->
                                <div id="step-1" class="content" role="tabpanel" aria-labelledby="step-1-trigger">
                                    <div class="form-group"><label>Nama Lengkap <span
                                                class="text-danger">*</span></label><input name="nama_lengkap"
                                            class="form-control" required></div>
                                    <div class="form-group"><label>Nama Panggilan <span
                                                class="text-danger">*</span></label><input name="nama_panggilan"
                                            class="form-control" required></div>
                                    <div class="form-group">
                                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-control" required>
                                            <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <label>Kelahiran <span class="text-danger">*</span></label>
                                    <div class="form-group input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Tempat & Tanggal</span>
                                        </div>
                                        <input name="tempat_lahir" class="form-control" required>
                                        <input type="date" name="tanggal_lahir" class="form-control" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Agama <span class="text-danger">*</span></label>
                                                <select name="agama" class="form-control" required>
                                                    <option value="" selected disabled>-</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Katholik">Katholik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Buddha">Buddha</option>
                                                    <option value="Khonghucu">Khonghucu</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Kewarganegaraan <span class="text-danger">*</span></label>
                                                <select name="kewarganegaraan" id="kewarganegaraan" class="form-control"
                                                    required>
                                                    <option value="Indonesia">Indonesia</option>
                                                    <option value="Malaysia">Malaysia</option>
                                                    <option value="Singapura">Singapura</option>
                                                    <option value="Thailand">Thailand</option>
                                                    <option value="Vietnam">Vietnam</option>
                                                    <option value="Jepang">Jepang</option>
                                                    <option value="Korea Selatan">Korea Selatan</option>
                                                    <option value="Amerika Serikat">Amerika Serikat</option>
                                                    <option value="Inggris">Inggris</option>
                                                    <option value="Jerman">Jerman</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Saudara</label>
                                        <input type="number" name="jumlah_saudara" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Bahasa Rumah</label>
                                        <input type="text" name="bahasa_rumah" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Golongan Darah</label>
                                        <select name="golongan_darah" class="form-control">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                    </div>

                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Alamat Rumah <span class="text-danger">*</span></label>
                                                <input type="text" name="alamat" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>No Telp Rumah <span class="text-danger">*</span></label>
                                                <input type="text" name="no_telp_rumah" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tinggal dengan</label>
                                        <input type="text" name="tinggal_dengan" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Jarak ke Sekolah (M) <span class="text-danger">*</span></label>

                                        <input type="text" id="jarak_sekolah_display" class="form-control" required>
                                        <input type="hidden" name="jarak_sekolah" id="jarak_sekolah">
                                    </div>

                                    <button type="button" class="btn btn-primary"
                                        onclick="stepper.next()">Selanjutnya</button>
                                </div>

                                <!-- Step 2 -->
                                <div id="step-2" class="content" role="tabpanel" aria-labelledby="step-2-trigger">
                                    <!-- Orang Tua -->
                                    <h5 class="mb-2">Data Orang Tua</h5>
                                    <div class="row">
                                        <!-- Ayah -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Ayah</label>
                                                <input type="text" name="nama_ayah" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Pendidikan Ayah</label>
                                                <input type="text" name="pendidikan_ayah" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Pekerjaan Ayah</label>
                                                <input type="text" name="pekerjaan_ayah" class="form-control">
                                            </div>
                                        </div>

                                        <!-- Ibu -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama Ibu</label>
                                                <input type="text" name="nama_ibu" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Pendidikan Ibu</label>
                                                <input type="text" name="pendidikan_ibu" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Pekerjaan Ibu</label>
                                                <input type="text" name="pekerjaan_ibu" class="form-control">
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
                                                <input type="text" name="nama_wali" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hubungan Keluarga</label>
                                                <input type="text" name="hubungan_wali" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pendidikan Wali</label>
                                                <input type="text" name="pendidikan_wali" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pekerjaan Wali</label>
                                                <input type="text" name="pekerjaan_wali" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="stepper.previous()">Kembali</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="stepper.next()">Selanjutnya</button>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div id="step-3" class="content" role="tabpanel" aria-labelledby="step-3-trigger">
                                    <!-- Murid Baru -->
                                    <h5 class="mb-2">Murid Baru (Tingkat I)</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Masuk Tingkat</label>
                                                <input type="text" name="masuk_tingkat" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Asal Murid</label>
                                                <input type="text" name="asal_murid" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nama Taman Kanak-Kanak</label>
                                                <input type="text" name="nama_tk" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal STTB</label>
                                                <input type="date" name="tgl_sttb" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No. STTB</label>
                                                <input type="text" name="no_sttb" class="form-control">
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
                                                <input type="text" name="pindahan_dari" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Dari Tingkat</label>
                                                <input type="text" name="pindahan_dari_tingkat" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Tanggal Diterima</label>
                                                <input type="date" name="tanggal_diterima" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="stepper.previous()">Kembali</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="stepper.next()">Selanjutnya</button>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div id="step-4" class="content" role="tabpanel" aria-labelledby="step-4-trigger">
                                    <div class="form-group text-center">
                                        <label for="foto">Foto</label>
                                        <div class="mb-3">
                                            <img id="preview-foto"
                                                src="{{ asset('assets/images/placeholder_profile.png') }}"
                                                alt="Foto Siswa" class="shadow"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        </div>
                                        <input type="file" name="foto" class="form-control" accept="image/*"
                                            onchange="previewFoto(event)">
                                        <small class="form-text text-muted">Upload foto wajah ukuran 3x4. Format JPG/PNG.
                                            Max 500KB.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="kesanggupan_spp">Kesanggupan SPP (Rp)</label>
                                        <input type="text" name="kesanggupan_spp" id="kesanggupan_spp"
                                            class="form-control" placeholder="Masukkan jumlah kesanggupan SPP" required>
                                        <small class="form-text text-muted">Masukkan nilai kesanggupan SPP dalam Rupiah.
                                        </small>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="stepper.previous()">Kembali</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="stepper.next()">Selanjutnya</button>
                                    </div>
                                </div>

                                <!-- Step 5 -->
                                <div id="step-5" class="content" role="tabpanel" aria-labelledby="step-5-trigger">
                                    <div class="parent-preview">
                                        <div class="helper">
                                            <div class="card">
                                                <div class="card-header bg-info text-white">
                                                    Catatan
                                                </div>
                                                <div class="card-body">
                                                    <ul class="mb-0">
                                                        <li>Pastikan semua data sudah diisi dengan benar.</li>
                                                        <li>Gunakan huruf kapital pada nama lengkap.</li>
                                                        <li>Jika siswa pindahan, lengkapi data sekolah asal.</li>
                                                        <li>Upload foto terbaru dengan jelas.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="biodata">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="fw-bold">Data Diri</h4>
                                                    <div class="mb-3 text-muted small">Berikut adalah data yang akan calon
                                                        murid gunakan untuk melakukan
                                                        pendaftaran</div>
                                                    <div class="card bg-light">
                                                        <div class="card-body" id="preview-data-diri"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="wali-murid">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="mb-3 fw-bold">Data Wali Murid</h3>
                                                    <div class="mb-3 text-muted small">Berikut adalah data wali murid calon
                                                        murid</div>
                                                    <div class="card bg-light">
                                                        <div class="card-body" id="preview-wali"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="akademik-murid">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="mb-3 fw-bold">Data Akademik</h3>
                                                    <div class="mb-3 text-muted small">Berikut adalah data riwayat akademik
                                                        calon murid</div>
                                                    <div class="card bg-light">
                                                        <div class="card-body" id="preview-akademik"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tambahan">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h3 class="mb-3 fw-bold">Tambahan</h3>
                                                    <div class="mb-3 text-muted small">Berikut adalah foto calon
                                                        murid</div>
                                                    <div class="card bg-light">
                                                        <div class="card-body" id="preview-file">
                                                            <div class="text-center mb-3">
                                                                <img id="preview-foto" src="/path/default.jpg"
                                                                    class="rounded-circle img-thumbnail" width="150"
                                                                    height="150" alt="Preview Foto">
                                                            </div>
                                                            <div id="preview-file-data"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-secondary"
                                        onclick="stepper.previous()">Kembali</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('adminlte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        document.querySelectorAll('#form-calon input').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

        const displayInput = document.getElementById('jarak_sekolah_display');
        const hiddenInput = document.getElementById('jarak_sekolah');

        displayInput.addEventListener('input', function(e) {
            let raw = e.target.value.replace(/\D/g, '');
            let formatted = new Intl.NumberFormat('id-ID').format(raw);

            e.target.value = formatted;
            hiddenInput.value = raw;
        });

        function previewFoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-foto');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {
            $('#kewarganegaraan').select2({
                theme: 'bootstrap4',
                tags: true,
                placeholder: "Pilih atau ketik negara...",
                allowClear: true,
                width: '100%'
            });
        });

        let fieldKesanggupan;

        function generatePreview() {
            const formData = new FormData(document.getElementById('form-calon'));
            const fieldGroups = {
                'preview-data-diri': ['nama_lengkap', 'nama_panggilan', 'jenis_kelamin', 'tempat_lahir',
                    'tanggal_lahir',
                    'agama', 'kewarganegaraan', 'jumlah_saudara', 'bahasa_rumah', 'golongan_darah', 'alamat',
                    'no_telp_rumah', 'tinggal_dengan', 'jarak_sekolah'
                ],
                'preview-wali': ['nama_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pendidikan_ibu',
                    'pekerjaan_ibu', 'nama_wali', 'hubungan_wali',
                    'pendidikan_wali', 'pekerjaan_wali'
                ],
                'preview-akademik': ['masuk_tingkat', 'asal_murid', 'nama_tk', 'tgl_sttb', 'no_sttb', 'pindahan_dari',
                    'pindahan_dari_tingkat', 'tanggal_diterima'
                ],
                'preview-file': ['foto', 'kesanggupan_spp']
            };

            for (const section in fieldGroups) {
                document.getElementById(section).innerHTML = '';
            }

            for (let [key, value] of formData.entries()) {
                if (key === '_token') continue;

                for (const section in fieldGroups) {
                    if (fieldGroups[section].includes(key)) {
                        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        if (key === 'foto') {
                            const file = formData.get('foto');
                            if (file && file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    document.getElementById('preview-file').innerHTML = `
                                <div class="text-center mb-3">
                                    <img src="${e.target.result}" class="rounded img-thumbnail" width="150" height="150" alt="Preview Foto">
                                </div>`;
                                };
                                reader.readAsDataURL(file);
                            }
                        } else {
                            document.getElementById(section).innerHTML += `
                        <div class="mb-2">
                            <div class="text-secondary small">${label}</div>
                            <div class="text-dark">${value || '-'}</div>
                        </div>`;
                        }
                        break;
                    }
                }
            }
        }

        $(function() {
            fieldKesanggupan = new AutoNumeric('#kesanggupan_spp', {
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalPlaces: 0,
                modifyValueOnWheel: false
            });
        });

        document.querySelector('#step-4 .btn.btn-primary').addEventListener('click', function() {
            generatePreview();
            stepper.next();
        });

        $('#form-calon').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menyimpan data ini?',
                text: "Pastikan data sudah benar sebelum disimpan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData();
                    formData.append('nama_lengkap', $('input[name="nama_lengkap"]').val());
                    formData.append('nama_panggilan', $('input[name="nama_panggilan"]').val());
                    formData.append('jenis_kelamin', $('select[name="jenis_kelamin"]').val());
                    formData.append('tempat_lahir', $('input[name="tempat_lahir"]').val());
                    formData.append('tanggal_lahir', $('input[name="tanggal_lahir"]').val());
                    formData.append('agama', $('select[name="agama"]').val());
                    formData.append('kewarganegaraan', $('select[name="kewarganegaraan"]').val());
                    formData.append('jumlah_saudara', $('input[name="jumlah_saudara"]').val());
                    formData.append('bahasa_rumah', $('input[name="bahasa_rumah"]').val());
                    formData.append('golongan_darah', $('select[name="golongan_darah"]').val());
                    formData.append('alamat', $('input[name="alamat"]').val());
                    formData.append('no_telp_rumah', $('input[name="no_telp_rumah"]').val());
                    formData.append('tinggal_dengan', $('input[name="tinggal_dengan"]').val());
                    formData.append('jarak_sekolah', $('input[name="jarak_sekolah"]').val());
                    formData.append('nama_ayah', $('input[name="nama_ayah"]').val());
                    formData.append('pendidikan_ayah', $('input[name="pendidikan_ayah"]').val());
                    formData.append('pekerjaan_ayah', $('input[name="pekerjaan_ayah"]').val());
                    formData.append('nama_ibu', $('input[name="nama_ibu"]').val());
                    formData.append('pendidikan_ibu', $('input[name="pendidikan_ibu"]').val());
                    formData.append('pekerjaan_ibu', $('input[name="pekerjaan_ibu"]').val());
                    formData.append('nama_wali', $('input[name="nama_wali"]').val());
                    formData.append('hubungan_wali', $('input[name="hubungan_wali"]').val());
                    formData.append('pendidikan_wali', $('input[name="pendidikan_wali"]').val());
                    formData.append('pekerjaan_wali', $('input[name="pekerjaan_wali"]').val());
                    formData.append('masuk_tingkat', $('input[name="masuk_tingkat"]').val());
                    formData.append('asal_murid', $('input[name="asal_murid"]').val());
                    formData.append('nama_tk', $('input[name="nama_tk"]').val());
                    formData.append('tgl_sttb', $('input[name="tgl_sttb"]').val());
                    formData.append('no_sttb', $('input[name="no_sttb"]').val());
                    formData.append('pindahan_dari', $('input[name="pindahan_dari"]').val());
                    formData.append('pindahan_dari_tingkat', $('input[name="pindahan_dari_tingkat"]')
                        .val());
                    formData.append('tanggal_diterima', $('input[name="tanggal_diterima"]').val());
                    formData.append('foto', $('input[name="foto"]').prop('files')[0]);
                    formData.append('kesanggupan_spp', fieldKesanggupan.getNumber());
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: '{{ route('pendaftaran.store') }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            if (res.status == success) {
                                $('#form-calon')[0].reset();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('pendaftaran.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: res.message
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Validasi Gagal',
                                    html: Object.values(errors).join('<br>')
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: 'Terjadi kesalahan saat menyimpan data.'
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
