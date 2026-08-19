@extends('admin.layout')
@section('content')
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Data Pengajar</h3>
                        <div class="d-flex align-items-center">
                            @if (isset($permissions['tambah']) && $permissions['tambah'] == 1)
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                                    <i class="bi bi-plus-lg"></i> Tambah Pengajar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table data-table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="50px">No</th>
                                <th>Kode Guru</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Jabatan</th>
                                <th>Pendidikan</th>
                                <th>No Telepon</th>
                                <th>Status</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade text-left" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false" data-focus="false">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="modalFormLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>

                <form id="formData">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="primary_id" name="primary_id">

                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Kode Guru <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="kode_pengajar" id="kode_pengajar" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">NIP <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nip" id="nip" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Nama <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="nama" id="nama" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="password" name="password" id="password" class="form-control">
                                        <small class="text-muted">Minimal 8 karakter</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Jenis Kelamin <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="jenis_kelamin" id="jenis_kelamin"
                                            class="form-select select-jenis-kelamin" required>
                                            <option value=""></option>
                                            <option value="L">Laki - laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Jabatan <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="jabatan" id="jabatan" class="form-select select-jabatan" required>
                                            <option value=""></option>
                                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                                            <option value="Wakil Kepala">Wakil Kepala</option>
                                            <option value="Guru Mapel">Guru Mapel</option>
                                            <option value="Guru BK">Guru BK</option>
                                            <option value="Wali Kelas">Wali Kelas</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Tempat Lahir <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Pendidikan Terakhir <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="pendidikan_terakhir" id="pendidikan_terakhir"
                                            class="form-select select-pendidikan" required>
                                            <option value=""></option>
                                            <option value="SLTA">SLTA</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">No Telepon <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_telepon" id="no_telepon" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="is_active" id="is_active" class="form-select select-status"
                                            required>
                                            <option value=""></option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Alamat <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                                <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="button-text">Simpan</span>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var permissions = @json($permissions);
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(document).ready(function() {
            $('.select-jenis-kelamin, .select-jabatan, .select-pendidikan, .select-status').select2({
                dropdownParent: $('#modalForm'),
                width: '100%',
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih opsi",
            });
        });

        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ordering: false,
                ajax: "{{ route('pengajar.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kode_pengajar',
                        name: 'kode_pengajar',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'nip',
                        name: 'nip',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'pendidikan_terakhir',
                        name: 'pendidikan_terakhir',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'no_telepon',
                        name: 'no_telepon',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, ]
            });
        });

        $(document).on('click', '[data-bs-target="#modalForm"]', function() {
            $('#modalFormLabel').text('Tambah Data Guru');
            $('#password').prop('required', true);
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalFormLabel').text('Edit Data Guru');
                    $('#primary_id').val(response.data.id);
                    $('#kode_pengajar').val(response.data.kode_pengajar);
                    $('#nip').val(response.data.nip);
                    $('#nama').val(response.data.nama);
                    $('#jenis_kelamin').val(response.data.jenis_kelamin).trigger('change');
                    $('#jabatan').val(response.data.jabatan).trigger('change');
                    $('#tempat_lahir').val(response.data.tempat_lahir);
                    $('#tanggal_lahir').val(response.data.tanggal_lahir);
                    $('#pendidikan_terakhir').val(response.data.pendidikan_terakhir).trigger('change');
                    $('#no_telepon').val(response.data.no_telepon);
                    $('#is_active').val(response.data.is_active ? '1' : '0').trigger('change');
                    $('#alamat').val(response.data.alamat);

                    $('#password').prop('required', false);
                    $('#password').val('');

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#jenis_kelamin, #jabatan, #pendidikan_terakhir, #is_active').val('').trigger('change');
            $('#password').prop('required', true);

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
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
            let url = id ? '{{ route('pengajar.update', ['pengajar' => ':id']) }}'.replace(':id', id) :
                '{{ route('pengajar.store') }}';
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
                    let msg = id ? "Data guru berhasil diupdate!" : "Data guru berhasil ditambahkan!";
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

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data guru ini akan dihapus secara permanen!',
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
                                toastr.success("Data guru telah dihapus!",
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
                                toastr.error("Gagal menghapus data pengajar.",
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
