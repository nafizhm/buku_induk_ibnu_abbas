<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrangTuaKegiatanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_dashboard_uses_database_and_qr_only_appears_for_active_upcoming_event(): void
    {
        $user = User::whereHas('siswa')->firstOrFail();
        $siswa = $user->siswa()->firstOrFail();
        Kegiatan::query()->update(['status' => 'non aktif']);

        $mendatang = Kegiatan::create([
            'tgl_kegiatan' => today()->addDay(), 'nama_kegiatan' => 'Kegiatan Mendatang Database',
            'zona_waktu' => 'WITA', 'status' => 'aktif',
        ]);
        $mendatang->presensi()->create([
            'siswa_id' => $siswa->id, 'qr_code' => 'QRTEST123456789', 'jenis' => 'undangan',
        ]);
        Kegiatan::create([
            'tgl_kegiatan' => today()->subDay(), 'nama_kegiatan' => 'Kegiatan Sudah Lewat',
            'zona_waktu' => 'WIB', 'status' => 'aktif',
        ]);

        $this->actingAs($user)->get(route('orang-tua.beranda'))
            ->assertOk()->assertSee('Kegiatan Mendatang Database')->assertSee('WITA');

        $this->get(route('orang-tua.kegiatan'))
            ->assertOk()->assertSee('id="qrcode"', false)->assertSee('QRTEST123456789');

        $mendatang->update(['status' => 'non aktif']);
        $this->get(route('orang-tua.kegiatan'))
            ->assertOk()->assertDontSee('id="qrcode"', false)->assertSee('Belum ada kegiatan aktif yang akan datang.');
    }
}
