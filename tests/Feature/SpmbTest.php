<?php

namespace Tests\Feature;

use App\Models\HakAkses;
use App\Models\SpmbPendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SpmbTest extends TestCase
{
    use DatabaseTransactions;

    private function payload(): array
    {
        return [
            'nama' => 'Calon Santri Uji', 'jk' => 'Putra (Banin)', 'jenjang' => 'SD',
            'ortu' => 'Orang Tua Uji', 'wa' => '081234567890',
            'bukti' => UploadedFile::fake()->create('transfer.pdf', 100, 'application/pdf'),
        ];
    }

    private function admin(): User
    {
        $access = HakAkses::where('lihat', 1)
            ->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.spmb.index'))->firstOrFail();

        return User::findOrFail($access->id_user);
    }

    public function test_incomplete_link_forms_can_be_saved_and_exported_by_level(): void
    {
        config(['spmb.allow_incomplete_forms' => true]);
        Storage::fake('local');
        $records = [];
        foreach (['SD', 'SMP'] as $jenjang) {
            $this->postJson('/spmb', array_replace($this->payload(), ['jenjang' => $jenjang]))->assertCreated();
            $record = SpmbPendaftaran::latest('id')->firstOrFail();
            $record->forceFill(['status' => 'isi formulir'])->save();
            $records[$jenjang] = $record;
            $url = route('spmb.formulir.store', $record->token);
            $this->get(route('spmb.formulir.isian', $record->token))->assertOk()->assertDontSee(' required', false);
            $this->postJson($url, ['_selesai' => 0, 'nama_lengkap' => null, 'jenis_kelamin' => null])->assertOk()->assertJsonPath('selesai', false);
            $this->postJson($url, ['email' => 'invalid'])->assertUnprocessable();
            $this->postJson($url, [
                '_selesai' => 1, 'nik' => $jenjang === 'SD' ? '0012345678901234' : '0012345678901235', 'no_akta' => 'AKTA-UJI',
                'ayah' => ['nama_ayah' => null, 'no_telp_ayah' => '08123450001'],
                'ibu' => ['nama_ibu' => null, 'no_telp_ibu' => '08123450002'],
                'wali' => ['hubungan_wali' => 'Paman Uji'],
            ])->assertOk()->assertJsonPath('selesai', true);
            $this->assertSame($record->nama, $record->fresh()->siswa->nama_lengkap);
        }
        $admin = $this->admin();
        $this->actingAs($admin);
        foreach (['SD' => 'admin.pendaftar', 'SMP' => 'admin.smp.pendaftar'] as $jenjang => $prefix) {
            $record = $records[$jenjang];
            $other = $records[$jenjang === 'SD' ? 'SMP' : 'SD'];
            $this->get(route($prefix.'.index'))->assertOk()->assertSee('Download Excel');
            $this->get(route($prefix.'.download-one', $record))->assertOk()
                ->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
                ->assertSee($jenjang === 'SD' ? '0012345678901234' : '0012345678901235')->assertSee('AKTA-UJI')->assertSee('08123450001')
                ->assertSee('08123450002')->assertSee('Paman Uji')
                ->assertSee('#2563eb')->assertSee('#16a34a')->assertSee('#db2777')->assertSee('#d97706')
                ->assertSee($record->token)->assertDontSee($other->token);
            $this->get(route($prefix.'.download'))->assertOk()->assertSee($record->token)->assertDontSee($other->token);
            $this->get(route($prefix.'.download', ['filter' => 'lengkap']))->assertOk()->assertDontSee($record->token);
            $this->get(route($prefix.'.download-one', $other))->assertNotFound();
            $record->refresh()->forceFill(['status' => 'isi formulir'])->save();
            $this->get(route($prefix.'.download-one', $record))->assertNotFound();
            HakAkses::where('id_user', $admin->id)->whereHas('menu', fn ($query) => $query->where('route_name', $prefix.'.index'))->update(['lihat' => 0]);
            $this->get(route($prefix.'.download'))->assertForbidden();
            $this->get(route($prefix.'.download-one', $record))->assertForbidden();
        }
    }

    public function test_dashboard_statistics_match_filtered_lists_and_required_attachments(): void
    {
        $this->actingAs($this->admin());
        $before = $this->get(route('dashboard'))->assertOk()->viewData('spmbStatistik');
        $records = [];
        foreach (['SD', 'SMP'] as $jenjang) {
            foreach (['baru', 'proses', 'kurang', 'lengkap'] as $state) {
                $record = SpmbPendaftaran::create([
                    'nama' => 'Statistik '.$jenjang.' '.$state, 'jk' => 'Putra (Banin)', 'jenjang' => $jenjang,
                    'ortu' => 'Orang Tua Statistik', 'wa' => '081234567890',
                    'bukti_path' => 'statistik.pdf', 'bukti_mime' => 'application/pdf',
                ]);
                if ($state !== 'baru') {
                    $record->forceFill([
                        'status' => $state === 'proses' ? 'isi formulir' : 'selesai',
                        'wa_dikirim_at' => now(),
                    ])->save();
                }
                if (in_array($state, ['kurang', 'lengkap'])) {
                    foreach (\App\Models\SpmbLampiran::DOKUMEN as $jenis => $document) {
                        if (! $document['required'] || ($state === 'kurang' && $jenis === 'pas_foto')) {
                            continue;
                        }
                        $record->lampiran()->create(['jenis' => $jenis, 'path' => 'statistik.pdf', 'mime' => 'application/pdf', 'nama_asli' => 'statistik.pdf', 'ukuran' => 100]);
                    }
                }
                $records[$jenjang][$state] = $record;
            }
        }
        $response = $this->get(route('dashboard'))->assertOk()->assertSee('SPMB SD')->assertSee('SPMB SMP');
        $stats = $response->viewData('spmbStatistik');
        foreach (['SD', 'SMP'] as $jenjang) {
            foreach (['semua' => 4, 'wa' => 3, 'proses' => 1, 'lengkap' => 1] as $filter => $delta) {
                $this->assertSame($before[$jenjang][$filter]['jumlah'] + $delta, $stats[$jenjang][$filter]['jumlah']);
                $list = $this->get($stats[$jenjang][$filter]['url'])->assertOk();
                $this->assertCount($stats[$jenjang][$filter]['jumlah'], $list->viewData('pendaftaran'));
                foreach ($records[$jenjang] as $state => $record) {
                    $included = $filter === 'semua' || ($filter === 'wa' && $state !== 'baru') || ($filter === 'proses' && $state === 'proses') || ($filter === 'lengkap' && $state === 'lengkap');
                    $included ? $list->assertSee($record->token) : $list->assertDontSee($record->token);
                }
                foreach ($records[$jenjang === 'SD' ? 'SMP' : 'SD'] as $record) {
                    $list->assertDontSee($record->token);
                }
            }
        }
        $records['SD']['lengkap']->lampiran()->where('jenis', 'pas_foto')->delete();
        $after = $this->get(route('dashboard'))->assertOk()->viewData('spmbStatistik');
        $this->assertSame($stats['SD']['lengkap']['jumlah'] - 1, $after['SD']['lengkap']['jumlah']);
        $this->getJson(route('admin.spmb.index', ['filter' => 'invalid']))->assertUnprocessable();
        HakAkses::where('id_user', auth()->id())->whereHas('menu', fn ($query) => $query->whereIn('route_name', ['admin.smp.spmb.index', 'admin.smp.pendaftar.index']))->update(['lihat' => 0]);
        $restricted = $this->get(route('dashboard'))->assertOk()->viewData('spmbStatistik');
        $this->assertArrayNotHasKey('SMP', $restricted);
        $this->get(route('admin.smp.spmb.index', ['filter' => 'wa']))->assertForbidden();
    }

    public function test_admin_menus_separate_sd_and_smp_records_and_access(): void
    {
        Storage::fake('local');
        $records = [];
        foreach (['SD', 'SMP'] as $jenjang) {
            $this->postJson('/spmb', array_replace($this->payload(), ['jenjang' => $jenjang]))->assertCreated();
            $records[$jenjang] = SpmbPendaftaran::latest('id')->firstOrFail();
        }
        $admin = $this->admin();
        $this->actingAs($admin);
        foreach (['SD' => 'admin', 'SMP' => 'admin.smp'] as $jenjang => $prefix) {
            $record = $records[$jenjang];
            $other = $records[$jenjang === 'SD' ? 'SMP' : 'SD'];
            $this->get(route($prefix.'.spmb.index'))->assertOk()
                ->assertSee('SPMB SD')->assertSee('SPMB SMP')->assertSee('SPMB '.$jenjang.' - Formulir')
                ->assertSee($record->token)->assertDontSee($other->token);
            $this->get(route($prefix.'.pendaftar.index'))->assertOk()->assertDontSee($record->token);
            $this->get(route($prefix.'.spmb.bukti', $other))->assertNotFound();
            $this->postJson(route($prefix.'.spmb.kirim-wa', $other))->assertNotFound();
            $this->postJson(route($prefix.'.spmb.kirim-wa', $record))->assertOk();
            $record->forceFill(['status' => 'selesai', 'selesai_at' => now()])->save();
            $this->get(route($prefix.'.pendaftar.index'))->assertOk()->assertSee($record->token)->assertDontSee($other->token);
            $this->get(route($prefix.'.pendaftar.detail', $record))->assertOk()->assertSee(route($prefix.'.pendaftar.index'));
            $this->get(route($prefix.'.pendaftar.detail', $other))->assertNotFound();
            $this->get(route($prefix.'.pendaftar.lampiran', [$other, 'akta_kelahiran']))->assertNotFound();
        }
        HakAkses::where('id_user', $admin->id)->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.smp.spmb.index'))->update(['lihat' => 0]);
        $this->get(route('admin.smp.spmb.index'))->assertForbidden();
        $this->postJson(route('admin.smp.spmb.kirim-wa', $records['SMP']))->assertForbidden();
        $this->get(route('admin.spmb.index'))->assertOk();
    }

    public function test_pendaftar_lists_only_submitted_forms_and_protects_details(): void
    {
        Storage::fake('local');
        $this->postJson('/spmb', $this->payload())->assertCreated();
        $record = SpmbPendaftaran::latest('id')->firstOrFail();
        $record->status = 'isi formulir';
        $record->save();
        $this->postJson(route('spmb.lampiran.store', $record->token), [
            'jenis' => 'akta_kelahiran',
            'file' => UploadedFile::fake()->create('akta.pdf', 100, 'application/pdf'),
        ])->assertSuccessful();
        $file = $record->lampiran()->firstOrFail();
        $this->assertStringStartsWith('spmb/lampiran/'.$record->id.'/', $file->path);
        $this->get(route('admin.pendaftar.index'))->assertRedirect(route('login'));
        $admin = $this->admin();
        $this->actingAs($admin)->get(route('admin.pendaftar.index'))->assertOk()->assertDontSee($record->token);
        $this->get(route('admin.pendaftar.detail', $record))->assertNotFound();
        $record->status = 'selesai';
        $record->selesai_at = now();
        $record->save();
        $this->get(route('admin.pendaftar.index'))->assertOk()->assertSee($record->token);
        $this->get(route('admin.pendaftar.detail', $record))->assertOk()->assertSee('Lihat Lampiran');
        $this->get(route('admin.pendaftar.lampiran', [$record, 'akta_kelahiran']))->assertOk();
        HakAkses::where('id_user', $admin->id)->whereHas('menu', fn ($q) => $q->where('route_name', 'admin.pendaftar.index'))->update(['lihat' => 0]);
        $this->get(route('admin.pendaftar.index'))->assertForbidden();
        $this->get(route('admin.pendaftar.detail', $record))->assertForbidden();
        $this->get(route('admin.pendaftar.lampiran', [$record, 'akta_kelahiran']))->assertForbidden();
    }

    public function test_jenjang_and_payment_information(): void
    {
        Storage::fake('local');
        $page = $this->get('/spmb')->assertOk()->assertSee('6922406810')->assertSee('081905059919')
            ->assertSee('name="jenjang"', false);
        $this->assertSame(14, $page->viewData('quotaAvailability')['SD']['Putra (Banin)']['limit']);
        $data = $this->payload();
        unset($data['jenjang']);
        $this->postJson('/spmb', $data)->assertUnprocessable()->assertJsonValidationErrors('jenjang');
        $data['jenjang'] = 'SMA';
        $this->postJson('/spmb', $data)->assertUnprocessable()->assertJsonValidationErrors('jenjang');
        $data['jenjang'] = 'SMP';
        $data['jk'] = 'Putri (Banat)';
        $this->postJson('/spmb', $data)->assertUnprocessable()->assertJsonValidationErrors('jk');
        $data['jk'] = 'Putra (Banin)';
        $this->postJson('/spmb', $data)->assertCreated();
        $record = SpmbPendaftaran::latest('id')->firstOrFail();
        $this->assertSame('SMP', $record->jenjang);
        $this->get('/spmb/berhasil')->assertOk()->assertSee('SMP');
        $data['jenjang'] = 'SD';
        $data['jk'] = 'Putri (Banat)';
        $this->postJson('/spmb', $data)->assertCreated();
        $this->assertDatabaseHas('spmb_pendaftaran', ['jenjang' => 'SD', 'jk' => 'Putri (Banat)', 'nama' => $data['nama']]);
    }

    public function test_submission_saves_private_receipt_and_shows_confirmation(): void
    {
        Storage::fake('local');
        $this->get('/spmb')->assertOk()->assertSee('name="_token"', false);
        $this->postJson('/spmb', $this->payload())->assertCreated()
            ->assertJsonPath('redirect', route('spmb.berhasil'));
        $record = SpmbPendaftaran::latest('id')->firstOrFail();
        $this->assertDatabaseHas('spmb_pendaftaran', ['id' => $record->id, 'ortu' => 'Orang Tua Uji', 'wa' => '081234567890']);
        Storage::disk('local')->assertExists($record->bukti_path);
        $this->get('/spmb/berhasil')->assertOk()->assertSee('Orang Tua Uji')->assertSee('081234567890')->assertSee('Admin SPMB');

        $this->get(route('admin.spmb.bukti', $record))->assertRedirect(route('login'));
        $admin = $this->admin();
        $this->actingAs($admin)->get(route('admin.spmb.index'))->assertOk()
            ->assertSee('Calon Santri Uji')->assertSee('Lihat Bukti')->assertSee('buktiModal');
        $this->get(route('admin.spmb.bukti', $record))->assertOk()->assertHeader('Content-Type', 'application/pdf');

        HakAkses::where('id_user', $admin->id)->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.spmb.index'))->update(['lihat' => 0]);
        $this->get(route('admin.spmb.index'))->assertForbidden();
        $this->get(route('admin.spmb.bukti', $record))->assertForbidden();
    }

    public function test_invalid_submissions_do_not_save_data(): void
    {
        Storage::fake('local');
        $count = SpmbPendaftaran::count();
        $this->postJson('/spmb', [])->assertUnprocessable()->assertJsonValidationErrors(['nama', 'jk', 'ortu', 'wa', 'bukti']);
        $data = $this->payload();
        $data['wa'] = 'bukan nomor';
        $data['bukti'] = UploadedFile::fake()->create('script.svg', 1, 'image/svg+xml');
        $this->postJson('/spmb', $data)->assertUnprocessable()->assertJsonValidationErrors(['wa', 'bukti']);
        $data = $this->payload();
        $data['bukti'] = UploadedFile::fake()->create('besar.pdf', 10241, 'application/pdf');
        $this->postJson('/spmb', $data)->assertUnprocessable()->assertJsonValidationErrors('bukti');
        $this->assertSame($count, SpmbPendaftaran::count());
        $this->assertSame([], Storage::disk('local')->allFiles());
    }

    public function test_image_receipt_is_served_to_authorized_admin(): void
    {
        Storage::fake('local');
        $data = $this->payload();
        $data['bukti'] = UploadedFile::fake()->create('transfer.png', 20, 'image/png');
        $this->postJson('/spmb', $data)->assertCreated();
        $record = SpmbPendaftaran::latest('id')->firstOrFail();
        $this->actingAs($this->admin())->get(route('admin.spmb.bukti', $record))->assertOk()->assertHeader('Content-Type', 'image/png');
    }

    public function test_success_page_requires_submission_session(): void
    {
        $this->get('/spmb/berhasil')->assertRedirect(route('spmb'));
        $this->get('/admin/spmb')->assertRedirect(route('login'));
    }

    public function test_followup_token_form_and_completion_lifecycle(): void
    {
        config(['spmb.allow_incomplete_forms' => false]);
        Storage::fake('local');
        $this->postJson('/spmb', $this->payload())->assertCreated();
        $record = SpmbPendaftaran::latest('id')->firstOrFail();
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{10}$/', $record->token);
        $this->assertSame('formulir', $record->status);
        $form = route('spmb.formulir', $record->token);
        $wa = route('admin.spmb.kirim-wa', $record);
        $this->get($form)->assertForbidden();
        $this->postJson($wa)->assertUnauthorized();
        $this->assertSame('formulir', $record->fresh()->status);

        $response = $this->actingAs($this->admin())->postJson($wa)->assertOk()->assertJsonPath('status', 'isi formulir');
        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $response->json('url'));
        $this->assertStringContainsString($form, rawurldecode($response->json('url')));
        $this->assertNotNull($record->fresh()->wa_dikirim_at);
        $this->get($form)->assertOk()->assertSee('1. Data Formulir')->assertSee('2. Berkas / Lampiran')->assertSee('3. Surat Pernyataan')->assertSee('0 dari 4 bagian selesai');
        $this->get($form.'/isian')->assertOk()->assertSee('spmbUrl', false);
        $this->get('/spmb/formulir/INVALID000')->assertNotFound();

        $this->postJson($form, ['_selesai' => 1])->assertUnprocessable();
        $this->assertSame('isi formulir', $record->fresh()->status);
        $data = ['nama_lengkap' => 'Santri Token Uji', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Balikpapan', 'tanggal_lahir' => '2020-01-01'];
        $id = $this->postJson($form, $data)->assertOk()->json('id');
        $this->assertSame($id, $record->fresh()->siswa_id);
        $this->postJson($form, $data)->assertOk()->assertJsonPath('id', $id);
        $this->get($form.'/data')->assertOk()->assertJsonPath('data.nama_lengkap', 'Santri Token Uji');
        $this->getJson('/mobile/siswa/'.$id)->assertForbidden();
        $this->putJson('/siswa/'.$id, ['_selesai' => 1])->assertForbidden();
        $this->putJson($form, ['jenis_kelamin' => 'invalid', '_selesai' => 1])->assertUnprocessable();
        $this->assertSame('isi formulir', $record->fresh()->status);
        $data += [
            'kewarganegaraan' => 'Indonesia (WNI)', 'punya_kip' => '01) Ya',
            'alamat' => 'Jalan Santri', 'jarak_sekolah' => 2.5, 'jumlah_saudara_kandung' => 2,
            'jenis_kebutuhan_khusus' => ['01) Tidak', '02) Netra (A)', '03) Rungu (B)'],
            'ayah' => ['nama_ayah' => 'Ayah Santri', 'berkebutuhan_ayah' => ['01) Tidak']],
            'ibu' => ['nama_ibu' => 'Ibu Santri'],
        ];
        $this->putJson($form, $data)->assertOk();
        $this->assertDatabaseHas('siswa', [
            'id' => $id, 'kewarganegaraan' => 'Indonesia (WNI)', 'punya_kip' => '01) Ya',
            'alamat' => 'Jalan Santri', 'jarak_sekolah' => 2.5, 'jumlah_saudara_kandung' => 2,
            'jenis_kebutuhan_khusus' => '02) Netra (A), 03) Rungu (B)', 'berkebutuhan_khusus' => 1,
        ]);
        $this->get($form.'/isian')->assertOk()->assertSee('Jalan Santri')->assertSee('Ayah Santri')->assertSee('Ibu Santri');
        $this->get($form)->assertOk()->assertSee('Draf tersimpan')->assertSee('0 dari 4 bagian selesai');
        $invalid = $data;
        $invalid['ibu']['nama_ibu'] = '';
        $this->putJson($form, $invalid + ['_selesai' => 1])->assertUnprocessable()->assertJsonValidationErrors('ibu.nama_ibu');
        $this->assertSame('isi formulir', $record->fresh()->status);
        $data['jenis_kebutuhan_khusus'] = [''];
        $data['ayah']['berkebutuhan_ayah'] = [''];
        $this->putJson($form, $data + ['_selesai' => 1])->assertOk();
        $this->assertDatabaseHas('siswa', ['id' => $id, 'jenis_kebutuhan_khusus' => '', 'berkebutuhan_khusus' => 0]);
        $this->assertSame('selesai', $record->fresh()->status);
        $this->assertNotNull($record->fresh()->selesai_at);
        $this->get($form.'/isian')->assertOk()->assertSee('Formulir Telah Diterima');
        $this->get($form)->assertOk()->assertSee('1 dari 4 bagian selesai')->assertSee('Selesai · Formulir terkirim');
        $this->postJson($wa)->assertOk()->assertJsonPath('status', 'selesai');
        $submittedAt = $record->fresh()->selesai_at->toDateTimeString();
        $this->get($form.'/isian')->assertOk()->assertSee('Edit Data Formulir');
        $this->get($form.'/isian?edit=1')->assertOk()->assertSee('Simpan Perubahan')->assertSee('Ayah Santri')->assertDontSee('Simpan Draf');
        $data['nama_lengkap'] = 'Perubahan setelah selesai';
        $this->putJson($form, $data + ['_selesai' => 1])->assertOk()->assertJsonPath('id', $id)->assertJsonPath('selesai', true);
        $this->putJson($form, $data + ['_selesai' => 0])->assertOk()->assertJsonPath('selesai', true);
        $this->assertSame($submittedAt, $record->fresh()->selesai_at->toDateTimeString());
        $this->assertDatabaseHas('siswa', ['id' => $id, 'nama_lengkap' => 'Perubahan setelah selesai', 'status_siswa' => 'Aktif']);
        $this->getJson($form.'/data')->assertOk()->assertJsonPath('data.nama_lengkap', 'Perubahan setelah selesai');
    }

    public function test_private_attachments_and_independent_registration_progress(): void
    {
        Storage::fake('local');
        $this->postJson('/spmb', $this->payload())->assertCreated();
        $record = SpmbPendaftaran::latest('id')->firstOrFail();
        $index = route('spmb.formulir', $record->token);
        $upload = route('spmb.lampiran.store', $record->token);
        $fileUrl = route('spmb.lampiran.view', [$record->token, 'akta_kelahiran']);
        $this->get($upload)->assertForbidden();
        $this->postJson($upload, [])->assertForbidden();
        $this->get($fileUrl)->assertForbidden();
        $record->status = 'isi formulir';
        $record->save();
        $this->get($upload)->assertOk()->assertSee('Fotocopy Ijazah TK')->assertSee('baju putih berkerah')->assertSee('jilbab putih');
        $this->postJson($upload, ['jenis' => 'unknown'])->assertUnprocessable()->assertJsonValidationErrors(['jenis', 'file']);
        $this->postJson($upload, ['jenis' => 'akta_kelahiran', 'file' => UploadedFile::fake()->create('script.svg', 1, 'image/svg+xml')])
            ->assertUnprocessable()->assertJsonValidationErrors('file');
        $this->postJson($upload, ['jenis' => 'akta_kelahiran', 'file' => UploadedFile::fake()->create('besar.pdf', 5121, 'application/pdf')])
            ->assertUnprocessable()->assertJsonValidationErrors('file');
        $this->postJson($upload, ['jenis' => 'pas_foto', 'file' => UploadedFile::fake()->create('foto.pdf', 100, 'application/pdf')])
            ->assertUnprocessable()->assertJsonValidationErrors('file');

        foreach (['akta_kelahiran', 'kartu_keluarga', 'ktp_ayah', 'ktp_ibu'] as $jenis) {
            $response = $this->postJson($upload, ['jenis' => $jenis, 'file' => UploadedFile::fake()->create($jenis.'.pdf', 100, 'application/pdf')])->assertOk();
            $this->assertStringContainsString('Hapus berkas', $response->json('html'));
            $this->assertStringContainsString(route('spmb.lampiran.delete', [$record->token, $jenis]), $response->json('html'));
            $this->assertStringContainsString('upload-spinner', $response->json('html'));
            $saved = $record->lampiran()->where('jenis', $jenis)->firstOrFail();
            Storage::disk('local')->assertExists($saved->path);
        }
        $this->assertNull($record->fresh()->siswa_id); // Berkas dapat dilengkapi sebelum formulir.
        $this->get($index)->assertOk()->assertSee('4 dari 5 berkas wajib')->assertSee('0 dari 4 bagian selesai');
        $original = $record->lampiran()->where('jenis', 'akta_kelahiran')->firstOrFail();
        Storage::disk('local')->assertExists($original->path);
        $this->get($fileUrl)->assertOk()->assertHeader('Content-Type', 'application/pdf')->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->post($upload, ['jenis' => 'akta_kelahiran', 'file' => UploadedFile::fake()->create('akta-baru.pdf', 100, 'application/pdf')])->assertRedirect();
        Storage::disk('local')->assertMissing($original->path);
        $this->assertSame(4, $record->lampiran()->count());
        $this->post($upload, ['jenis' => 'pas_foto', 'file' => UploadedFile::fake()->create('foto.png', 100, 'image/png')])->assertRedirect();
        $this->get($index)->assertOk()->assertSee('1 dari 4 bagian selesai')->assertSee('Selesai · 5 dari 5 berkas wajib')->assertSee('4. Wawancara');

        $data = ['nama_lengkap' => 'Uji Lampiran', 'jenis_kelamin' => 'L', 'ayah' => ['nama_ayah' => 'Ayah'], 'ibu' => ['nama_ibu' => 'Ibu'], '_selesai' => 1];
        $this->postJson($index, $data)->assertOk();
        $this->get($index)->assertOk()->assertSee('2 dari 4 bagian selesai')->assertDontSee('3 dari 4 bagian selesai');
        $this->post($upload, ['jenis' => 'ijazah_tk', 'file' => UploadedFile::fake()->create('ijazah.pdf', 100, 'application/pdf')])->assertRedirect();
        $this->assertSame(6, $record->lampiran()->count()); // Tetap dapat diunggah setelah formulir dikirim.

        $other = SpmbPendaftaran::create([
            'nama' => 'Pendaftar Lain', 'jk' => 'Putra (Banin)', 'jenjang' => 'SD',
            'ortu' => 'Orang Tua Lain', 'wa' => '081234567899',
            'bukti_path' => 'spmb/bukti/other.pdf', 'bukti_mime' => 'application/pdf',
        ]);
        $other->status = 'isi formulir';
        $other->save();
        $this->get(route('spmb.lampiran.view', [$other->token, 'akta_kelahiran']))->assertNotFound();
        $this->get('/spmb/formulir/INVALID000/lampiran/akta_kelahiran')->assertNotFound();
        $this->get(route('spmb.formulir', $other->token))->assertOk()->assertSee('0 dari 4 bagian selesai');

        $this->get($upload)->assertOk()->assertSee('id="preview-dialog"', false)
            ->assertSee('Perbesar Foto KTP Ayah')->assertSee('Hapus berkas')
            ->assertSee('lampiran.js')->assertSee('lampiran.css');
        $photo = $record->lampiran()->where('jenis', 'pas_foto')->firstOrFail();
        $deleteUrl = route('spmb.lampiran.delete', [$record->token, 'pas_foto']);
        $this->delete(route('spmb.lampiran.delete', [$other->token, 'pas_foto']))->assertNotFound();
        $other->status = 'formulir';
        $other->save();
        $this->delete(route('spmb.lampiran.delete', [$other->token, 'pas_foto']))->assertForbidden();
        Storage::disk('local')->assertExists($photo->path);
        $this->delete('/spmb/formulir/INVALID000/lampiran/pas_foto')->assertNotFound();
        $this->delete($deleteUrl)->assertRedirect($upload);
        Storage::disk('local')->assertMissing($photo->path);
        $this->assertDatabaseMissing('spmb_lampiran', ['id' => $photo->id]);
        $this->get($index)->assertOk()->assertSee('1 dari 4 bagian selesai')->assertSee('Belum lengkap · 4 dari 5 berkas wajib');
        $this->get(route('spmb.lampiran.view', [$record->token, 'pas_foto']))->assertNotFound();
        $this->delete($deleteUrl)->assertNotFound();
        $this->assertSame(5, $record->lampiran()->count());
    }

    public function test_registration_time_is_displayed_in_wita_and_tokens_are_distinct(): void
    {
        Storage::fake('local');
        $this->postJson('/spmb', $this->payload())->assertCreated();
        $first = SpmbPendaftaran::latest('id')->firstOrFail();
        $first->created_at = \Illuminate\Support\Carbon::parse('2026-09-28 02:15:00', 'UTC')->timezone(config('app.timezone'));
        $first->save();
        $this->postJson('/spmb', $this->payload())->assertCreated();
        $second = SpmbPendaftaran::latest('id')->firstOrFail();
        $this->assertNotSame($first->token, $second->token);
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/spmb')->assertOk()->assertSee('28-09-2026 10:15 WITA')->assertSee('Kirim WA');
        HakAkses::where('id_user', $admin->id)->whereHas('menu', fn ($q) => $q->where('route_name', 'admin.spmb.index'))->update(['lihat' => 0]);
        $this->postJson(route('admin.spmb.kirim-wa', $first))->assertForbidden();
        $this->assertSame('formulir', $first->fresh()->status);
    }
}
