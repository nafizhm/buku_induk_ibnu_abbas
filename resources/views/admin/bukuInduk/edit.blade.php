@extends('admin.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bs-stepper/css/bs-stepper.min.css') }}">

    <style>
        #nav-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: flex-start;
        }

        .btn-nav {
            background-color: transparent;
            color: #000;
            padding: 6px 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 18px;
            transition: 0.3s;
        }

        .btn-nav:hover {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .btn-nav.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    @php
                        $section = request('section');
                    @endphp

                    <div id="nav-buttons" aria-label="Navigation Buttons">
                        <a href="?section=user" class="btn-nav {{ $section == 'user' ? 'active' : '' }}">User</a>
                        <a href="?section=biodata" class="btn-nav {{ $section == 'biodata' ? 'active' : '' }}">Biodata</a>
                        <a href="?section=wali" class="btn-nav {{ $section == 'wali' ? 'active' : '' }}">Wali Murid</a>
                        <a href="?section=pendidikan" class="btn-nav {{ $section == 'pendidikan' ? 'active' : '' }}">Riwayat
                            Pendidikan</a>
                        <a href="?section=jasmani" class="btn-nav {{ $section == 'jasmani' ? 'active' : '' }}">Jasmani</a>
                        <a href="?section=beasiswa"
                            class="btn-nav {{ $section == 'beasiswa' ? 'active' : '' }}">Beasiswa</a>
                        <a href="?section=tamat" class="btn-nav {{ $section == 'tamat' ? 'active' : '' }}">Tamat Belajar</a>
                        <a href="?section=pindah" class="btn-nav {{ $section == 'pindah' ? 'active' : '' }}">Pindah</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="bukuIndukForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="section" value="{{ request('section', 'user') }}">

        <div class="row">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="card bg-light shadow border mb-3">
                            <div class="card-body">
                                @php
                                    $section = request()->get('section', 'user');
                                @endphp

                                @includeIf('content.bukuInduk.form.' . $section)
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="{{ asset('adminlte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Setup CSRF token untuk AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            document.getElementById('togglePin').addEventListener('click', function() {
                const input = document.getElementById('pin_rfid');
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            // Format jarak sekolah
            const displayInput = document.getElementById('jarak_sekolah_display');
            const hiddenInput = document.getElementById('jarak_sekolah');

            if (displayInput && hiddenInput) {
                displayInput.addEventListener('input', function(e) {
                    let raw = e.target.value.replace(/\D/g, '');
                    let formatted = new Intl.NumberFormat('id-ID').format(raw);

                    e.target.value = formatted;
                    hiddenInput.value = raw;
                });
            }

            // Format kesanggupan SPP
            const kesanggupanSppInput = document.getElementById('kesanggupan_spp');
            if (kesanggupanSppInput) {
                kesanggupanSppInput.addEventListener('input', function(e) {
                    let raw = e.target.value.replace(/\D/g, '');
                    let formatted = new Intl.NumberFormat('id-ID').format(raw);

                    e.target.value = formatted;
                });
            }

            // Preview foto
            window.previewFoto = function(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('preview-foto');

                if (file && preview) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            };

            // Validasi password real-time
            $('#password_confirmation').on('input', function() {
                const password = $('#password').val();
                const passwordConfirmation = $(this).val();

                if (passwordConfirmation && password !== passwordConfirmation) {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after(
                            '<div class="invalid-feedback">Konfirmasi password tidak cocok</div>');
                    }
                } else if (passwordConfirmation && password === passwordConfirmation) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    $(this).next('.invalid-feedback').remove();
                } else {
                    $(this).removeClass('is-invalid is-valid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            $('#password').on('input', function() {
                const password = $(this).val();
                const passwordConfirmation = $('#password_confirmation').val();

                if (password.length < 6) {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback">Password minimal 6 karakter</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    $(this).next('.invalid-feedback').remove();
                }

                if (passwordConfirmation) {
                    $('#password_confirmation').trigger('input');
                }
            });

            // Form submission
            $('#bukuIndukForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted');

                const form = $(this);
                const formData = new FormData(this);
                const section = formData.get('section');

                console.log('Section:', section);
                console.log('Form action:', form.attr('action'));

                // Validasi khusus untuk form password
                if (section === 'user') {
                    const nfcUid = $('#nfc_uid').val();
                    const pinNfc = $('#pin_rfid').val();
                    const password = $('#password').val();
                    const passwordConfirmation = $('#password_confirmation').val();

                    if (!pinNfc || pinNfc.trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            text: 'Pin NFC harus diisi'
                        });
                        return;
                    }

                    // Validasi NFC UID (harus diisi)
                    if (!nfcUid || nfcUid.trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            text: 'NFC UID harus diisi'
                        });
                        return;
                    }

                    // Validasi password hanya jika diisi
                    if (password) {
                        if (password.length < 6) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                text: 'Password minimal 6 karakter'
                            });
                            return;
                        }

                        if (password !== passwordConfirmation) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                text: 'Konfirmasi password tidak cocok'
                            });
                            return;
                        }
                    }

                    // Jika password diisi tapi konfirmasi kosong
                    if (password && !passwordConfirmation) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            text: 'Konfirmasi password harus diisi'
                        });
                        return;
                    }
                }

                // Validasi khusus untuk form biodata
                if (section === 'biodata') {
                    const requiredFields = [
                        'nipd', 'nisn', 'nama_lengkap', 'nama_panggilan', 'jenis_kelamin',
                        'tempat_lahir', 'tanggal_lahir', 'agama', 'kewarganegaraan',
                        'alamat', 'no_telepon_rumah', 'jarak_sekolah'
                    ];

                    for (let field of requiredFields) {
                        const value = $(`[name="${field}"]`).val();
                        if (!value || value.trim() === '') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                text: `${field.replace('_', ' ').toUpperCase()} harus diisi`
                            });
                            return;
                        }
                    }
                }

                // Validasi khusus untuk form wali murid
                if (section === 'wali') {
                    const namaAyah = $('[name="nama_ayah"]').val();
                    const namaIbu = $('[name="nama_ibu"]').val();
                    const namaWali = $('[name="nama_wali"]').val();

                    if (!namaAyah && !namaIbu && !namaWali) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            text: 'Minimal harus mengisi data Ayah, Ibu, atau Wali'
                        });
                        return;
                    }
                }

                // Set nilai asli untuk format angka
                if (kesanggupanSppInput) {
                    const formattedValue = kesanggupanSppInput.value;
                    const rawValue = formattedValue.replace(/\D/g, '');
                    formData.set('kesanggupan_spp', rawValue);
                }

                // Debug: log form data
                console.log('Form data entries:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                $.ajax({
                    url: "{{ route('bukuIndukSiswa', $siswa->id) }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        console.log('Sending AJAX request...');
                        Swal.fire({
                            title: 'Tunggu sebentar...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: function(res) {
                        console.log('Success response:', res);
                        if (res.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message ||
                                    'Terjadi kesalahan saat menyimpan data',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log('Error response:', xhr);
                        let msg = 'Terjadi kesalahan saat menyimpan data';

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            msg = Object.values(errors).join('<br>');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal',
                                html: msg,
                                confirmButtonText: 'OK'
                            });
                        } else if (xhr.status === 419) {
                            Swal.fire({
                                icon: 'error',
                                title: 'CSRF Token Mismatch',
                                text: 'Halaman telah kadaluarsa. Silakan refresh halaman dan coba lagi.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: msg,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: msg,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
