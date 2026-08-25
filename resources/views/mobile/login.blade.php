@extends('mobile.mobile-layout')

@section('content')
    <div class="flex h-dvh justify-center overflow-x-clip bg-muted select-none">
        <div
            class="relative flex w-full max-w-md flex-col overflow-hidden overscroll-x-none border-x border-border bg-background">
            <div class="p-6 min-h-full">
                <div class="flex h-full flex-col">
                    <div class="flex flex-1 flex-col justify-center gap-4">

                        {{-- Branding --}}
                        <div class="w-fit rounded-md bg-chart-3 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-xl font-medium">
                                Selamat Datang!
                            </h1>

                            <p class="text-sm text-muted-foreground">
                                Masukkan nomor telepon dan password untuk masuk
                            </p>
                        </div>

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('admin.loginPost') }}" class="space-y-2" x-data="{
                                loading: false,
                                error: '',
                                submit(e) {
                                    e.preventDefault()
                                    this.loading = true
                                    this.error = ''
                                    const form = e.target
                                    fetch(form.action, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                                        },
                                        body: JSON.stringify({
                                            username: form.username.value,
                                            password: form.password.value,
                                        })
                                    })
                                        .then(async res => {
                                            if (res.ok) {
                                                window.location.href = '{{ route('orang-tua.beranda') }}'
                                                return
                                            }
                                            const json = await res.json()
                                            const errors = json.errors ?? {}
                                            this.error = Object.values(errors)[0]?.[0] ?? 'Gagal masuk.'
                                        })
                                        .catch(() => this.error = 'Terjadi kesalahan, coba lagi.')
                                        .finally(() => this.loading = false)
                                }
                            }" @submit="submit($event)">
                            @csrf

                            <div class="space-y-2">

                                {{-- Username (No. Telepon) --}}
                                <div class="space-y-2">
                                    <label for="username" class="text-sm leading-none">
                                        No. Telepon
                                    </label>

                                    <x-input id="username" type="tel" name="username" inputmode="numeric"
                                        autocomplete="username" placeholder="08xxxxxxxxxx"
                                        value="{{ old('username') }}" required />
                                </div>

                                {{-- Password --}}
                                <div class="space-y-2">
                                    <label for="password" class="text-sm leading-none">
                                        Password
                                    </label>

                                    <x-password id="password" name="password" autocomplete="current-password"
                                        placeholder="Masukkan password anda" required />
                                </div>

                                {{-- Error --}}
                                <p x-show="error !== ''" x-text="error" x-cloak class="text-sm text-destructive"
                                    role="alert"></p>

                                @if ($errors->any())
                                    <p class="text-sm text-destructive" role="alert">
                                        {{ $errors->first() }}
                                    </p>
                                @endif

                                {{-- Forgot Password --}}
                                <div class="flex justify-end">
                                    <x-button type="button" variant="link" class="h-auto p-0 text-sm">
                                        Lupa password?
                                    </x-button>
                                </div>

                                {{-- Submit --}}
                                <div>
                                    <x-button type="submit" class="w-full">
                                        <span x-show="!loading">Masuk</span>
                                        <span x-show="loading" x-cloak>Memproses...</span>
                                    </x-button>
                                </div>

                            </div>
                        </form>

                        {{-- Register --}}
                        <p class="text-center text-sm text-muted-foreground">
                            Belum punya akun?

                            <a href="{{ route('mobile.register') }}"
                                class="font-medium text-primary underline-offset-4 hover:underline">
                                Daftar sekarang
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
