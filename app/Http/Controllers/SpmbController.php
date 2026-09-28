<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use App\Models\SpmbPendaftaran;
use App\Models\SpmbLampiran;
use App\Models\Siswa;
use App\Http\Controllers\Mobile\SiswaController as MobileSiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SpmbController extends Controller
{
    use \App\Http\Controllers\Concerns\ValidatesDapodik;

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', Rule::in(['SD', 'SMP'])],
            'jk' => ['required', Rule::in($request->input('jenjang') === 'SMP' ? ['Putra (Banin)'] : ['Putra (Banin)', 'Putri (Banat)'])],
            'ortu' => ['required', 'string', 'max:255'],
            'wa' => ['required', 'string', 'max:25', 'regex:/^\+?[0-9][0-9\s\-]{7,23}$/'],
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:10240'],
        ], [
            'jenjang.required' => 'Pilih jenjang SD atau SMP.',
            'jenjang.in' => 'Jenjang yang tersedia adalah SD dan SMP.',
            'jk.in' => 'Jenjang SMP hanya tersedia untuk Putra (Banin).',
            'bukti.required' => 'Mohon upload bukti transfer.',
            'bukti.mimes' => 'Bukti transfer harus berupa JPG, PNG, WebP, atau PDF.',
            'bukti.max' => 'Ukuran bukti transfer maksimal 10 MB.',
            'wa.regex' => 'Masukkan nomor WhatsApp yang valid.',
        ]);
        $file = $request->file('bukti');
        $path = $file->store('spmb/bukti', 'local');
        abort_unless($path, 500, 'Bukti transfer gagal disimpan. Silakan coba lagi.');
        unset($data['bukti']);
        try {
            $pendaftaran = SpmbPendaftaran::create($data + [
                'bukti_path' => $path, 'bukti_mime' => $file->getMimeType(),
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }
        $request->session()->put('spmb_berhasil_id', $pendaftaran->id);

        return response()->json(['redirect' => route('spmb.berhasil')], 201);
    }

    public function berhasil(Request $request)
    {
        $pendaftaran = SpmbPendaftaran::find($request->session()->get('spmb_berhasil_id'));
        if (! $pendaftaran) {
            return redirect()->route('spmb');
        }

        return response()->view('spmb-berhasil', compact('pendaftaran'))
            ->header('Cache-Control', 'no-store, private');
    }

    private function authorizeAdmin(string $menu = 'admin.spmb.index'): void
    {
        abort_unless(HakAkses::where('id_user', auth()->id())->where('lihat', 1)
            ->whereHas('menu', fn ($query) => $query->where('route_name', $menu))->exists(), 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        return view('admin.spmb.index', ['pendaftaran' => SpmbPendaftaran::latest('id')->get()]);
    }

    public function pendaftar()
    {
        $this->authorizeAdmin('admin.pendaftar.index');

        return view('admin.spmb.index', [
            'pendaftaran' => SpmbPendaftaran::where('status', 'selesai')->latest('selesai_at')->get(),
            'isPendaftar' => true,
        ]);
    }

    private function authorizeDetail(SpmbPendaftaran $pendaftaran): void
    {
        $menu = request()->routeIs('admin.pendaftar.*') ? 'admin.pendaftar.index' : 'admin.spmb.index';
        $this->authorizeAdmin($menu);
        abort_if($menu === 'admin.pendaftar.index' && $pendaftaran->status !== 'selesai', 404);
    }

    public function detail(SpmbPendaftaran $pendaftaran)
    {
        $this->authorizeDetail($pendaftaran);

        return response()->view('admin.spmb.detail', [
            'pendaftaran' => $pendaftaran->load('lampiran'),
            'siswa' => $pendaftaran->siswa_id ? Siswa::with('orangTua')->findOrFail($pendaftaran->siswa_id) : null,
            'routePrefix' => request()->routeIs('admin.pendaftar.*') ? 'admin.pendaftar' : 'admin.spmb',
        ])->header('Cache-Control', 'no-store, private');
    }

    public function adminLampiran(SpmbPendaftaran $pendaftaran, string $jenis)
    {
        $this->authorizeDetail($pendaftaran);
        $lampiran = $pendaftaran->lampiran()->where('jenis', $jenis)->firstOrFail();
        abort_unless(Storage::disk('local')->exists($lampiran->path), 404);

        return response()->file(Storage::disk('local')->path($lampiran->path), [
            'Cache-Control' => 'no-store, private', 'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function bukti(SpmbPendaftaran $pendaftaran)
    {
        $this->authorizeDetail($pendaftaran);
        abort_unless(Storage::disk('local')->exists($pendaftaran->bukti_path), 404);

        return response()->file(Storage::disk('local')->path($pendaftaran->bukti_path), [
            'Content-Type' => $pendaftaran->bukti_mime,
            'Cache-Control' => 'no-store, private',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function kirimWa(SpmbPendaftaran $pendaftaran)
    {
        $this->authorizeAdmin();
        $phone = preg_replace('/\D/', '', $pendaftaran->wa);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }
        $link = route('spmb.formulir', ['token' => $pendaftaran->token]);
        $message = "Assalamu'alaikum Bapak/Ibu {$pendaftaran->ortu},\n\nTerima kasih atas pendaftaran calon santri {$pendaftaran->nama}. Silakan lengkapi data registrasi dan berkas pendaftaran melalui link berikut:\n{$link}\n\nSilakan buka Data Formulir dan Berkas / Lampiran, lalu lengkapi setiap bagian. Progres dapat dilihat melalui tautan yang sama. Surat Pernyataan akan menyusul.\n\nAdmin SPMB Rumah Qur'an Ibnu Abbas";

        // Follow-up tidak boleh mengembalikan status pendaftaran yang sudah selesai.
        SpmbPendaftaran::whereKey($pendaftaran->id)->where('status', 'formulir')
            ->update(['status' => 'isi formulir', 'wa_dikirim_at' => now()]);

        return response()->json([
            'url' => 'https://wa.me/'.$phone.'?text='.rawurlencode($message),
            'status' => $pendaftaran->fresh()->status,
        ]);
    }

    private function registration(string $token): SpmbPendaftaran
    {
        $registration = SpmbPendaftaran::where('token', $token)->firstOrFail();
        abort_unless(hash_equals($registration->token, $token), 404);
        abort_if($registration->status === 'formulir', 403, 'Link formulir belum diaktifkan oleh admin.');

        return $registration;
    }

    public function formulir(string $token)
    {
        $spmb = $this->registration($token);
        $lampiran = $spmb->lampiran()->get()->keyBy('jenis');
        $required = collect(SpmbLampiran::DOKUMEN)->where('required', true)->keys();
        $uploaded = $required->filter(fn ($jenis) => $lampiran->has($jenis))->count();
        $formComplete = $spmb->status === 'selesai';
        $filesComplete = $uploaded === $required->count();
        $completed = (int) $formComplete + (int) $filesComplete;

        return $this->registrationView('spmb.registrasi', compact('spmb', 'uploaded', 'formComplete', 'filesComplete', 'completed'));
    }

    private function registrationView(string $view, array $data)
    {
        return response()->view($view, $data)->header('Cache-Control', 'no-store, private')
            ->header('Referrer-Policy', 'no-referrer');
    }

    public function lampiran(string $token)
    {
        $spmb = $this->registration($token);
        $lampiran = $spmb->lampiran()->get()->keyBy('jenis');
        $dokumen = SpmbLampiran::DOKUMEN;

        return $this->registrationView('spmb.lampiran', compact('spmb', 'lampiran', 'dokumen'));
    }

    public function uploadLampiran(Request $request, string $token)
    {
        $spmb = $this->registration($token);
        $data = $request->validate([
            'jenis' => ['required', Rule::in(array_keys(SpmbLampiran::DOKUMEN))],
            'file' => ['required', 'file', $request->input('jenis') === 'pas_foto' ? 'mimes:jpg,jpeg,png,webp' : 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'file.required' => 'Pilih berkas yang akan diunggah.',
            'file.mimes' => 'Gunakan JPG, PNG, WebP, atau PDF. Pas foto harus berupa gambar.',
            'file.max' => 'Ukuran berkas maksimal 5 MB.',
        ]);
        $file = $request->file('file');
        $path = $file->store('spmb/lampiran/'.$spmb->id, 'local');
        abort_unless($path, 500, 'Berkas gagal disimpan. Silakan coba kembali.');
        try {
            $oldPath = DB::transaction(function () use ($spmb, $data, $file, $path) {
                $registration = SpmbPendaftaran::whereKey($spmb->id)->lockForUpdate()->firstOrFail();
                $attachment = $registration->lampiran()->firstOrNew(['jenis' => $data['jenis']]);
                $oldPath = $attachment->path;
                $attachment->fill([
                    'path' => $path, 'nama_asli' => mb_substr($file->getClientOriginalName(), 0, 255),
                    'mime' => $file->getMimeType(), 'ukuran' => $file->getSize(),
                ])->save();

                return $oldPath;
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }
        if ($oldPath) Storage::disk('local')->delete($oldPath);

        if ($request->expectsJson()) {
            $jenis = $data['jenis'];
            $document = SpmbLampiran::DOKUMEN[$jenis];
            $number = array_search($jenis, array_keys(SpmbLampiran::DOKUMEN), true) + 1;
            $lampiran = $spmb->lampiran()->get()->keyBy('jenis');

            return response()->json([
                'message' => $document['label'].' berhasil disimpan.',
                'html' => view('spmb.lampiran-card', compact('spmb', 'jenis', 'document', 'number', 'lampiran'))->render(),
            ])->header('Cache-Control', 'no-store, private');
        }

        return redirect()->route('spmb.lampiran', $token)->with('success', SpmbLampiran::DOKUMEN[$data['jenis']]['label'].' berhasil disimpan.');
    }

    public function hapusLampiran(string $token, string $jenis)
    {
        $spmb = $this->registration($token);
        abort_unless(array_key_exists($jenis, SpmbLampiran::DOKUMEN), 404);
        DB::transaction(function () use ($spmb, $jenis) {
            $registration = SpmbPendaftaran::whereKey($spmb->id)->lockForUpdate()->firstOrFail();
            $lampiran = $registration->lampiran()->where('jenis', $jenis)->firstOrFail();
            $disk = Storage::disk('local');
            if ($disk->exists($lampiran->path) && ! $disk->delete($lampiran->path)) {
                abort(500, 'Berkas belum dapat dihapus. Silakan coba kembali.');
            }
            $lampiran->delete();
        });

        return redirect()->route('spmb.lampiran', $token)
            ->with('success', SpmbLampiran::DOKUMEN[$jenis]['label'].' berhasil dihapus. Silakan unggah penggantinya.');
    }

    public function lihatLampiran(string $token, string $jenis)
    {
        $spmb = $this->registration($token);
        $lampiran = $spmb->lampiran()->where('jenis', $jenis)->firstOrFail();
        abort_unless(Storage::disk('local')->exists($lampiran->path), 404);

        return response()->file(Storage::disk('local')->path($lampiran->path), [
            'Content-Type' => $lampiran->mime,
            'Cache-Control' => 'no-store, private',
            'Referrer-Policy' => 'no-referrer',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function isian(string $token)
    {
        $spmb = $this->registration($token);
        if ($spmb->status === 'selesai') {
            return $this->registrationView('spmb.formulir-selesai', [
                'spmb' => $spmb,
                'title' => 'Formulir Telah Diterima',
                'description' => 'Formulir pendaftaran Anda sudah berhasil dikirim. Terima kasih. — Admin SPMB',
                'primaryLabel' => 'Kembali ke Registrasi', 'primaryUrl' => route('spmb.formulir', $token),
            ]);
        }

        $siswa = $spmb->siswa_id ? Siswa::with('orangTua')->findOrFail($spmb->siswa_id) : new Siswa([
            'nama_lengkap' => $spmb->nama,
            'jenis_kelamin' => $spmb->jk === 'Putra (Banin)' ? 'L' : 'P',
            'no_hp' => $spmb->wa,
        ]);
        $orangTua = $siswa->orangTua;

        return response()->view('spmb-formulir', compact('spmb', 'siswa', 'orangTua'))->header('Cache-Control', 'no-store, private')
            ->header('Referrer-Policy', 'same-origin');
    }

    public function dataFormulir(string $token)
    {
        $spmb = $this->registration($token);
        abort_unless($spmb->siswa_id, 404);
        abort_if($spmb->status === 'selesai', 403);

        return app(MobileSiswaController::class)->show(Siswa::findOrFail($spmb->siswa_id));
    }

    public function simpanFormulir(Request $request, string $token)
    {
        $registration = $this->registration($token);

        return DB::transaction(function () use ($request, $registration) {
            $spmb = SpmbPendaftaran::whereKey($registration->id)->lockForUpdate()->firstOrFail();
            abort_if($spmb->status === 'selesai', 409, 'Formulir sudah dikirim.');
            foreach (['jenis_kebutuhan_khusus', 'ayah.berkebutuhan_ayah', 'ibu.berkebutuhan_ibu'] as $key) {
                $value = $request->input($key);
                if (is_array($value)) {
                    $input = $request->all();
                    data_set($input, $key, array_values(array_filter($value, fn ($item) => $item !== null && $item !== '')));
                    $request->replace($input);
                }
            }
            $request->validate([
                'jenis_kelamin' => ['required', Rule::in($spmb->jenjang === 'SMP' ? ['L'] : ['L', 'P'])],
            ], ['jenis_kelamin.in' => 'Jenjang SMP hanya tersedia untuk Putra (Banin).']);
            $data = $this->mapSiswaFields($this->validateSection($request, 'siswa')['fields']);
            unset($data['status_siswa'], $data['kelas_saat_masuk'], $data['tahun_ajaran_masuk']);
            $family = [];
            foreach (['ayah', 'ibu', 'wali'] as $section) {
                $rules = $this->orangTuaRules($section);
                if (! $request->boolean('_selesai')) {
                    $rules['nama_'.$section] = 'nullable|string|max:200';
                }
                $validated = $request->validate(collect($rules)->mapWithKeys(fn ($rule, $key) => ["{$section}.{$key}" => $rule])->all());
                foreach ($validated[$section] ?? [] as $key => $value) {
                    if (is_array($value)) {
                        $values = collect($value)->filter()->unique();
                        if ($values->count() > 1) $values = $values->reject(fn ($item) => str_starts_with($item, '01)'));
                        $value = $values->implode(', ');
                    }
                    $family[$key] = $value;
                }
            }
            $siswa = $spmb->siswa_id ? Siswa::findOrFail($spmb->siswa_id) : new Siswa;
            $siswa->fill($data);
            $siswa->status_siswa = $request->boolean('_selesai') ? 'Aktif' : 'Draft';
            $siswa->save();
            $siswa->orangTua()->updateOrCreate([], $family);
            $spmb->siswa_id = $siswa->id;
            if ($request->boolean('_selesai')) {
                $spmb->status = 'selesai';
                $spmb->selesai_at = now();
            }
            $spmb->save();

            return response()->json(['id' => $siswa->id, 'ok' => true, 'selesai' => $spmb->status === 'selesai']);
        });
    }
}
