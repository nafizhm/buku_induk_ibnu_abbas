@extends('mobile.mobile-layout')

@section('content')
    <div class="flex h-dvh justify-center overflow-x-clip bg-muted select-none">
        <div
            class="relative flex w-full max-w-md flex-col overflow-hidden overscroll-x-none border-x border-border bg-background [-webkit-tap-highlight-color:transparent]">
            <div class="p-6 min-h-full">
                <div class="flex h-full flex-col">
                    <div class="flex flex-1 flex-col justify-center gap-4">

                        {{-- Branding --}}
                        <div class="w-fit rounded-md bg-chart-3 p-2">
                            {{-- Icon AMD --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                {{-- SVG AMD --}}
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-xl font-medium">
                                Selamat Datang!
                            </h1>

                            <p class="text-sm text-muted-foreground">
                                Silahkan masukkan email dan password anda untuk akses fitur komunitas
                            </p>
                        </div>

                        {{-- Login Form --}}
                        <form method="POST" action="{{ route('login') }}" class="space-y-2">
                            @csrf

                            <div class="space-y-2">

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label for="email" class="text-sm leading-none">
                                        Email
                                    </label>

                                    <x-input id="email" type="email" name="email" autocomplete="email"
                                        placeholder="Masukkan email anda" value="{{ old('email') }}" required />
                                </div>

                                {{-- Password --}}
                                <div class="space-y-2">
                                    <label for="password" class="text-sm leading-none">
                                        Password
                                    </label>

                                    <x-password id="password" name="password" autocomplete="current-password"
                                        placeholder="Masukkan password anda" required />
                                </div>

                                <x-select name="status" value="draft" size="lg" placeholder="Pilih status...">
                                    <x-select.trigger />

                                    <x-select.content>
                                        <x-select.group>
                                            <x-select.label>Status</x-select.label>

                                            <x-select.item value="draft" label="Draft">Draft</x-select.item>
                                            <x-select.item value="published" label="Published">Published</x-select.item>

                                            <x-select.separator />

                                            <x-select.item value="archived" label="Archived" disabled>
                                                Archived
                                            </x-select.item>
                                        </x-select.group>
                                    </x-select.content>
                                </x-select>

                                {{-- Error --}}
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
                                        Masuk
                                    </x-button>
                                </div>

                            </div>
                        </form>

                        {{-- Register --}}
                        <p class="text-center text-sm text-muted-foreground">
                            Belum punya akun?

                            <a href="" class="font-medium text-primary underline-offset-4 hover:underline">
                                Daftar sekarang
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
