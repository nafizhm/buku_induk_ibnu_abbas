<?php

namespace Tests\Feature;

use App\Models\SpmbPendaftaran;
use App\Support\SpmbQuota;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SpmbQuotaTest extends TestCase
{
    use DatabaseTransactions;

    public function test_each_category_accepts_last_slot_and_rejects_further_registrations(): void
    {
        Storage::fake('local');
        foreach (SpmbQuota::LIMITS as $jenjang => $categories) {
            foreach ($categories as $jk => $limit) {
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
}
