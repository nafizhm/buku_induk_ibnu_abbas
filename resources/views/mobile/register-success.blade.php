@extends('mobile.mobile-layout')

@section('content')
    <div class="flex h-dvh justify-center overflow-x-clip bg-muted select-none">
        <div
            class="relative flex w-full max-w-md flex-col overflow-hidden overscroll-x-none border-x border-border bg-background">
            <div class="p-6 min-h-full">
                <div class="flex h-full flex-col items-center justify-center gap-4 text-center">

                    <div class="rounded-full bg-chart-3/10 p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-chart-3">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                            <path d="m9 11 3 3L22 4" />
                        </svg>
                    </div>

                    <div class="space-y-1">
                        <h1 class="text-xl font-medium">Akun Berhasil Dibuat</h1>

                        <p class="text-sm text-muted-foreground">
                            Akun orang tua kamu sudah aktif. Gunakan nomor telepon sebagai username untuk masuk.
                        </p>
                    </div>

                    <a href="{{ route('mobile.login') }}" class="w-full">
                        <x-button type="button" class="w-full">
                            Masuk Sekarang
                        </x-button>
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection
