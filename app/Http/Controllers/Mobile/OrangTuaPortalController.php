<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\LampiranSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrangTuaPortalController extends Controller
{
    private const DOKUMEN = [
        'foto_siswa' => 'Foto Siswa',
        'kartu_keluarga' => 'Kartu Keluarga',
        'akta_kelahiran' => 'Akta Kelahiran',
        'ktp_ayah' => 'KTP Ayah',
        'ktp_ibu' => 'KTP Ibu',
    ];

    public function beranda()
    {
        return $this->portalView('beranda');
    }

    public function presensi()
    {
        return $this->portalView('presensi');
    }

    public function kegiatan()
    {
        return $this->portalView('kegiatan');
    }

    public function hafalan()
    {
        return $this->portalView('hafalan');
    }

    public function profil(Request $request)
    {
        $form = $request->query('form');

        return $this->portalView('profil', [
            'profileForm' => in_array($form, ['siswa', 'ayah', 'ibu', 'wali'], true) ? $form : null,
            'profileSummary' => $this->profileSummary(),
        ]);
    }

    public function updateProfil(Request $request, string $section)
    {
        $siswa = $this->resolveSiswa();

        if (! in_array($section, ['siswa', 'ayah', 'ibu', 'wali'], true)) {
            abort(404);
        }

        $data = $this->validateSection($request, $section);

        if ($section === 'siswa') {
            $siswa->update($this->mapSiswaFields($data['fields']));
        } else {
            $ot = $siswa->orangTua()->firstOrNew([]);
            foreach ($data['fields'][$section] ?? [] as $field => $value) {
                $ot->{$field} = $value;
            }
            $ot->save();
        }

        return response()->json(['message' => 'Data berhasil disimpan.', 'ok' => true]);
    }

    public function uploadLampiran(Request $request)
    {
        $siswa = $this->resolveSiswa();

        $validated = $request->validate([
            'jenis_dokumen' => 'required|in:' . implode(',', array_keys(self::DOKUMEN)),
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [], self::DOKUMEN);

        $file = $request->file('file');
        $path = $file->store('lampiran-siswa/' . $siswa->id, 'public');

        LampiranSiswa::updateOrCreate(
            ['siswa_id' => $siswa->id, 'jenis_dokumen' => $validated['jenis_dokumen']],
            [
                'path' => $path,
                'nama_asli' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'ukuran' => $file->getSize(),
            ]
        );

        return response()->json(['message' => 'Lampiran berhasil diunggah.', 'ok' => true]);
    }

    public function viewLampiran(LampiranSiswa $lampiran)
    {
        $this->authorizeLampiran($lampiran);

        return response()->download(
            Storage::disk('public')->path($lampiran->path),
            $lampiran->nama_asli,
            ['Content-Type' => $lampiran->mime_type ?? 'application/octet-stream'],
            'inline'
        );
    }

    public function deleteLampiran(LampiranSiswa $lampiran)
    {
        $this->authorizeLampiran($lampiran);

        Storage::disk('public')->delete($lampiran->path);
        $lampiran->delete();

        return response()->json(['message' => 'Lampiran berhasil dihapus.', 'ok' => true]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mobile.login')->with('success', 'Anda telah logout.');
    }

    // ------------------------------------------------------------------

    private function portalView(string $activeView, array $extra = [])
    {
        $siswa = $this->resolveSiswa();
        $siswa->load(['kelas', 'orangTua', 'lampiran']);

        return view('orang-tua.dashboard', array_merge([
            'account' => Auth::user(),
            'siswa' => $siswa,
            'orangTua' => $siswa->orangTua,
            'activeView' => $activeView,
            'profileForm' => null,
            'profileSummary' => $this->profileSummary(),
            'presensiData' => $this->mockPresensi(),
            'hafalanData' => $this->mockHafalan(),
        ], $extra));
    }

    private function resolveSiswa(): Siswa
    {
        $siswa = Auth::user()?->siswa()->first();

        abort_if(! $siswa, 403, 'Akun ini tidak terhubung dengan data santri.');

        return $siswa;
    }

    private function authorizeLampiran(LampiranSiswa $lampiran): void
    {
        abort_unless($lampiran->siswa_id === $this->resolveSiswa()->id, 403);
    }

    private function profileSummary(): array
    {
        $siswa = $this->resolveSiswa();
        $siswa->load(['orangTua', 'lampiran']);
        $ot = $siswa->orangTua;

        $sections = [
            ['label' => 'Data siswa', 'form' => 'siswa', 'values' => [
                'Nama lengkap' => $siswa->nama_lengkap,
                'Tempat/tanggal lahir' => ($siswa->tempat_lahir && $siswa->tanggal_lahir) ? 'x' : null,
                'Jenis kelamin' => $siswa->jenis_kelamin,
                'Alamat' => $siswa->alamat,
                'NISN' => $siswa->nisn,
            ]],
            ['label' => 'Data ayah', 'form' => 'ayah', 'values' => [
                'Nama ayah' => $ot?->nama_ayah, 'NIK' => $ot?->nik_ayah,
                'Pekerjaan' => $ot?->pekerjaan_ayah, 'No. HP' => $ot?->no_telp_ayah,
            ]],
            ['label' => 'Data ibu', 'form' => 'ibu', 'values' => [
                'Nama ibu' => $ot?->nama_ibu, 'NIK' => $ot?->nik_ibu,
                'Pekerjaan' => $ot?->pekerjaan_ibu, 'No. HP' => $ot?->no_telp_ibu,
            ]],
            ['label' => 'Data wali', 'form' => 'wali', 'optional' => true, 'values' => [
                'Nama wali' => $ot?->nama_wali, 'Hubungan' => $ot?->hubungan_wali,
            ]],
        ];

        $missingDocs = collect(self::DOKUMEN)
            ->reject(fn($label, $kind) => $siswa->lampiran->contains('jenis_dokumen', $kind))
            ->values()
            ->all();

        $sections[] = ['label' => 'Lampiran', 'form' => 'berkas', 'missing' => $missingDocs, 'values' => empty($missingDocs) ? ['x'] : []];

        return array_map(function ($s) use ($siswa) {
            $missing = $s['missing'] ?? collect($s['values'])
                ->reject(fn($v) => $v !== null && $v !== '')
                ->keys()
                ->all();

            return [
                'label' => $s['label'],
                'form' => $s['form'],
                'optional' => $s['optional'] ?? false,
                'complete' => empty($missing),
                'missing' => $missing,
                'updated_at' => $siswa->updated_at,
            ];
        }, $sections);
    }

    private function validateSection(Request $request, string $section): array
    {
        $rules = match ($section) {
            'siswa' => [
                'nama_lengkap' => 'required|string|max:200',
                    'nipd' => 'nullable|string|max:20',
                    'jenis_kelamin' => 'nullable|in:L,P',
                    'tempat_lahir' => 'nullable|string|max:100',
                    'tanggal_lahir' => 'nullable|date',
                    'tanggal_masuk_sekolah' => 'nullable|date',
                    'agama' => 'nullable|string|max:50',
                    'kewarganegaraan' => 'nullable|string|max:50',
                    'alamat' => 'nullable|string|max:1000',
                    'nisn' => 'nullable|string|max:25',
                    'nik' => 'nullable|string|max:16',
                    'no_kk' => 'nullable|string|max:16',
                    'nama_panggilan' => 'nullable|string|max:100',
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
                    'status_tempat_tinggal' => 'nullable|string|max:30',
                    'jarak_sekolah' => 'nullable|numeric|min:0',
                    'moda_transportasi' => 'nullable|string|max:100',
                    'no_hp_darurat' => 'nullable|string|max:20',
                    'golongan_darah' => 'nullable|string|max:5',
                    'tinggi_badan' => 'nullable|numeric',
                    'berat_badan' => 'nullable|numeric',
                    'lingkar_kepala' => 'nullable|numeric',
                    'berkebutuhan_khusus' => 'nullable|in:0,1',
                    'jenis_kebutuhan_khusus' => 'nullable|string|max:191',
                    'riwayat_kesehatan' => 'nullable|string|max:1000',
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
                "berkebutuhan_{$prefix}" => 'nullable|string|max:50',
            ],
            'ibu' => [
                "nama_{$prefix}" => 'required|string|max:200',
                "nik_{$prefix}" => 'nullable|string|max:16',
                "tahun_lahir_{$prefix}" => 'nullable|integer|min:1900|max:2099',
                "no_telp_{$prefix}" => 'nullable|string|max:20',
                "pendidikan_{$prefix}" => 'nullable|string|max:100',
                "pekerjaan_{$prefix}" => 'nullable|string|max:100',
                "penghasilan_{$prefix}" => 'nullable|string|max:50',
                "berkebutuhan_{$prefix}" => 'nullable|string|max:50',
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
        if (($data['tanggal_masuk_sekolah'] ?? '') === '') {
            unset($data['tanggal_masuk_sekolah']);
        }

        if (array_key_exists('jarak_sekolah', $data)) {
            $data['jarak_sekolah'] = $data['jarak_sekolah'] === '' ? null : $data['jarak_sekolah'];
        }

        return $data;
    }

    // Placeholder sampai tabel presensi/hafalan dibuat
    private function mockPresensi(): array
    {
        return [
            '2026-08-03' => 'ontime', '2026-08-04' => 'ontime', '2026-08-05' => 'late',
            '2026-08-06' => 'izin', '2026-08-10' => 'ontime', '2026-08-11' => 'ontime',
            '2026-08-12' => 'sakit', '2026-08-13' => 'ontime', '2026-08-17' => 'ontime',
            '2026-08-18' => 'alpa', '2026-08-19' => 'ontime', '2026-08-20' => 'ontime',
        ];
    }

    private function mockHafalan(): array
    {
        return [
            '2026-08-03' => ['surah' => "An-Naba'", 'ayat' => '1 – 16', 'hadits' => 'Arbain Nawawi No. 1 — Niat'],
            '2026-08-04' => ['surah' => "An-Naba'", 'ayat' => '17 – 30', 'hadits' => 'Arbain Nawawi No. 1 — Murajaah Niat'],
            '2026-08-05' => ['surah' => "An-Naba'", 'ayat' => '31 – 40', 'hadits' => 'Arbain Nawawi No. 2 — Rukun Islam & Iman'],
            '2026-08-10' => ['surah' => "An-Nazi'at", 'ayat' => '1 – 14', 'hadits' => 'Arbain Nawawi No. 2 — Murajaah'],
            '2026-08-11' => ['surah' => "An-Nazi'at", 'ayat' => '15 – 26', 'hadits' => 'Arbain Nawawi No. 3 — Rukun Islam'],
            '2026-08-13' => ['surah' => "'Abasa", 'ayat' => '1 – 16', 'hadits' => 'Bulughul Maram — Bab Wudhu No. 1'],
            '2026-08-17' => ['surah' => "'Abasa", 'ayat' => '17 – 32', 'hadits' => 'Bulughul Maram — Bab Wudhu No. 2'],
            '2026-08-20' => ['surah' => 'At-Takwir', 'ayat' => '1 – 14', 'hadits' => 'Arbain Nawawi No. 4 — Penciptaan Manusia'],
        ];
    }
}
