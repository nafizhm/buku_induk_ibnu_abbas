<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait ValidatesDapodik
{
    private function validateSection(Request $request, string $section): array
    {
        $rules = match ($section) {
            'akun' => [
                'akun.nama' => 'required|string|max:191',
                'akun.password' => 'nullable|string|min:6|confirmed',
            ],
            'siswa' => [
                'nama_lengkap' => 'required|string|max:200',
                    'nipd' => 'nullable|string|max:20',
                    'jenis_kelamin' => 'nullable|in:L,P',
                    'tempat_lahir' => 'nullable|string|max:100',
                    'tanggal_lahir' => 'nullable|date',
                    'tanggal_masuk_sekolah' => 'nullable|date',
                    'agama' => 'nullable|string|max:50',
                    'kewarganegaraan' => 'nullable|string|max:50',
                    'nama_negara' => 'nullable|string|max:100',
                    'alamat' => 'nullable|string|max:1000',
                    'nisn' => 'nullable|string|max:25',
                    'nik' => 'nullable|string|max:16',
                    'no_kk' => 'nullable|string|max:16',
                    'no_akta' => 'nullable|string|max:100',
                    'nama_panggilan' => 'nullable|string|max:100',
                    'pekerjaan' => 'nullable|string|max:100',
                    'punya_kip' => 'nullable|in:01) Ya,02) Tidak',
                    'terima_kip' => 'nullable|in:01) Ya,02) Tidak',
                    'alasan_tolak_pip' => 'nullable|string|max:100',
                    'anak_ke' => 'nullable|integer|min:1',
                    'jumlah_saudara_kandung' => 'nullable|integer|min:0',
                    'jumlah_saudara_tiri' => 'nullable|integer|min:0',
                    'jumlah_saudara_angkat' => 'nullable|integer|min:0',
                    'status_anak' => 'nullable|string|max:20',
                    'status_dalam_keluarga' => 'nullable|string|max:100',
                    'tahun_ajaran_masuk' => 'nullable|string|max:20',
                    'kelas_saat_masuk' => 'nullable|string|max:50',
                    'status_siswa' => 'nullable|string|max:20',
                    'npsn_sekolah_asal' => 'nullable|string|max:20',
                    'no_ijazah_sebelumnya' => 'nullable|string|max:100',
                    'no_skhun_sttb' => 'nullable|string|max:100',
                    'rt' => 'nullable|string|max:5',
                    'rw' => 'nullable|string|max:5',
                    'dusun' => 'nullable|string|max:100',
                    'desa_kelurahan' => 'nullable|string|max:100',
                    'kecamatan' => 'nullable|string|max:100',
                    'kabupaten_kota' => 'nullable|string|max:100',
                    'provinsi' => 'nullable|string|max:100',
                    'kode_pos' => 'nullable|string|max:10',
                    'lintang' => 'nullable|numeric|between:-90,90',
                    'bujur' => 'nullable|numeric|between:-180,180',
                    'status_tempat_tinggal' => 'nullable|string|max:30',
                    'jarak_sekolah' => 'nullable|numeric|min:0',
                    'moda_transportasi' => 'nullable|string|max:100',
                    'no_hp_darurat' => 'nullable|string|max:20',
                    'no_telepon_rumah' => 'nullable|string|max:20',
                    'no_hp' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:191',
                    'golongan_darah' => 'nullable|string|max:5',
                    'tinggi_badan' => 'nullable|numeric',
                    'berat_badan' => 'nullable|numeric',
                    'lingkar_kepala' => 'nullable|numeric',
                    'jenis_kebutuhan_khusus' => 'nullable|array',
                    'jenis_kebutuhan_khusus.*' => 'string|max:100',
                    'riwayat_kesehatan' => 'nullable|string|max:1000',
                    'waktu_jam' => 'nullable|integer|min:0|max:23',
                    'waktu_menit' => 'nullable|integer|min:0|max:59',
                    'jenis_kesejahteraan' => 'nullable|string|max:50',
                    'no_kartu' => 'nullable|string|max:100',
                    'nama_di_kartu' => 'nullable|string|max:191',
                    'kompetensi_keahlian' => 'nullable|string|max:100',
                    'jenis_pendaftaran' => 'nullable|string|max:50',
                    'sekolah_asal' => 'nullable|string|max:191',
                    'no_peserta_un' => 'nullable|string|max:100',
                    'no_seri_ijazah' => 'nullable|string|max:100',
                    'no_skhun' => 'nullable|string|max:100',
                    'keluar_karena' => 'nullable|string|max:50',
                    'tanggal_keluar' => 'nullable|date',
                    'alasan_keluar' => 'nullable|string|max:1000',
            ],
            default => collect($this->orangTuaRules($section))
                ->mapWithKeys(fn($rule, $name) => ["{$section}.{$name}" => $rule])
                ->all(),
        };

        return ['fields' => $request->validate($rules)];
    }

    private function orangTuaRules(string $prefix): array
    {
        return match ($prefix) {
            'ayah' => [
                "nama_{$prefix}" => 'required|string|max:200',
                "nik_{$prefix}" => 'nullable|string|max:16',
                "tahun_lahir_{$prefix}" => 'nullable|integer|min:1900|max:2099',
                "no_telp_{$prefix}" => 'nullable|string|max:20',
                "pendidikan_{$prefix}" => 'nullable|string|max:100',
                "pekerjaan_{$prefix}" => 'nullable|string|max:100',
                "penghasilan_{$prefix}" => 'nullable|string|max:50',
                "berkebutuhan_{$prefix}" => 'nullable|array',
                "berkebutuhan_{$prefix}.*" => 'string|max:100',
            ],
            'ibu' => [
                "nama_{$prefix}" => 'required|string|max:200',
                "nik_{$prefix}" => 'nullable|string|max:16',
                "tahun_lahir_{$prefix}" => 'nullable|integer|min:1900|max:2099',
                "no_telp_{$prefix}" => 'nullable|string|max:20',
                "pendidikan_{$prefix}" => 'nullable|string|max:100',
                "pekerjaan_{$prefix}" => 'nullable|string|max:100',
                "penghasilan_{$prefix}" => 'nullable|string|max:50',
                "berkebutuhan_{$prefix}" => 'nullable|array',
                "berkebutuhan_{$prefix}.*" => 'string|max:100',
            ],
            'wali' => [
                'nama_wali' => 'nullable|string|max:200',
                'hubungan_wali' => 'nullable|string|max:100',
                'nik_wali' => 'nullable|string|max:16',
                'tahun_lahir_wali' => 'nullable|integer|min:1900|max:2099',
                'pendidikan_wali' => 'nullable|string|max:100',
                'pekerjaan_wali' => 'nullable|string|max:100',
                'penghasilan_wali' => 'nullable|string|max:50',
            ],
        };
    }

    private function mapSiswaFields(array $data): array
    {
        if (array_key_exists('jenis_kebutuhan_khusus', $data)) {
            $needs = collect($data['jenis_kebutuhan_khusus'])->filter()->unique();
            if ($needs->count() > 1) $needs = $needs->reject(fn ($value) => str_starts_with($value, '01)'));
            $data['jenis_kebutuhan_khusus'] = $needs->implode(', ');
            $data['berkebutuhan_khusus'] = $needs->isNotEmpty() && ! str_starts_with((string) $needs->first(), '01)');
        } else {
            unset($data['jenis_kebutuhan_khusus']);
        }

        if (($data['tanggal_masuk_sekolah'] ?? '') === '') {
            unset($data['tanggal_masuk_sekolah']);
        }

        if (array_key_exists('jarak_sekolah', $data)) {
            $data['jarak_sekolah'] = $data['jarak_sekolah'] === '' ? null : $data['jarak_sekolah'];
        }

        return $data;
    }

}
