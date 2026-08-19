@extends('admin.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="page-title text-truncate text-dark font-weight-bold mb-1">Data Siswa Kelas</h2>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <h3 class="card-title">Data Kelas</h3>
                        <div class="d-flex">
                            <a href="javascript:void(0);" onclick="reloadTable()"
                                class="btn btn-default btn-rounded btn-sm ml-2" title="Reload Tabel">
                                <i class="fas fa-sync-alt"></i> Reload
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped data-table">
                            <thead>
                                <tr>
                                    <th width="40px">No</th>
                                    <th>Nama Siswa</th>
                                    <th width="250px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
        let table;
        const ajaxUrl = "{{ route('kenaikanSiswa', [$rombel, $tahunAjaran]) }}";

        function loadRombelOptions() {
            let rombel = "{{ $rombel }}";
            $.ajax({
                url: "{{ route('getRombelTujuan') }}",
                method: "GET",
                data: {
                    rombel_id: rombel
                },
                success: function(res) {
                    if (res.status == success) {
                        $('.select-naik').each(function() {
                            let options = `<option value="">Naik Kelas</option>`;
                            res.data.naik.forEach(r => {
                                options += `<option value="${r.id}">${r.kelas}</option>`;
                            });
                            $(this).html(options);
                        });

                        $('.select-tinggal').each(function() {
                            let options = `<option value="">Tinggal Kelas</option>`;
                            res.data.tinggal.forEach(r => {
                                options += `<option value="${r.id}">${r.kelas}</option>`;
                            });
                            $(this).html(options);
                        })
                    }
                },
                error: function() {
                    alert("Terjadi Kesalahan.");
                }
            });
        }

        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                paging: false,
                info: false,
                ordering: false,
                searching: false,
                ajax: {
                    url: ajaxUrl,
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa',
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                    <select class="btn btn-info btn-sm select-naik" data-siswa-id="${row.id}">
                                        <option value="">Naik Kelas</option>
                                    </select>
                                    <select class="btn btn-danger btn-sm select-tinggal" data-siswa-id="${row.id}">
                                        <option value="">Tinggal Kelas</option>
                                    </select>
                                `;
                        },
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });

            setTimeout(() => {
                loadRombelOptions();
            }, 300);
        });

        $(document).on('change', '.select-naik', function() {
            let siswa_id = $(this).data('siswa-id');
            let rombel_id = $(this).val();

            let url = `{{ route('changeKelas') }}`;
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    siswa_id: siswa_id,
                    rombel_id: rombel_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {},
                error: function() {
                    alert("Terjadi Kesalahan.");
                }
            });
        });

        function reloadTable() {
            table.ajax.reload(null, false);
        }
    </script>
@endsection
