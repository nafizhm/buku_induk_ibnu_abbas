@extends('mobile.mobile-layout')

@php
    $lbl = 'block text-sm font-medium text-neutral-800';
    $field = 'space-y-1';
    $err = 'block text-xs text-red-600 mt-1';
@endphp

@section('content')
    <div class="flex min-h-dvh justify-center bg-slate-50 select-none">
        <div class="relative flex w-full max-w-md flex-col overflow-hidden border-x border-slate-200 bg-white">
            <div class="p-5 min-h-full">
    <div x-data="wizard()"
        @select-change="if ($event.detail.name === 'kewarganegaraan') kewarganegaraan = $event.detail.value; clearError($event.detail.name)"
        class="space-y-4">

                    {{-- Header --}}
                    <div>
                        <h1 class="text-lg font-semibold text-black">Pendaftaran Peserta Didik</h1>
                        <p class="text-xs text-neutral-500">Isi data dengan lengkap dan benar.</p>
                    </div>

                    {{-- Step Indicator --}}
                    <div class="flex gap-1.5">
                        <template x-for="n in 4" :key="n">
                            <div class="h-1.5 flex-1 rounded-full" :class="step >= n ? 'bg-primary' : 'bg-slate-200'"></div>
                        </template>
                    </div>

                        <form action="{{ route('siswa.daftar.store') }}" method="POST" class="space-y-5" @submit.prevent @input="if($event.target.name) clearError($event.target.name)" @change="if($event.target.name) clearError($event.target.name)">
                        @csrf

                        {{-- ================= STEP 1 ================= --}}
                        <div x-show="step === 1" x-cloak data-step="1" class="space-y-5">

                            <h2 class="text-sm font-semibold text-black">Step 1 &middot; Data Pribadi &amp; Kontak</h2>

                            {{-- Data Pribadi --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Data Pribadi</h3>

                                <div class="{{ $field }}">
                                    <label for="nama_lengkap" class="{{ $lbl }}">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <x-input id="nama_lengkap" name="nama_lengkap" type="text" placeholder="Nama lengkap" value="{{ old('nama_lengkap') }}" required />
                                    <p x-show="errors.nama_lengkap" class="{{ $err }}" x-text="errors.nama_lengkap"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <span class="{{ $lbl }}">Jenis Kelamin <span class="text-red-500">*</span></span>
                                    <div class="flex gap-4 pt-1">
                                        <label class="inline-flex items-center gap-2 text-sm text-neutral-800">
                                            <input type="radio" name="jenis_kelamin" value="L" class="accent-primary" required> Laki-laki
                                        </label>
                                        <label class="inline-flex items-center gap-2 text-sm text-neutral-800">
                                            <input type="radio" name="jenis_kelamin" value="P" class="accent-primary" required> Perempuan
                                        </label>
                                    </div>
                                    <p x-show="errors.jenis_kelamin" class="{{ $err }}" x-text="errors.jenis_kelamin"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="nisn" class="{{ $lbl }}">NISN</label>
                                    <x-input id="nisn" name="nisn" type="text" inputmode="numeric" placeholder="Nomor NISN" value="{{ old('nisn') }}" />
                                    <p x-show="errors.nisn" class="{{ $err }}" x-text="errors.nisn"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="nik" class="{{ $lbl }}">NIK / No. KITAS</label>
                                    <x-input id="nik" name="nik" type="text" inputmode="numeric" placeholder="NIK / No. KITAS" value="{{ old('nik') }}" />
                                    <p x-show="errors.nik" class="{{ $err }}" x-text="errors.nik"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="no_kk" class="{{ $lbl }}">No. KK</label>
                                    <x-input id="no_kk" name="no_kk" type="text" inputmode="numeric" placeholder="Nomor Kartu Keluarga" value="{{ old('no_kk') }}" />
                                    <p x-show="errors.no_kk" class="{{ $err }}" x-text="errors.no_kk"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="tempat_lahir" class="{{ $lbl }}">Tempat Lahir <span class="text-red-500">*</span></label>
                                    <x-input id="tempat_lahir" name="tempat_lahir" type="text" placeholder="Kota/kabupaten lahir" value="{{ old('tempat_lahir') }}" required />
                                    <p x-show="errors.tempat_lahir" class="{{ $err }}" x-text="errors.tempat_lahir"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="tanggal_lahir" class="{{ $lbl }}">Tanggal Lahir <span class="text-red-500">*</span></label>
                                    <x-input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}" required />
                                    <p x-show="errors.tanggal_lahir" class="{{ $err }}" x-text="errors.tanggal_lahir"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="no_akta" class="{{ $lbl }}">No. Registrasi Akta Lahir</label>
                                    <x-input id="no_akta" name="no_akta" type="text" placeholder="No. registrasi akta" value="{{ old('no_akta') }}" />
                                    <p x-show="errors.no_akta" class="{{ $err }}" x-text="errors.no_akta"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Agama &amp; Kepercayaan</label>
                                    <x-select name="agama" size="lg" placeholder="Pilih agama..." value="{{ old('agama') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Agama</x-select.label>
                                                <x-select.item value="Islam">Islam</x-select.item>
                                                <x-select.item value="Kristen">Kristen</x-select.item>
                                                <x-select.item value="Katolik">Katolik</x-select.item>
                                                <x-select.item value="Hindu">Hindu</x-select.item>
                                                <x-select.item value="Buddha">Buddha</x-select.item>
                                                <x-select.item value="Khonghucu">Khonghucu</x-select.item>
                                                <x-select.item value="Kepercayaan Lainnya">Kepercayaan Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Kewarganegaraan</label>
                                    <x-select name="kewarganegaraan" size="lg" placeholder="Pilih..." value="WNI">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Kewarganegaraan</x-select.label>
                                                <x-select.item value="WNI">WNI</x-select.item>
                                                <x-select.item value="WNA">WNA</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                <div class="{{ $field }}" x-show="kewarganegaraan === 'WNA'">
                                    <label for="nama_negara" class="{{ $lbl }}">Nama Negara</label>
                                    <x-input id="nama_negara" name="nama_negara" type="text" placeholder="Nama negara" value="{{ old('nama_negara') }}" />
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Berkebutuhan Khusus</label>
                                    <x-select name="berkebutuhan_khusus" size="lg" placeholder="Pilih..." value="{{ old('berkebutuhan_khusus') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Berkebutuhan Khusus</x-select.label>
                                                <x-select.item value="Tidak">Tidak</x-select.item>
                                                <x-select.item value="Netra (A)">Netra (A)</x-select.item>
                                                <x-select.item value="Rungu (B)">Rungu (B)</x-select.item>
                                                <x-select.item value="Grahita (C)">Grahita (C)</x-select.item>
                                                <x-select.item value="Daksa (D)">Daksa (D)</x-select.item>
                                                <x-select.item value="Laras (E)">Laras (E)</x-select.item>
                                                <x-select.item value="Wicara (F)">Wicara (F)</x-select.item>
                                                <x-select.item value="Tunaganda (G)">Tunaganda (G)</x-select.item>
                                                <x-select.item value="Hiperaktif (H)">Hiperaktif (H)</x-select.item>
                                                <x-select.item value="Cerdas Istimewa (I)">Cerdas Istimewa (I)</x-select.item>
                                                <x-select.item value="Bakat Istimewa (J)">Bakat Istimewa (J)</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Alamat</h3>

                                <div class="{{ $field }}">
                                    <label for="alamat_jalan" class="{{ $lbl }}">Alamat Jalan</label>
                                    <x-input id="alamat_jalan" name="alamat_jalan" type="text" placeholder="Nama jalan & nomor" value="{{ old('alamat_jalan') }}" />
                                    <p x-show="errors.alamat_jalan" class="{{ $err }}" x-text="errors.alamat_jalan"></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="rt" class="{{ $lbl }}">RT</label>
                                        <x-input id="rt" name="rt" type="text" inputmode="numeric" placeholder="RT" value="{{ old('rt') }}" />
                                        <p x-show="errors.rt" class="{{ $err }}" x-text="errors.rt"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="rw" class="{{ $lbl }}">RW</label>
                                        <x-input id="rw" name="rw" type="text" inputmode="numeric" placeholder="RW" value="{{ old('rw') }}" />
                                        <p x-show="errors.rw" class="{{ $err }}" x-text="errors.rw"></p>
                                    </div>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="nama_dusun" class="{{ $lbl }}">Nama Dusun</label>
                                    <x-input id="nama_dusun" name="nama_dusun" type="text" placeholder="Dusun/lingkungan" value="{{ old('nama_dusun') }}" />
                                    <p x-show="errors.nama_dusun" class="{{ $err }}" x-text="errors.nama_dusun"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="kelurahan" class="{{ $lbl }}">Kelurahan / Desa</label>
                                    <x-input id="kelurahan" name="kelurahan" type="text" value="{{ old('kelurahan') }}" />
                                    <p x-show="errors.kelurahan" class="{{ $err }}" x-text="errors.kelurahan"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="kecamatan" class="{{ $lbl }}">Kecamatan</label>
                                    <x-input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan') }}" />
                                    <p x-show="errors.kecamatan" class="{{ $err }}" x-text="errors.kecamatan"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="kode_pos" class="{{ $lbl }}">Kode Pos</label>
                                    <x-input id="kode_pos" name="kode_pos" type="text" inputmode="numeric" placeholder="Kode Pos" value="{{ old('kode_pos') }}" />
                                    <p x-show="errors.kode_pos" class="{{ $err }}" x-text="errors.kode_pos"></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="lintang" class="{{ $lbl }}">Lintang</label>
                                        <x-input id="lintang" name="lintang" type="text" placeholder="e.g. -6.2" value="{{ old('lintang') }}" />
                                        <p x-show="errors.lintang" class="{{ $err }}" x-text="errors.lintang"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="bujur" class="{{ $lbl }}">Bujur</label>
                                        <x-input id="bujur" name="bujur" type="text" placeholder="e.g. 106.8" value="{{ old('bujur') }}" />
                                        <p x-show="errors.bujur" class="{{ $err }}" x-text="errors.bujur"></p>
                                    </div>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Tempat Tinggal</label>
                                    <x-select name="tempat_tinggal" size="lg" placeholder="Pilih..." value="{{ old('tempat_tinggal') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Tempat Tinggal</x-select.label>
                                                <x-select.item value="Bersama Orang Tua">Bersama Orang Tua</x-select.item>
                                                <x-select.item value="Wali">Wali</x-select.item>
                                                <x-select.item value="Kost">Kost</x-select.item>
                                                <x-select.item value="Asrama">Asrama</x-select.item>
                                                <x-select.item value="Pesantren">Pesantren</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Moda Transportasi</label>
                                    <x-select name="moda_transportasi" size="lg" placeholder="Pilih..." value="{{ old('moda_transportasi') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Moda Transportasi</x-select.label>
                                                <x-select.item value="Jalan Kaki">Jalan Kaki</x-select.item>
                                                <x-select.item value="Sepeda">Sepeda</x-select.item>
                                                <x-select.item value="Sepeda Motor">Sepeda Motor</x-select.item>
                                                <x-select.item value="Ojek">Ojek</x-select.item>
                                                <x-select.item value="Angkutan Umum">Angkutan Umum</x-select.item>
                                                <x-select.item value="Mobil Pribadi">Mobil Pribadi</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                            </div>

                            {{-- Lainnya --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Lainnya</h3>

                                <div class="{{ $field }}">
                                    <label for="anak_ke" class="{{ $lbl }}">Anak ke-berapa</label>
                                    <x-input id="anak_ke" name="anak_ke" type="number" min="1" placeholder="Urutan anak" value="{{ old('anak_ke') }}" />
                                    <p x-show="errors.anak_ke" class="{{ $err }}" x-text="errors.anak_ke"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="pekerjaan" class="{{ $lbl }}">Pekerjaan (jika warga belajar)</label>
                                    <x-input id="pekerjaan" name="pekerjaan" type="text" placeholder="Pekerjaan" value="{{ old('pekerjaan') }}" />
                                    <p x-show="errors.pekerjaan" class="{{ $err }}" x-text="errors.pekerjaan"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Punya KIP</label>
                                    <x-select name="punya_kip" size="lg" placeholder="Pilih..." value="{{ old('punya_kip') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Punya KIP</x-select.label>
                                                <x-select.item value="Ya">Ya</x-select.item>
                                                <x-select.item value="Tidak">Tidak</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Tetap menerima KIP</label>
                                    <x-select name="terima_kip" size="lg" placeholder="Pilih..." value="{{ old('terima_kip') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Tetap menerima KIP</x-select.label>
                                                <x-select.item value="Ya">Ya</x-select.item>
                                                <x-select.item value="Tidak">Tidak</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Alasan menolak PIP</label>
                                    <x-select name="alasan_tolak_pip" size="lg" placeholder="Pilih..." value="{{ old('alasan_tolak_pip') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Alasan menolak PIP</x-select.label>
                                                <x-select.item value="Tidak Berminat">Tidak Berminat</x-select.item>
                                                <x-select.item value="Sudah Mampu">Sudah Mampu</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                            </div>

                            {{-- Kontak --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Kontak</h3>

                                <div class="{{ $field }}">
                                    <label for="telp_rumah" class="{{ $lbl }}">Nomor Telepon Rumah</label>
                                    <x-input id="telp_rumah" name="telp_rumah" type="tel" placeholder="021-xxxx" value="{{ old('telp_rumah') }}" />
                                    <p x-show="errors.telp_rumah" class="{{ $err }}" x-text="errors.telp_rumah"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="no_hp" class="{{ $lbl }}">Nomor HP</label>
                                    <x-input id="no_hp" name="no_hp" type="tel" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" />
                                    <p x-show="errors.no_hp" class="{{ $err }}" x-text="errors.no_hp"></p>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="email" class="{{ $lbl }}">Email</label>
                                    <x-input id="email" name="email" type="email" placeholder="nama@email.com" value="{{ old('email') }}" />
                                    <p x-show="errors.email" class="{{ $err }}" x-text="errors.email"></p>
                                </div>
                            </div>
                        </div>

                        {{-- ================= STEP 2 ================= --}}
                        <div x-show="step === 2" x-cloak data-step="2" class="space-y-5">

                            <h2 class="text-sm font-semibold text-black">Step 2 &middot; Data Keluarga</h2>

                            {{-- Ayah --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Ayah Kandung</h3>

                                <div class="{{ $field }}">
                                    <label for="ayah_nama" class="{{ $lbl }}">Nama</label>
                                    <x-input id="ayah_nama" name="ayah_nama" type="text" value="{{ old('ayah_nama') }}" />
                                    <p x-show="errors.ayah_nama" class="{{ $err }}" x-text="errors.ayah_nama"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="ayah_nik" class="{{ $lbl }}">NIK</label>
                                    <x-input id="ayah_nik" name="ayah_nik" type="text" inputmode="numeric" value="{{ old('ayah_nik') }}" />
                                    <p x-show="errors.ayah_nik" class="{{ $err }}" x-text="errors.ayah_nik"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="ayah_tahun_lahir" class="{{ $lbl }}">Tahun Lahir</label>
                                    <x-input id="ayah_tahun_lahir" name="ayah_tahun_lahir" type="number" min="1900" max="2099" value="{{ old('ayah_tahun_lahir') }}" />
                                    <p x-show="errors.ayah_tahun_lahir" class="{{ $err }}" x-text="errors.ayah_tahun_lahir"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Pendidikan</label>
                                    <x-select name="ayah_pendidikan" size="lg" placeholder="Pilih..." value="{{ old('ayah_pendidikan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Pendidikan</x-select.label>
                                                <x-select.item value="Tidak Sekolah">Tidak Sekolah</x-select.item>
                                                <x-select.item value="SD/Sederajat">SD/Sederajat</x-select.item>
                                                <x-select.item value="SMP/Sederajat">SMP/Sederajat</x-select.item>
                                                <x-select.item value="SMA/Sederajat">SMA/Sederajat</x-select.item>
                                                <x-select.item value="D1">D1</x-select.item>
                                                <x-select.item value="D2">D2</x-select.item>
                                                <x-select.item value="D3">D3</x-select.item>
                                                <x-select.item value="S1">S1</x-select.item>
                                                <x-select.item value="S2">S2</x-select.item>
                                                <x-select.item value="S3">S3</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Pekerjaan</label>
                                    <x-select name="ayah_pekerjaan" size="lg" placeholder="Pilih..." value="{{ old('ayah_pekerjaan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Pekerjaan</x-select.label>
                                                <x-select.item value="Tidak Bekerja">Tidak Bekerja</x-select.item>
                                                <x-select.item value="Petani">Petani</x-select.item>
                                                <x-select.item value="Nelayan">Nelayan</x-select.item>
                                                <x-select.item value="Buruh">Buruh</x-select.item>
                                                <x-select.item value="Pedagang">Pedagang</x-select.item>
                                                <x-select.item value="PNS">PNS</x-select.item>
                                                <x-select.item value="TNI/Polri">TNI/Polri</x-select.item>
                                                <x-select.item value="Karyawan Swasta">Karyawan Swasta</x-select.item>
                                                <x-select.item value="Wiraswasta">Wiraswasta</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Penghasilan Bulanan</label>
                                    <x-select name="ayah_penghasilan" size="lg" placeholder="Pilih..." value="{{ old('ayah_penghasilan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Penghasilan Bulanan</x-select.label>
                                                <x-select.item value="Tidak Berpenghasilan">Tidak Berpenghasilan</x-select.item>
                                                <x-select.item value="&lt; 500.000">&lt; 500.000</x-select.item>
                                                <x-select.item value="500.000 - 1.000.000">500.000 - 1.000.000</x-select.item>
                                                <x-select.item value="1.000.000 - 2.000.000">1.000.000 - 2.000.000</x-select.item>
                                                <x-select.item value="2.000.000 - 5.000.000">2.000.000 - 5.000.000</x-select.item>
                                                <x-select.item value="&gt; 5.000.000">&gt; 5.000.000</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Berkebutuhan Khusus</label>
                                    <x-select name="ayah_berkebutuhan" size="lg" placeholder="Pilih..." value="{{ old('ayah_berkebutuhan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Berkebutuhan Khusus</x-select.label>
                                                <x-select.item value="Tidak">Tidak</x-select.item>
                                                <x-select.item value="Netra (A)">Netra (A)</x-select.item>
                                                <x-select.item value="Rungu (B)">Rungu (B)</x-select.item>
                                                <x-select.item value="Grahita (C)">Grahita (C)</x-select.item>
                                                <x-select.item value="Daksa (D)">Daksa (D)</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                            </div>

                            {{-- Ibu --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Ibu Kandung</h3>

                                <div class="{{ $field }}">
                                    <label for="ibu_nama" class="{{ $lbl }}">Nama</label>
                                    <x-input id="ibu_nama" name="ibu_nama" type="text" value="{{ old('ibu_nama') }}" />
                                    <p x-show="errors.ibu_nama" class="{{ $err }}" x-text="errors.ibu_nama"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="ibu_nik" class="{{ $lbl }}">NIK</label>
                                    <x-input id="ibu_nik" name="ibu_nik" type="text" inputmode="numeric" value="{{ old('ibu_nik') }}" />
                                    <p x-show="errors.ibu_nik" class="{{ $err }}" x-text="errors.ibu_nik"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="ibu_tahun_lahir" class="{{ $lbl }}">Tahun Lahir</label>
                                    <x-input id="ibu_tahun_lahir" name="ibu_tahun_lahir" type="number" min="1900" max="2099" value="{{ old('ibu_tahun_lahir') }}" />
                                    <p x-show="errors.ibu_tahun_lahir" class="{{ $err }}" x-text="errors.ibu_tahun_lahir"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Pendidikan</label>
                                    <x-select name="ibu_pendidikan" size="lg" placeholder="Pilih..." value="{{ old('ibu_pendidikan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Pendidikan</x-select.label>
                                                <x-select.item value="Tidak Sekolah">Tidak Sekolah</x-select.item>
                                                <x-select.item value="SD/Sederajat">SD/Sederajat</x-select.item>
                                                <x-select.item value="SMP/Sederajat">SMP/Sederajat</x-select.item>
                                                <x-select.item value="SMA/Sederajat">SMA/Sederajat</x-select.item>
                                                <x-select.item value="D1">D1</x-select.item>
                                                <x-select.item value="D2">D2</x-select.item>
                                                <x-select.item value="D3">D3</x-select.item>
                                                <x-select.item value="S1">S1</x-select.item>
                                                <x-select.item value="S2">S2</x-select.item>
                                                <x-select.item value="S3">S3</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Pekerjaan</label>
                                    <x-select name="ibu_pekerjaan" size="lg" placeholder="Pilih..." value="{{ old('ibu_pekerjaan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Pekerjaan</x-select.label>
                                                <x-select.item value="Tidak Bekerja">Tidak Bekerja</x-select.item>
                                                <x-select.item value="Petani">Petani</x-select.item>
                                                <x-select.item value="Nelayan">Nelayan</x-select.item>
                                                <x-select.item value="Buruh">Buruh</x-select.item>
                                                <x-select.item value="Pedagang">Pedagang</x-select.item>
                                                <x-select.item value="PNS">PNS</x-select.item>
                                                <x-select.item value="TNI/Polri">TNI/Polri</x-select.item>
                                                <x-select.item value="Karyawan Swasta">Karyawan Swasta</x-select.item>
                                                <x-select.item value="Wiraswasta">Wiraswasta</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Penghasilan Bulanan</label>
                                    <x-select name="ibu_penghasilan" size="lg" placeholder="Pilih..." value="{{ old('ibu_penghasilan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Penghasilan Bulanan</x-select.label>
                                                <x-select.item value="Tidak Berpenghasilan">Tidak Berpenghasilan</x-select.item>
                                                <x-select.item value="&lt; 500.000">&lt; 500.000</x-select.item>
                                                <x-select.item value="500.000 - 1.000.000">500.000 - 1.000.000</x-select.item>
                                                <x-select.item value="1.000.000 - 2.000.000">1.000.000 - 2.000.000</x-select.item>
                                                <x-select.item value="2.000.000 - 5.000.000">2.000.000 - 5.000.000</x-select.item>
                                                <x-select.item value="&gt; 5.000.000">&gt; 5.000.000</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Berkebutuhan Khusus</label>
                                    <x-select name="ibu_berkebutuhan" size="lg" placeholder="Pilih..." value="{{ old('ibu_berkebutuhan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Berkebutuhan Khusus</x-select.label>
                                                <x-select.item value="Tidak">Tidak</x-select.item>
                                                <x-select.item value="Netra (A)">Netra (A)</x-select.item>
                                                <x-select.item value="Rungu (B)">Rungu (B)</x-select.item>
                                                <x-select.item value="Grahita (C)">Grahita (C)</x-select.item>
                                                <x-select.item value="Daksa (D)">Daksa (D)</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                            </div>

                            {{-- Wali --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Wali (Opsional)</h3>

                                <div class="{{ $field }}">
                                    <label for="wali_nama" class="{{ $lbl }}">Nama</label>
                                    <x-input id="wali_nama" name="wali_nama" type="text" value="{{ old('wali_nama') }}" />
                                    <p x-show="errors.wali_nama" class="{{ $err }}" x-text="errors.wali_nama"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="wali_nik" class="{{ $lbl }}">NIK</label>
                                    <x-input id="wali_nik" name="wali_nik" type="text" inputmode="numeric" value="{{ old('wali_nik') }}" />
                                    <p x-show="errors.wali_nik" class="{{ $err }}" x-text="errors.wali_nik"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="wali_tahun_lahir" class="{{ $lbl }}">Tahun Lahir</label>
                                    <x-input id="wali_tahun_lahir" name="wali_tahun_lahir" type="number" min="1900" max="2099" value="{{ old('wali_tahun_lahir') }}" />
                                    <p x-show="errors.wali_tahun_lahir" class="{{ $err }}" x-text="errors.wali_tahun_lahir"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Pendidikan</label>
                                    <x-select name="wali_pendidikan" size="lg" placeholder="Pilih..." value="{{ old('wali_pendidikan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Pendidikan</x-select.label>
                                                <x-select.item value="Tidak Sekolah">Tidak Sekolah</x-select.item>
                                                <x-select.item value="SD/Sederajat">SD/Sederajat</x-select.item>
                                                <x-select.item value="SMP/Sederajat">SMP/Sederajat</x-select.item>
                                                <x-select.item value="SMA/Sederajat">SMA/Sederajat</x-select.item>
                                                <x-select.item value="D1">D1</x-select.item>
                                                <x-select.item value="D2">D2</x-select.item>
                                                <x-select.item value="D3">D3</x-select.item>
                                                <x-select.item value="S1">S1</x-select.item>
                                                <x-select.item value="S2">S2</x-select.item>
                                                <x-select.item value="S3">S3</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Pekerjaan</label>
                                    <x-select name="wali_pekerjaan" size="lg" placeholder="Pilih..." value="{{ old('wali_pekerjaan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Pekerjaan</x-select.label>
                                                <x-select.item value="Tidak Bekerja">Tidak Bekerja</x-select.item>
                                                <x-select.item value="Petani">Petani</x-select.item>
                                                <x-select.item value="Nelayan">Nelayan</x-select.item>
                                                <x-select.item value="Buruh">Buruh</x-select.item>
                                                <x-select.item value="Pedagang">Pedagang</x-select.item>
                                                <x-select.item value="PNS">PNS</x-select.item>
                                                <x-select.item value="TNI/Polri">TNI/Polri</x-select.item>
                                                <x-select.item value="Karyawan Swasta">Karyawan Swasta</x-select.item>
                                                <x-select.item value="Wiraswasta">Wiraswasta</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Penghasilan Bulanan</label>
                                    <x-select name="wali_penghasilan" size="lg" placeholder="Pilih..." value="{{ old('wali_penghasilan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Penghasilan Bulanan</x-select.label>
                                                <x-select.item value="Tidak Berpenghasilan">Tidak Berpenghasilan</x-select.item>
                                                <x-select.item value="&lt; 500.000">&lt; 500.000</x-select.item>
                                                <x-select.item value="500.000 - 1.000.000">500.000 - 1.000.000</x-select.item>
                                                <x-select.item value="1.000.000 - 2.000.000">1.000.000 - 2.000.000</x-select.item>
                                                <x-select.item value="2.000.000 - 5.000.000">2.000.000 - 5.000.000</x-select.item>
                                                <x-select.item value="&gt; 5.000.000">&gt; 5.000.000</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                            </div>
                        </div>

                        {{-- ================= STEP 3 ================= --}}
                        <div x-show="step === 3" x-cloak data-step="3" class="space-y-5">

                            <h2 class="text-sm font-semibold text-black">Step 3 &middot; Data Periodik &amp; Kesejahteraan</h2>

                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Data Periodik</h3>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="tinggi_badan" class="{{ $lbl }}">Tinggi (cm)</label>
                                        <x-input id="tinggi_badan" name="tinggi_badan" type="number" step="0.1" value="{{ old('tinggi_badan') }}" />
                                        <p x-show="errors.tinggi_badan" class="{{ $err }}" x-text="errors.tinggi_badan"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="berat_badan" class="{{ $lbl }}">Berat (kg)</label>
                                        <x-input id="berat_badan" name="berat_badan" type="number" step="0.1" value="{{ old('berat_badan') }}" />
                                        <p x-show="errors.berat_badan" class="{{ $err }}" x-text="errors.berat_badan"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="lingkar_kepala" class="{{ $lbl }}">Lingkar Kepala (cm)</label>
                                        <x-input id="lingkar_kepala" name="lingkar_kepala" type="number" step="0.1" value="{{ old('lingkar_kepala') }}" />
                                        <p x-show="errors.lingkar_kepala" class="{{ $err }}" x-text="errors.lingkar_kepala"></p>
                                    </div>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="jarak_tempuh" class="{{ $lbl }}">Jarak Tempuh Spesifik (km)</label>
                                    <x-input id="jarak_tempuh" name="jarak_tempuh" type="number" step="0.1" placeholder="e.g. 2.5" value="{{ old('jarak_tempuh') }}" />
                                    <p x-show="errors.jarak_tempuh" class="{{ $err }}" x-text="errors.jarak_tempuh"></p>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="waktu_jam" class="{{ $lbl }}">Waktu Tempuh (Jam)</label>
                                        <x-input id="waktu_jam" name="waktu_jam" type="number" min="0" value="{{ old('waktu_jam') }}" />
                                        <p x-show="errors.waktu_jam" class="{{ $err }}" x-text="errors.waktu_jam"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="waktu_menit" class="{{ $lbl }}">Waktu Tempuh (Menit)</label>
                                        <x-input id="waktu_menit" name="waktu_menit" type="number" min="0" max="59" value="{{ old('waktu_menit') }}" />
                                        <p x-show="errors.waktu_menit" class="{{ $err }}" x-text="errors.waktu_menit"></p>
                                    </div>
                                </div>

                                <div class="{{ $field }}">
                                    <label for="jumlah_saudara" class="{{ $lbl }}">Jumlah Saudara Kandung</label>
                                    <x-input id="jumlah_saudara" name="jumlah_saudara" type="number" min="0" value="{{ old('jumlah_saudara') }}" />
                                    <p x-show="errors.jumlah_saudara" class="{{ $err }}" x-text="errors.jumlah_saudara"></p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Kesejahteraan Peserta Didik</h3>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Jenis Kesejahteraan</label>
                                    <x-select name="jenis_kesejahteraan" size="lg" placeholder="Pilih..." value="{{ old('jenis_kesejahteraan') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Jenis Kesejahteraan</x-select.label>
                                                <x-select.item value="KPS">KPS</x-select.item>
                                                <x-select.item value="KIP">KIP</x-select.item>
                                                <x-select.item value="PKH">PKH</x-select.item>
                                                <x-select.item value="Beasiswa">Beasiswa</x-select.item>
                                                <x-select.item value="PIP">PIP</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="no_kartu" class="{{ $lbl }}">No. Kartu</label>
                                    <x-input id="no_kartu" name="no_kartu" type="text" value="{{ old('no_kartu') }}" />
                                    <p x-show="errors.no_kartu" class="{{ $err }}" x-text="errors.no_kartu"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="nama_di_kartu" class="{{ $lbl }}">Nama di Kartu</label>
                                    <x-input id="nama_di_kartu" name="nama_di_kartu" type="text" value="{{ old('nama_di_kartu') }}" />
                                    <p x-show="errors.nama_di_kartu" class="{{ $err }}" x-text="errors.nama_di_kartu"></p>
                                </div>
                            </div>
                        </div>

                        {{-- ================= STEP 4 ================= --}}
                        <div x-show="step === 4" x-cloak data-step="4" class="space-y-5">

                            <h2 class="text-sm font-semibold text-black">Step 4 &middot; Registrasi, Prestasi &amp; Beasiswa</h2>

                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Registrasi</h3>

                                <div class="{{ $field }}">
                                    <label for="kompetensi_keahlian" class="{{ $lbl }}">Kompetensi Keahlian</label>
                                    <x-input id="kompetensi_keahlian" name="kompetensi_keahlian" type="text" value="{{ old('kompetensi_keahlian') }}" />
                                    <p x-show="errors.kompetensi_keahlian" class="{{ $err }}" x-text="errors.kompetensi_keahlian"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="nis" class="{{ $lbl }}">NIS</label>
                                    <x-input id="nis" name="nis" type="text" value="{{ old('nis') }}" />
                                    <p x-show="errors.nis" class="{{ $err }}" x-text="errors.nis"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="tanggal_masuk" class="{{ $lbl }}">Tanggal Masuk Sekolah</label>
                                    <x-input id="tanggal_masuk" name="tanggal_masuk" type="date" value="{{ old('tanggal_masuk') }}" />
                                    <p x-show="errors.tanggal_masuk" class="{{ $err }}" x-text="errors.tanggal_masuk"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="sekolah_asal" class="{{ $lbl }}">Sekolah Asal</label>
                                    <x-input id="sekolah_asal" name="sekolah_asal" type="text" value="{{ old('sekolah_asal') }}" />
                                    <p x-show="errors.sekolah_asal" class="{{ $err }}" x-text="errors.sekolah_asal"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="no_peserta_un" class="{{ $lbl }}">Nomor Peserta UN SMP</label>
                                    <x-input id="no_peserta_un" name="no_peserta_un" type="text" value="{{ old('no_peserta_un') }}" />
                                    <p x-show="errors.no_peserta_un" class="{{ $err }}" x-text="errors.no_peserta_un"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="no_seri_ijazah" class="{{ $lbl }}">No. Seri Ijazah</label>
                                    <x-input id="no_seri_ijazah" name="no_seri_ijazah" type="text" value="{{ old('no_seri_ijazah') }}" />
                                    <p x-show="errors.no_seri_ijazah" class="{{ $err }}" x-text="errors.no_seri_ijazah"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="no_skhun" class="{{ $lbl }}">No. SKHUN</label>
                                    <x-input id="no_skhun" name="no_skhun" type="text" value="{{ old('no_skhun') }}" />
                                    <p x-show="errors.no_skhun" class="{{ $err }}" x-text="errors.no_skhun"></p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Keluar (jika ada)</h3>

                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Keluar Karena</label>
                                    <x-select name="keluar_karena" size="lg" placeholder="Pilih..." value="{{ old('keluar_karena') }}">
                                        <x-select.trigger />
                                        <x-select.content>
                                            <x-select.group>
                                                <x-select.label>Keluar Karena</x-select.label>
                                                <x-select.item value="Lulus">Lulus</x-select.item>
                                                <x-select.item value="Pindah">Pindah</x-select.item>
                                                <x-select.item value="Drop Out">Drop Out</x-select.item>
                                                <x-select.item value="Meninggal">Meninggal</x-select.item>
                                                <x-select.item value="Lainnya">Lainnya</x-select.item>
                                            </x-select.group>
                                        </x-select.content>
                                    </x-select>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="tanggal_keluar" class="{{ $lbl }}">Tanggal Keluar</label>
                                    <x-input id="tanggal_keluar" name="tanggal_keluar" type="date" value="{{ old('tanggal_keluar') }}" />
                                    <p x-show="errors.tanggal_keluar" class="{{ $err }}" x-text="errors.tanggal_keluar"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="alasan_keluar" class="{{ $lbl }}">Alasan</label>
                                    <x-input id="alasan_keluar" name="alasan_keluar" type="text" value="{{ old('alasan_keluar') }}" />
                                    <p x-show="errors.alasan_keluar" class="{{ $err }}" x-text="errors.alasan_keluar"></p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Prestasi</h3>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="prestasi_jenis" class="{{ $lbl }}">Jenis</label>
                                        <x-input id="prestasi_jenis" name="prestasi_jenis" type="text" value="{{ old('prestasi_jenis') }}" />
                                        <p x-show="errors.prestasi_jenis" class="{{ $err }}" x-text="errors.prestasi_jenis"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="prestasi_tingkat" class="{{ $lbl }}">Tingkat</label>
                                        <x-input id="prestasi_tingkat" name="prestasi_tingkat" type="text" placeholder="Kab/Kota/Prov/Nas" value="{{ old('prestasi_tingkat') }}" />
                                        <p x-show="errors.prestasi_tingkat" class="{{ $err }}" x-text="errors.prestasi_tingkat"></p>
                                    </div>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="prestasi_nama" class="{{ $lbl }}">Nama Prestasi</label>
                                    <x-input id="prestasi_nama" name="prestasi_nama" type="text" value="{{ old('prestasi_nama') }}" />
                                    <p x-show="errors.prestasi_nama" class="{{ $err }}" x-text="errors.prestasi_nama"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="prestasi_tahun" class="{{ $lbl }}">Tahun</label>
                                        <x-input id="prestasi_tahun" name="prestasi_tahun" type="number" min="1900" max="2099" value="{{ old('prestasi_tahun') }}" />
                                        <p x-show="errors.prestasi_tahun" class="{{ $err }}" x-text="errors.prestasi_tahun"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="prestasi_penyelenggara" class="{{ $lbl }}">Penyelenggara</label>
                                        <x-input id="prestasi_penyelenggara" name="prestasi_penyelenggara" type="text" value="{{ old('prestasi_penyelenggara') }}" />
                                        <p x-show="errors.prestasi_penyelenggara" class="{{ $err }}" x-text="errors.prestasi_penyelenggara"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Beasiswa</h3>

                                <div class="{{ $field }}">
                                    <label for="beasiswa_jenis" class="{{ $lbl }}">Jenis</label>
                                    <x-input id="beasiswa_jenis" name="beasiswa_jenis" type="text" value="{{ old('beasiswa_jenis') }}" />
                                    <p x-show="errors.beasiswa_jenis" class="{{ $err }}" x-text="errors.beasiswa_jenis"></p>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="beasiswa_keterangan" class="{{ $lbl }}">Keterangan</label>
                                    <x-input id="beasiswa_keterangan" name="beasiswa_keterangan" type="text" value="{{ old('beasiswa_keterangan') }}" />
                                    <p x-show="errors.beasiswa_keterangan" class="{{ $err }}" x-text="errors.beasiswa_keterangan"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="{{ $field }}">
                                        <label for="beasiswa_tahun_mulai" class="{{ $lbl }}">Tahun Mulai</label>
                                        <x-input id="beasiswa_tahun_mulai" name="beasiswa_tahun_mulai" type="number" min="1900" max="2099" value="{{ old('beasiswa_tahun_mulai') }}" />
                                        <p x-show="errors.beasiswa_tahun_mulai" class="{{ $err }}" x-text="errors.beasiswa_tahun_mulai"></p>
                                    </div>
                                    <div class="{{ $field }}">
                                        <label for="beasiswa_tahun_selesai" class="{{ $lbl }}">Tahun Selesai</label>
                                        <x-input id="beasiswa_tahun_selesai" name="beasiswa_tahun_selesai" type="number" min="1900" max="2099" value="{{ old('beasiswa_tahun_selesai') }}" />
                                        <p x-show="errors.beasiswa_tahun_selesai" class="{{ $err }}" x-text="errors.beasiswa_tahun_selesai"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ================= NAVIGASI ================= --}}
                        <div class="flex items-center justify-between gap-3 pt-1">
                            <x-button type="button" variant="outline" x-show="step > 1" @click="back()" x-bind:disabled="saving">
                                Kembali
                            </x-button>

                            <div class="ml-auto flex flex-col items-end gap-1">
                                <div class="flex gap-3">
                                    <x-button type="button" x-show="step < 4" @click.prevent="next()" x-bind:disabled="saving">
                                        <span x-show="!saving">Selanjutnya</span>
                                        <span x-show="saving">Menyimpan…</span>
                                    </x-button>

                                    <x-button type="button" x-show="step === 4" @click.prevent="submit()" x-bind:disabled="saving">
                                        <span x-show="!saving">Kirim Data</span>
                                        <span x-show="saving">Mengirim…</span>
                                    </x-button>
                                </div>
                                <p x-show="error" class="text-xs text-red-600" x-text="error"></p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function wizard() {
            return {
                step: 1,
                kewarganegaraan: 'WNI',
                id: null,
                saving: false,
                loadingHydrate: false,
                error: '',
                errors: {},
                async init() {
                    this.id = localStorage.getItem('siswa_daftar_id') || null;
                    const s = parseInt(localStorage.getItem('siswa_daftar_step') || '1', 10);
                    if (s >= 1 && s <= 4) this.step = s;
                    if (this.id) {
                        await this.hydrate();
                    }
                },
                async hydrate() {
                    if (!this.id) return;
                    this.loadingHydrate = true;
                    try {
                        let res = await fetch('/mobile/siswa/' + this.id, {
                            headers: { 'Accept': 'application/json' },
                        });
                        // fallback ke /siswa untuk kompatibilitas data lama
                        if (!res.ok) {
                            const alt = await fetch('/siswa/' + this.id, {
                                headers: { 'Accept': 'application/json' },
                            });
                            if (alt.ok) res = alt;
                        }
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const json = await res.json();
                        const data = json.data || json;
                        // value di localStorage mungkin string, pastikan kewarganegaraan sinkron sebelum fill
                        if (data.kewarganegaraan) this.kewarganegaraan = data.kewarganegaraan;
                        // delay agar semua x-data select sudah ter-init (items terisi)
                        await new Promise(r => setTimeout(r, 80));
                        // juga tunggu Alpine nextTick jika tersedia
                        if (this.$nextTick) await this.$nextTick(() => {});
                        this.fillAll(data);
                        // second pass after Alpine render
                        await new Promise(r => setTimeout(r, 50));
                        this.fillAll(data);
                    } catch (e) {
                        console.warn('hydrate gagal', e);
                        // jika 404, bersihkan storage agar tidak stuck di step terakhir dengan data kosong
                        if (String(e).includes('404')) {
                            localStorage.removeItem('siswa_daftar_id');
                            localStorage.removeItem('siswa_daftar_step');
                            this.id = null;
                            this.step = 1;
                        }
                    } finally {
                        this.loadingHydrate = false;
                    }
                },
                fillAll(data) {
                    for (const [k, v] of Object.entries(data)) {
                        if (v === null || v === undefined || v === '') continue;
                        this.fillField(k, v);
                    }
                },
                fillField(name, value) {
                    const strVal = String(value);
                    // radio (jenis_kelamin)
                    const radios = document.querySelectorAll('input[type=\"radio\"][name=\"' + name + '\"]');
                    if (radios.length) {
                        radios.forEach(r => {
                            r.checked = r.value === strVal;
                            if (r.checked) r.dispatchEvent(new Event('change', { bubbles: true }));
                        });
                        return;
                    }
                    // select custom (hidden input di dalam div[x-data])
                    const hidden = document.querySelector('input[type=\"hidden\"][name=\"' + name + '\"]');
                    if (hidden) {
                        const root = hidden.closest('div[x-data]');
                        if (root) {
                            let alpine = null;
                            try {
                                if (root._x_dataStack && root._x_dataStack[0]) alpine = root._x_dataStack[0];
                                else if (window.Alpine && typeof Alpine.$data === 'function') alpine = Alpine.$data(root);
                            } catch (_) {}
                            if (alpine) {
                                alpine.value = strVal;
                                if (alpine.items && alpine.items[strVal] !== undefined) alpine._label = alpine.items[strVal];
                                else alpine._label = strVal;
                                hidden.value = strVal;
                                // trigger @select-change di wizard
                                hidden.dispatchEvent(new CustomEvent('select-change', { detail: { name, value: strVal }, bubbles: true }));
                                root.dispatchEvent(new CustomEvent('select-change', { detail: { name, value: strVal }, bubbles: true }));
                                if (name === 'kewarganegaraan') this.kewarganegaraan = strVal;
                                return;
                            }
                        }
                        hidden.value = strVal;
                        hidden.dispatchEvent(new Event('change', { bubbles: true }));
                        hidden.dispatchEvent(new CustomEvent('select-change', { detail: { name, value: strVal }, bubbles: true }));
                        if (name === 'kewarganegaraan') this.kewarganegaraan = strVal;
                        return;
                    }
                    // input / textarea / date / number biasa
                    const el = document.querySelector('[name=\"' + name + '\"]');
                    if (el) {
                        el.value = strVal;
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        if (name === 'kewarganegaraan') this.kewarganegaraan = strVal;
                    }
                },
                clearError(name) {
                    if (this.errors[name]) {
                        delete this.errors[name];
                        this.errors = { ...this.errors };
                    }
                },
                token() {
                    const el = document.querySelector('input[name=_token]');
                    return el ? el.value : '';
                },
                collect(step) {
                    const el = document.querySelector('[data-step="' + step + '"]');
                    const fd = new FormData();
                    if (!el) return fd;
                    el.querySelectorAll('input, select, textarea').forEach(i => {
                        if (!i.name) return;
                        if ((i.type === 'radio' || i.type === 'checkbox') && !i.checked) return;
                        if (i.value === '' || i.value === null || i.value === undefined) return;
                        fd.append(i.name, i.value);
                    });
                    return fd;
                },
                validate(step) {
                    const el = document.querySelector('[data-step="' + step + '"]');
                    if (!el) return true;
                    const newErrors = {};
                    const checked = new Set();
                    el.querySelectorAll('input[type=radio]:checked').forEach(r => checked.add(r.name));
                    const req = el.querySelectorAll('[required]');
                    for (const f of req) {
                        if (f.type === 'radio') {
                            if (!checked.has(f.name)) {
                                newErrors[f.name] = 'Field ini wajib diisi.';
                            }
                        } else if (!f.value || !String(f.value).trim()) {
                            newErrors[f.name] = 'Field ini wajib diisi.';
                        }
                    }
                    this.errors = { ...this.errors, ...newErrors };
                    if (Object.keys(newErrors).length) {
                        const first = el.querySelector('[required]');
                        if (first) first.scrollIntoView({ block: 'center' });
                        return false;
                    }
                    return true;
                },
                async send(step, method, url) {
                    this.saving = true;
                    this.error = '';
                    const fd = this.collect(step);
                    if (step === 4) fd.append('_selesai', '1');
                    fd.append('_token', this.token());
                    // Laravel tidak parsing multipart pada PUT murni (PHP tidak isi $_POST),
                    // jadi spoof method via POST + _method agar FormData terbaca
                    let fetchMethod = method;
                    if (method === 'PUT') {
                        fd.append('_method', 'PUT');
                        fetchMethod = 'POST';
                    }
                    console.log('SEND', method, url, fetchMethod === 'POST' ? '(spoofed POST)' : '');
                    for (const [k, v] of fd.entries()) console.log('  ', k, '=', v);
                    try {
                        const res = await fetch(url, {
                            method: fetchMethod,
                            headers: {
                                'X-CSRF-TOKEN': this.token(),
                                'Accept': 'application/json',
                            },
                            body: fd,
                        });
                        const json = await res.json().catch(() => null);
                        console.log('RESPONSE', res.status, json);
                        if (!res.ok) {
                            if (res.status === 422 && json && json.errors) {
                                const newErrors = {};
                                for (const [field, msgs] of Object.entries(json.errors)) {
                                    newErrors[field] = msgs[0];
                                }
                                this.errors = { ...this.errors, ...newErrors };
                                this.error = 'Periksa field yang ditandai merah.';
                            } else {
                                this.error = 'Gagal menyimpan. Periksa koneksi dan coba lagi.';
                            }
                            throw new Error('HTTP ' + res.status);
                        }
                        if (!json) {
                            this.error = 'Server mengembalikan response kosong.';
                            throw new Error('Empty response');
                        }
                        this.errors = {};
                        return json;
                    } catch (e) {
                        console.error('SEND ERROR', e);
                        if (!this.error) this.error = 'Gagal menyimpan. Periksa koneksi dan coba lagi.';
                        throw e;
                    } finally {
                        this.saving = false;
                    }
                },
                async next() {
                    console.log('NEXT called, step=', this.step, 'id=', this.id);
                    const valid = this.validate(this.step);
                    console.log('VALIDATE result=', valid, 'errors=', this.errors);
                    if (!valid) return;
                    try {
                        if (this.step === 1) {
                            if (!this.id) {
                                console.log('POST /mobile/siswa (create)');
                                const r = await this.send(1, 'POST', '/mobile/siswa');
                                console.log('POST success', r);
                                this.id = r.id;
                                localStorage.setItem('siswa_daftar_id', this.id);
                            } else {
                                try {
                                    console.log('PUT /mobile/siswa/' + this.id + ' (update)');
                                    await this.send(1, 'PUT', '/mobile/siswa/' + this.id);
                                } catch (e) {
                                    console.log('PUT failed, retrying POST');
                                    const r = await this.send(1, 'POST', '/mobile/siswa');
                                    this.id = r.id;
                                    localStorage.setItem('siswa_daftar_id', this.id);
                                }
                            }
                        } else {
                            await this.send(this.step, 'PUT', '/mobile/siswa/' + this.id);
                        }
                        localStorage.setItem('siswa_daftar_step', String(this.step + 1));
                        this.step++;
                        console.log('NOW step=', this.step);
                    } catch (e) { console.error('NEXT error', e); }
                },
                back() {
                    if (this.step > 1) {
                        this.step--;
                        localStorage.setItem('siswa_daftar_step', String(this.step));
                        // re-hydrate previous step data sudah ada di DOM setelah init, tidak perlu fetch ulang
                        // tapi pastikan select yang ter-hide tetap terisi - hydration sudah dilakukan di init
                    }
                },
                async submit() {
                    if (!this.validate(this.step)) return;
                    try {
                        await this.send(4, 'PUT', '/mobile/siswa/' + this.id);
                        localStorage.removeItem('siswa_daftar_id');
                        localStorage.removeItem('siswa_daftar_step');
                        window.location.href = "{{ route('siswa.daftar.success') }}";
                    } catch (e) { /* error sudah di-set */ }
                },
            };
        }
    </script>
@endsection
