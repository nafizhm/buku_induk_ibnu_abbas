<?php

namespace Database\Seeders;

use App\Models\BeasiswaSiswa;
use App\Models\OrangTua;
use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SiswaDummySeeder extends Seeder
{
    private array $agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Kepercayaan Lainnya'];
    private array $negara = ['Malaysia', 'Singapura', 'Brunei', 'Arab Saudi', 'Yaman', 'Thailand', 'Filipina'];
    private array $tempatTinggal = ['Bersama Orang Tua', 'Wali', 'Kost', 'Asrama', 'Pesantren', 'Lainnya'];
    private array $moda = ['Jalan Kaki', 'Sepeda', 'Sepeda Motor', 'Ojek', 'Angkutan Umum', 'Mobil Pribadi', 'Lainnya'];
    private array $jarak = ['Kurang dari 1 Km', 'Lebih dari 1 Km'];
    private array $alasanPip = ['Tidak Berminat', 'Sudah Mampu', 'Lainnya'];
    private array $kesejahteraan = ['KPS', 'KIP', 'PKH', 'Beasiswa', 'PIP', 'Lainnya'];
    private array $pendaftaran = ['Siswa Baru', 'Pindahan', 'Kembali'];
    private array $keluar = ['Lulus', 'Pindah', 'Drop Out', 'Meninggal', 'Lainnya'];
    private array $pendidikan = ['Tidak Sekolah', 'SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3', 'Lainnya'];
    private array $pekerjaan = ['Tidak Bekerja', 'Petani', 'Nelayan', 'Buruh', 'Pedagang', 'PNS', 'TNI/Polri', 'Karyawan Swasta', 'Wiraswasta', 'Lainnya'];
    private array $penghasilan = ['Tidak Berpenghasilan', '< 500.000', '500.000 - 1.000.000', '1.000.000 - 2.000.000', '2.000.000 - 5.000.000', '> 5.000.000'];
    private array $berkebutuhan = ['Tidak', 'Netra (A)', 'Rungu (B)', 'Grahita (C)', 'Daksa (D)', 'Laras (E)', 'Wicara (F)', 'Tunaganda (G)', 'Hiperaktif (H)', 'Cerdas Istimewa (I)', 'Bakat Istimewa (J)', 'Lainnya'];
    private array $firstMale = ['Muhammad', 'Ahmad', 'Budi', 'Eko', 'Rizki', 'Dimas', 'Fajar', 'Galih', 'Hendra', 'Ilham', 'Joko', 'Kevin', 'Lutfi', 'Miftah', 'Naufal', 'Oka', 'Putra', 'Rangga', 'Sandi', 'Tegar'];
    private array $firstFemale = ['Siti', 'Aisyah', 'Fatimah', 'Nur', 'Dewi', 'Sri', 'Rina', 'Ani', 'Fitri', 'Salsabila', 'Nabila', 'Indah', 'Putri', 'Rara', 'Suci', 'Tari', 'Umi', 'Wulan', 'Yuni', 'Zahra'];
    private array $last = ['Santoso', 'Wijaya', 'Prasetyo', 'Hidayat', 'Kusuma', 'Saputra', 'Setiawan', 'Gunawan', 'Permana', 'Lestari', 'Utami', 'Anggraini', 'Rahayu', 'Sari', 'Halim'];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        \DB::table('beasiswa_siswa')->truncate();
        \DB::table('prestasi_siswa')->truncate();
        \DB::table('orang_tua')->truncate();
        \DB::table('siswa')->truncate();
        Schema::enableForeignKeyConstraints();

        $kelas = \DB::table('kelas')->get(['id_kelas', 'nama_kelas', 'jenis']);

        foreach ($kelas as $k) {
            for ($i = 1; $i <= 5; $i++) {
                $jk = $k->jenis === 'banin' ? 'L' : ($k->jenis === 'banat' ? 'P' : fake()->randomElement(['L', 'P']));
                $first = $jk === 'L' ? fake()->randomElement($this->firstMale) : fake()->randomElement($this->firstFemale);
                $nama = $first . ' ' . fake()->randomElement($this->last);

                $bk = fake()->randomElement(['Tidak', 'Tidak', 'Tidak', 'Netra (A)', 'Rungu (B)', 'Grahita (C)', 'Daksa (D)', 'Lainnya']);
                $wna = fake()->boolean(15);
                $punyaKip = fake()->boolean(40);
                $pip = fake()->randomElement($this->kesejahteraan);

                $siswa = Siswa::create([
                    'nama_lengkap' => $nama,
                    'jenis_kelamin' => $jk,
                    'nisn' => fake()->numerify('##########'),
                    'nik' => fake()->numerify('################'),
                    'no_kk' => fake()->numerify('################'),
                    'no_akta' => fake()->bothify('AKTA-####/??'),
                    'tempat_lahir' => fake()->city(),
                    'tanggal_lahir' => fake()->dateTimeBetween('-15 years', '-6 years')->format('Y-m-d'),
                    'agama' => fake()->randomElement($this->agama),
                    'kewarganegaraan' => $wna ? 'WNA' : 'WNI',
                    'nama_negara' => $wna ? fake()->randomElement($this->negara) : null,
                    'jenis_kebutuhan_khusus' => $bk,
                    'berkebutuhan_khusus' => $bk !== 'Tidak',
                    'alamat' => fake()->streetAddress(),
                    'rt' => fake()->numerify('###'),
                    'rw' => fake()->numerify('###'),
                    'dusun' => 'Dusun ' . fake()->randomLetter(),
                    'desa_kelurahan' => 'Kel. ' . fake()->city(),
                    'kecamatan' => fake()->city(),
                    'kabupaten_kota' => fake()->city(),
                    'provinsi' => fake()->randomElement(['Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'DKI Jakarta', 'Banten', 'Yogyakarta']),
                    'kode_pos' => fake()->numerify('#####'),
                    'lintang' => fake()->latitude(),
                    'bujur' => fake()->longitude(),
                    'status_tempat_tinggal' => fake()->randomElement($this->tempatTinggal),
                    'moda_transportasi' => fake()->randomElement($this->moda),
                    'anak_ke' => fake()->numberBetween(1, 5),
                    'jumlah_saudara' => fake()->numberBetween(0, 6),
                    'pekerjaan' => fake()->optional(0.3)->jobTitle(),
                    'no_telepon_rumah' => fake()->numerify('021-#######'),
                    'no_hp' => fake()->numerify('08##########'),
                    'email' => fake()->unique()->safeEmail(),
                    'jarak_sekolah' => fake()->randomElement([0.5, 1.5, 2.5, 5.0, 10.0]),
                    'jarak_tempuh' => fake()->randomFloat(2, 0.1, 25),
                    'waktu_jam' => fake()->numberBetween(0, 2),
                    'waktu_menit' => fake()->numberBetween(0, 59),
                    'tinggi_badan' => fake()->randomFloat(2, 110, 170),
                    'berat_badan' => fake()->randomFloat(2, 20, 70),
                    'lingkar_kepala' => fake()->randomFloat(2, 40, 58),
                    'punya_kip' => $punyaKip,
                    'terima_kip' => $punyaKip ? fake()->boolean(70) : false,
                    'alasan_tolak_pip' => !$punyaKip && fake()->boolean(30) ? fake()->randomElement($this->alasanPip) : null,
                    'jenis_kesejahteraan' => $pip,
                    'no_kartu' => $pip !== 'Lainnya' ? fake()->numerify('################') : null,
                    'nama_di_kartu' => $pip !== 'Lainnya' ? $nama : null,
                    'kompetensi_keahlian' => $k->jenis === '' ? fake()->randomElement(['Rekayasa Perangkat Lunak', 'Tata Boga', 'Teknik Kendaraan Ringan', 'Akuntansi']) : null,
                    'jenis_pendaftaran' => fake()->randomElement($this->pendaftaran),
                    'nis' => fake()->numerify('#####'),
                    'tanggal_masuk_sekolah' => fake()->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
                    'sekolah_asal' => fake()->randomElement(['SDN 1 ', 'SDN 2 ', 'MI ', 'SMPN 1 ', 'MTs ']) . fake()->city(),
                    'no_peserta_un' => fake()->numerify('##########'),
                    'no_seri_ijazah' => fake()->numerify('##########'),
                    'no_skhun' => fake()->numerify('##########'),
                    'keluar_karena' => null,
                    'tanggal_keluar' => null,
                    'alasan_keluar' => null,
                    'status_siswa' => 'Aktif',
                    'kelas_id' => $k->id_kelas,
                ]);

                $this->buatOrangTua($siswa);

                if (fake()->boolean(60)) {
                    PrestasiSiswa::create([
                        'siswa_id' => $siswa->id,
                        'jenis' => fake()->randomElement(['Akademik', 'Non-Akademik', 'Olahraga', 'Seni']),
                        'tingkat' => fake()->randomElement(['Kab/Kota', 'Provinsi', 'Nasional', 'Internasional']),
                        'nama' => fake()->sentence(3),
                        'tahun' => fake()->numberBetween(2019, 2025),
                        'penyelenggara' => fake()->company(),
                    ]);
                }

                if (fake()->boolean(50)) {
                    BeasiswaSiswa::create([
                        'siswa_id' => $siswa->id,
                        'jenis' => fake()->randomElement(['KIP', 'Beasiswa Prestasi', 'Beasiswa Miskin', 'PIP']),
                        'keterangan' => fake()->sentence(4),
                        'tahun_mulai' => fake()->numberBetween(2020, 2024),
                        'tahun_selesai' => fake()->numberBetween(2025, 2027),
                    ]);
                }
            }
        }
    }

    private function buatOrangTua(Siswa $siswa): void
    {
        $ayah = fake()->randomElement($this->firstMale) . ' ' . fake()->randomElement($this->last);
        $ibu = fake()->randomElement($this->firstFemale) . ' ' . fake()->randomElement($this->last);
        $wali = fake()->randomElement($this->firstMale) . ' ' . fake()->randomElement($this->last);

        OrangTua::create([
            'siswa_id' => $siswa->id,
            'nama_ayah' => $ayah,
            'nik_ayah' => fake()->numerify('################'),
            'tahun_lahir_ayah' => fake()->numberBetween(1955, 1985),
            'pendidikan_ayah' => fake()->randomElement($this->pendidikan),
            'pekerjaan_ayah' => fake()->randomElement($this->pekerjaan),
            'penghasilan_ayah' => fake()->randomElement($this->penghasilan),
            'berkebutuhan_ayah' => fake()->randomElement(['Tidak', 'Tidak', 'Netra (A)', 'Lainnya']),
            'nama_ibu' => $ibu,
            'nik_ibu' => fake()->numerify('################'),
            'tahun_lahir_ibu' => fake()->numberBetween(1955, 1985),
            'pendidikan_ibu' => fake()->randomElement($this->pendidikan),
            'pekerjaan_ibu' => fake()->randomElement($this->pekerjaan),
            'penghasilan_ibu' => fake()->randomElement($this->penghasilan),
            'berkebutuhan_ibu' => fake()->randomElement(['Tidak', 'Tidak', 'Netra (A)', 'Lainnya']),
            'nama_wali' => $wali,
            'hubungan_wali' => fake()->randomElement(['Kakek', 'Nenek', 'Paman', 'Lainnya']),
            'nik_wali' => fake()->numerify('################'),
            'tahun_lahir_wali' => fake()->numberBetween(1955, 1985),
            'pendidikan_wali' => fake()->randomElement($this->pendidikan),
            'pekerjaan_wali' => fake()->randomElement($this->pekerjaan),
            'penghasilan_wali' => fake()->randomElement($this->penghasilan),
        ]);
    }
}
