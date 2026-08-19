@extends('admin.layout')

@section('content')
    <style>
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .table-container .table {
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        .table-container .thead-dark th {
            position: sticky !important;
            top: 0 !important;
            z-index: 1020 !important;
            background-color: #343a40 !important;
            border-color: #454d55 !important;
        }

        .table-container .table tbody tr:last-child td {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        @media (max-width: 768px) {
            .modal-xl {
                max-width: 95% !important;
            }

            .table-container {
                max-height: 300px;
            }
        }

        .modal-body {
            overflow: hidden !important;
        }
    </style>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="card-title">Data Siswa Tahun Ajaran <span
                                class="font-weight-bold">{{ $tahun['tahun'] }}</span></h3>
                        <div class="d-flex">
                            <a type="button" class="btn btn-primary btn-rounded btn-sm" id="btn-add">
                                <i class="fas fa-plus"></i> Tambah Siswa
                            </a>
                            <a href="javascript:void(0);" onclick="reloadTable()"
                                class="btn btn-default btn-rounded btn-sm ml-2" title="Reload Tabel">
                                <i class="fas fa-sync-alt"></i> Reload
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>NFC</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Kelas</th>
                                    <th width="150px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade text-left" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false" data-focus="false">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="modalFormLabel">Tambah Tahun Ajaran</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formData">
                        @csrf
                        <input type="hidden" id="primary_id" name="primary_id">
                        <div class="modal-body">
                            <!-- Alert Info -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Petunjuk:</strong> Pilih kelas untuk setiap calon siswa yang akan ditambahkan ke
                                data siswa
                                aktif.
                            </div>

                            <!-- Form dengan table full width -->
                            <div class="table-container">
                                <table id="table-calonSiswa" class="table table-bordered table-striped w-100">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th width="50px">No</th>
                                            <th width="120px">NISN</th>
                                            <th width="120px">NIPD</th>
                                            <th>Nama Lengkap</th>
                                            <th width="100px">Jenis Kelamin</th>
                                            <th width="120px">Tanggal Lahir</th>
                                            <th width="200px" class="text-center">Pilih Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan dimuat via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div>
                                <span class="text-muted">
                                    <i class="fas fa-check-circle text-success mr-1"></i>
                                    <span id="selected-count">0</span> siswa dipilih
                                </span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                                <button type="button" class="btn btn-primary" id="btn-save">
                                    <i class="fas fa-save mr-1"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;
        let tableCalon;
        let selectedData = [];

        function loadRombelOptions() {
            $.ajax({
                url: "{{ route('getRombel') }}",
                type: 'GET',
                success: function(response) {
                    if (response.status == 'success') {

                        const rombelOptions = response.data.map(rombel =>
                            `<option value="${rombel.id}">${rombel.jenjang_kelas.jenjang} ${rombel.nama}</option>`
                        ).join('');

                        console.log(rombelOptions);

                        $('.select-rombel').each(function() {
                            const currentValue = $(this).val();
                            $(this).html('<option value="">Pilih Kelas</option>' + rombelOptions);
                            if (currentValue) {
                                $(this).val(currentValue);
                            }
                        });
                    }
                },
                error: function() {
                    console.error('Gagal memuat data rombel');
                }
            });
        }

        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: "{{ route('siswa.index') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nisn',
                        name: 'nisn'
                    },
                    {
                        data: 'nfc_uid',
                        name: 'nfc_uid'
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin'
                    },
                    {
                        data: 'tanggal_lahir',
                        name: 'tanggal_lahir'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
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

        $('#btn-add').on('click', function() {
            $('#modal-form').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });

        // Handle modal show event untuk reset scroll
        $('#modal-form').on('shown.bs.modal', function() {
            // Reset scroll position
            $(this).find('.table-container').scrollTop(0);
        });

        $('#modal-form .btn-secondary').on('click', function() {
            resetModal();
        });

        $('#modal-form').on('hidden.bs.modal', function() {
            resetModal();
        });

        function resetModal() {
            selectedData = [];
            $('.select-rombel').val('').removeClass('border-success border-danger');
            updateSelectedCount();
        }

        $('#modal-form').on('show.bs.modal', function() {
            if (!$.fn.DataTable.isDataTable('#table-calonSiswa')) {
                tableCalon = $('#table-calonSiswa').DataTable({
                    processing: true,
                    serverSide: false,
                    lengthChange: false,
                    paging: false,
                    info: false,
                    ajax: {
                        url: "{{ route('listCalonSiswa') }}",
                        dataSrc: 'data'
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nisn',
                            orderable: false
                        },
                        {
                            data: 'nipd',
                            orderable: false
                        },
                        {
                            data: 'nama_lengkap'
                        },
                        {
                            data: 'jenis_kelamin',
                            searchable: false
                        },
                        {
                            data: 'tanggal_lahir',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                        <select class="form-control select-rombel" data-siswa-id="${row.id}" style="min-width: 150px;">
                                            <option value="">Pilih Kelas</option>
                                        </select>
                                    `;
                            },
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    drawCallback: function(settings) {
                        loadRombelOptions();
                    }
                });
            } else {
                tableCalon.ajax.reload();
            }
        });

        $(document).on('change', '.select-rombel', function() {
            const siswa_id = $(this).data('siswa-id');
            const rombel_id = $(this).val();

            if (rombel_id) {
                const existIndex = selectedData.findIndex(e => e.siswa_id == siswa_id);
                if (existIndex !== -1) {
                    selectedData[existIndex].rombel_id = rombel_id;
                } else {
                    selectedData.push({
                        siswa_id,
                        rombel_id
                    });
                }
                // Tambah class untuk visual feedback
                $(this).removeClass('border-danger').addClass('border-success');
            } else {
                selectedData = selectedData.filter(e => e.siswa_id != siswa_id);
                // Hapus class visual feedback
                $(this).removeClass('border-success border-danger');
            }

            // Update counter
            updateSelectedCount();
        });

        function updateSelectedCount() {
            $('#selected-count').text(selectedData.length);
        }

        $('#btn-save').click(function(e) {
            e.preventDefault();

            if (selectedData.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data Dipilih',
                    text: 'Silakan pilih kelas untuk minimal satu siswa terlebih dahulu.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Validasi apakah semua data memiliki rombel_id
            const invalidData = selectedData.filter(data => !data.rombel_id);
            if (invalidData.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Beberapa siswa belum dipilih kelasnya. Silakan lengkapi terlebih dahulu.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Disable button dan show loading
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');

            $.ajax({
                url: "{{ route('addSiswa') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    penempatan: selectedData
                },
                success: function(res) {
                    if (res.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#modal-form').modal('hide');
                        resetModal();
                        tableCalon.ajax.reload();
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message || 'Terjadi kesalahan saat menyimpan data.',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            html: Object.values(errors).join('<br>'),
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                },
                complete: function() {
                    // Re-enable button
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });

        function reloadTable() {
            table.ajax.reload(null, false);
        }

        // Handler untuk delete siswa
        $(document).on('click', '.deleteSiswa', function() {
            const siswaId = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data siswa akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('siswa.destroy', ':id') }}".replace(':id', siswaId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Terhapus!',
                                    response.message,
                                    'success'
                                );
                                table.ajax.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
