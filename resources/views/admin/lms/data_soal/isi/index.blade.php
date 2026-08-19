@extends('admin.layout')
@section('content')
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Isi Soal {{ $data->nama }}</h3>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('data-soal.isi-create', $data->id) }}" class="btn btn-primary btn-sm"><i
                                    class="bi bi-plus-lg"></i> Tambah Soal</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table data-table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="50px">No</th>
                                <th>Soal</th>
                                <th width="180px">Jenis Jawaban</th>
                                <th width="100px" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script></script>
    <script>
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(document).ready(function() {
            const successMsg = sessionStorage.getItem('success');
            if (successMsg) {
                audio.play();
                toastr.success(successMsg, "BERHASIL", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                sessionStorage.removeItem('success');
            }
        });

        $(function() {
            var id = '{{ $data->id }}';
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: true,
                ordering: false,
                responsive: true,
                ajax: "{{ route('data-soal.show', ':id') }}".replace(':id', id),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'soal',
                        name: 'soal',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
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
                }]
            });
        });

        // Hapus data
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Soal ini akan dihapus secara permanen!',
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
                                toastr.success("Soal telah dihapus!",
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
                                toastr.error("Gagal menghapus Soal.",
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
