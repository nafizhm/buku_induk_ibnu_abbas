<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\LampiranSiswa;
use App\Models\Kegiatan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrangTuaPortalController extends Controller
{
    use \App\Http\Controllers\Concerns\ValidatesDapodik;

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
        $form = $request->query('form') ?? $request->query('tab');

        return $this->portalView('profil', [
            'profileForm' => in_array($form, ['dapodik', 'siswa', 'ayah', 'ibu', 'wali', 'berkas'], true) ? $form : null,
            'profileSummary' => $this->profileSummary(),
        ]);
    }

    public function updateProfil(Request $request, string $section)
    {
        $siswa = $this->resolveSiswa();

        if (! in_array($section, ['akun', 'siswa', 'ayah', 'ibu', 'wali'], true)) {
            abort(404);
        }

        $data = $this->validateSection($request, $section, $section !== 'akun');

        if ($section === 'akun') {
            $account = Auth::user();
            $account->nama = $data['fields']['akun']['nama'];
            if (! empty($data['fields']['akun']['password'])) $account->password = $data['fields']['akun']['password'];
            $account->save();
        } elseif ($section === 'siswa') {
            if (empty($data['fields']['nama_lengkap'])) {
                unset($data['fields']['nama_lengkap']);
            }
            $siswa->update($this->mapSiswaFields($data['fields']));
        } else {
            $ot = $siswa->orangTua()->firstOrNew([]);
            foreach ($data['fields'][$section] ?? [] as $field => $value) {
                if (is_array($value)) {
                    $values = collect($value)->filter()->unique();
                    if ($values->count() > 1) $values = $values->reject(fn ($item) => str_starts_with($item, '01)'));
                    $value = $values->implode(', ');
                }
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

        $lampiran = LampiranSiswa::updateOrCreate(
            ['siswa_id' => $siswa->id, 'jenis_dokumen' => $validated['jenis_dokumen']],
            [
                'path' => $path,
                'nama_asli' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'ukuran' => $file->getSize(),
            ]
        );

        return response()->json([
            'message' => 'Lampiran berhasil diunggah.',
            'ok' => true,
            'file' => [
                'id' => $lampiran->id,
                'nama_asli' => $lampiran->nama_asli,
                'view_url' => route('orang-tua.lampiran.view', $lampiran->id),
                'delete_url' => route('orang-tua.lampiran.delete', $lampiran->id),
            ],
        ]);
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
        $kegiatanMendatang = Kegiatan::query()
            ->where('status', 'aktif')
            ->whereDate('tgl_kegiatan', '>=', today())
            ->orderBy('tgl_kegiatan')->orderBy('id')->get();
        $daftarKegiatan = Kegiatan::query()->orderByDesc('tgl_kegiatan')->orderByDesc('id')->get();
        $kegiatanBerikutnya = $kegiatanMendatang->first();
        $qrKegiatan = $kegiatanBerikutnya
            ? $kegiatanBerikutnya->presensi()->where('siswa_id', $siswa->id)->first()
            : null;

        return view('orang-tua.dashboard', array_merge([
            'account' => Auth::user(),
            'siswa' => $siswa,
            'orangTua' => $siswa->orangTua,
            'activeView' => $activeView,
            'profileForm' => null,
            'allowIncomplete' => true,
            'profileSummary' => $this->profileSummary(),
            'presensiData' => $this->mockPresensi(),
            'hafalanData' => $this->mockHafalan(),
            'kegiatanMendatang' => $kegiatanMendatang,
            'daftarKegiatan' => $daftarKegiatan,
            'qrKegiatan' => $qrKegiatan,
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
