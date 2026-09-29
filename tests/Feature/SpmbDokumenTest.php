<?php

namespace Tests\Feature;

use App\Models\HakAkses;
use App\Models\SpmbPendaftaran;
use App\Models\User;
use App\Support\SpmbDokumen;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SpmbDokumenTest extends TestCase
{
    use DatabaseTransactions;

    private function registration(string $jenjang = 'SD'): SpmbPendaftaran
    {
        $record = SpmbPendaftaran::create([
            'nama' => 'Santri Dokumen', 'jenjang' => $jenjang, 'jk' => 'Putra (Banin)',
            'ortu' => 'Orang Tua Dokumen', 'wa' => '081234567890',
            'bukti_path' => 'test.pdf', 'bukti_mime' => 'application/pdf',
        ]);
        $record->forceFill(['status' => 'isi formulir'])->save();

        return $record;
    }

    public function test_documents_follow_level_and_saved_consent_is_immutable(): void
    {
        foreach (['SD' => 11, 'SMP' => 9] as $jenjang => $count) {
            $record = $this->registration($jenjang);
            $this->assertCount($count, SpmbDokumen::pertanyaan($jenjang));
            $this->get(route('spmb.dokumen', $record->token))->assertRedirect(route('spmb.pernyataan', $record->token));
            $this->get(route('spmb.pernyataan', $record->token))->assertOk()
                ->assertSee(SpmbDokumen::program($jenjang))->assertSee('50 %')->assertDontSee('4. Wawancara')->assertDontSee('<textarea', false);
            $page = $this->get(route('spmb.wawancara', $record->token))->assertOk()
                ->assertSee(SpmbDokumen::program($jenjang))->assertSee('4. Wawancara')->assertDontSee('3. Surat Pernyataan')->assertDontSee('Simpan Persetujuan');
            foreach (SpmbDokumen::pertanyaan($jenjang) as $question) $page->assertSee($question);
            $jenjang === 'SD' ? $page->assertDontSee('program menghafal') : $page->assertDontSee('Speech Delay');
            $this->postJson(route('spmb.pernyataan.store', $record->token), ['peraturan' => 1])->assertUnprocessable()->assertJsonValidationErrors('biaya');
            $this->assertNull($record->fresh()->pernyataan_at);
            $this->post(route('spmb.pernyataan.store', $record->token), ['peraturan' => 1, 'biaya' => 1])->assertRedirect();
            $record->refresh();
            $snapshot = $record->pernyataan_data;
            $date = $record->pernyataan_at->toDateTimeString();
            $this->assertTrue($snapshot['persetujuan']['peraturan']);
            $this->assertEquals(SpmbDokumen::pernyataan($jenjang), $snapshot['dokumen']);
            $record->update(['ortu' => 'Nama Berubah']);
            $this->post(route('spmb.pernyataan.store', $record->token), ['peraturan' => 1, 'biaya' => 1])->assertRedirect();
            $this->assertSame($snapshot, $record->fresh()->pernyataan_data);
            $this->assertSame($date, $record->fresh()->pernyataan_at->toDateTimeString());
            $this->get(route('spmb.pernyataan', $record->token))->assertOk()->assertSee('Disetujui oleh Orang Tua Dokumen');
            $this->get(route('spmb.formulir', $record->token))->assertOk()->assertSee('1 dari 4 bagian selesai');
        }
    }

    public function test_interview_validates_all_questions_and_persists_updates_without_changing_other_registration(): void
    {
        foreach (['SD', 'SMP'] as $jenjang) {
            $record = $this->registration($jenjang);
            $other = $this->registration($jenjang);
            $url = route('spmb.wawancara.store', $record->token);
            $answers = array_fill_keys(array_keys(SpmbDokumen::pertanyaan($jenjang)), 'Jawaban orang tua');
            $this->postJson($url, ['jawaban' => [], 'kebenaran' => 1])->assertUnprocessable();
            $this->postJson($url, ['jawaban' => $answers, 'kebenaran' => 0])->assertUnprocessable();
            $this->assertNull($record->fresh()->wawancara_at);
            $this->post($url, ['jawaban' => $answers, 'kebenaran' => 1])->assertRedirect(route('spmb.wawancara', $record->token));
            $this->assertEquals($answers, $record->fresh()->wawancara_data['jawaban']);
            $this->assertSame(array_keys($answers), $record->fresh()->wawancara_data['urutan']);
            $this->get(route('spmb.wawancara', $record->token))->assertOk()->assertSee('Jawaban orang tua');
            $answers['biaya'] = '<script>alert(1)</script>';
            $this->post($url, ['jawaban' => $answers, 'kebenaran' => 1])->assertRedirect();
            $this->get(route('spmb.wawancara', $record->token))->assertOk()->assertSee($answers['biaya'])->assertDontSee($answers['biaya'], false);
            $this->assertNull($other->fresh()->wawancara_data);
        }
    }

    public function test_inactive_and_unknown_tokens_cannot_read_or_submit(): void
    {
        $record = $this->registration();
        $record->forceFill(['status' => 'formulir'])->save();
        foreach ([$record->token => 403, 'INVALID000' => 404] as $token => $status) {
            $this->get(route('spmb.dokumen', $token))->assertStatus($status);
            $this->get(route('spmb.pernyataan', $token))->assertStatus($status);
            $this->get(route('spmb.wawancara', $token))->assertStatus($status);
            $this->post(route('spmb.pernyataan.store', $token), [])->assertStatus($status);
            $this->post(route('spmb.wawancara.store', $token), [])->assertStatus($status);
        }
    }

    public function test_admin_details_require_menu_access_and_matching_level(): void
    {
        foreach (['SD' => 'admin.pendaftar', 'SMP' => 'admin.smp.pendaftar'] as $jenjang => $prefix) {
            $record = $this->registration($jenjang);
            $record->forceFill(['status' => 'selesai', 'selesai_at' => now()])->save();
            $other = $this->registration($jenjang === 'SD' ? 'SMP' : 'SD');
            $access = HakAkses::where('lihat', 1)->whereHas('menu', fn ($q) => $q->where('route_name', $prefix.'.index'))->firstOrFail();
            $admin = User::findOrFail($access->id_user);
            foreach (['pernyataan', 'wawancara'] as $jenis) {
                $this->get(route($prefix.'.'.$jenis, $record))->assertRedirect();
            }
            $this->post(route('spmb.pernyataan.store', $record->token), ['peraturan' => 1, 'biaya' => 1])->assertRedirect();
            $answers = array_fill_keys(array_keys(SpmbDokumen::pertanyaan($jenjang)), 'Jawaban tersimpan');
            $this->post(route('spmb.wawancara.store', $record->token), ['jawaban' => $answers, 'kebenaran' => 1])->assertRedirect();
            $this->actingAs($admin)->get(route($prefix.'.index'))->assertOk()->assertSee(route($prefix.'.pernyataan', $record), false)->assertSee(route($prefix.'.wawancara', $record), false);
            $this->get(route($prefix.'.pernyataan', $record))->assertOk()->assertSee('Disetujui')->assertSee('50 %');
            $this->get(route($prefix.'.wawancara', $record))->assertOk()->assertSee('Jawaban tersimpan');
            foreach (['pernyataan', 'wawancara'] as $jenis) $this->get(route($prefix.'.'.$jenis, $other))->assertNotFound();
            $access->update(['lihat' => 0]);
            foreach (['pernyataan', 'wawancara'] as $jenis) $this->get(route($prefix.'.'.$jenis, $record))->assertForbidden();
            $access->update(['lihat' => 1]);
            auth()->logout();
        }
    }
}
