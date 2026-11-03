<?php

namespace Tests\Feature;

use App\Models\SpmbPendaftaran;
use App\Models\HakAkses;
use App\Models\User;
use App\Support\SpmbQuota;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SpmbQuotaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_each_category_accepts_last_slot_and_rejects_further_registrations(): void
    {
        Storage::fake('local');
        foreach (SpmbQuota::LIMITS as $jenjang => $categories) {
            foreach ($categories as $jk => $limit) {
                DB::table('spmb_quota_locks')->where(compact('jenjang', 'jk'))->update(['kuota' => $limit]);
                $payload = ['nama' => 'Uji Kuota', 'ortu' => 'Orang Tua Uji', 'wa' => '081234567890', 'jenjang' => $jenjang, 'jk' => $jk];
                $query = SpmbPendaftaran::where('jenjang', $jenjang)->where('jk', $jk);
                // Temporarily isolate existing data; DatabaseTransactions restores it after this test.
                $query->update(['jk' => 'Kategori uji terisolasi']);
                for ($i = 0; $i < $limit - 1; $i++) {
                    SpmbPendaftaran::create($payload + ['bukti_path' => 'uji.pdf', 'bukti_mime' => 'application/pdf']);
                }
                $this->assertSame(1, SpmbQuota::availability()[$jenjang][$jk]['remaining']);
                $this->postJson('/spmb', $payload + ['bukti' => UploadedFile::fake()->create('transfer.pdf', 1, 'application/pdf')])->assertCreated();
                $filesBefore = Storage::disk('local')->allFiles('spmb/bukti');
                $this->postJson('/spmb', $payload + ['bukti' => UploadedFile::fake()->create('transfer.pdf', 1, 'application/pdf')])
                    ->assertUnprocessable()->assertJsonValidationErrors('jk');
                $this->assertSame($limit, $query->count());
                $this->assertSame($filesBefore, Storage::disk('local')->allFiles('spmb/bukti'));
                $this->assertSame(0, SpmbQuota::availability()[$jenjang][$jk]['remaining']);
                $page = $this->get($jenjang === 'SD' ? '/spmb' : '/spmb-smp')->assertOk()->assertHeader('Cache-Control', 'no-store, private');
                $this->assertSame(0, $page->viewData('quotaAvailability')[$jenjang][$jk]['remaining']);
            }
        }
    }

    public function test_admin_can_update_quota_with_edit_permission_and_counts_remain_visible(): void
    {
        $this->put(route('admin.kuota.update'), [])->assertRedirect(route('login'));
        $access = HakAkses::where('lihat', 1)->whereHas('menu', fn ($query) => $query->where('route_name', 'admin.spmb.index'))->firstOrFail();
        $this->assertSame(1, (int) $access->menu->edit, 'Menu Formulir harus menyediakan centang Edit di Hak Akses.');
        $access->update(['edit' => 1]);
        $this->actingAs(User::findOrFail($access->id_user));
        $payload = ['jenjang' => 'SD', 'jk' => 'Putra (Banin)', 'kuota' => 0];
        $this->put(route('admin.kuota.update'), $payload)->assertRedirect(route('dashboard'));
        $quota = SpmbQuota::availability()['SD']['Putra (Banin)'];
        $this->assertSame(0, $quota['limit']);
        $this->assertSame(0, $quota['remaining']);
        $this->assertSame(SpmbPendaftaran::where('jenjang', 'SD')->where('jk', 'Putra (Banin)')->count(), $quota['registered']);
        $this->get(route('dashboard'))->assertOk()->assertSee('Pengaturan Kuota Pendaftaran')->assertSee('Sudah mendaftar')->assertSee('Simpan Kuota');
        Storage::fake('local');
        $this->postJson('/spmb', [
            'nama' => 'Uji Kuota', 'ortu' => 'Wali', 'wa' => '081234567890',
            'jenjang' => 'SD', 'jk' => 'Putra (Banin)',
            'bukti' => UploadedFile::fake()->create('transfer.pdf', 1, 'application/pdf'),
        ])->assertUnprocessable()->assertJsonValidationErrors('jk');
        $this->putJson(route('admin.kuota.update'), array_replace($payload, ['kuota' => -1]))->assertUnprocessable();
        $access->update(['edit' => 0]);
        $this->putJson(route('admin.kuota.update'), array_replace($payload, ['kuota' => 50]))->assertForbidden();
        $this->assertSame(0, SpmbQuota::availability()['SD']['Putra (Banin)']['limit']);
    }
}
