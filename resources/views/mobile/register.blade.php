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
                                Buat Akun
                            </h1>

                            <p class="text-sm text-muted-foreground">
                                Lengkapi data berikut untuk mendaftarkan akun orang tua / wali
                            </p>
                        </div>

                        {{-- Register Form --}}
                        <form method="POST" action="{{ route('mobile.register.store') }}" class="space-y-2"
                            x-data="{
                                pw: '',
                                pw2: '',
                                role: 'ayah',
                                siswaList: [],
                                loading: false,
                                loaded: false,
                                async loadSiswa(kelasId) {
                                    if (!kelasId) return
                                    this.loading = true
                                    this.$dispatch('siswa-reset')
                                    try {
                                        const res = await fetch(`{{ url('mobile/register/siswa') }}/${kelasId}`)
                                        this.siswaList = (await res.json()).data ?? []
                                    } catch (e) {
                                        this.siswaList = []
                                    }
                                    this.loaded = true
                                    this.loading = false
                                }
                            }"
                            @select-change="$event.detail.name === 'kelas_id' && loadSiswa($event.detail.value)">
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

                                    <x-select name="kelas_id" size="lg" placeholder="Pilih kelas..."
                                        value="{{ old('kelas_id') }}">
                                        <x-select.trigger />

                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Kelas</x-select.label>

                                                @foreach ($kelas as $k)
                                                    <x-select.item value="{{ $k->id_kelas }}"
                                                        label="{{ $k->nama_kelas }}">
                                                        {{ $k->nama_kelas }}
                                                    </x-select.item>
                                                @endforeach
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                {{-- Siswa (multi pilih) --}}
                                <div class="space-y-2">
                                    <label class="text-sm leading-none">
                                        Anak <span class="text-muted-foreground">(boleh pilih lebih dari satu)</span>
                                    </label>

                                    <x-select name="siswa_ids" size="lg" searchable multiple
                                        placeholder="Pilih anak..." @siswa-reset.window="value = []">
                                        <x-select.trigger />

                                        <x-select.content>
                                            <li x-show="loading"
                                                class="px-2 py-1.5 text-sm text-muted-foreground list-none">
                                                Memuat data siswa...
                                            </li>

                                            <li x-show="!loading && !loaded"
                                                class="px-2 py-1.5 text-sm text-muted-foreground list-none">
                                                Pilih kelas terlebih dahulu untuk menampilkan daftar siswa.
                                            </li>

                                            <li x-show="!loading && loaded && siswaList.length === 0"
                                                class="px-2 py-1.5 text-sm text-muted-foreground list-none">
                                                Tidak ada siswa aktif di kelas ini.
                                            </li>

                                            <template x-for="s in siswaList" :key="s.id">
                                                <li @click="select(s.id, s.nama_lengkap)"
                                                    x-effect="items[s.id] = s.nama_lengkap"
                                                    x-show="!(query) || s.nama_lengkap.toLowerCase().includes(query.toLowerCase())"
                                                    role="option"
                                                    :aria-selected="value.includes(s.id)"
                                                    :data-value="s.id"
                                                    data-slot="select-item"
                                                    :data-size="size"
                                                    class="relative flex w-full cursor-pointer items-center gap-1.5 rounded-md outline-hidden select-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
                                                    x-bind:class="{
                                                        'py-2 pr-9 pl-2 text-sm': size === 'lg',
                                                        'bg-accent text-accent-foreground': active == s.id,
                                                    }">
                                                    <span class="flex flex-1 shrink-0 gap-2 whitespace-nowrap"
                                                        x-text="s.nama_lengkap"></span>

                                                    <span
                                                        class="pointer-events-none absolute right-2 flex size-4 items-center justify-center"
                                                        x-show="value.includes(s.id)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="size-4">
                                                            <path d="M20 6L9 17l-5-5" />
                                                        </svg>
                                                    </span>
                                                </li>
                                            </template>
                                        </x-select.content>
                                    </x-select>

                                    @if ($errors->has('siswa_ids'))
                                        <p class="text-xs text-destructive">{{ $errors->first('siswa_ids') }}</p>
                                    @endif
                                </div>

                                {{-- No. Telp --}}
                                <div class="space-y-2">
                                    <label for="phone" class="text-sm leading-none">
                                        No. Telepon <span x-text="role === 'ayah' ? 'Ayah' : 'Ibu'"></span>
                                    </label>

                                    <x-input id="phone" type="tel" name="phone" inputmode="numeric"
                                        autocomplete="tel" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}"
                                        required />
                                    <p class="text-xs text-muted-foreground">
                                        Nomor telepon ini akan menjadi username untuk masuk aplikasi.
                                    </p>
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

                            <a href="{{ route('mobile.login') }}"
                                class="font-medium text-primary underline-offset-4 hover:underline">
                                Masuk
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
