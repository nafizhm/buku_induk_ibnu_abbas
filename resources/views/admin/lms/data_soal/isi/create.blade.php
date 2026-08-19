@extends('admin.layout')
@section('content')
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header bg-primary mb-4 d-flex align-items-center">
                    <h3 class="font-weight-bold text-xl text-white m-0">Tambah Isi Soal {{ $data->nama }}</h3>
                </div>
                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="soal" class="col-sm-2 col-form-label">Soal</label>
                            <div class="col-sm-4">
                                <textarea name="soal" id="soal" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Foto</label>
                            <div class="col-sm-4">
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewFoto"
                                    style="max-width: 265px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Jenis Jawaban</label>
                            <div class="col-sm-4">
                                <select class="form-select select-jenis" name="jenis" id="jenis">
                                    <option value=""></option>
                                    <option value="1">Single Choice</option>
                                    <option value="2">Multiple Choice</option>
                                    <option value="3">Essay</option>
                                    <option value="4">Select</option>
                                    <option value="5">Rating</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="jumlah_rating" class="col-sm-2 col-form-label">Jumlah Rating</label>
                            <div class="col-sm-2">
                                <input type="text" id="jumlah_rating" name="jumlah_rating"
                                    class="form-control format-number">
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <div class="col-sm-10 offset-sm-2">
                                <div id="jawaban-container">
                                    <div class="jawaban-row">
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="jawaban[]">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" class="btn btn-danger hapus-baris">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary tambah-baris"><i
                                            class="bi bi-plus-lg me-1"></i>Tambah Jawaban</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="button-text">Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select-jenis').select2({
                dropdownParent: $('body'),
                width: '100%',
                placeholder: 'Pilih Jenis Jawaban',
                allowClear: true,
            });

            if ($('body').hasClass('dark')) {
                $('.select2-container').addClass('select2-dark');
            }
        });

        $('#foto').on('change', function() {
            const file = this.files[0];
            const previewDiv = $('#previewFoto');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.html(
                        `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
                };
                reader.readAsDataURL(file);
            } else {
                previewDiv.html('<span style="color: #6c757d;">Tidak ada foto</span>');
            }
        });

        $(document).ready(function() {
            function toggleJenis(value) {
                if (value === "1" || value === "2" || value === "4") {
                    $("#jawaban-container").closest(".row.mb-3.mt-3").show();
                    $("#jumlah_rating").closest(".row.mb-3").hide();
                } else if (value === "5") {
                    $("#jumlah_rating").closest(".row.mb-3").show();
                    $("#jawaban-container").closest(".row.mb-3.mt-3").hide();
                } else {
                    $("#jawaban-container").closest(".row.mb-3.mt-3").hide();
                    $("#jumlah_rating").closest(".row.mb-3").hide();
                }
            }

            toggleJenis($("#jenis").val());

            $("#jenis").on("change", function() {
                toggleJenis($(this).val());
            });
        });

        $('.tambah-baris').on('click', function() {
            let newRow = `
             <div class="jawaban-row">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="jawaban[]">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger hapus-baris">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

            $('#jawaban-container').append(newRow);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('.jawaban-row').remove();
        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = '{{ $data->id }}';
            let url = '{{ route('data-soal.isi-store', ':id') }}'.replace(':id', id);
            let method = 'POST';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);
            formData.append('_method', method);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    sessionStorage.setItem('success', 'Soal berhasil ditambahkan!');
                    window.location.href = "{{ route('data-soal.show', ':id') }}".replace(':id', id);
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        audio.play();
                        toastr.error("Ada inputan yang salah!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            if (key.includes('.')) {
                                let parts = key.split('.');
                                let field = parts[0];
                                let index = parseInt(parts[1]);

                                let inputSelector;

                                if ($(`[name="${field}[]"]`).length > 0) {
                                    inputSelector = $(`[name="${field}[]"]`).eq(index);
                                } else {
                                    return;
                                }

                                inputSelector.addClass('is-invalid');
                                inputSelector.closest('.form-control, .form-select').parent()
                                    .find('.invalid-feedback').remove();
                                inputSelector.closest('.form-control, .form-select').parent()
                                    .append(
                                        `<span class="invalid-feedback" role="alert"><strong>${val[0]}</strong></span>`
                                    );
                            } else {
                                let input = $('#' + key);
                                input.addClass('is-invalid');
                                input.parent().find('.invalid-feedback').remove();
                                input.parent().append(
                                    '<span class="invalid-feedback" role="alert"><strong>' +
                                    val[0] + '</strong></span>'
                                );
                            }
                        });

                        spinner.addClass('d-none');
                        btnText.text('Simpan');
                        submitBtn.prop('disabled', false);
                    }
                }
            });
        });
    </script>
@endpush
