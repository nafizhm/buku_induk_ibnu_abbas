@extends('admin.layout')
@section('content')
    <div class="page-title">
        <div class="row mb-3">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>Hasil Supervisi Akademik</h4>
            </div>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="row">
                @foreach ($soals as $index => $soal)
                    @php
                        $jawabanSoal = $jawaban[$soal->id] ?? collect();
                    @endphp

                    <div class="col-sm-12 d-flex align-items-stretch">
                        <div class="card flex-fill">
                            <div class="card-header">
                                {{ $soal->soal }}
                            </div>
                            <div class="card-body">

                                @if ($soal->jenis == 1)
                                    @foreach ($soal->detailJawaban as $detail)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="jawaban[{{ $soal->id }}]" value="{{ $detail->id }}"
                                                {{ $jawabanSoal->pluck('id_jawaban')->contains($detail->id) ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label">{{ $detail->jawaban }}</label>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($soal->jenis == 2)
                                    @foreach ($soal->detailJawaban as $detail)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="jawaban[{{ $soal->id }}][]" value="{{ $detail->id }}"
                                                {{ $jawabanSoal->pluck('id_jawaban')->contains($detail->id) ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label">{{ $detail->jawaban }}</label>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($soal->jenis == 3)
                                    <div class="form-group with-title">
                                        <textarea class="form-control" rows="3" disabled>{{ $jawabanSoal->first()->jawaban_essay ?? '' }}</textarea>
                                        <label>Jawaban</label>
                                    </div>
                                @endif

                                @if ($soal->jenis == 4)
                                    <div class="form-group">
                                        <select class="form-select" disabled>
                                            <option value=""></option>
                                            @foreach ($soal->detailJawaban as $detail)
                                                <option value="{{ $detail->id }}"
                                                    {{ $jawabanSoal->pluck('id_jawaban')->contains($detail->id) ? 'selected' : '' }}>
                                                    {{ $detail->jawaban }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($soal->jenis == 5)
                                    @php
                                        $jumlahBintang = (int) ($soal->detailJawaban->first()->jawaban ?? 5);
                                        $nilai = $jawabanSoal->first()->id_jawaban ?? 0;
                                    @endphp
                                    <div class="d-flex justify-content-center align-items-center" style="min-height:100px;">
                                        <div class="rating-star d-flex justify-content-center disabled">
                                            @for ($i = 1; $i <= $jumlahBintang; $i++)
                                                <i class="bi bi-star{{ $i <= $nilai ? '-fill text-warning' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </section>

    </div>
@endsection

@push('scripts')
    <script>
        // document.querySelectorAll('.rating-star').forEach(starContainer => {
        //     let soalId = starContainer.getAttribute('data-soal');
        //     let hiddenInput = starContainer.parentElement.querySelector(`input[name="jawaban[${soalId}]"]`);
        //     let stars = starContainer.querySelectorAll('i');

        //     stars.forEach(star => {
        //         star.addEventListener('click', () => {
        //             let rating = star.getAttribute('data-value');

        //             stars.forEach(s => {
        //                 s.classList.remove('active');
        //                 if (s.getAttribute('data-value') <= rating) {
        //                     s.classList.add('active');
        //                 }
        //             });

        //             hiddenInput.value = rating;
        //         });
        //     });
        // });


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
    </script>
@endpush
