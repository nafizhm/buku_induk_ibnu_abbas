@extends('admin.layout')
@section('content')
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Upload Tugas</h3>
                        <div class="d-flex align-items-center">
                            @if (isset($permissions['tambah']) && $permissions['tambah'] == 1)
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                                    <i class="bi bi-plus-lg"></i> Tambah Tugas
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table data-table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Guru</th>
                                    <th>Nama Tugas</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Akhir</th>
                                    <th width="100px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade text-left" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false" data-focus="false">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="myModalLabel160">Form Data Tugas
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formData" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="primary_id" name="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="name" class="col-sm-3 col-form-label">Nama Tugas</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">File</label>
                            <div class="col-sm-4">
                                <input type="file" name="file" id="file" class="form-control"
                                    accept=".png, .jpg, .jpeg, .pdf">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewFoto"
                                    style="max-width: 165px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Tanggal Mulai</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
                            </div>
                            <label class="col-sm-2 col-form-label">Jam Mulai</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="jam_mulai" name="jam_mulai">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Tanggal Akhir</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir">
                            </div>
                            <label class="col-sm-2 col-form-label">Jam Akhir</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="jam_akhir" name="jam_akhir">
                            </div>
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <span class="button-text">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                            aria-hidden="true"></span>
                        <span class="button-text">Simpan</span>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#jam_mulai, #jam_akhir').mask('00:00');
        });

        $(document).ready(function() {
            $('.select-status').select2({
                dropdownParent: $('#modalForm'),
                width: '100%',
                placeholder: 'Pilih Status',
                allowClear: true,
                minimumResultsForSearch: Infinity,
            });

            if ($('body').hasClass('dark')) {
                $('.select2-container').addClass('select2-dark');
            }
        });

        $('#file').on('change', function() {
            const file = this.files[0];
            const previewDiv = $('#previewFoto');

            if (!file) {
                previewDiv.html('<span style="color: #6c757d;">Tidak ada foto</span>');
                return;
            }

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.html(
                        `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                const url = URL.createObjectURL(file);
                previewDiv.html(`
            <div class="text-center">
                <a href="${url}" target="_blank">Preview PDF</a>
            </div>
        `);
            } else {
                previewDiv.html('<span style="color: #6c757d;">Format file tidak didukung</span>');
            }
        });


        var permissions = @json($permissions);
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);
        var visibleGuruColumn = {{ $auth->id_role == 2 ? 'false' : 'true' }};
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: true,
                ordering: false,
                responsive: true,
                ajax: "{{ route('upload-tugas.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },

                    {
                        data: 'guru',
                        name: 'guru',
                        orderable: false,
                        searchable: false,
                        visible: visibleGuruColumn
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'waktu_mulai',
                        name: 'waktu_mulai',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'waktu_akhir',
                        name: 'waktu_akhir',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: showActionColumn,
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }]
            });
        });

        // Tombol edit
        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#primary_id').val(response.data.id);
                    $('#nama').val(response.data.nama);
                    $('#tgl_mulai').val(response.data.tgl_mulai);
                    $('#tgl_akhir').val(response.data.tgl_akhir);
                    $('#jam_mulai').val(response.data.jam_mulai);
                    $('#jam_akhir').val(response.data.jam_akhir);

                    let file = response.data.file;
                    let preview = $('#previewFoto');

                    if (file) {
                        let ext = file.split('.').pop().toLowerCase();
                        let fileUrl = '/assets/bahan_tugas/' + file;

                        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                            preview.html(
                                `<img src="${fileUrl}" alt="File" style="max-height: 100%; max-width: 100%;">`
                            );
                        } else if (ext === 'pdf') {
                            preview.html(
                                `<div class="text-center">
                            <a href="${fileUrl}" target="_blank">Preview PDF</a>
                        </div>`
                            );
                        } else {
                            preview.html('<span style="color: #6c757d;">Format file tidak didukung</span>');
                        }
                    } else {
                        preview.html('<span style="color: #6c757d;">Tidak ada file</span>');
                    }

                    $('#modalForm').modal('show');
                }
            });
        });


        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('#status').val('').trigger('change');
            $('#id_role').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            $('#previewFoto').html('<span style="color: #6c757d;">Tidak ada foto</span>');

        });

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = id ? '{{ route('upload-tugas.update', ['upload_tuga' => ':id']) }}'.replace(':id',
                    id) :
                '{{ route('upload-tugas.store') }}';
            let method = id ? 'PUT' : 'POST';

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
                success: function(response) {
                    $('#modalForm').modal('hide');
                    audio.play();
                    let msg = id ? "Tugas berhasil diupdate!" : "Tugas berhasil ditambahkan!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    $('.data-table').DataTable().ajax.reload();
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
                            let input = $('#' + key);
                            input.addClass('is-invalid');
                            input.parent().find('.invalid-feedback').remove();
                            input.parent().append(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                val[0] + '</strong></span>'
                            );
                        });

                        spinner.addClass('d-none');
                        btnText.text('Simpan');
                        submitBtn.prop('disabled', false);
                    }
                }
            });
        });

        // Hapus data
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Tugas ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Hapus</span>',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: false,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-secondary'
                },
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const confirmBtn = Swal.getConfirmButton();
                        const btnText = confirmBtn.querySelector('.swal-btn-text');

                        btnText.innerHTML =
                            `<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...`;
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function() {
                                audio.play();
                                toastr.success("Tugas telah dihapus!",
                                    "BERHASIL", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right"
                                    });

                                $('.data-table').DataTable().ajax.reload(null,
                                    false);
                                Swal.close();
                            },
                            error: function() {
                                audio.play();
                                toastr.error("Gagal menghapus Tugas.",
                                    "GAGAL!", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right"
                                    });

                                btnText.innerHTML = `Ya, Hapus`;
                                confirmBtn.disabled = false;
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush
