@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            @foreach ($menu as $item)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <a href="{{ route('detailKelas', $item->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm h-100 border-0 overflow-hidden transition-all"
                            style="transition: all 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-container me-3 flex-shrink-0">
                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <i class="bi bi-mortarboard text-white fs-3"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1 fw-bold text-white">
                                            Kelas {{ $item->jenjangKelas->jenjang }}
                                        </h5>
                                        <p class="card-text text-muted mb-0 fs-6">
                                            {{ $item->nama }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        Lihat Detail
                                    </small>
                                    <i class="fas fa-arrow-right text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .card .icon-container {
            transition: transform 0.3s ease;
        }

        .card:hover .icon-container {
            transform: scale(1.1);
        }

        .card:hover .fa-arrow-right {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .icon-container i {
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1rem !important;
            }

            .icon-container div {
                width: 50px !important;
                height: 50px !important;
            }

            .icon-container i {
                font-size: 1.25rem !important;
            }
        }
    </style>
@endsection
