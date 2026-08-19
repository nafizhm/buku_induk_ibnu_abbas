@extends('admin.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h2 class="page-title text-truncate text-dark font-weight-bold">Kenaikan Kelas</h2>
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
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th width="100px" class="text-center">Jumlah Siswa</th>
                                    <th width="150px" class="text-center">Action</th>
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

@push('scripts')
    <script>
        let table;

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
                    url: "{{ route('kenaikan-kelas.index') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kelas',
                        name: 'kelas',
                    },
                    {
                        data: 'wali_kelas',
                        name: 'wali_kelas',
                    },
                    {
                        data: 'jumlah_siswa',
                        name: 'jumlah_siswa',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    },
                ]
            });
        });

        function reloadTable() {
            table.ajax.reload(null, false);
        }
    </script>
@endpush
