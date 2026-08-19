@extends('admin.layout')
@section('content')
    <div class="page-title">
        <div class="row mb-3">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>Capaian Rapor Kinerja Guru</h4>
            </div>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <form id="formData">
                @csrf
                <div class="row">
                    @foreach ($soals as $soal)
                        <div class="col-sm-12 d-flex align-items-stretch">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    {{ $soal->soal }}
                                </div>
                                <div class="card-body">
                                    {{-- Radio --}}
                                    @if ($soal->jenis == 1)
                                        @foreach ($soal->detailJawaban as $detail)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="jawaban[{{ $soal->id }}]" value="{{ $detail->id }}">
                                                <label class="form-check-label">
                                                    {{ $detail->jawaban }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- Checkbox --}}
                                    @if ($soal->jenis == 2)
                                        @foreach ($soal->detailJawaban as $detail)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="jawaban[{{ $soal->id }}][]" value="{{ $detail->id }}">
                                                <label class="form-check-label">
                                                    {{ $detail->jawaban }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- Textarea --}}
                                    @if ($soal->jenis == 3)
                                        <div class="form-group with-title">
                                            <textarea class="form-control" name="jawaban[{{ $soal->id }}]" rows="3"></textarea>
                                            <label>Jawaban</label>
                                        </div>
                                    @endif

                                    {{-- Select --}}
                                    @if ($soal->jenis == 4)
                                        <div class="form-group">
                                            <select class="form-select select-jawaban" name="jawaban[{{ $soal->id }}]">
                                                <option value=""></option>
                                                @foreach ($soal->detailJawaban as $detail)
                                                    <option value="{{ $detail->id }}">{{ $detail->jawaban }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    {{-- Rating --}}
                                    @if ($soal->jenis == 5)
                                        @php
                                            $jumlahBintang = (int) ($soal->detailJawaban->first()->jawaban ?? 5);
                                        @endphp
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="min-height:100px;">
                                            <div class="rating-star d-flex justify-content-center"
                                                data-soal="{{ $soal->id }}">
                                                @for ($i = 1; $i <= $jumlahBintang; $i++)
                                                    <i class="bi bi-star" data-value="{{ $i }}"></i>
                                                @endfor
                                            </div>
                                            {{-- hidden input untuk kirim nilai rating --}}
                                            <input type="hidden" name="jawaban[{{ $soal->id }}]" value="">
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        <span class="button-text">Simpan Capaian Kinerja</span>
                    </button>
                </div>
            </form>

        </section>

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.rating-star').forEach(starContainer => {
            let soalId = starContainer.getAttribute('data-soal');
            let hiddenInput = starContainer.parentElement.querySelector(`input[name="jawaban[${soalId}]"]`);
            let stars = starContainer.querySelectorAll('i');

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    let rating = star.getAttribute('data-value');

                    stars.forEach(s => {
                        s.classList.remove('active');
                        if (s.getAttribute('data-value') <= rating) {
                            s.classList.add('active');
                        }
                    });

                    hiddenInput.value = rating;
                });
            });
        });


        $(document).ready(function() {
            $('.select-jawaban').select2({
                dropdownParent: $('body'),
                width: '100%',
                placeholder: 'Pilih Jawaban',
                allowClear: true,
            });

            if ($('body').hasClass('dark')) {
                $('.select2-container').addClass('select2-dark');
            }
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

            let url = '{{ route('capaian-rapor-kinerja-guru.store') }}';
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
                    sessionStorage.setItem('success', 'Capaian berhasil ditambahkan!');
                    window.location.href = "{{ route('capaian-rapor-kinerja-guru.index') }}";
                    // spinner.addClass('d-none');
                    // btnText.text('Simpan Capaian Kinerja');
                    // submitBtn.prop('disabled', false);
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
                        $('.card').removeClass('border-danger shadow');
                        $('.invalid-feedback-card').remove();

                        $.each(errors, function(key, val) {
                            if (key.startsWith("jawaban.")) {
                                let soalId = key.split(".")[1];
                                let card = $(
                                    `[name="jawaban[${soalId}]"], [name="jawaban[${soalId}][]"]`
                                ).closest('.card');
                                card.addClass('border-danger shadow');
                                if (card.find('.invalid-feedback-card').length === 0) {
                                    card.append(
                                        `<div class="invalid-feedback-card text-danger p-2"><strong>${val[0]}</strong></div>`
                                    );
                                }
                            }
                        });

                        spinner.addClass('d-none');
                        btnText.text('Simpan Capaian Kinerja');
                        submitBtn.prop('disabled', false);
                    }
                }

            });
        });
    </script>
@endpush
