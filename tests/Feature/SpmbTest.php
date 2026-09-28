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
            'nama' => 'Calon Santri Uji', 'jk' => 'Putra (Banin)',
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
        $this->get($form)->assertOk()->assertSee('spmbUrl', false);
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
        $this->get($form)->assertOk()->assertSee('Jalan Santri')->assertSee('Ayah Santri')->assertSee('Ibu Santri');
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
        $this->get($form)->assertOk()->assertSee('Formulir Telah Diterima');
        $this->postJson($wa)->assertOk()->assertJsonPath('status', 'selesai');
        $this->putJson($form, ['nama_lengkap' => 'Perubahan setelah selesai'])->assertStatus(409);
        $this->getJson($form.'/data')->assertForbidden();
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
