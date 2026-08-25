@extends('mobile.mobile-layout')

@php
    $title = $title ?? 'Berhasil!';
    $description = $description ?? 'Proses berhasil diselesaikan.';
    $primaryLabel = $primaryLabel ?? 'Lanjutkan';
    $primaryUrl = $primaryUrl ?? route('mobile.login');
    $secondaryLabel = $secondaryLabel ?? null;
    $secondaryUrl = $secondaryUrl ?? null;
@endphp

@section('content')
    <div class="flex h-dvh justify-center overflow-x-clip bg-muted select-none">
        <div
            class="relative flex w-full max-w-md flex-col overflow-hidden overscroll-x-none border-x border-border bg-background">
            <div class="flex min-h-dvh flex-col items-center justify-center p-6 text-center">
                <div class="flex flex-col items-center gap-6 w-full max-w-sm">
                    {{-- Animated Icon --}}
                    <div class="relative">
                        {{-- ping ring --}}
                        <div class="absolute inset-0 rounded-full bg-chart-3/20 success-ping"></div>
                        {{-- main circle --}}
                        <div class="relative rounded-full bg-chart-3/10 p-5 success-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-chart-3">
                                {{-- circle --}}
                                <path class="success-circle" d="M21.801 10A10 10 0 1 1 17 3.335" />
                                {{-- check --}}
                                <path class="success-check" d="m9 11 3 3L22 4" />
                            </svg>
                        </div>
                        {{-- subtle dots --}}
                        <span class="absolute -top-1 -right-1 size-2 rounded-full bg-chart-2/60 success-dot"></span>
                        <span
                            class="absolute -bottom-1 -left-2 size-1.5 rounded-full bg-chart-4/50 success-dot delay-150"></span>
                    </div>

                    {{-- Text --}}
                    <div class="success-text pt-2">
                        <h1 class="text-lg font-medium tracking-tight text-foreground">
                            {{ $title }}
                        </h1>
                        <p class="text-sm leading-relaxed text-muted-foreground">
                            {{ $description }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex w-full flex-col gap-3 pt-2 success-actions">
                        <a href="{{ $primaryUrl }}" class="w-full">
                            <x-button type="button" class="w-full justify-center">
                                {{ $primaryLabel }}
                            </x-button>
                        </a>

                        @if ($secondaryLabel && $secondaryUrl)
                            <a href="{{ $secondaryUrl }}"
                                class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors underline-offset-4 hover:underline">
                                {{ $secondaryLabel }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .success-icon {
            animation: success-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        .success-ping {
            animation: success-ping 1.8s cubic-bezier(0, 0, 0.2, 1) 0.4s both;
        }

        .success-text {
            animation: success-fade-up 0.5s ease-out 0.35s both;
        }

        .success-actions {
            animation: success-fade-up 0.5s ease-out 0.55s both;
        }

        .success-dot {
            animation: success-dot-pop 0.4s ease-out 0.7s both;
        }

        .success-dot.delay-150 {
            animation-delay: 0.85s;
        }

        /* circle draw */
        .success-circle {
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: success-draw 0.7s ease-out 0.25s forwards;
        }

        .success-check {
            stroke-dasharray: 30;
            stroke-dashoffset: 30;
            animation: success-draw 0.4s ease-out 0.75s forwards;
        }

        @keyframes success-pop {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes success-ping {
            0% {
                transform: scale(0.8);
                opacity: 0.6;
            }

            100% {
                transform: scale(1.25);
                opacity: 0;
            }
        }

        @keyframes success-fade-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes success-draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes success-dot-pop {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .success-icon,
            .success-ping,
            .success-text,
            .success-actions,
            .success-dot,
            .success-circle,
            .success-check {
                animation: none;
                opacity: 1;
                transform: none;
                stroke-dashoffset: 0;
            }
        }
    </style>
@endsection
