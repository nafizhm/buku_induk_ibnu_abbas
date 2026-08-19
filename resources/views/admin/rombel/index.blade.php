@extends('admin.layout')
@section('content')
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Rombongan Belajar</h3>
                        <div class="d-flex align-items-center gap-2">
                            <a href="javascript:void(0);" onclick="syncRombel()" class="btn btn-info btn-rounded btn-sm"><i
                                    class="bi bi-arrow-repeat"></i> Sinkronisasi</a>
                            <a type="button" class="btn btn-primary btn-rounded btn-sm ml-2" data-bs-toggle="modal"
                                data-bs-target="#modalForm" id="btn-add">
                                <i class="bi bi-plus-lg"></i> Tambah Rombel
                            </a>
                            <a href="javascript:void(0);" onclick="reloadTable()"
                                class="btn btn-secondary btn-rounded btn-sm ml-2" title="Reload Tabel">
                                <i class="bi bi-arrow-repeat"></i> Reload
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table data-table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="40px">#</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Wali Kelas</th>
                                <th width="120px" class="text-center">Jumlah Siswa</th>
                                <th width="200px" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade text-left" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" data-focus="false">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="modalFormLabel">Tambah Rombel</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form id="formData">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="rombel_id" name="rombel_id">
                        <input type="hidden" id="tahun_ajaran_id" name="tahun_ajaran_id" value="{{ $tahun_id }}">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenjang_kelas_id">Jenjang Kelas <span class="text-danger">*</span></label>
                                <select name="jenjang_kelas_id" id="jenjang_kelas_id" class="form-select select-jenjang">
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nama">Nama Kelas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Masukkan Nama Kelas">
                            </div>

                            <div class="mb-3">
                                <label for="walas_id">Wali Kelas <span class="text-danger">*</span></label>
                                <select name="walas_id" id="walas_id" class="form-select select-walas">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
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
    <script>
        let table;
        let isEdit = false;

        var permissions = @json($permissions);
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(function() {
            let tahun1 = @json($tahun1);
            let tahun2 = @json($tahun2);

            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: "{{ route('rombel.index', [':tahun1', ':tahun2']) }}".replace(':tahun1', tahun1)
                        .replace(':tahun2', tahun2),
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
                    },
                    {
                        data: 'wali_kelas',
                        name: 'wali_kelas',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jumlah_siswa',
                        name: 'jumlah_siswa',
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
                    },
                ]
            });
        });

        $(document).ready(function() {
            $('.select-jenjang').select2({
                width: "100%",
                placeholder: 'Pilih Jenjang Kelas',
                dropdownParent: $('#modalForm'),
                ajax: {
                    url: "{{ route('get-jenjang') }}",
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.jenjang
                                }
                            })
                        };
                    }
                }
            });

            $('.select-walas').select2({
                width: "100%",
                placeholder: 'Pilih Wali Kelas',
                dropdownParent: $('#modalForm'),
                ajax: {
                    url: "{{ route('get-guru') }}",
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                }
                            })
                        };
                    }
                }
            });

            $('#btn-add').on('click', function() {
                isEdit = false;
                $('#formData')[0].reset();
                $('#rombel_id').val('');
                $('#btn-save').text('Simpan');
                $('#modalForm').modal('show');
            });

            $('#modalForm').on('hidden.bs.modal', function() {
                $('#formData')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $('#jenjang_kelas_id').val(null).trigger('change');
                $('#walas_id').val(null).trigger('change');
            })

            $(document).on('click', '.editRombel', function() {
                isEdit = true;
                const id = $(this).data('id');

                $.get(`/rombel/${id}`, function(data) {
                    $('#rombel_id').val(data.id);

                    // Set Jenjang (wajib ada)
                    let newOption = new Option(data.jenjang, data.jenjang_kelas_id, true, true);
                    $('#jenjang_kelas_id').append(newOption).trigger('change');

                    // Cek dulu kalau walas_id dan nama_walas valid
                    if (data.walas_id && data.nama_walas) {
                        let newWalas = new Option(data.nama_walas, data.walas_id, true, true);
                        $('#walas_id').append(newWalas).trigger('change');
                    } else {
                        // Clear select2 jika kosong
                        $('#walas_id').val(null).trigger('change');
                    }

                    $('#nama').val(data.nama || ''); // amanin kalau null
                    $('#btn-save').text('Update');
                    $('#modalForm').modal('show');
                });
            });

            $('#formData').on('submit', function(e) {
                e.preventDefault();

                let id = $('#rombel_id').val();
                let nama = $('#nama').val();
                let tahun_ajaran_id = $('#tahun_ajaran_id').val();
                let method = isEdit ? 'PUT' : 'POST';
                let url = isEdit ? `{{ route('rombel.update', ':id') }}`.replace(':id', id) :
                    `{{ route('rombel.store') }}`;

                let submitBtn = $('#submitBtn');
                let spinner = submitBtn.find('.spinner-border');
                let btnText = submitBtn.find('.button-text');

                spinner.removeClass('d-none');
                btnText.text('Menyimpan...');
                submitBtn.prop('disabled', true);

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        nama: nama,
                        jenjang_kelas_id: $('#jenjang_kelas_id').val(),
                        tahun_ajaran_id: tahun_ajaran_id,
                        walas_id: $('#walas_id').val(),
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(res) {
                        $('#modalForm').modal('hide');
                        $('#formData')[0].reset();
                        $('#table').DataTable().ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
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

                        spinner.addClass('d-none');
                        btnText.text('Simpan');
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '.deleteRombel', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('rombel.destroy', ':id') }}`.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.status == 'success') {
                                    $('#table').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: res.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: res.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops!',
                                    text: 'Terjadi kesalahan saat menghapus data.'
                                });
                            }
                        });
                    }
                });
            });
        });

        function reloadTable() {
            table.ajax.reload(null, false);
        }

        let tahun = @json($tahun_id);

        function syncRombel() {
            Swal.fire({
                title: 'Menyinkronkan data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ route('syncRombel', ':tahun') }}`.replace(':tahun', tahun),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    Swal.close();
                    if (res.status == 'success') {
                        $('#table').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Terjadi kesalahan saat menyinkronkan data.'
                    });
                }
            });
        }
    </script>
@endpush
