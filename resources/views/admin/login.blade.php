<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rumah Qur'an Ibnu Abbas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('images/logo-ibnu-abbas.jpg') }}" type="image/jpeg">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">


    <style>
        .login-icon {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 150px;
            /* atau sesuaikan ukuran */
            height: auto;
        }

        body {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .overlay::before {
            content: "";
            display: block;
            position: absolute;
            z-index: -1;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background: #4f5053;
            background: -webkit-linear-gradient(bottom, #4f5053, #4f5053bb);
            background: -o-linear-gradient(bottom, #4f5053, #4f5053bb);
            background: -moz-linear-gradient(bottom, #4f5053, #4f5053bb);
            background: linear-gradient(bottom, #4f5053, #4f5053bb);
            opacity: 0.9;
        }
    </style>
</head>

<body class="d-flex  overflow-hidden overlay align-items-center justify-content-center min-vh-100"
    style="background-image: url('{{ asset('images/gedung.jpg') }}');
">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <img src="{{ asset('images/logo-ibnu-abbas.jpg') }}" alt="Logo Rumah Qur'an Ibnu Abbas"
                            class="login-icon mb-3">
                        <h4 class="text-center mb-4">Rumah Qur'an Ibnu Abbas</h4>

                        <form id="formLogin">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan username">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-pill">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- JS -->
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(document).ready(function() {
            function refreshCsrfToken(callback) {
                $.get('{{ route('refresh.csrf') }}', function(data) {
                    $('meta[name="csrf-token"]').attr('content', data.token);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': data.token
                        }
                    });
                    if (typeof callback === 'function') callback();
                });
            }

            $('#formLogin').on('submit', function(e) {
                e.preventDefault();

                let form = this;

                refreshCsrfToken(function() {
                    let url = '{{ route('admin.loginPost') }}';
                    let formData = new FormData(form);

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function() {
                            window.location.href = "{{ route('dashboard') }}";
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                audio.play();
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, val) {
                                    let input = $('#' + key);
                                    input.addClass('is-invalid');
                                    input.after(
                                        '<span class="invalid-feedback" role="alert"><strong>' +
                                        val[0] + '</strong></span>'
                                    );
                                });
                            } else {
                                alert(
                                    'Terjadi kesalahan pada server. Silakan coba lagi.');
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
