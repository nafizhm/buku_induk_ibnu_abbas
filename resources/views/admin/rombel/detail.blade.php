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
                    <h2 class="page-title text-truncate text-dark font-weight-bold">Rombel</h2>
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
                        <h3 class="card-title">Data Siswa Tahun Ajaran <span
                                class="font-weight-bold">{{ $tahun }}</span></h3>
                        <div class="d-flex">
                            @php
                                $isGuru = !empty(Auth::user()->id_guru) && Auth::user()->id_guru != 0;
                            @endphp
                            @if ($isGuru)
                                <a href="{{ route('absensi.detail', $rombel) }}"
                                    class="btn btn-info btn-rounded btn-sm ml-2" title="Data Absensi">
                                    <i class="fas fa-book-open"></i> Data Absensi
                                </a>
                            @endif
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

    <div class="modal fade" id="modal-form">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Calon Siswa</h4>
                </div>
                <div class="modal-body">
                    <form id="form-kelasSiswa">
                        @csrf
                        <div class="table-responsive">
                            <table id="table-calonSiswa" class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NISN</th>
                                        <th>NIPD</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th width="150px" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
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

        const ajaxUrl = "{{ route('detailKelas', [$rombel]) }}";

        $(document).ready(function() {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });
        });

        function reloadTable() {
            table.ajax.reload(null, false);
        }
    </script>
@endsection
