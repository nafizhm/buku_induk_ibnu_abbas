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
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-xl font-medium">
                                Buat Akun
                            </h1>

                            <p class="text-sm text-muted-foreground">
                                Lengkapi data berikut untuk mendaftarkan akun orang tua / wali
                            </p>
                        </div>

                        {{-- Register Form --}}
                        <form method="POST" action="{{ route('mobile.register') }}" class="space-y-2" x-data="{ pw: '', pw2: '', role: 'ayah' }">
                            @csrf

                            <input type="hidden" name="peran" :value="role">

                            <div class="space-y-2">

                                {{-- Tab Ayah / Ibu --}}
                                <div class="flex rounded-lg border border-border bg-muted p-0.5">
                                    <button type="button" @click="role = 'ayah'"
                                        class="flex-1 rounded-md py-2 text-sm font-medium transition-colors"
                                        :class="role === 'ayah' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'">
                                        Ayah
                                    </button>
                                    <button type="button" @click="role = 'ibu'"
                                        class="flex-1 rounded-md py-2 text-sm font-medium transition-colors"
                                        :class="role === 'ibu' ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'">
                                        Ibu
                                    </button>
                                </div>

                                {{-- Kelas --}}
                                <div class="space-y-2">
                                    <label class="text-sm leading-none">Kelas</label>

                                    <x-select name="kelas" size="lg" placeholder="Pilih kelas...">
                                        <x-select.trigger />

                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Kelas</x-select.label>

                                                {{-- Ganti dengan data kelas dari backend --}}
                                                <x-select.item value="1" label="Kelas 1">Kelas 1</x-select.item>
                                                <x-select.item value="2" label="Kelas 2">Kelas 2</x-select.item>
                                                <x-select.item value="3" label="Kelas 3">Kelas 3</x-select.item>
                                                <x-select.item value="4" label="Kelas 4">Kelas 4</x-select.item>
                                                <x-select.item value="5" label="Kelas 5">Kelas 5</x-select.item>
                                                <x-select.item value="6" label="Kelas 6">Kelas 6</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                {{-- Siswa --}}
                                <div class="space-y-2">
                                    <label class="text-sm leading-none">Siswa</label>

                                    <x-select name="siswa" size="lg" searchable placeholder="Pilih siswa...">
                                        <x-select.trigger />

                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Data Siswa</x-select.label>

                                                {{-- Ganti dengan data siswa dari backend --}}
                                                <x-select.item value="1" label="Ahmad Fauzi">Ahmad Fauzi</x-select.item>
                                                <x-select.item value="2" label="Siti Aminah">Siti Aminah</x-select.item>
                                                <x-select.item value="3" label="Muhammad Rizki">Muhammad Rizki</x-select.item>
                                                <x-select.item value="4" label="Fatimah Zahra">Fatimah Zahra</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                {{-- No. Telp --}}
                                <div class="space-y-2">
                                    <label for="phone" class="text-sm leading-none">
                                        No. Telepon <span x-text="role === 'ayah' ? 'Ayah' : 'Ibu'"></span>
                                    </label>

                                    <x-input id="phone" type="tel" name="phone" autocomplete="tel"
                                        placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required />
                                </div>

                                {{-- Password --}}
                                <div class="space-y-2">
                                    <label for="password" class="text-sm leading-none">Password</label>

                                    <x-password id="password" name="password" x-model="pw"
                                        autocomplete="new-password" placeholder="Masukkan password" required />
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div class="space-y-2">
                                    <label for="password_confirmation" class="text-sm leading-none">
                                        Konfirmasi Password
                                    </label>

                                    <x-password id="password_confirmation" name="password_confirmation" x-model="pw2"
                                        autocomplete="new-password" placeholder="Ulangi password" required />

                                    <p x-show="pw2 !== '' && pw !== pw2" x-cloak class="text-xs text-destructive">
                                        Password tidak cocok
                                    </p>
                                </div>

                                {{-- Error --}}
                                @if ($errors->any())
                                    <p class="text-sm text-destructive" role="alert">
                                        {{ $errors->first() }}
                                    </p>
                                @endif

                                {{-- Submit --}}
                                <div>
                                    <x-button type="submit" class="w-full">
                                        Daftar
                                    </x-button>
                                </div>

                            </div>
                        </form>

                        {{-- Login --}}
                        <p class="text-center text-sm text-muted-foreground">
                            Sudah punya akun?

                            <a href="{{ route('login') }}" class="font-medium text-primary underline-offset-4 hover:underline">
                                Masuk
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
