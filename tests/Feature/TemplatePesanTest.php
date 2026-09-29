<?php

namespace Tests\Feature;

use App\Models\HakAkses;
use App\Models\SpmbPendaftaran;
use App\Models\TemplatePesan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TemplatePesanTest extends TestCase
{
    use DatabaseTransactions;

    private function access(): HakAkses
    {
        return HakAkses::where('lihat', 1)->where('tambah', 1)
            ->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.template.index'))->firstOrFail();
    }

    public function test_crud_validation_and_permissions(): void
    {
        $this->get(route('admin.template.index'))->assertRedirect(route('login'));
        $access = $this->access();
        $this->actingAs(User::findOrFail($access->id_user));
        $this->get(route('admin.template.index'))->assertOk()->assertSee('Pengisian Formulir Awal SPMB');
        $this->get(route('admin.template.create'))->assertOk()->assertSee('[[link]]');
        $this->post(route('admin.template.store'), ['nama' => 'Pesan Uji', 'isi' => 'Halo [[nama]]', 'penggunaan' => ''])->assertRedirect(route('admin.template.index'));
        $template = TemplatePesan::where('nama', 'Pesan Uji')->firstOrFail();
        $this->get(route('admin.template.show', $template))->assertOk()->assertSee('Halo [[nama]]');
        $this->get(route('admin.template.edit', $template))->assertOk();
        $this->put(route('admin.template.update', $template), ['nama' => 'Pesan Baru', 'isi' => '<script>alert(1)</script> [[link]]', 'penggunaan' => ''])->assertRedirect();
        $this->get(route('admin.template.show', $template))->assertOk()->assertSee('<script>alert(1)</script>')->assertDontSee('<script>alert(1)</script>', false);
        $this->postJson(route('admin.template.store'), ['nama' => 'Duplikat', 'isi' => '[[link]]', 'penggunaan' => 'formulir_awal'])->assertUnprocessable()->assertJsonValidationErrors('penggunaan');
        $awal = TemplatePesan::where('penggunaan', 'formulir_awal')->firstOrFail();
        $this->putJson(route('admin.template.update', $awal), ['nama' => 'Awal', 'isi' => 'Tanpa tautan', 'penggunaan' => 'formulir_awal'])->assertUnprocessable()->assertJsonValidationErrors('isi');
        $this->postJson(route('admin.template.store'), ['nama' => 'Kode salah', 'isi' => '[[unknown]]'])->assertUnprocessable()->assertJsonValidationErrors('isi');
        $access->update(['tambah' => 0, 'edit' => 0, 'hapus' => 0]);
        $this->get(route('admin.template.index'))->assertOk();
        $this->get(route('admin.template.create'))->assertForbidden();
        $this->postJson(route('admin.template.store'), [])->assertForbidden();
        $this->get(route('admin.template.edit', $template))->assertForbidden();
        $this->putJson(route('admin.template.update', $template), [])->assertForbidden();
        $this->delete(route('admin.template.destroy', $template))->assertForbidden();
        $access->update(['hapus' => 1]);
        $this->delete(route('admin.template.destroy', $template))->assertRedirect(route('admin.template.index'));
        $this->assertDatabaseMissing('template_pesan', ['id' => $template->id]);
        $access->update(['lihat' => 0]);
        $this->get(route('admin.template.index'))->assertForbidden();
        $this->get(route('admin.template.show', $awal))->assertForbidden();
    }

    public function test_wa_uses_edited_template_for_both_levels_and_handles_deleted_template(): void
    {
        $this->actingAs(User::findOrFail($this->access()->id_user));
        $template = TemplatePesan::where('penggunaan', 'formulir_awal')->firstOrFail();
        $this->put(route('admin.template.update', $template), [
            'nama' => 'Formulir Awal', 'penggunaan' => 'formulir_awal',
            'isi' => "Halo [[ortu]],\n[[nama]] - [[jenjang]]\n[[link]]\n[[link]]",
        ])->assertRedirect();
        foreach (['SD' => 'admin.spmb', 'SMP' => 'admin.smp.spmb'] as $jenjang => $prefix) {
            $record = SpmbPendaftaran::create([
                'nama' => 'Siswa & Uji', 'ortu' => 'Wali Uji', 'jk' => 'Putra (Banin)', 'jenjang' => $jenjang,
                'wa' => '081234567890', 'bukti_path' => 'uji.pdf', 'bukti_mime' => 'application/pdf',
            ]);
            $response = $this->postJson(route($prefix.'.kirim-wa', $record))->assertOk();
            parse_str(parse_url($response->json('url'), PHP_URL_QUERY), $query);
            $link = route('spmb.formulir', $record->token);
            $this->assertSame("Halo Wali Uji,\nSiswa & Uji - {$jenjang}\n{$link}\n{$link}", $query['text']);
            $this->assertNotNull($record->fresh()->wa_dikirim_at);
        }
        $this->delete(route('admin.template.destroy', $template))->assertRedirect();
        $record->refresh()->forceFill(['status' => 'formulir', 'wa_dikirim_at' => null])->save();
        $this->postJson(route('admin.smp.spmb.kirim-wa', $record))->assertUnprocessable()->assertJsonPath('message', 'Template formulir awal belum tersedia. Tambahkan melalui menu Template sebelum mengirim WA.');
        $this->assertSame('formulir', $record->fresh()->status);
        $this->assertNull($record->fresh()->wa_dikirim_at);
    }
}
