@extends('admin.layout')

@section('css')
@endsection

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <h4>Buku Penghubung</h4>
                        <hr>

                        <div class="text-right mb-3">
                            <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-form">
                                <i class="fas fa-plus"></i> Tambah Berita
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-penghubung">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="40px">#</th>
                                        <th width="200px">Tanggal/Hari</th>
                                        <th>Berita/Uraian</th>
                                        <th class="text-center" width="120px">Status Baca</th>
                                        <th class="text-center" width="100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penghubung as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</strong><br>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}</small>
                                            </td>
                                            <td class="text-justify">
                                                {{ $item->berita }}
                                            </td>
                                            <td class="text-center">
                                                @if ($item->is_read)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Sudah Dibaca
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Belum Dibaca
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!$item->is_read)
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="markAsRead({{ $item->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem({{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="fas fa-info-circle"></i> Belum ada data buku penghubung
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="modal-form" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form-buku-penghubung" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-book"></i> Form Buku Penghubung
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                            <label for="berita">
                                Berita/Uraian <span class="text-danger">*</span>
                            </label>
                            <textarea name="berita" id="berita" rows="5" class="form-control" placeholder="Masukkan berita atau uraian..."
                                required></textarea>
                            <div class="mt-2">
                                <small class="text-muted">Karakter: <span id="char-count">0</span>/1000</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Character counter
            $('#berita').on('input', function() {
                const length = $(this).val().length;
                $('#char-count').text(length);

                if (length > 1000) {
                    $('#char-count').addClass('text-danger');
                } else {
                    $('#char-count').removeClass('text-danger');
                }
            });

            // Handle form submission
            $('#form-buku-penghubung').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = new FormData(this);

                // Convert to uppercase
                const berita = $('#berita').val();
                formData.set('berita', berita);

                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: "{{ route('buku-penghubung.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $('#modal-form').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Reset form when modal is closed
            $('#modal-form').on('hidden.bs.modal', function() {
                $('#form-buku-penghubung')[0].reset();
                $('#char-count').text('0');
            });
        });

        // Function to mark as read
        function markAsRead(id) {
            Swal.fire({
                title: 'Tandai sebagai sudah dibaca?',
                text: 'Status ini menandakan bahwa wali murid sudah membaca berita ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, tandai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/buku-penghubung/${id}/mark-read`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Gagal memperbarui status'
                            });
                        }
                    });
                }
            });
        }

        // Function to delete item
        function deleteItem(id) {
            Swal.fire({
                title: 'Hapus berita ini?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/buku-penghubung/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Gagal menghapus data'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
